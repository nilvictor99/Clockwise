<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentUser extends Model
{
    protected $fillable = [
        'department_id',
        'user_id',
        'type',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
