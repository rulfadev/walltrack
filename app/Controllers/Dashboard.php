<?php

namespace App\Controllers;

use App\Models\BudgetModel;
use App\Models\TransactionModel;
use App\Models\WalletModel;

class Dashboard extends BaseController
{
    protected TransactionModel $transactionModel;
    protected WalletModel $walletModel;
    protected BudgetModel $budgetModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->walletModel = new WalletModel();
        $this->budgetModel = new BudgetModel();
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
        return view('pages/dashboard', [
            'title' => 'Walltrack | Dashboard',
        ]);
    }

    public function data()
    {
        $userId = $this->userId();

        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');

        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');

        $totalIncome = $this->transactionModel
            ->where('user_id', $userId)
            ->where('type', 'income')
            ->where('transaction_date >=', $startOfMonth)
            ->where('transaction_date <=', $endOfMonth)
            ->selectSum('amount')
            ->get()
            ->getRow()
            ->amount ?? 0;

        $totalExpense = $this->transactionModel
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->where('transaction_date >=', $startOfMonth)
            ->where('transaction_date <=', $endOfMonth)
            ->selectSum('amount')
            ->get()
            ->getRow()
            ->amount ?? 0;

        $totalTransfer = $this->transactionModel
            ->where('user_id', $userId)
            ->where('type', 'transfer')
            ->where('transaction_date >=', $startOfMonth)
            ->where('transaction_date <=', $endOfMonth)
            ->selectSum('amount')
            ->get()
            ->getRow()
            ->amount ?? 0;

        $totalBalance = $this->walletModel
            ->where('user_id', $userId)
            ->selectSum('current_balance')
            ->get()
            ->getRow()
            ->current_balance ?? 0;

        $wallets = $this->walletModel
            ->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $cashflow = $this->transactionModel
            ->where('user_id', $userId)
            ->where('transaction_date >=', $startOfMonth)
            ->where('transaction_date <=', $endOfMonth)
            ->select('
                DATE(transaction_date) as date,
                SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense
            ')
            ->groupBy('DATE(transaction_date)')
            ->orderBy('date', 'ASC')
            ->findAll();

        $categoryExpenses = $this->transactionModel
            ->select('
                categories.name as category_name,
                categories.icon as category_icon,
                categories.color as category_color,
                SUM(transactions.amount) as total
            ')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->where('transactions.user_id', $userId)
            ->where('transactions.type', 'expense')
            ->where('transactions.transaction_date >=', $startOfMonth)
            ->where('transactions.transaction_date <=', $endOfMonth)
            ->groupBy('transactions.category_id, categories.name, categories.icon, categories.color')
            ->orderBy('total', 'DESC')
            ->findAll(8);

        $recentTransactions = $this->transactionModel
            ->select('
                transactions.*,
                categories.name as category_name,
                wallets.name as wallet_name,
                target_wallet.name as target_wallet_name
            ')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->join('wallets', 'wallets.id = transactions.wallet_id', 'left')
            ->join('wallets as target_wallet', 'target_wallet.id = transactions.transfer_to_wallet_id', 'left')
            ->where('transactions.user_id', $userId)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->orderBy('transactions.id', 'DESC')
            ->findAll(6);

        $budgetData = $this->getBudgetOverview($userId, $currentMonth, $currentYear);

        return $this->response->setJSON([
            'summary' => [
                'total_income' => (float) $totalIncome,
                'total_expense' => (float) $totalExpense,
                'total_transfer' => (float) $totalTransfer,
                'total_balance' => (float) $totalBalance,
                'month_label' => $this->monthName($currentMonth) . ' ' . $currentYear,
            ],
            'budget_summary' => $budgetData['summary'],
            'budget_progress' => $budgetData['items'],
            'wallets' => array_map(static function ($wallet) {
                return [
                    'id' => (int) $wallet['id'],
                    'name' => $wallet['name'],
                    'type' => $wallet['type'],
                    'current_balance' => (float) $wallet['current_balance'],
                    'is_default' => (int) $wallet['is_default'],
                ];
            }, $wallets),
            'cashflow' => [
                'dates' => array_column($cashflow, 'date'),
                'income' => array_map('floatval', array_column($cashflow, 'income')),
                'expense' => array_map('floatval', array_column($cashflow, 'expense')),
            ],
            'category_expenses' => array_map(static function ($item) {
                return [
                    'category_name' => $item['category_name'] ?: 'Tanpa Kategori',
                    'category_icon' => $item['category_icon'] ?: 'bi-tag',
                    'category_color' => $item['category_color'] ?: '#134686',
                    'total' => (float) $item['total'],
                ];
            }, $categoryExpenses),
            'recent_transactions' => array_map(static function ($transaction) {
                return [
                    'id' => (int) $transaction['id'],
                    'type' => $transaction['type'],
                    'amount' => (float) $transaction['amount'],
                    'transaction_date' => $transaction['transaction_date'],
                    'description' => $transaction['description'],
                    'category_name' => $transaction['category_name'],
                    'wallet_name' => $transaction['wallet_name'],
                    'target_wallet_name' => $transaction['target_wallet_name'],
                ];
            }, $recentTransactions),
        ]);
    }

    private function getBudgetOverview(int $userId, int $month, int $year): array
    {
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
            ->findAll();

        $items = [];
        $totalBudget = 0;
        $totalSpent = 0;

        foreach ($budgets as $budget) {
            $spent = $this->getSpentAmount(
                $userId,
                (int) $budget['category_id'],
                $month,
                $year
            );

            $amount = (float) $budget['amount'];
            $remaining = $amount - $spent;
            $percentage = $amount > 0 ? ($spent / $amount) * 100 : 0;

            $totalBudget += $amount;
            $totalSpent += $spent;

            $items[] = [
                'id' => (int) $budget['id'],
                'category_id' => (int) $budget['category_id'],
                'category_name' => $budget['category_name'] ?: 'Tanpa Kategori',
                'category_icon' => $budget['category_icon'] ?: 'bi-tag',
                'category_color' => $budget['category_color'] ?: '#134686',
                'amount' => $amount,
                'spent' => $spent,
                'remaining' => $remaining,
                'percentage' => min(100, round($percentage, 1)),
                'raw_percentage' => round($percentage, 1),
                'status' => $this->budgetStatus($percentage),
            ];
        }

        usort($items, static function ($a, $b) {
            return $b['raw_percentage'] <=> $a['raw_percentage'];
        });

        $limitedItems = array_slice($items, 0, 5);

        return [
            'summary' => [
                'total_budget' => $totalBudget,
                'total_spent' => $totalSpent,
                'total_remaining' => $totalBudget - $totalSpent,
                'total_items' => count($items),
                'month_label' => $this->monthName($month) . ' ' . $year,
            ],
            'items' => $limitedItems,
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

    private function monthName(int $month): string
    {
        $months = [
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

        return $months[$month] ?? '-';
    }
}