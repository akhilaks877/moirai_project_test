<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block_content extends Model
{
    protected $table='block_contents';
    protected $fillable = ['block_type','block_content','book_id'];
}
