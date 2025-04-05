<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    protected $fillable = ['name', 'compatible_types'];
    protected $casts = ['compatible_types' => 'array'];
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
