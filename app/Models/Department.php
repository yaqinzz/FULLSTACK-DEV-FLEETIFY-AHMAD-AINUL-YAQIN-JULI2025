<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
        'max_clock_in_time',
        'max_clock_out_time'
    ];

    protected $casts = [
        'max_clock_in_time' => 'string',
        'max_clock_out_time' => 'string'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
