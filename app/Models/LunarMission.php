<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LunarMission extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'launch_date',
        'launch_site_name',
        'launch_latitude',
        'launch_longitude',
        'landing_date',
        'landing_site_name',
        'landing_latitude',
        'landing_longitude',
        'spacecraft_id'
    ];

    protected $casts = [
        'launch_date' => 'date',
        'landing_date' => 'date',
    ];
    public function spacecraft()
    {
        return $this->belongsTo(Spacecraft::class); 
    }
}
