<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/16/2018
 * Time: 4:49 PM
 */

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Tables\Report\DailySaleReportTable;
use App\Tables\TableFacade;

class DailySaleReportsController extends Controller
{
    /**
     * Tên dùng để phần quyền
     * @var string
     */
    protected $name = 'daily-sale-report';

    public function index()
    {
        return view('report.daily_sales.index', [
            'user' => new User()
        ]);
    }

    public function table()
    {
        return (new TableFacade(new DailySaleReportTable()))->getDataTable();
    }
}