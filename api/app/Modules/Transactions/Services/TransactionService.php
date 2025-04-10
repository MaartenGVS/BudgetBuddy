<?php

namespace App\Modules\Transactions\Services;

use App\Models\Category;
use App\Models\Transaction;
use App\Modules\Core\Services\Service;
use Illuminate\Support\Facades\App;

class TransactionService extends Service
{


    protected $fields = [
        'all' => [
            'transaction_number',
            'amount',
            'transactions.description',
            'categories_languages.name as category_name',
            'categories_languages.category_type as category_type'
        ],
        'find' => [
            'transaction_number',
            'amount',
            'month',
            'year',
            'description',
            'category_id'
        ]
    ];


    public function __construct(Transaction $model)
    {
        parent::__construct($model);
        $this->fields = $this->getFields();
        $this->rules = [
            "create-update" => [
                'month' => 'required|integer|min:1|max:12',
                'year' => 'required|integer|min:2024|max:2100',
                'amount' => 'required|numeric|min:0',
                'description' => 'required|string|max:255',
                'category_id' => 'required|integer|exists:categories,id'
            ]
        ];
    }


    public function getTransactionsInPeriodWithPagingAndFilter
    (
        int $userId, array $period, string $type, int $perPage, string $search
    )
    {
        $transactions = $this->model::byUserId($userId)
            ->inPeriod($period['month'], $period['year'])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('categories_languages', 'categories.id', '=', 'categories_languages.category_id')
            ->where('categories_languages.language', App::getLocale())
            ->select($this->fields['all'])
            ->when($search, function ($query, $search) {
                return $query
                    ->where(function ($query) use ($search) {
                        $query->where('amount', 'like', "%$search%")
                            ->orWhere('categories_languages.name', 'like', "%$search%");
                    });
            })
            ->orderBy('transactions.created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $this->filterTransactionsOnType($transactions, $type);

        return $transactions;
    }

    public function getTransactionsInPeriod($userId, int $month, int $year)
    {
        $transactions = $this->model::byUserId($userId)
            ->inPeriod($month, $year)->get();
        return $this->transformTransactionsWithCategory($transactions);
    }

    public function get($userId, $transactionNumber)
    {
        $transaction = $this->model::byUserId($userId)
            ->byTransactionNumber($transactionNumber)
            ->select($this->getFields()['find'])
            ->firstOrFail();

        $transaction->category = Category::findWithLanguage($transaction->category_id);

        return $transaction;
    }

    private function transformTransactionsWithCategory($transactions)
    {
        return $transactions->map(function ($transaction) {
            $transaction->category = Category::findWithLanguage($transaction->category_id);
            return $transaction;
        });
    }

    private function filterTransactionsOnType($transactions, $type): void
    {
        $categoryType = $this->mapCategoryType($type);

        $transactions->setCollection($categoryType
            ? $transactions->getCollection()->filter(fn($trans) => $trans->category_type === $categoryType)->values()
            : $transactions->getCollection());
    }

    public function delete($transactionNumber)
    {
        return $this->model::byTransactionNumber($transactionNumber)->delete();
    }

    public function create(array $data, string $ruleKey)
    {
        $this->validate($data, $ruleKey);

        if ($this->hasErrors()) {
            return;
        }

        return $this->model->create($data);
    }

    public function update($transactionNumber, array $data, string $ruleKey)
    {
        $this->validate($data, $ruleKey);

        if ($this->hasErrors()) {
            return;
        }

        return $this->model::byTransactionNumber($transactionNumber)->update($data);
    }

    private function mapCategoryType($type)
    {
        return match ($type) {
            __('constants.incomes') => __('constants.income'),
            __('constants.expenses') => __('constants.expense'),
            default => null,
        };
    }

    private function getFields(): array
    {
        return [
            'all' => [
                'transaction_number',
                'amount',
                'transactions.description',
                'categories_languages.name as category_name',
                'categories_languages.category_type as category_type',
            ],
            'find' => [
                'transaction_number',
                'amount',
                'month',
                'year',
                'description',
                'category_id',
            ],
        ];
    }
}

