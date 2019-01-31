<?php

namespace App\Models;

/**
 * App\Models\Commission
 *
 * @property int $id
 * @property int|null $contract_id
 * @property int|null $user_id
 * @property float|null $net_total
 * @property float|null $to_percent
 * @property float|null $tele_amount
 * @property float|null $rep_percent
 * @property float|null $cs_percent
 * @property float|null $total_percent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read \App\Models\ActivityLog $createdBy
 * @property-read \App\Models\ActivityLog $deletedBy
 * @property-read mixed $confirmations
 * @property-read mixed $created_at_text
 * @property-read \App\Models\ActivityLog $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel andFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel dateBetween($dates, $column = 'created_at', $format = 'd-m-Y', $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel filters($filterDatas, $boolean = 'and', $filterConfigs = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereCsPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereNetTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereRepPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereTeleAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereToPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereTotalPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Commission whereUserId($value)
 * @mixin \Eloquent
 */
class Commission extends \App\Models\Base\Commission
{
	protected $fillable = [
		'contract_id',
		'user_id',
		'net_total',
		'to_percent',
		'tele_amount',
		'rep_percent',
		'cs_percent',
		'total_percent'
	];
	public static $logName = 'Commission';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [];

    public $filters = [
        'contract_no' => 'like'
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

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
