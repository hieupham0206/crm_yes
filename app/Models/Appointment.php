<?php

namespace App\Models;

use App\Enums\Confirmation;
use App\Traits\LeadManagementTrait;

/**
 * App\Models\Appointment
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $lead_id
 * @property string|null $code
 * @property string|null $spouse_name
 * @property string|null $spouse_phone
 * @property \Illuminate\Support\Carbon|null $appointment_datetime
 * @property int $state      -1: Hủy; 1: Sử dụng;
 * @property int $is_show_up -1: Không; 1: Có
 * @property int $is_queue   -1: Không; 1: Có
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventData[] $busyEvents
 * @property-read \App\Models\ActivityLog $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventData[] $dealEvents
 * @property-read \App\Models\ActivityLog $deletedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventData[] $events
 * @property-read mixed $confirmations
 * @property-read mixed $created_at_text
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventData[] $noRepEvents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventData[] $overflowEvents
 * @property-read \App\Models\ActivityLog $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel andFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel dateBetween($dates, $column = 'created_at', $format = 'd-m-Y', $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel filters($filterDatas, $boolean = 'and', $filterConfigs = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereAppointmentDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereIsQueue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereIsShowUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereSpouseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereSpousePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereUserId($value)
 * @mixin \Eloquent
 */
class Appointment extends \App\Models\Base\Appointment
{
    use LeadManagementTrait;

    protected $fillable = [
        'user_id',
        'lead_id',
        'state',
        'type',
        'appointment_datetime',
        'code',
        'spouse_name',
        'spouse_phone',
        'is_show_up',
        'is_queue',
        'state',
        'ambassador'
    ];
    public static $logName = 'Appointment';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [
        'position'   => 'Vị trí',
        'lead_name'  => 'Tên khách hàng',
        'is_show_up' => 'Show up',
        'is_queue'   => 'Queue',
    ];

    public $filters = [
        'user_id'    => '=',
        'lead_id'    => '=',
        'code'       => 'like',
        'is_show_up' => '=',
        'is_queue'   => '=',
        'state'      => '=',
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

    public function getIsQueueTextAttribute()
    {
        return $this->contextLabel(Confirmation::getDescription($this->is_queue), $this->is_queue === 1 ? 'success' : 'danger');
    }

    public function getIsShowUpTextAttribute()
    {
        return $this->contextLabel(Confirmation::getDescription($this->is_show_up), $this->is_show_up === 1 ? 'success' : 'danger');
    }

    public function cancel()
    {
        return $this->update(['state' => Confirmation::NO]);
    }

    public function notShowUp()
    {
        return $this->update(['is_show_up' => Confirmation::NO]);
    }

    public function events()
    {
        return $this->hasMany(EventData::class);
    }

    public function noRepEvents()
    {
        return $this->hasMany(EventData::class)->whereNull('rep_id');
    }

    public function overflowEvents()
    {
        return $this->hasMany(EventData::class)->whereState(4);
    }

    public function busyEvents()
    {
        return $this->hasMany(EventData::class)->whereState(3);
    }

    public function dealEvents()
    {
        return $this->hasMany(EventData::class)->whereState(2);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function history_calls()
    {
        return $this->morphMany(HistoryCall::class, 'call');
    }

    public static function checkPhoneIsShowUp($phone)
    {
        return self::whereHas('lead', function($q) use ($phone) {
            $q->where('phone', $phone);
        })->where('is_show_up', -1)->exists();
    }
}
