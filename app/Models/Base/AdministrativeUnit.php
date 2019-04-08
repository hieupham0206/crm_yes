<?php

/**
 * Created by hieu.pham.
 * Date: Mon, 08 Apr 2019 14:46:45 +0700.
 */

namespace App\Models\Base;

use App\Models\BaseModel as Eloquent;

/**
 * Class AdministrativeUnit
 * 
 * @property int $id
 * @property string $city_name
 * @property string $city_code
 * @property string $county_name
 * @property string $county_code
 * @property string $ward_name
 * @property string $ward_code
 *
 * @package App\Models\Base
 */
class AdministrativeUnit extends Eloquent
{
	use \Spatie\Activitylog\Traits\LogsActivity;
	public $timestamps = false;
}
