<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function scheduleExclusion()
    {
        return $this->hasMany(ScheduleExclusion::class);
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }
}
