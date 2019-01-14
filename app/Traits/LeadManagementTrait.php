<?php
/**
 * User: ADMIN
 * Date: 12/4/2018 9:58 PM
 */

namespace App\Traits;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;

trait LeadManagementTrait
{
    public function scopeAuthorize(Builder $builder)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $builder;
        }

        if ($user->isManager()) {
            //1 Manager quan lý nhiều phòng. Xem toàn bộ các báo cáo của TELE_LEADER và TELE_MARKETER cùng phòng

            $departmentsOfManager = $user->getDepartmentsOfManager();
            $managedUserIds       = [];
            foreach ($departmentsOfManager as $department) {
                $managedUserIds[] = $department->users->pluck('id');
            }
            $managedUserIds = collect($managedUserIds)->flatten()->unique()->toArray();
            $leadIds        = Lead::getLeadOfLeader($managedUserIds)->pluck('id');

            $builder->whereIn('lead_id', $leadIds);
        } elseif ($user->isLeader()) {
            //1 Leader quan lý 1 phòng. Xem toàn bộ các báo cáo của TELE_MARKETER cùng phòng

            $departmentOfLeader = $user->getDepartmentOfLeader();
            $managedUserIds     = $departmentOfLeader->users->pluck('id');
            $leadIds            = Lead::getLeadOfLeader($managedUserIds)->pluck('id');

            $builder->whereIn('lead_id', $leadIds);
        } else {
            $builder->where('user_id', $user->id);
        }

        return $builder;
    }
}