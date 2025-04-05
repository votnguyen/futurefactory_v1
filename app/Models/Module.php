<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'cost', 'assembly_time', 
        'specifications', 'image_path'
    ];

    protected $casts = [
        'specifications' => 'array'
    ];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class)
                    ->withPivot('assembly_order')
                    ->orderBy('assembly_order');
    }
}