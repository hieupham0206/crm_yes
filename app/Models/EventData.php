<?php

namespace App\Models;

use App\Enums\EventDataState;

class EventData extends \App\Models\Base\EventData
{
    protected $fillable = [
        'appointment_id',
        'lead_id',
        'code',
        'time_in',
        'time_out',
        'state',
        'to_id',
        'rep_id',
        'cs_id',
        'note',
        'hot_bonus',
    ];
    public static $logName = 'Event Data';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [];

    public $filters = [
        'code'    => 'like',
        'lead_id' => '=',
        'to_id'   => '=',
        'rep_id'  => '=',
        'state'   => '=',
    ];

    protected $appends = [
        'state_name'
    ];

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

    public function getStatesAttribute()
    {
        return \App\Enums\EventDataState::toSelectArray();
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id', 'id');
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id', 'id');
    }

    public function getStateNameAttribute()
    {
        return EventDataState::getDescription($this->state);
    }
}
