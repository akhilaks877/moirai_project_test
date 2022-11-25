<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education_programm_element extends Model
{
    protected $table='education_programm_elements';
    public $timestamps = false;

    protected $fillable = ['name','book_id','is_main','parent_element','order_no'];
}
