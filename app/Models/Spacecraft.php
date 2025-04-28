<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Spacecraft extends Model
{
    use HasFactory;

    protected $fillable = ['command_module', 'lunar_module'];

    public function crew()
{
    return $this->belongsToMany(CrewMember::class, 'spacecraft_crew', 'spacecraft_id', 'crew_member_id')->withPivot('role');
}
public function lunarMissions()
{
    return $this->hasMany(LunarMission::class);
}
}
