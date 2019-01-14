<?php

/**
 * Created by hieu.pham.
 * Date: Tue, 27 Nov 2018 21:01:57 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Audit
 * 
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $time_in
 * @property \Carbon\Carbon $time_out
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models\Base
 */
class Audit extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $dates = [
		'time_in',
		'time_out'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
