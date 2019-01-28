<?php

/**
 * Created by hieu.pham.
 * Date: Mon, 28 Jan 2019 11:16:29 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class CommissionRole
 * 
 * @property int $id
 * @property int $role_id
 * @property int $specification
 * @property int $level
 * @property float $percent_commission
 * @property float $percent_commission_bonus
 * @property float $deal_completed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models\Base
 */
class CommissionRole extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'role_id' => 'int',
		'specification' => 'int',
		'level' => 'int',
		'percent_commission' => 'float',
		'percent_commission_bonus' => 'float',
		'deal_completed' => 'float'
	];
}
