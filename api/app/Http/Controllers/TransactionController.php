<?php

namespace App\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Http\Controllers\Core\ApiServiceController;
use App\Modules\Transactions\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends ApiServiceController
{


    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }


    public function getAll(Request $request)
    {
        $userId = Auth::id();
        $transactions = $this->fetchTransactionsByParams($userId, $request);
        return $this->okResponse($transactions);
    }

    public function get($transactionNumber)
    {
        $userId = Auth::id();
        $transaction = $this->service->get($userId, $transactionNumber);
        if (!$transaction) return $this->notFoundResponse();
        return $this->okResponse($transaction);
    }

    public function create(Request $request)
    {
        $transaction = $this->service->create($request->all(), 'create-update');

        if ($this->service->hasErrors()) {
            $errors = $this->service->getErrors();
            return $this->badRequestResponse($errors);
        }

        return $this->createdResponse($transaction);
    }

    public function update(Request $request, $transactionNumber)
    {
        $userId = Auth::id();
        $transaction = $this->service->get($userId, $transactionNumber);

        if (!$transaction) return $this->notFoundResponse();

        $updatedTransaction = $this->service->update($transactionNumber, $request->all(), "create-update");

        if ($this->service->hasErrors()) {
            $errors = $this->service->getErrors();
            return $this->badRequestResponse($errors);
        }

        return $this->okResponse($updatedTransaction);
    }

    public function delete($transactionNumber)
    {
        $userId = Auth::id();

        $transaction = $this->service->get($userId, $transactionNumber);
        if (!$transaction) return $this->notFoundResponse();

        $deleteSucceeded = $this->service->delete($transactionNumber);
        if (!$deleteSucceeded) return $this->notFoundResponse();

        return $this->noContentResponse();
    }

    private function fetchTransactionsByParams(int $userId, Request $request)
    {
        $period = RequestHelper::extractMonthAndYear($request);
        $type = RequestHelper::extractQueryParam($request, 'type', '');
        $perPage = RequestHelper::extractQueryParam($request, 'perPage', 10);
        $search = RequestHelper::extractQueryParam($request, 'search', '');

        return $this->service
            ->getTransactionsInPeriodWithPagingAndFilter(
                $userId,$period, $type, $perPage, $search
            );
    }
}
