<?php

namespace App\Models;

use App\Enums\EventDataState;

/**
 * App\Models\EventData
 *
 * @property int $id
 * @property int|null $rep_id
 * @property int|null $to_id
 * @property int|null $cs_id
 * @property int $appointment_id
 * @property int $lead_id Tương ứng với lead_id bên appointment
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $time_in
 * @property \Illuminate\Support\Carbon|null $time_out
 * @property string|null $note
 * @property int|null $state 1: Không Deal; 2: Deal; 3: Busy; 4: Overflow
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $hot_bonus -1: Không; 1: Có
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read \App\Models\Appointment $appointment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Contract[] $contracts
 * @property-read \App\Models\ActivityLog $createdBy
 * @property-read \App\Models\User|null $cs
 * @property-read \App\Models\ActivityLog $deletedBy
 * @property-read mixed $confirmations
 * @property-read mixed $created_at_text
 * @property-read mixed $state_name
 * @property-read mixed $states
 * @property-read \App\Models\Lead $lead
 * @property-read \App\Models\User|null $rep
 * @property-read \App\Models\User|null $to
 * @property-read \App\Models\ActivityLog $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel andFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel dateBetween($dates, $column = 'created_at', $format = 'd-m-Y', $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel filters($filterDatas, $boolean = 'and', $filterConfigs = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereCsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereHotBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereTimeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereTimeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventData whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function cs()
    {
        return $this->belongsTo(User::class, 'cs_id', 'id');
    }

    public function getStateNameAttribute()
    {
        return EventDataState::getDescription($this->state);
    }
}
