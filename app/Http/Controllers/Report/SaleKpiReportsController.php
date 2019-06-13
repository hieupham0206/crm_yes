<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/16/2018
 * Time: 4:49 PM
 */

namespace App\Http\Controllers\Report;

use App\Exports\SaleKpiExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Tables\Report\SaleKpiReportTable;
use App\Tables\TableFacade;
use Illuminate\Http\Request;

class SaleKpiReportsController extends Controller
{
    /**
     * Tên dùng để phần quyền
     * @var string
     */
    protected $name = 'sale-kpi-report';

    public function index()
    {
        return view('report.sale_kpis.index', [
            'user' => new User(),
        ]);
    }

    public function table()
    {
        return (new TableFacade(new SaleKpiReportTable()))->getDataTable();
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();

        return (new SaleKpiExport($filters))->download('sale_kpi_' . time() . '.xlsx');
    }
}