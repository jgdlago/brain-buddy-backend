<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityArea extends Model
{
    protected $table = 'activity_areas';

    protected $fillable = [
        'name',
        'description'
    ];
}
