<?php

namespace App\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Http\Controllers\Core\ApiServiceController;
use App\Modules\Budgets\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Application;

class BudgetController extends ApiServiceController
{
    public function __construct(BudgetService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request)
    {
        $data = RequestHelper::extractMonthAndYear($request);

        $userId = Auth::id();
        $budget = $this->retrieveBudget($userId, $data['month'], $data['year']);
        $localizedMonth = $this->getLocalizedMonth($data['month']);

        return $this->okResponse($this->prepareResponse($budget, $localizedMonth, $data['year']));
    }

    private function retrieveBudget(int $userId, int $month, int $year): float
    {
        return $this->service->getBudget($userId, $month, $year);
    }

    private function getLocalizedMonth(int $month)
    {
        return __('translations.Month_' . $month);
    }

    private function prepareResponse($budget, $month, $year): array
    {
        return compact('budget', 'month', 'year');
    }
}
