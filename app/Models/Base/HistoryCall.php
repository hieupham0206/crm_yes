<?php

/**
 * Created by hieu.pham.
 * Date: Tue, 13 Nov 2018 20:45:38 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class HistoryCall
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lead_id
 * @property int $member_id
 * @property int $time_of_call
 * @property int $type
 * @property string $comment
 * @property int $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Lead $lead
 * @property \App\Models\Member $member
 * @property \App\Models\User $user
 *
 * @package App\Models\Base
 */
class HistoryCall extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'user_id' => 'int',
		'lead_id' => 'int',
		'member_id' => 'int',
		'time_of_call' => 'int',
		'type' => 'int',
		'state' => 'int'
	];

	public function lead()
	{
		return $this->belongsTo(\App\Models\Lead::class)->withDefault();
	}

	public function member()
	{
		return $this->belongsTo(\App\Models\Member::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
