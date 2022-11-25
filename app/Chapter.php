<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table='chapters';

    protected $fillable = ['title','book_id','order',];
}
