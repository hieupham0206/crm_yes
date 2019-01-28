<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/16/2018
 * Time: 4:49 PM
 */

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\User;
use App\Tables\Cs\CommissionDetailTable;
use App\Tables\Cs\CommissionTable;
use App\Tables\Report\DailyTeleReportTable;
use App\Tables\TableFacade;

class CommissionsController extends Controller
{
    /**
     * Tên dùng để phần quyền
     * @var string
     */
    protected $name = 'commission';

    public function index()
    {
        return view('business.commissions.index', [
            'contract' => new Contract()
        ]);
    }

    public function table()
    {
        return (new TableFacade(new CommissionTable()))->getDataTable();
    }
}