<?php

/**
 * Created by hieu.pham.
 * Date: Wed, 09 Jan 2019 15:26:38 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class PaymentCost
 * 
 * @property int $id
 * @property string $payment_method
 * @property string $name
 * @property string $payment_cost
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models\Base
 */
class PaymentCost extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;
}
