<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question_type extends Model
{
    protected $table='question_types';
    public $timestamps = false;

    protected $fillable = ['name','slug'];
}
