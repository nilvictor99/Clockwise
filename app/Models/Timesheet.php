<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar',
        'staff_id',
        'user_id',
        'type',
        'day_in',
        'day_out',
        'hours',
        'location',
    ];

    protected $casts = [
        'day_in' => 'datetime',
        'day_out' => 'datetime',
        'location' => 'array',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWithStaffProfile(Builder $query)
    {
        return $query->with('staff.profile');
    }

    public function scopeSearchTimeSheetData(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('staff', function ($q2) use ($search) {
                $q2->where('name', 'ILIKE', "%{$search}%")
                    ->orWhereHas('profile', function ($q3) use ($search) {
                        $q3->where('full_name', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('phones', function ($q3) use ($search) {
                        $q3->where('phone_number', 'ILIKE', "%{$search}%");
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

    public function scopeFilterByStaff(Builder $query, $staffId)
    {
        return $query->when($staffId, function ($query) use ($staffId) {
            $query->where('staff_id', $staffId);
        });
    }

    public function scopeSearchData(Builder $query, $search = null, $startDate = null, $endDate = null, $staffId = null)
    {
        if (! empty($search)) {
            $query->searchTimeSheetData($search);
        }
        if (! empty($startDate) || ! empty($endDate)) {
            $query->filterByDateRange($startDate, $endDate);
        }
        if (! empty($staffId)) {
            $query->filterByStaff($staffId);
        }

        return $query;
    }

    public function scopeForStaffAndDate(Builder $query, $staffId, $currentDate)
    {
        return $query->where('staff_id', $staffId)->whereDate('day_in', $currentDate);
    }

    public function scopeOpen(Builder $query)
    {
        return $query->whereNull('day_out');
    }

    public function scopeFilterByUserAccess(Builder $query, $userId)
    {
        return $query->where('staff_id', $userId);
    }

    public function scopeFindOpenByType(Builder $query, $staffId, $currentDate, $newType)
    {
        return $query->where('staff_id', $staffId)
            ->whereDate('day_in', $currentDate)
            ->where('type', $newType)
            ->whereNull('day_out');
    }
}
