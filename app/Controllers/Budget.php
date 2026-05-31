<?php

namespace App\Controllers;

use App\Models\BudgetModel;
use App\Models\CategoryModel;
use App\Models\TransactionModel;

class Budget extends BaseController
{
    protected BudgetModel $budgetModel;
    protected CategoryModel $categoryModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->budgetModel = new BudgetModel();
        $this->categoryModel = new CategoryModel();
        $this->transactionModel = new TransactionModel();
    }

    private function userId(): int
    {
        $user = session()->get('user');

        if (is_array($user) && isset($user['id'])) {
            return (int) $user['id'];
        }

        return (int) session()->get('user.id');
    }

    public function index()
    {
        $userId = $this->userId();

        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year = (int) ($this->request->getGet('year') ?: date('Y'));

        if ($month < 1 || $month > 12) {
            $month = (int) date('n');
        }

        if ($year < 2000 || $year > 2100) {
            $year = (int) date('Y');
        }

        $budgets = $this->budgetModel
            ->select('
                budgets.*,
                categories.name as category_name,
                categories.icon as category_icon,
                categories.color as category_color
            ')
            ->join('categories', 'categories.id = budgets.category_id', 'left')
            ->where('budgets.user_id', $userId)
            ->where('budgets.month', $month)
            ->where('budgets.year', $year)
            ->orderBy('categories.name', 'ASC')
            ->findAll();

        $budgets = array_map(function ($budget) use ($userId, $month, $year) {
            $spent = $this->getSpentAmount(
                $userId,
                (int) $budget['category_id'],
                $month,
                $year
            );

            $amount = (float) $budget['amount'];
            $remaining = $amount - $spent;
            $percentage = $amount > 0 ? ($spent / $amount) * 100 : 0;

            $budget['spent'] = $spent;
            $budget['remaining'] = $remaining;
            $budget['percentage'] = min(100, round($percentage, 1));
            $budget['raw_percentage'] = round($percentage, 1);
            $budget['status'] = $this->budgetStatus($percentage);

            return $budget;
        }, $budgets);

        $summary = [
            'total_budget' => array_sum(array_map(static fn($item) => (float) $item['amount'], $budgets)),
            'total_spent' => array_sum(array_map(static fn($item) => (float) $item['spent'], $budgets)),
        ];

        $summary['total_remaining'] = $summary['total_budget'] - $summary['total_spent'];

        return view('budgets/index', [
            'title' => 'Walltrack | Budget Bulanan',
            'budgets' => $budgets,
            'summary' => $summary,
            'filters' => [
                'month' => $month,
                'year' => $year,
            ],
            'monthLabel' => $this->monthName($month) . ' ' . $year,
        ]);
    }

    public function create()
    {
        $userId = $this->userId();

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('budgets/create', [
            'title' => 'Walltrack | Tambah Budget',
            'categories' => $categories,
            'months' => $this->monthOptions(),
        ]);
    }

    public function store()
    {
        $userId = $this->userId();

        $validated = $this->validateBudgetRequest($userId);

        if ($validated['status'] === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with($validated['flash_type'], $validated['message']);
        }

        $data = $validated['data'];

        $duplicate = $this->budgetModel
            ->where('user_id', $userId)
            ->where('category_id', $data['category_id'])
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->first();

        if ($duplicate) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Budget untuk kategori dan periode tersebut sudah ada.');
        }

        $this->budgetModel->insert($data);

        return redirect()
            ->to('/budgets?month=' . $data['month'] . '&year=' . $data['year'])
            ->with('success', 'Budget berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = $this->userId();

        $budget = $this->budgetModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$budget) {
            return redirect()
                ->to('/budgets')
                ->with('error', 'Budget tidak ditemukan.');
        }

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('budgets/edit', [
            'title' => 'Walltrack | Edit Budget',
            'budget' => $budget,
            'categories' => $categories,
            'months' => $this->monthOptions(),
        ]);
    }

    public function update($id)
    {
        $userId = $this->userId();

        $budget = $this->budgetModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$budget) {
            return redirect()
                ->to('/budgets')
                ->with('error', 'Budget tidak ditemukan.');
        }

        $validated = $this->validateBudgetRequest($userId);

        if ($validated['status'] === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with($validated['flash_type'], $validated['message']);
        }

        $data = $validated['data'];

        $duplicate = $this->budgetModel
            ->where('user_id', $userId)
            ->where('category_id', $data['category_id'])
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->where('id !=', $id)
            ->first();

        if ($duplicate) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Budget untuk kategori dan periode tersebut sudah ada.');
        }

        $this->budgetModel->update((int) $id, $data);

        return redirect()
            ->to('/budgets?month=' . $data['month'] . '&year=' . $data['year'])
            ->with('success', 'Budget berhasil diperbarui.');
    }

    public function delete()
    {
        $json = $this->request->getJSON();
        $budgetId = $json->id ?? null;
        $userId = $this->userId();

        if (!$budgetId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Budget tidak valid.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $budget = $this->budgetModel
            ->where('id', $budgetId)
            ->where('user_id', $userId)
            ->first();

        if (!$budget) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Budget tidak ditemukan.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $this->budgetModel->delete((int) $budgetId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Budget berhasil dihapus.',
            'csrfHash' => csrf_hash(),
        ]);
    }

    private function validateBudgetRequest(int $userId): array
    {
        $rules = [
            'category_id' => 'required|is_natural_no_zero',
            'month' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[12]',
            'year' => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to[2100]',
            'amount' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return [
                'status' => false,
                'flash_type' => 'errors',
                'message' => $this->validator->getErrors(),
            ];
        }

        $categoryId = (int) $this->request->getPost('category_id');

        $category = $this->categoryModel
            ->where('id', $categoryId)
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->first();

        if (!$category) {
            return [
                'status' => false,
                'flash_type' => 'error',
                'message' => 'Budget hanya bisa dibuat untuk kategori Pengeluaran.',
            ];
        }

        return [
            'status' => true,
            'data' => [
                'user_id' => $userId,
                'category_id' => $categoryId,
                'month' => (int) $this->request->getPost('month'),
                'year' => (int) $this->request->getPost('year'),
                'amount' => (float) $this->request->getPost('amount'),
            ],
        ];
    }

    private function getSpentAmount(int $userId, int $categoryId, int $month, int $year): float
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $row = $this->transactionModel
            ->where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->where('type', 'expense')
            ->where('transaction_date >=', $startDate)
            ->where('transaction_date <=', $endDate)
            ->selectSum('amount')
            ->get()
            ->getRowArray();

        return (float) ($row['amount'] ?? 0);
    }

    private function budgetStatus(float $percentage): array
    {
        if ($percentage >= 100) {
            return [
                'label' => 'Melebihi Budget',
                'class' => 'danger',
            ];
        }

        if ($percentage >= 80) {
            return [
                'label' => 'Hampir Habis',
                'class' => 'warning',
            ];
        }

        return [
            'label' => 'Aman',
            'class' => 'success',
        ];
    }

    private function monthOptions(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }

    private function monthName(int $month): string
    {
        return $this->monthOptions()[$month] ?? '-';
    }
}