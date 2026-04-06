<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = '23810310084_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_visible',
    ];
}