<?php

namespace App\Models;

use App\Observers\PasswordVaultObserver;
use App\Traits\Auth\BelongsToAuthenticatedUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([PasswordVaultObserver::class])]
class PasswordVault extends Model
{
    use BelongsToAuthenticatedUser;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'username',
        'password',
        'url',
        'notes',
        'type',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passwordShares()
    {
        return $this->hasMany(PasswordShare::class, 'password_id');
    }

    public function scopeVisibleToUser(Builder $query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('type', 'public')
                ->orWhere(function ($sub) use ($userId) {
                    $sub->where('type', 'private')
                        ->where('user_id', $userId);
                })
                ->orWhereHas('passwordShares', function ($sub) use ($userId) {
                    $sub->where('shared_with', $userId);
                });
        });
    }

    public function canView($userId)
    {
        if ($this->user_id == $userId) {
            return true;
        }
        if ($this->type == 'public') {
            return true;
        }

        return $this->passwordShares()->where('shared_with', $userId)->whereIn('permissions', ['view', 'edit'])->exists();
    }

    public function canEdit($userId)
    {
        if ($this->user_id == $userId) {
            return true;
        }

        return $this->passwordShares()->where('shared_with', $userId)->where('permissions', 'edit')->exists();
    }

    public function scopeWithUserProfile(Builder $query)
    {
        return $query->with('user.profile');
    }

    public function scopeSearchPassword(Builder $query, $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('username', 'ILIKE', "%{$search}%")
                    ->orWhere('url', 'ILIKE', "%{$search}%");
            });
        });
    }

    public function scopeFilterByDateRange(Builder $query, $startDate, $endDate)
    {
        if (! empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if (! empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }

    public function scopeSearchData(Builder $query, $search = null, $startDate = null, $endDate = null)
    {
        if (! empty($search)) {
            $query->searchPassword($search);
        }
        if (! empty($startDate) || ! empty($endDate)) {
            $query->filterByDateRange($startDate, $endDate);
        }

        return $query;
    }

    public function scopeFilterByUserAccess(Builder $query, $userId)
    {
        return $query->where(function ($query) use ($userId) {
            $query->where('type', 'public')
                ->orWhere('user_id', $userId)
                ->orWhereHas('passwordShares', function ($query) use ($userId) {
                    $query->where('shared_with', $userId)
                        ->whereIn('permissions', ['view', 'edit']);
                });
        });
    }
}
