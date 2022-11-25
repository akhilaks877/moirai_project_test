<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Models\StudentClass;
use App\Models\ClassModel;

class Student extends Model
{
    // protected $table='students';
	use SoftDeletes;

  protected $table='students';
    // protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'email',
        'first_name',
        'last_name',
        'id_magento',
        'file',
        'language',
        'created_by',
        
    ];

	public function created_user(){
        return $this->belongsTo(User::class,'created_by','id')->with('roles');
    }

	public function updated_user(){
        return $this->belongsTo(User::class,'updated_by','id')->with('roles');
    }

    public function teachers()
	{
        return $this->hasMany(StudentClass::class,'student_id','id')->with('class_name');
    }

}
