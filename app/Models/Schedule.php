<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'module_id', 'robot_id', 'start_time', 'end_time'];
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    
    public function robot()
    {
        return $this->belongsTo(Robot::class);
    }
}