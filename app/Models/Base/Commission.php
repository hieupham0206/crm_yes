<?php

/**
 * Created by hieu.pham.
 * Date: Fri, 25 Jan 2019 10:06:54 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Commission
 * 
 * @property int $id
 * @property int $contract_id
 * @property int $user_id
 * @property float $sdm_percent
 * @property float $to_percent
 * @property float $tele_percent
 * @property float $private_percent
 * @property float $ambassador_percent
 * @property float $rep_percent
 * @property float $cs_percent
 * @property float $homesit_percent
 * @property float $total_percent
 * @property float $provisional_commission
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models\Base
 */
class Commission extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'contract_id' => 'int',
		'user_id' => 'int',
		'sdm_percent' => 'float',
		'to_percent' => 'float',
		'tele_percent' => 'float',
		'private_percent' => 'float',
		'ambassador_percent' => 'float',
		'rep_percent' => 'float',
		'cs_percent' => 'float',
		'homesit_percent' => 'float',
		'total_percent' => 'float',
		'provisional_commission' => 'float'
	];
}
