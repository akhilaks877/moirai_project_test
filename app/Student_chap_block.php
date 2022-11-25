<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_chap_block extends Model
{
    protected $table='student_chapter_blocks';

    protected $fillable = ['content','metadatas','block_type','chapter_id','book_id','note_id','order','is_file','active','user_id'];
}
