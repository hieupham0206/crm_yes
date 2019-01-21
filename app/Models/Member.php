<?php

namespace App\Models;

use App\Enums\PersonTitle;
use Carbon\Carbon;

/**
 * App\Models\Member
 *
 * @property int $id
 * @property string|null $title
 * @property string $name
 * @property int $gender                       1: Nam; 2: Nữ
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $address
 * @property int|null $city                    Tỉnh thành phố
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $spouse_name
 * @property string|null $spouse_phone
 * @property \Illuminate\Support\Carbon|null $spouse_birthday
 * @property string|null $spouse_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $spouse_title
 * @property string|null $identity
 * @property int|null $identity_address        Tỉnh cấp CMND
 * @property \Illuminate\Support\Carbon|null $identity_date
 * @property string|null $spouse_identity
 * @property int|null $spouse_identity_address Tỉnh cấp CMND
 * @property \Illuminate\Support\Carbon|null $spouse_identity_date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Contract[] $contracts
 * @property-read \App\Models\ActivityLog $createdBy
 * @property-read \App\Models\ActivityLog $deletedBy
 * @property-read mixed $confirmations
 * @property-read mixed $created_at_text
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HistoryCall[] $history_calls
 * @property-read \App\Models\ActivityLog $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel andFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel dateBetween($dates, $column = 'created_at', $format = 'd-m-Y', $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel filters($filterDatas, $boolean = 'and', $filterConfigs = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereIdentityAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereIdentityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpouseBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpouseEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpouseIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpouseIdentityAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpouseIdentityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpouseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpousePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereSpouseTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Member extends \App\Models\Base\Member
{
    protected $fillable = [
        'title',
        'name',
        'gender',
        'birthday',
        'address',
        'city',
        'phone',
        'email',
        'spouse_name',
        'spouse_phone',
        'spouse_birthday',
        'spouse_email',
        'spouse_title',
        'identity',
        'identity_address',
        'identity_date',
        'spouse_identity',
        'spouse_identity_address',
        'spouse_identity_date',
    ];
    public static $logName = 'Member';

    protected static $logOnlyDirty = true;

    protected static $logFillable = true;

    public $labels = [
        'identity'         => 'CMND/Hộ chiếu',
        'identity_address' => 'Nơi cấp',
        'identity_date'    => 'Ngày cấp',

        'spouse_identity'         => 'CMND/Hộ chiếu',
        'spouse_identity_address' => 'Nơi cấp',
        'spouse_identity_date'    => 'Ngày cấp',

        'birthday'        => 'Ngày sinh',
        'spouse_birthday' => 'Ngày sinh',

        'title'        => 'Anh/Chị',
        'spouse_title' => 'Anh/Chị',

        'name' => 'Tên',
    ];

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

    public static function isMember($identityHusband, $identityWife)
    {
        return self::where('identity', $identityHusband)->orWhere('spouse_identity', $identityWife)->first();
    }

    public function getTitlesAttribute()
    {
        return PersonTitle::toSelectArray();
    }

    public function setSpouseIdentityDateAttribute($value)
    {
        if ($value) {
            $this->attributes['spouse_identity_date'] = $value instanceof DateTime ? $value->format('Y-m-d') : Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

    public function setIdentityDateAttribute($value)
    {
        if ($value) {
            $this->attributes['identity_date'] = $value instanceof DateTime ? $value->format('Y-m-d') : Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

    public function setBirthdayAttribute($value)
    {
        if ($value) {
            $this->attributes['spouse_birthday'] = $value instanceof DateTime ? $value->format('Y-m-d') : Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

    public function setSpouseBirthdayAttribute($value)
    {
        if ($value) {
            $this->attributes['birthday'] = $value instanceof DateTime ? $value->format('Y-m-d') : Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

    public function province()
    {
        return $this->belongsTo(\App\Models\Province::class, 'city', 'id');
    }
}
