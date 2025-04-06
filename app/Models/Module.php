<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'cost',
        'assembly_time',
        'specifications',
        'image_path'
    ];

    protected $casts = [
        'specifications' => 'array',
        'cost' => 'decimal:2',
        'assembly_time' => 'integer'
    ];

    // Relatie met voertuigen
    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class)
                   ->withPivot('assembly_order')
                   ->orderBy('assembly_order');
    }

    // Toegang voor type-specifieke relaties
    public function typeSpecific()
    {
        $type = strtolower($this->type);
        if (method_exists($this, $type)) {
            return $this->{$type}();
        }
        return null;
    }
}