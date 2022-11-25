<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\ClassModel;

class Teacher extends Model
{
    // protected $table='schools';

    protected $fillable = [
		    'user_id',
        'email',
        'first_name',
        'last_name',
        'id_magento',
        'file',
        'language',
        'created_by',
        'updated_by',
    ];

	public function created_user(){
        return $this->belongsTo(User::class,'created_by','id')->with('roles');
    }

    public function updated_user(){
        return $this->belongsTo(User::class,'updated_by','id')->with('roles');
    }

	public function students(){
	   return $this->hasMany(ClassModel::class,'teacher_id','id');
    }

	public function classes(){
	   return $this->hasMany(ClassModel::class,'teacher_id','id')->with('school');
    }

	/*public function school(){
	   return $this->belongsTo(Teacher::class,'teacher_id','id');
    }*/
}
