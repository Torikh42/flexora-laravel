<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_memberships');
    }

    public function studioClasses()
    {
        return $this->belongsToMany(StudioClass::class, 'membership_studio_class');
    }
}