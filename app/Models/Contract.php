<?php

namespace App\Models;

use App\Enums\ContractLimit;
use App\Enums\ContractMembership;
use App\Enums\ContractRoomType;
use App\Enums\ContractState;
use App\Enums\PaymentMethod;
use Carbon\Carbon;

/**
 * App\Models\Contract
 *
 * @property int $id
 * @property int $member_id
 * @property int $event_data_id
 * @property float $amount
 * @property float $net_amount
 * @property int $state                                   -1: Not yet; 1: Done; 2: Problem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $contract_no
 * @property int $membership                              1: Dynasty; 2: Emerald; 3: Crystal
 * @property int $room_type                               1: 1 giường; 2: 2 giường; 3: 3 giường; 4: phòng ngủ
 * @property int $limit                                   1: 2 lớn, 2 nhỏ <6 2: 4 lớn, 2 nhỏ <6 3: 6 lớn, 2 nhỏ <6
 * @property \Illuminate\Support\Carbon|null $signed_date Ngày kí hợp đồng
 * @property \Illuminate\Support\Carbon|null $effective_time
 * @property \Illuminate\Support\Carbon|null $start_date  Năm bắt đầu
 * @property int|null $end_time                           Số năm
 * @property float $year_cost                             Chi phí hàng năm
 * @property int $num_of_payment                          Số lần thanh toán
 * @property float $total_payment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read \App\Models\ActivityLog $createdBy
 * @property-read \App\Models\ActivityLog $deletedBy
 * @property-read \App\Models\EventData $event_data
 * @property-read mixed $confirmations
 * @property-read mixed $created_at_text
 * @property-read mixed $limit_text
 * @property-read mixed $limits
 * @property-read mixed $membership_text
 * @property-read mixed $memberships
 * @property-read mixed $room_type_text
 * @property-read mixed $room_types
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\ActivityLog $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel andFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel dateBetween($dates, $column = 'created_at', $format = 'd-m-Y', $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel filters($filterDatas, $boolean = 'and', $filterConfigs = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereContractNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereEffectiveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereEventDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereMembership($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereNumOfPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereRoomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereSignedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereTotalPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereYearCost($value)
 * @mixin \Eloquent
 */
class Contract extends \App\Models\Base\Contract
{
    protected $fillable = [
        'member_id',
        'event_data_id',
        'amount',
        'net_amount',
        'state',
        'contract_no',
        'membership',
        'room_type',
        'limit',
        'signed_date',
        'effective_time',
        'start_date',
        'end_time',
        'year_cost',
        'num_of_payment',
        'total_payment',
        'cotnract_fee_cost'
    ];
    public static $logName = 'Contract';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [
        'membership'      => 'Loại',
        'end_time'        => 'Hết hạn',
        'amount'          => 'Giá trị hợp đồng',
        'signed_date'     => 'Ngày kí hợp đồng',
        'room_type'       => 'Loại phòng',
        'limit'           => 'Giới hạn',
        'start_date'      => 'Năm bắt đầu',
        'year_cost'       => 'Phí quản lý hàng năm',
        'total_paid_deal' => 'Số tiền thanh toán lần đầu',
        'pay_date'        => 'Ngày thanh toán',
        'contract_no'     => 'Số hợp đồng',
    ];

    public $filters = [
        'contract_no' => 'like',
        'phone'       => 'like',
        'lead_id'     => '=',
        'membership'  => '=',
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

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class);
    }

    public function payment_cost()
    {
        return $this->belongsTo(\App\Models\PaymentCost::class);
    }

    public function getMembershipsAttribute()
    {
        return ContractMembership::toSelectArray();
    }

    public function getMembershipTextAttribute()
    {
        return ContractMembership::getDescription($this->membership);
    }

    public function getLimitsAttribute()
    {
        return ContractLimit::toSelectArray();
    }

    public function getLimitTextAttribute()
    {
        return ContractLimit::getDescription($this->limit);
    }

    public function getRoomTypesAttribute()
    {
        return ContractRoomType::toSelectArray();
    }

    public function getRoomTypeTextAttribute()
    {
        return ContractRoomType::getDescription($this->room_type);
    }

    public static function createContractNo($contractNo, $provinceId)
    {
        switch ($provinceId) {
            case 24:
                $contractNo = "HN(HYV$contractNo)";
                break;
            case 28:
                $contractNo = "HP(PYV$contractNo)";
                break;
            case 30:
                $contractNo = "SG(YV$contractNo)";
                break;
        }

        return $contractNo;
    }

    public static function checkContractNoExists($contractNo)
    {
        return self::where('contract_no', $contractNo)->exists();
    }

    public function setSignedDateAttribute($value)
    {
        if ($value) {
            $this->attributes['signed_date'] = $value instanceof DateTime ? $value->format('Y-m-d') : Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

    public function setStartDateAttribute($value)
    {
        if ($value) {
            $this->attributes['start_date'] = $value instanceof DateTime ? $value->format('Y-m-d') : Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

    public function payment_details()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function changeState($state)
    {
        return $this->update(['state' => $state]);
    }

    public function markContractDone()
    {
        $paymentDetails = $this->payment_details()->whereHas('payment_cost', function($q) {
            $q->where('payment_method', PaymentMethod::AMORTIZATION);
        })->get();

        if ($paymentDetails->count() > 0) {
            $this->changeState(ContractState::INSTALLMENT);
        }

        $this->changeState(ContractState::FULL);
    }
}
