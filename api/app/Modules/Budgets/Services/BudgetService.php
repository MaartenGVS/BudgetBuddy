<?php

namespace App\Modules\Budgets\Services;

use App\Models\Transaction;
use App\Modules\Core\Services\Service;
use App\Modules\Transactions\Services\TransactionService;

class BudgetService extends Service
{

    private TransactionService $transactionService;

    public function __construct(Transaction $model, TransactionService $transactionService)
    {
        parent::__construct($model);
        $this->transactionService = $transactionService;
    }

    public function getBudget($userId, $month, $year): float
    {
        $transactions = $this->transactionService->getTransactionsInPeriod($userId, $month, $year);
        return $this->calculateBudget($transactions);
    }

    private function calculateBudget($transactions): float
    {
        $incomes = $transactions->filter(fn($trans) => $trans->category->category_type === __('constants.income'));
        $expenses = $transactions->filter(fn($trans) => $trans->category->category_type === __('constants.expense'));

        return $incomes->sum('amount') - $expenses->sum('amount');
    }
}
