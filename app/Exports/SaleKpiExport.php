<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/6/2018
 * Time: 3:38 PM
 */

namespace App\Exports;

use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SaleKpiExport implements FromView
{
    use Exportable;

    private $filters;

    public function __construct($filters)
    {
//        $this->filters = $filters;

        $this->filters = \Cache::get('sale_kpi_filter', []);
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $fromDate = $this->filters['from_date'];
        $toDate   = $this->filters['to_date'];
        $userId   = $this->filters['user_id'];

        $whereUserCondition = $whereDateCondition = $whereDateCondition1 = '';
        if ($fromDate && $toDate) {
            $fromDate            = date('Y-m-d', strtotime($fromDate)) . ' 00:00:00';
            $toDate              = date('Y-m-d', strtotime($toDate)) . ' 23:59:59';
            $whereDateCondition  = "and hc.created_at between '$fromDate' and '$toDate'";
            $whereDateCondition1 = "where e.created_at between '$fromDate' and '$toDate'";
        }

        if ($userId) {
            $whereUserCondition = " and u.id = $userId";
        }

        $sql = "select 
       u.name,
       u.id as user_id,
       date(hc.created_at)                      as created_at,
       r.name                                   as role_name,
       count(*)                                 as total_call,
       sum(time_of_call)                        as total_duration,
       sum(IF(hc.state not in (2, 5, 3), 1, 0)) as total_connection,
       sum(IF(hc.state in (2, 5, 3), 1, 0))     as total_no_answer,
       sum(IF(hc.state in (2, 5, 3), 1, 0))     as total_appointment,
       max(hc.created_at)                       as logout_time,
       min(hc.created_at)                       as login_time
from history_calls as hc
         left join users as u on u.id = user_id
         left join model_has_roles as mhr on mhr.model_id = u.id
         left join roles as r on r.id = mhr.role_id
where u.deleted_at is null
  $whereDateCondition $whereUserCondition
group by user_id, DATE(hc.created_at)";

        $results = \DB::select(\DB::raw($sql));

        $sql1     = "select u.id, date(e.created_at) as created_at, count(e.id) as total_show
from users as u
         left join event_datas as e on e.rep_id = u.id
$whereDateCondition1 $whereUserCondition
group by u.id;";
        $result1s = \DB::select(\DB::raw($sql1));

        $sql2     = "select u.id, date(e.created_at) as created_at, count(e.id) as total_tour
from users as u
         left join appointments as a on a.user_id = u.id
         left join event_datas as e on e.rep_id = u.id and e.appointment_id = a.id
$whereDateCondition1 $whereUserCondition
group by u.id;";
        $result2s = \DB::select(\DB::raw($sql2));

        $dataArray = [];
        foreach ($results as $result) {
            $formatedTime = $totalShow = $totalTour = 0;
            $totalSecond  = $result->total_duration;
            if ($totalSecond > 0) {
                $hours   = floor($totalSecond / 3600);
                $minutes = floor(($totalSecond / 60) % 60);
                $seconds = $totalSecond % 60;

                $formatedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }

            $createdAt = $result->created_at;

            $result1sFiltered = collect($result1s)->filter(function ($result1) use ($createdAt) {
                return $result1->created_at === $createdAt;
            })->first();

            if ($result1sFiltered) {
                $totalShow = $result1sFiltered->total_show;
            }

            $result1sFiltered = collect($result2s)->filter(function ($result1) use ($createdAt) {
                return $result1->created_at === $createdAt;
            })->first();

            if ($result1sFiltered) {
                $totalTour = $result1sFiltered->total_tour;
            }

            $dataArray[] = [
                $result->name,
                $result->role_name,
                date('d-m-Y', strtotime($createdAt)),
                date('H:i:s', strtotime($result->login_time)),
                date('H:i:s', strtotime($result->logout_time)),
                $formatedTime,
                $result->total_call,
                $result->total_connection,
                $result->total_no_answer,
                $result->total_appointment,
                $totalShow,
                $totalTour,
            ];
        }

        return view('report.sale_kpis._template_export', [
            'datas' => $dataArray,
        ]);
    }
}