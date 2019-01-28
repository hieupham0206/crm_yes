<?php

/**
 * Created by hieu.pham.
 * Date: Mon, 28 Jan 2019 11:16:00 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class Commission
 * 
 * @property int $id
 * @property int $contract_id
 * @property int $user_id
 * @property float $net_total
 * @property float $to_percent
 * @property float $tele_amount
 * @property float $rep_percent
 * @property float $cs_percent
 * @property float $total_percent
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
		'net_total' => 'float',
		'to_percent' => 'float',
		'tele_amount' => 'float',
		'rep_percent' => 'float',
		'cs_percent' => 'float',
		'total_percent' => 'float'
	];
}
