<?php

/**
 * Created by hieu.pham.
 * Date: Tue, 27 Nov 2018 21:25:32 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Callback
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lead_id
 * @property \Carbon\Carbon $callback_datetime
 * @property int $state
 * @property \Carbon\Carbon $call_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Lead $lead
 * @property \App\Models\User $user
 *
 * @package App\Models\Base
 */
class Callback extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'user_id' => 'int',
		'lead_id' => 'int',
		'state' => 'int'
	];

	protected $dates = [
		'callback_datetime',
		'call_date'
	];

	public function lead()
	{
		return $this->belongsTo(\App\Models\Lead::class)->withDefault();
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
