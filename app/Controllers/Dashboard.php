<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        return view('pages/dashboard');
    }

    public function data()
    {
        $userId = session()->get('user.id'); // Ambil user ID dari sesi

        $totalIncome = $this->transactionModel->where('user_id', $userId)->where('type', 'debit')->selectSum('amount')->get()->getRow()->amount ?? 0;
        $totalExpense = $this->transactionModel->where('user_id', $userId)->where('type', 'kredit')->selectSum('amount')->get()->getRow()->amount ?? 0;
        $totalBalance = $totalIncome - $totalExpense;

        $transactions = $this->transactionModel->where('user_id', $userId)
            ->select('DATE(transaction_date) as date, SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = "kredit" THEN amount ELSE 0 END) as expense')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->findAll();

        $dates = [];
        $incomeData = [];
        $expenseData = [];

        foreach ($transactions as $transaction) {
            $dates[] = $transaction['date'];
            $incomeData[] = (float) $transaction['income'];
            $expenseData[] = (float) $transaction['expense'];
        }

        return $this->response->setJSON([
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'total_balance' => $totalBalance,
            'dates' => $dates,
            'income_data' => $incomeData,
            'expense_data' => $expenseData
        ]);
    }
}
