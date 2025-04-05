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
        'total_cost',
        'total_assembly_time',
        'status'
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
<<<<<<< HEAD

    public function schedules()
{
    return $this->hasMany(Schedule::class);
}

=======
>>>>>>> parent of 426174f (User story 3, Planner en robot)
}