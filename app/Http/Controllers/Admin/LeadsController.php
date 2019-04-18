<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tables\Admin\LeadTable;
use App\Tables\TableFacade;

class LeadsController extends Controller
{
    /**
     * Tên dùng để phân quyền
     * @var string
     */
    protected $name = 'lead';

    /**
     * Lấy danh sách Lead cho trang table ở trang index
     * @return string
     */
    public function table()
    {
        return (new TableFacade(new LeadTable()))->getDataTable();
    }
}