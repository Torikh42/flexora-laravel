<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'coach_name',
        'start_time',
        'end_time',
    ];

    public function studioClass()
    {
        return $this->belongsTo(StudioClass::class, 'class_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}
