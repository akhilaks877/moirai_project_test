<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    protected $table='contributor_lists';
    protected $fillable = ['firstname','lastname','role','biography','user_image'];

    public function books() {
        return $this->belongsToMany('App\Book', 'book_contributors', 'contributor_id', 'book_id');
    }
}
