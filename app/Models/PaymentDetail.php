<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * App\Models\PaymentDetail
 *
 * @property int $id
 * @property int $contract_id
 * @property int $payment_cost_id
 * @property int|null $pay_time Lần thanh toán
 * @property float $total_paid_deal
 * @property float $total_paid_real
 * @property string|null $bank_name
 * @property string|null $bank_no
 * @property string|null $note
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereBankNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail wherePaymentCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereTotalPaidDeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereTotalPaidReal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentDetail extends \App\Models\Base\PaymentDetail
{
	protected $fillable = [
		'contract_id',
		'payment_cost_id',
		'pay_time',
		'pay_date',
		'total_paid_deal',
		'total_paid_real',
		'bank_name',
		'bank_no',
		'note'
	];
	public static $logName = 'PaymentDetail';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [];

    public $filters = [];

    protected $dates = [
        'pay_date'
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

    public function setPayDateAttribute($value)
    {
        if ($value) {
            $this->attributes['pay_date'] = $value instanceof DateTime ? $value->format('Y-m-d') : Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

    public function payment_cost()
    {
        return $this->belongsTo(\App\Models\PaymentCost::class);
    }
}
