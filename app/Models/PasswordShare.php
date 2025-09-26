<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'password_id',
        'shared_by',
        'shared_with',
        'permissions',
    ];

    public function password()
    {
        return $this->belongsTo(PasswordVault::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }

    public function scopeVisibleToUser(Builder $query, $userId)
    {
        return $query->where('shared_by', $userId);
    }

    public function scopeWithRelations(Builder $query)
    {
        return $query->with(['password', 'sharedBy', 'sharedWith']);
    }

    public function scopeSearchPassword(Builder $query, $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('password', function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%");
                })
                    ->orWhereHas('sharedBy', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('sharedWith', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%{$search}%");
                    });
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
            $query->where('shared_by', $userId)
                ->orWhere('shared_with', $userId);
        });
    }

    public function scopeFilterBySharedWith(Builder $query, $userId)
    {
        return $query->where('shared_with', $userId);
    }
}
