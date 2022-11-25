<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $fillable = [
        'name','slug'
    ];

    /**
     * A role can have many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('App\User');
    }
	
	public function permissions() {
        return $this->belongsToMany("App\Permission");
    }

}