<?php

/**
 * Created by hieu.pham.
 * Date: Wed, 21 Nov 2018 10:20:07 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Lead
 * 
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $email
 * @property int $gender
 * @property \Carbon\Carbon $birthday
 * @property string $address
 * @property int $province_id
 * @property string $phone
 * @property int $state
 * @property \Carbon\Carbon $call_date
 * @property string $comment
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Province $province
 * @property \Illuminate\Database\Eloquent\Collection $appointments
 * @property \Illuminate\Database\Eloquent\Collection $callbacks
 * @property \Illuminate\Database\Eloquent\Collection $event_datas
 * @property \Illuminate\Database\Eloquent\Collection $history_calls
 *
 * @package App\Models\Base
 */
class Lead extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'gender' => 'int',
		'province_id' => 'int',
		'state' => 'int',
		'user_id' => 'int'
	];

	protected $dates = [
		'birthday',
		'call_date'
	];

	public function province()
	{
		return $this->belongsTo(\App\Models\Province::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function appointments()
	{
		return $this->hasMany(\App\Models\Appointment::class);
	}

	public function callbacks()
	{
		return $this->hasMany(\App\Models\Callback::class);
	}

	public function event_datas()
	{
		return $this->hasMany(\App\Models\EventData::class);
	}

	public function history_calls()
	{
		return $this->hasMany(\App\Models\HistoryCall::class);
	}
}
