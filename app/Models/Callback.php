<?php

namespace App\Models;

use App\Traits\LeadManagementTrait;

/**
 * App\Models\Callback
 *
 * @property int $id
 * @property int $user_id
 * @property int $lead_id
 * @property \Illuminate\Support\Carbon|null $callback_datetime
 * @property int $state                                 1: Done; -1: Not Yet
 * @property \Illuminate\Support\Carbon|null $call_date Thời gian đã gọi lại
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read \App\Models\ActivityLog $createdBy
 * @property-read \App\Models\ActivityLog $deletedBy
 * @property-read mixed $confirmations
 * @property-read mixed $created_at_text
 * @property-read \App\Models\Lead $lead
 * @property-read \App\Models\ActivityLog $updatedBy
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel andFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel dateBetween($dates, $column = 'created_at', $format = 'd-m-Y', $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel filters($filterDatas, $boolean = 'and', $filterConfigs = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereCallDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereCallbackDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Callback whereUserId($value)
 * @mixin \Eloquent
 */
class Callback extends \App\Models\Base\Callback
{
    use LeadManagementTrait;

    public static $logName = 'Callback';
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    public $labels = [];
    public $filters = [
        'user_id' => '=',
        'lead_id' => '=',
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
    protected $fillable = [
        'user_id',
        'lead_id',
        'state',
        'callback_datetime',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return parent::getDescriptionEvent($eventName);
    }

    public function getStateTextAttribute()
    {
        $stateText = 'Not yet';
        if ($this->state == 1) {
            $stateText = 'Done';
        }

        return $this->contextLabel($stateText, $this->state === 1 ? 'success' : 'danger');
    }
}
