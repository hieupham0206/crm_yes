<?php

/**
 * Created by hieu.pham.
 * Date: Wed, 09 Jan 2019 15:22:53 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class PaymentDetail
 * 
 * @property int $id
 * @property int $contract_id
 * @property int $payment_cost_id
 * @property int $pay_time
 * @property float $total_paid_deal
 * @property float $total_paid_real
 * @property string $bank_name
 * @property string $bank_no
 * @property string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models\Base
 */
class PaymentDetail extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;

	protected $casts = [
		'contract_id' => 'int',
		'payment_cost_id' => 'int',
		'pay_time' => 'int',
		'total_paid_deal' => 'float',
		'total_paid_real' => 'float'
	];
}
