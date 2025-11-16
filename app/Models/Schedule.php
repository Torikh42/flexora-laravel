<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'studio_class_id',
        'instructor',
        'start_time',
        'end_time',
        'price',
    ];

    public function studioClass()
    {
        return $this->belongsTo(StudioClass::class, 'studio_class_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}
