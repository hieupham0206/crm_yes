<?php

/**
 * Created by hieu.pham.
 * Date: Wed, 16 Jan 2019 13:38:14 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Member
 * 
 * @property int $id
 * @property string $title
 * @property string $name
 * @property int $gender
 * @property \Carbon\Carbon $birthday
 * @property string $address
 * @property int $city
 * @property string $phone
 * @property string $email
 * @property string $spouse_name
 * @property string $spouse_phone
 * @property \Carbon\Carbon $spouse_birthday
 * @property string $spouse_email
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $spouse_title
 * @property string $identity
 * @property int $identity_address
 * @property \Carbon\Carbon $identity_date
 * @property string $spouse_identity
 * @property int $spouse_identity_address
 * @property \Carbon\Carbon $spouse_identity_date
 * 
 * @property \Illuminate\Database\Eloquent\Collection $contracts
 * @property \Illuminate\Database\Eloquent\Collection $history_calls
 *
 * @package App\Models\Base
 */
class Member extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'gender' => 'int',
		'city' => 'int',
		'identity_address' => 'int',
		'spouse_identity_address' => 'int'
	];

	protected $dates = [
		'birthday',
		'spouse_birthday',
		'identity_date',
		'spouse_identity_date'
	];

	public function contracts()
	{
		return $this->hasMany(\App\Models\Commission::class);
	}

	public function history_calls()
	{
		return $this->hasMany(\App\Models\HistoryCall::class);
	}
}
