<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block_type extends Model
{
    protected $table='book_block_types';

    protected $fillable = [
        'block_name','block_slug'
    ];

    public $timestamps = false;
}
