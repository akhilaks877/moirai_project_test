<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Book;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'firstname', 'lastname', 'user_img', 'preferred_language','is_administrator','created_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles() {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function scopeExclude($query,$value = array())
	{
		return $query->select( array_diff( $this->columns,(array) $value) );
    }

    public function created_user(){
        return $this->belongsTo(User::class,'created_by','id')->with('roles');
    }

    public function updated_user(){
        return $this->belongsTo(User::class,'updated_by','id')->with('roles');
    }

    public function purchase_informs(){
        return $this->hasMany('App\Models\Order_infos','user_id','id');
    }


    public function hasAccessBook(Book $book){
        return ($this->purchase_informs()->where([['bookitem_id', $book->id],['status',1]])->count() == 1
         &&
         $this->purchase_informs()->whereHas('book_items',function($q)use($book){
             $q->where('id',$book->id);})->count() == 1);
    }


}
