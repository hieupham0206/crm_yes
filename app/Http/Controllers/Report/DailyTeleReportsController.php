<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/16/2018
 * Time: 4:49 PM
 */

namespace App\Http\Controllers\Report;

use App\Exports\DailyTeleExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Tables\Report\DailyTeleReportTable;
use App\Tables\TableFacade;
use Illuminate\Http\Request;

class DailyTeleReportsController extends Controller
{
    /**
     * Tên dùng để phần quyền
     * @var string
     */
    protected $name = 'daily-tele-report';

    public function index()
    {
        return view('report.daily_teles.index', [
            'user' => new User()
        ]);
    }

    public function table()
    {
        return (new TableFacade(new DailyTeleReportTable()))->getDataTable();
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();

        return (new DailyTeleExport($filters))->download('daily_tele_' . time() . '.xlsx');
    }
}