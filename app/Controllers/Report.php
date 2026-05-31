<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\WalletModel;

class Report extends BaseController
{
    protected WalletModel $walletModel;
    protected CategoryModel $categoryModel;
    protected $db;

    public function __construct()
    {
        $this->walletModel = new WalletModel();
        $this->categoryModel = new CategoryModel();
        $this->db = db_connect();
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
        $filters = $this->getFilters();

        $transactions = $this->baseFilteredQuery($userId, $filters)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->orderBy('transactions.id', 'DESC')
            ->get()
            ->getResultArray();

        $summary = $this->getSummary($userId, $filters);

        $wallets = $this->walletModel
            ->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('type', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('reports/index', [
            'title' => 'Walltrack | Laporan',
            'transactions' => $transactions,
            'summary' => $summary,
            'wallets' => $wallets,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    public function exportCsv()
    {
        $userId = $this->userId();
        $filters = $this->getFilters();

        $transactions = $this->baseFilteredQuery($userId, $filters)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->orderBy('transactions.id', 'DESC')
            ->get()
            ->getResultArray();

        $summary = $this->getSummary($userId, $filters);

        $filename = 'laporan-walltrack-' . date('Ymd-His') . '.csv';

        $handle = fopen('php://temp', 'r+');

        // BOM supaya Excel membaca UTF-8 dengan benar.
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['Laporan Walltrack']);
        fputcsv($handle, ['Periode', $filters['start_date'] . ' s/d ' . $filters['end_date']]);
        fputcsv($handle, []);

        fputcsv($handle, ['Ringkasan']);
        fputcsv($handle, ['Total Pemasukan', $summary['total_income']]);
        fputcsv($handle, ['Total Pengeluaran', $summary['total_expense']]);
        fputcsv($handle, ['Saldo Bersih', $summary['net_balance']]);
        fputcsv($handle, ['Total Transfer', $summary['total_transfer']]);
        fputcsv($handle, ['Jumlah Transaksi', $summary['total_transactions']]);
        fputcsv($handle, []);

        fputcsv($handle, [
            'Tanggal',
            'Tipe',
            'Wallet',
            'Wallet Tujuan',
            'Kategori',
            'Nominal',
            'Catatan',
        ]);

        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction['transaction_date'],
                $this->typeLabel($transaction['type']),
                $transaction['wallet_name'] ?? '-',
                $transaction['target_wallet_name'] ?? '-',
                $transaction['category_name'] ?? '-',
                $transaction['amount'],
                $transaction['description'] ?? '',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    public function exportExcel()
    {
        $userId = $this->userId();
        $filters = $this->getFilters();

        $transactions = $this->baseFilteredQuery($userId, $filters)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->orderBy('transactions.id', 'DESC')
            ->get()
            ->getResultArray();

        $summary = $this->getSummary($userId, $filters);

        $filename = 'laporan-walltrack-' . date('Ymd-His') . '.xls';

        $html = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }

                    th, td {
                        border: 1px solid #999;
                        padding: 8px;
                    }

                    th {
                        background: #134686;
                        color: #ffffff;
                    }

                    .summary-title {
                        font-weight: bold;
                        background: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                <h2>Laporan Walltrack</h2>
                <p>Periode: ' . $this->e($filters['start_date']) . ' s/d ' . $this->e($filters['end_date']) . '</p>

                <table>
                    <tr>
                        <td class="summary-title">Total Pemasukan</td>
                        <td>' . $this->formatNumber($summary['total_income']) . '</td>
                    </tr>
                    <tr>
                        <td class="summary-title">Total Pengeluaran</td>
                        <td>' . $this->formatNumber($summary['total_expense']) . '</td>
                    </tr>
                    <tr>
                        <td class="summary-title">Saldo Bersih</td>
                        <td>' . $this->formatNumber($summary['net_balance']) . '</td>
                    </tr>
                    <tr>
                        <td class="summary-title">Total Transfer</td>
                        <td>' . $this->formatNumber($summary['total_transfer']) . '</td>
                    </tr>
                    <tr>
                        <td class="summary-title">Jumlah Transaksi</td>
                        <td>' . (int) $summary['total_transactions'] . '</td>
                    </tr>
                </table>

                <br>

                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Wallet</th>
                            <th>Wallet Tujuan</th>
                            <th>Kategori</th>
                            <th>Nominal</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
        ';

        foreach ($transactions as $transaction) {
            $html .= '
                <tr>
                    <td>' . $this->e($transaction['transaction_date']) . '</td>
                    <td>' . $this->e($this->typeLabel($transaction['type'])) . '</td>
                    <td>' . $this->e($transaction['wallet_name'] ?? '-') . '</td>
                    <td>' . $this->e($transaction['target_wallet_name'] ?? '-') . '</td>
                    <td>' . $this->e($transaction['category_name'] ?? '-') . '</td>
                    <td>' . $this->formatNumber($transaction['amount']) . '</td>
                    <td>' . $this->e($transaction['description'] ?? '') . '</td>
                </tr>
            ';
        }

        $html .= '
                    </tbody>
                </table>
            </body>
            </html>
        ';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($html);
    }

    private function getFilters(): array
    {
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');

        if (!$this->isValidDate($startDate)) {
            $startDate = date('Y-m-01');
        }

        if (!$this->isValidDate($endDate)) {
            $endDate = date('Y-m-d');
        }

        if (strtotime($startDate) > strtotime($endDate)) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
        }

        $type = $this->request->getGet('type');
        $type = in_array($type, ['income', 'expense', 'transfer'], true) ? $type : '';

        $walletId = $this->request->getGet('wallet_id');
        $walletId = is_numeric($walletId) && (int) $walletId > 0 ? (int) $walletId : '';

        $categoryId = $this->request->getGet('category_id');
        $categoryId = is_numeric($categoryId) && (int) $categoryId > 0 ? (int) $categoryId : '';

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'wallet_id' => $walletId,
            'category_id' => $categoryId,
            'type' => $type,
        ];
    }

    private function baseFilteredQuery(int $userId, array $filters)
    {
        $builder = $this->db->table('transactions')
            ->select('
                transactions.*,
                categories.name as category_name,
                categories.type as category_type,
                categories.icon as category_icon,
                categories.color as category_color,
                wallets.name as wallet_name,
                target_wallet.name as target_wallet_name
            ')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->join('wallets', 'wallets.id = transactions.wallet_id', 'left')
            ->join('wallets as target_wallet', 'target_wallet.id = transactions.transfer_to_wallet_id', 'left')
            ->where('transactions.user_id', $userId)
            ->where('transactions.deleted_at', null)
            ->where('transactions.transaction_date >=', $filters['start_date'])
            ->where('transactions.transaction_date <=', $filters['end_date']);

        if (!empty($filters['wallet_id'])) {
            $builder->groupStart()
                ->where('transactions.wallet_id', $filters['wallet_id'])
                ->orWhere('transactions.transfer_to_wallet_id', $filters['wallet_id'])
                ->groupEnd();
        }

        if (!empty($filters['category_id'])) {
            $builder->where('transactions.category_id', $filters['category_id']);
        }

        if (!empty($filters['type'])) {
            $builder->where('transactions.type', $filters['type']);
        }

        return $builder;
    }

    private function getSummary(int $userId, array $filters): array
    {
        $row = $this->baseFilteredQuery($userId, $filters)
            ->select('
                COALESCE(SUM(CASE WHEN transactions.type = "income" THEN transactions.amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN transactions.type = "expense" THEN transactions.amount ELSE 0 END), 0) as total_expense,
                COALESCE(SUM(CASE WHEN transactions.type = "transfer" THEN transactions.amount ELSE 0 END), 0) as total_transfer,
                COUNT(transactions.id) as total_transactions
            ', false)
            ->get()
            ->getRowArray();

        $totalIncome = (float) ($row['total_income'] ?? 0);
        $totalExpense = (float) ($row['total_expense'] ?? 0);
        $totalTransfer = (float) ($row['total_transfer'] ?? 0);

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'total_transfer' => $totalTransfer,
            'net_balance' => $totalIncome - $totalExpense,
            'total_transactions' => (int) ($row['total_transactions'] ?? 0),
        ];
    }

    private function isValidDate(string $date): bool
    {
        $parsed = date_create_from_format('Y-m-d', $date);

        return $parsed && $parsed->format('Y-m-d') === $date;
    }

    private function typeLabel(string $type): string
    {
        return match ($type) {
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
            'transfer' => 'Transfer',
            default => ucfirst($type),
        };
    }

    private function formatNumber($value): string
    {
        return number_format((float) $value, 0, ',', '.');
    }

    private function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}