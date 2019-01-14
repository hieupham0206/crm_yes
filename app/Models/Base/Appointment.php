<?php

/**
 * Created by hieu.pham.
 * Date: Wed, 21 Nov 2018 10:25:26 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Appointment
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lead_id
 * @property string $code
 * @property string $spouse_name
 * @property string $spouse_phone
 * @property \Carbon\Carbon $appointment_datetime
 * @property int $state
 * @property int $is_show_up
 * @property int $is_queue
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Lead $lead
 * @property \App\Models\User $user
 *
 * @package App\Models\Base
 */
class Appointment extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'user_id' => 'int',
		'lead_id' => 'int',
		'state' => 'int',
		'is_show_up' => 'int',
		'is_queue' => 'int'
	];

	protected $dates = [
		'appointment_datetime'
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
