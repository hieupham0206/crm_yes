<?php

namespace App\Models;

class Commission extends \App\Models\Base\Commission
{
	protected $fillable = [
		'contract_id',
		'user_id',
		'sdm_percent',
		'to_percent',
		'tele_percent',
		'private_percent',
		'ambassador_percent',
		'rep_percent',
		'cs_percent',
		'homesit_percent',
		'total_percent',
		'provisional_commission'
	];
	public static $logName = 'Commission';

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
