<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Robot extends Model
{

    use HasFactory;
    // Voeg 'description' toe aan de fillable array
    protected $fillable = ['name', 'description', 'compatible_types'];

    // Zorg ervoor dat 'compatible_types' als een array wordt gecast
    protected $casts = [
        'compatible_types' => 'array',
    ];
    
    // Relatie met 'Schedule' model
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
