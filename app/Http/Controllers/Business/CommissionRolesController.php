<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/16/2018
 * Time: 4:49 PM
 */

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CommissionRole;
use App\Tables\Business\CommissionRoleTable;
use App\Tables\Cs\CommissionTable;
use App\Tables\TableFacade;

class CommissionRolesController extends Controller
{
    /**
     * Tên dùng để phần quyền
     * @var string
     */
    protected $name = 'commission';

    public function index()
    {
        return view('business.commission_roles.index', [
            'contract' => new Commission(),
        ]);
    }

    public function table()
    {
        return (new TableFacade(new CommissionRoleTable()))->getDataTable();
    }

    public function store()
    {
        $percentCommission = request()->get('percentCommission');
        $level             = request()->get('level');
        $bonusCommission   = request()->get('bonusCommission');
        $dealCompleted     = request()->get('dealCompleted');
        $roleId            = request()->get('roleId');
        $commissionRoleId  = request()->get('commissionRoleId');
        $spec              = request()->get('spec');
        $attributes        = [
            'level'                    => $level,
            'percent_commission'       => $percentCommission,
            'percent_commission_bonus' => $bonusCommission,
            'deal_completed'           => $dealCompleted,
            'role_id'                  => $roleId,
            'specification'            => $spec ?: 1,
        ];

        if ($commissionRoleId) {
            $commissionRole = CommissionRole::find($commissionRoleId);
            $commissionRole->update($attributes);
        } else {
            CommissionRole::create($attributes);
        }

        return response()->json([
            'message' => __('Data created successfully'),
        ]);
    }
}