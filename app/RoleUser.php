<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model {

    protected $fillable = [
        'user_id','role_id'
    ];

    /**
     * A role can have many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany("App\Role");
    }
}