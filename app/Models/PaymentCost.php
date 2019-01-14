<?php

namespace App\Models;

use App\Enums\PaymentMethod;

/**
 * App\Models\PaymentCost
 *
 * @property int $id
 * @property string|null $payment_method 1: Tiền mặt
 * 2: Trả góp ngân hàng
 * 3: Cà thẻ
 * 4: Chuyển khoản
 * @property string|null $name
 * @property string|null $payment_cost
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost wherePaymentCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentCost extends \App\Models\Base\PaymentCost
{
    protected $fillable = [
        'payment_method',
        'bank_name',
        'cost',
    ];
    public static $logName = 'PaymentCost';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [
        'bank_name'      => 'Tên ngân hàng',
        'payment_method' => 'Hình thức thanh toán',
        'cost'           => 'Phí',
        'bank_no'         => 'Số tài khoản',
    ];

    public $filters = [
        'payment_method' => '=',
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

    public function getPaymentMethodsAttribute()
    {
        return PaymentMethod::toSelectArray();
    }

    public function getPaymentMethodTextAttribute()
    {
        return PaymentMethod::getDescription($this->payment_method);
    }
}
