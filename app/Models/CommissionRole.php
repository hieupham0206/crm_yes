<?php

namespace App\Models;

class CommissionRole extends \App\Models\Base\CommissionRole
{
	protected $fillable = [
		'role_id',
		'specification',
		'level',
		'percent_commission',
		'percent_commission_bonus',
		'deal_completed'
	];
	public static $logName = 'CommissionRole';

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
