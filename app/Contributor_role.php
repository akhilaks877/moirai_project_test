<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contributor_role extends Model
{
    protected $table='contributor_roles';
    protected $fillable = ['name'];
}
