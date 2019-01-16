<?php

/**
 * Created by hieu.pham.
 * Date: Wed, 16 Jan 2019 10:42:24 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class PaymentCost
 * 
 * @property int $id
 * @property string $payment_method
 * @property string $bank_name
 * @property string $cost
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models\Base
 */
class PaymentCost extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;
}
