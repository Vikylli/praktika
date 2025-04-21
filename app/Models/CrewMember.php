<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CrewMember extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function spacecrafts(): BelongsToMany
    {
        return $this->belongsToMany(Spacecraft::class, 'spacecraft_crew', 'crew_member_id', 'spacecraft_id')
            ->withPivot('role');
    }
}
