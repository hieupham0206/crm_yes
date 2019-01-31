<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/16/2018
 * Time: 4:49 PM
 */

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\User;
use App\Tables\Cs\CommissionDetailTable;
use App\Tables\Cs\CommissionUserTable;
use App\Tables\Report\DailyTeleReportTable;
use App\Tables\TableFacade;

class CommissionUsersController extends Controller
{
    /**
     * Tên dùng để phần quyền
     * @var string
     */
    protected $name = 'commission';

    public function index()
    {
        return view('report.commission_users.index', [
            'contract' => new Commission()
        ]);
    }

    public function table()
    {
        return (new TableFacade(new CommissionUserTable()))->getDataTable();
    }
}