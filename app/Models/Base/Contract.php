<?php

/**
 * Created by hieu.pham.
 * Date: Wed, 09 Jan 2019 15:17:50 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Contract
 * 
 * @property int $id
 * @property int $member_id
 * @property int $event_data_id
 * @property float $amount
 * @property float $net_amount
 * @property int $membership
 * @property int $room_type
 * @property int $limit
 * @property \Carbon\Carbon $effective_time
 * @property int $end_time
 * @property float $year_cost
 * @property int $num_of_payment
 * @property float $total_payment
 * @property int $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\EventData $event_data
 * @property \App\Models\Member $member
 *
 * @package App\Models\Base
 */
class Contract extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'member_id' => 'int',
		'event_data_id' => 'int',
		'amount' => 'float',
		'net_amount' => 'float',
		'membership' => 'int',
		'room_type' => 'int',
		'limit' => 'int',
		'end_time' => 'int',
		'year_cost' => 'float',
		'num_of_payment' => 'int',
		'total_payment' => 'float',
		'state' => 'int'
	];

	protected $dates = [
		'effective_time'
	];

	public function event_data()
	{
		return $this->belongsTo(\App\Models\EventData::class);
	}

	public function member()
	{
		return $this->belongsTo(\App\Models\Member::class);
	}
}
