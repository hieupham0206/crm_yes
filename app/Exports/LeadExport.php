<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/6/2018
 * Time: 3:38 PM
 */

namespace App\Exports;

use App\Enums\LeadState;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\HistoryCall;
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class LeadExport implements FromView
{
    use Exportable;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $isNotEmpty = collect($this->filters)->filter(function ($filter) {
            return ! isValueEmpty($filter);
        })->isNotEmpty();

        $leads = Lead::query()->with(['province'])->limit(15000);

        if ($isNotEmpty) {
            $leads = $leads->filters($this->filters);
        }

        $leadDatas = $leads->orderBy('id', 'desc')->get();
        $leadIds   = $leadDatas->pluck('id')->toArray();

        HistoryCall::whereIn('lead_id', $leadIds)->whereHas('lead', function ($q) {
            $q->whereNotIn('state', [LeadState::APPOINTMENT, LeadState::MEMBER, LeadState::CALL_LATER]);
        })->forceDelete();

        Lead::query()
            ->whereIn('id', $leadIds)
            ->whereNotIn('state', [LeadState::APPOINTMENT, LeadState::MEMBER, LeadState::CALL_LATER])
            ->doesntHave('history_calls')
            ->doesntHave('appointments')
            ->doesntHave('callbacks')
            ->forceDelete();

        return view('business.leads._template_export', [
            'leads' => $leadDatas,
            'lead'  => new Lead(),
        ]);
    }
}