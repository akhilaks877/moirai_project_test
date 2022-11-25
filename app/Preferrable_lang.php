<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preferrable_lang extends Model
{
    protected $table='preferrable_langs';

    protected $fillable = [
        'lang_name','short_code','slug'
    ];

    public $timestamps = false;
}
