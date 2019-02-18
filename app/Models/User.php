<?php

namespace App\Models;

use App\Enums\Confirmation;
use App\Enums\HistoryCallType;
use App\Enums\UserState;
use App\Traits\{Core\Labelable, Core\Linkable, Core\Modelable, Core\Queryable, Core\Searchable, Core\UserOnlineTrait};
use Illuminate\{Database\Eloquent\SoftDeletes, Foundation\Auth\User as Authenticatable, Notifications\Notifiable, Support\Carbon, Support\Facades\Cache, Support\Facades\Session};
use Psr\SimpleCache\InvalidArgumentException;
use Spatie\{Activitylog\Traits\LogsActivity, Permission\Traits\HasRoles};

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string|null $email
 * @property int $state                  -1: Chưa kích hoạt; 1: Đã kích hoạt
 * @property string|null $phone
 * @property float $basic_salary
 * @property string|null $birthday
 * @property string|null $first_day_work
 * @property string|null $address
 * @property string|null $note
 * @property int $use_otp                -1: Không sử dụng; 1: có sử dụng
 * @property string|null $otp
 * @property string|null $otp_expired_at OTP hết hạn trong 5 phút
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $actor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Department[] $departments
 * @property-read mixed $confirmations
 * @property-read mixed $created_at_text
 * @property-read mixed $is_use_otp
 * @property-read mixed $state_name
 * @property-read mixed $state_text
 * @property-read mixed $states
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HistoryCall[] $history_calls
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User andFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User dateBetween($dates, $column = 'created_at', $format = 'd-m-Y', $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User filters($filterDatas, $boolean = 'and', $filterConfigs = null)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User orFilterWhere($conditions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User search($term)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBasicSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirstDayWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereOtpExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUseOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable, LogsActivity, HasRoles, Searchable, Labelable, Queryable, SoftDeletes, Linkable, Modelable, UserOnlineTrait;

    public static $logName = 'User';
    protected static $logAttributes = ['username', 'employee_id'];
    protected static $ignoreChangedAttributes = ['last_login', 'remember_token', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    public $route = 'users';
    public $action = '';
    public $displayAttribute = 'username';
    public $labels = [
        'use_otp' => 'Sử dụng OTP',
        'name'    => 'Tên người dùng',
    ];
    public $filters = [
        'username' => 'like',
        'name'     => 'like',
        'phone'    => 'like',
        'email'    => 'like',
        'state'    => '=',
    ];
    protected $fillable = [
        'username',
        'name',
        'phone',
        'email',
        'password',
        'state',
//        'use_otp',
//        'otp',
//        'otp_expired_at',
        'actor_id',
        'birthday',
        'first_day_work',
        'address',
        'note',
        'basic_salary',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $dates = [
        'last_login',
        'first_day_work',
        'birthday',
    ];
    public $bgClassOnDashboard = '';
    public $bgClassModal = '';

    public function getBgClassOnDashboard()
    {
        if ($this->bgClassOnDashboard === '') {
            $class = $classModal = '';
            if ($this->isOnline()) {
                $class      = ' m--bg-success';
                $classModal = ' modal-success';
            } elseif ( ! $this->isOnline()) {
                $class      = ' m--bg-metal';
                $classModal = ' modal-metal';
            } elseif ($this->isPause()) {
                $class      = ' m--bg-primary';
                $classModal = ' modal-primary';
            } elseif ($this->isOnline()) {
                $class      = ' m--bg-danger';
                $classModal = ' modal-danger';
            }
            $this->bgClassOnDashboard = $class;
            $this->bgClassModal       = $classModal;
        }

        return [$this->bgClassOnDashboard, $this->bgClassModal];
    }

    public function actor()
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function (User $user) {
            if ($user->isDirty('state')) {
                $user->action = 'activated';
                if ($user->state == 0) {
                    $user->action = 'deactivated';
                }
            }
        });

        self::deleted(function (User $user) {
            $user->update([
                'username' => $user->username . '_' . time(),
            ]);
        });
    }

    public function getIsUseOtpAttribute()
    {
        return Confirmation::getDescription($this->state);
    }

    /**
     * @param $username
     *
     * @return string
     */
    public static function getPhone($username)
    {
        $user = self::query()->where('username', $username)->where('use_otp', 1)->first();

        return $user->phone ?? '';
    }

    public function getStateNameAttribute()
    {
        return UserState::getDescription($this->state);
    }

    public function getStateTextAttribute()
    {
        return $this->contextBadge($this->state_name, $this->state === 1 ? 'success' : 'danger');
    }

    public function getStatesAttribute()
    {
        return \App\Enums\UserState::toSelectArray();
    }

    /**
     * Check tài khoản có duoc active hay không
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->state == 1;
    }

    /**
     * Check tài khoản có phãi admin hay không
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * @param $username
     *
     * @return bool
     */
    public static function isUseOtp($username)
    {
        return self::query()->where('username', $username)->where('use_otp', 1)->exists();
    }

//    public function bonuses()
//    {
//        return $this->hasMany(\App\Models\Bonus::class);
//    }
//
//    public function contract_details()
//    {
//        return $this->hasMany(\App\Models\ContractDetail::class);
//    }
//
//    public function final_salaries()
//    {
//        return $this->hasMany(\App\Models\FinalSalary::class);
//    }

    public function history_calls()
    {
        return $this->hasMany(\App\Models\HistoryCall::class);
    }

    public function departments()
    {
        return $this->belongsToMany(\App\Models\Department::class, 'user_department')
                    ->withPivot(['id', 'position'])
                    ->withTimestamps();
    }

    /**
     * @return Department
     */
    public function getDepartmentOfLeader()
    {
        return $this->departments->first();
    }

    /**
     * @return Department[]
     */
    public function getDepartmentsOfManager()
    {
        return $this->departments;
    }

    public function isManager()
    {
        return $this->departments->filter(function (Department $department) {
            return $department->pivot->position === 3;
        })->isNotEmpty();
    }

    public function isLeader()
    {
        return $this->departments->filter(function (Department $department) {
            return $department->pivot->position === 2;
        })->isNotEmpty();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function ambassadors()
    {
        return $this->hasMany(Appointment::class, 'ambassador', 'id');
    }

    /**
     * @inheritdoc
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return $this->getDescriptionEvent($eventName);
    }

    public function getLoginTimeStringAttribute()
    {
//        $lastLoginTime = $this->last_login;
        $lastAudit = $this->getLatestAudit();
        if ( ! $lastAudit) {
            return '0:0:0';
        }

        $lastLoginTime = $lastAudit->time_in;
        $oldestAudit   = $this->getOldestAuditOffToday();

        if ($oldestAudit && $oldestAudit->isNot($lastAudit)) {
            $lastLoginTime = $oldestAudit->time_in;
        }

        $diffTime = now()->diffAsCarbonInterval($lastLoginTime);

        return "{$diffTime->h}:{$diffTime->i}:{$diffTime->s}";
    }

    public function getLoginTimeInSecondAttribute()
    {
//        $lastLoginTime = $this->last_login;
        $lastAudit = $this->getLatestAudit();
        if ( ! $lastAudit) {
            return 0;
        }
        $lastLoginTime = $lastAudit->time_in;

        return now()->diffAsCarbonInterval($lastLoginTime)->totalSeconds;
    }

    public function getCurrentStateAttribute()
    {
        if ( ! $this->isOnline()) {
            return 'Off';
        }

        $callCache = $this->getCallCache();
        if ($callCache) {
            return 'In call';
        }

        $breakCache = $this->getBreakCache();
        if ($breakCache) {
            return 'Pause';
        }

        return 'Online';
    }

    public function putCallCache($lead, $typeCall = 1)
    {
        $callCache = $this->getCallCache();

        if ($callCache) {
            $totalCall = $callCache['totalCall'] + 1;
        } else {
            $totalCall = 1;
        }
        session(['UserCall_' . $this->id => [
            'leadId'    => $lead->id,
            'leadName'  => $lead->name,
            'callAt'    => now(),
            'typeCall'  => HistoryCallType::getDescription($typeCall),
            'totalCall' => $totalCall,
        ]]);
    }

    public function getCallCache()
    {
        return session('UserCall_' . $this->id);
    }

    public function removeCallCache()
    {
        session()->remove('UserCall_' . $this->id);
    }

    public function getBreakCache()
    {
        return Session::get('UserBreak-' . $this->id);
    }

    public function putBreakCache($datas = [], $minutes = 5)
    {
        Session::put('UserBreak-' . $this->id, $datas);
    }

    public function removeBreakCache()
    {
        Session::remove('UserBreak-' . $this->id);
    }

    public function isPause()
    {
        $hasBreakCache = $this->getBreakCache();

        return ! empty($hasBreakCache);
    }

    public function isCheckedIn()
    {
        $lastAudit = $this->getLatestAudit();

//        dd($lastAudit);
        return ! empty($lastAudit);
    }

    public function isLoadPrivateOnly()
    {
        return session()->has('get_private_lead_only_' . $this->id);
    }

    public function getLatestAudit()
    {
        return Audit::query()
                    ->where('user_id', $this->id)
                    ->whereDate('created_at', Carbon::today())
                    ->whereNull('time_out')
                    ->whereNotNull('time_in')->latest()->first();
    }

    public function getOldestAuditOffToday()
    {
        return Audit::query()
                    ->where('user_id', auth()->id())
                    ->whereDate('created_at', Carbon::today())
                    ->oldest()->first();
    }

    public function checkOut()
    {
        $lastAudit = $this->getLatestAudit();

        if ($lastAudit) {
            $lastAudit->update([
                'time_out' => now()->toDateTimeString(),
            ]);

            return true;
        }

        return false;
    }

    public function getShortName()
    {
        $names     = explode('-', str_slug($this->name));
        $shortName = '';
        foreach ($names as $nameKey => $empName) {
            if (++$nameKey === count($names)) {
                $shortName = $empName . $shortName;
            } else {
                $shortName .= substr($empName, 0, 1);
            }
        }

        return $shortName;
    }
}
