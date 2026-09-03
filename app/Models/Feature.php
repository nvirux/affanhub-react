<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'features';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'default_value',
        'description',
    ];
}
