<?php

namespace App\Models;

class AdministrativeUnit extends \App\Models\Base\AdministrativeUnit
{
	protected $fillable = [
		'city_name',
		'city_code',
		'county_name',
		'county_code',
		'ward_name',
		'ward_code'
	];
	public static $logName = 'AdministrativeUnit';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [];

    public $filters = [];

    /**
     * Route của model dùng cho Linkable trait
     * @var string
     */
    public $route = '';

    /**
     * Column dùng để hiển thị cho model (Default là name)
     * @var string
     */
     public $displayAttribute = 'name';

	public function getDescriptionForEvent(string $eventName): string
    {
        return parent::getDescriptionEvent($eventName);
    }
}
