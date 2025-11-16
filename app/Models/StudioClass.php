<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioClass extends Model
{
    use HasFactory;

    protected $table = 'studio_classes';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'studio_class_id');
    }

    public function memberships()
    {
        return $this->belongsToMany(Membership::class, 'membership_studio_class');
    }
}
