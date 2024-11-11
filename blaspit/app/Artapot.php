<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Artapot extends Model
{
    protected $table = 'artapot';
    protected $fillable = [
        'banner', 'artikel', 'categori', 'title'
    ];
    
}
