<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'type_of_page',
        'section',
        'image',
        'title',
        'body',
    ];
}
