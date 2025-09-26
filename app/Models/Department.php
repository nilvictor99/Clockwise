<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'manager_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'department_users');
    }

    public function departamentUsers()
    {
        return $this->hasMany(DepartmentUser::class);
    }
}
