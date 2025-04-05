<?php

// app/Models/Chassis.php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Chassis extends Module
{
    protected $table = 'modules';

    protected static function booted()
    {
        static::addGlobalScope('chassis', function (Builder $builder) {
            $builder->where('type', 'chassis');
        });
    }
}