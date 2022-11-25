<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table='subjects';

    protected $fillable = ['name'];
    public $timestamps = false;

    public function books(){
        return $this->hasMany('App\Book','subject','id');
    }
}
