<?php

/**
 * Created by hieu.pham.
 * Date: Fri, 15 Mar 2019 09:18:04 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class SmsLog
 * 
 * @property int $id
 * @property string $params
 * @property string $response
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models\Base
 */
class SmsLog extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;
}
