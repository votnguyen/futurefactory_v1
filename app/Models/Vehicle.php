<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'status',
        'total_cost',
        'total_assembly_time',
        'completion_status',       // Nieuw
        'completion_percentage',   // Nieuw
        'expected_delivery'        // Nieuw
    ];

    
    public function modules()
    {
    return $this->belongsToMany(Module::class)
                ->withPivot('assembly_order')
                ->orderBy('assembly_order');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function calculateTotals()
    {
        $this->total_cost = $this->modules->sum('cost');
        $this->total_assembly_time = $this->modules->sum('assembly_time');
        $this->save();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('end_time', 'desc');
    }
    
    public function getTypeAttribute()
    {
        return $this->modules->where('type', 'chassis')->first()->specifications['voertuig_type'] ?? 'onbekend';
    }
    public function updateCompletionStatus(): void
    {
        $totalModules = $this->modules()->count();
        $scheduledModules = $this->schedules()->count();
        
        $this->completion_percentage = $totalModules > 0 
            ? round(($scheduledModules / $totalModules) * 100) 
            : 0;
    
        if ($scheduledModules === $totalModules) {
            $this->completion_status = 'voltooid';
            $this->expected_delivery = $this->schedules()->max('end_time');
        } elseif ($scheduledModules > 0) {
            $this->completion_status = 'in_productie';
        } else {
            $this->completion_status = 'concept';
        }
    
        $this->save();
    }

public function getDeliveryEstimateAttribute()
{
    return $this->schedules()->exists()
        ? $this->schedules()->max('end_time')
        : null;

    }

protected $casts = [
    'status' => 'string'
];

public static $statuses = [
    'concept' => 'Concept',
    'in_productie' => 'In productie',
    'gereed_voor_levering' => 'Gereed voor levering',
    'geleverd' => 'Geleverd'
];

public function getStatusAttribute($value)
{
    return self::$statuses[$value] ?? $value;
}
    
}