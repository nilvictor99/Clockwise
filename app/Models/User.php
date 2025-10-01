<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\System\SecuritySystemTrait;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use SecuritySystemTrait;
    use SoftDeletes;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type_user',
        'qr_code',
        'bg_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function addresses()
    {
        return $this->morphMany(Addresse::class, 'addressable');
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class, 'staff_id');
    }

    public function passwordShares()
    {
        return $this->hasMany(PasswordShare::class, 'shared_by');
    }

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class, 'user_id');
    }

    public function staffTimesheets()
    {
        return $this->hasMany(Timesheet::class, 'staff_id');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_users');
    }

    public function scopeWithStaffs(Builder $query)
    {
        return $query->whereHas('staffTimesheets')
            ->with(['profile'])
            ->select('id', 'name');
    }

    public function scopeWithProfile(Builder $query)
    {
        return $query->with('profile');
    }

    public function scopeWithBasicData(Builder $query)
    {
        return $query->select(['id', 'name', 'email']);
    }

    public function scopeWithNotEmail(Builder $query)
    {
        return $query->select(['id', 'name']);
    }

    public function scopeStaffOnly(Builder $query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'Staff');
        });
    }

    public function scopePresentToday(Builder $query)
    {
        $today = Carbon::today()->toDateString();

        return $query->whereHas('staffTimesheets', function ($q) use ($today) {
            $q->whereDate('day_in', $today);
        })->select('id', 'name');
    }

    public function scopeAbsentToday(Builder $query)
    {
        $today = Carbon::today()->toDateString();

        return $query->staffOnly()
            ->whereDoesntHave('staffTimesheets', function ($q) use ($today) {
                $q->whereDate('day_in', $today);
            })
            ->select('id', 'name');
    }

    public function scopeWithDataQr(Builder $query)
    {
        return $query->select(['id', 'name', 'email', 'qr_code']);
    }
}
