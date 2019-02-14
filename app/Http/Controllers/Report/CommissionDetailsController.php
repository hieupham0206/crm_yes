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
use App\Tables\Report\CommissionDetailTable;
use App\Tables\TableFacade;

class CommissionDetailsController extends Controller
{
    /**
     * Tên dùng để phần quyền
     * @var string
     */
    protected $name = 'commissionDetail';

    public function index()
    {
        return view('report.commission_details.index', [
            'contract' => new Commission(),
        ]);
    }

    public function table()
    {
        return (new TableFacade(new CommissionDetailTable()))->getDataTable();
    }
}