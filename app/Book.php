<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table='books';

    protected $fillable = [
        'title','subtitle','isbn','magento_sku','language','country','page_nos','editor_name','cover_image','description','physical_unit','length','width','thickness','weight',
        'availability','publication_date','inventory','price','subject'];

    // public function block_contents() {
    //     return $this->belongsToMany('App\Block_content', 'block_books', 'book_id', 'block_id');
    // }
    public function block_contents() {
            return $this->belongsToMany('App\Chapter_block', 'chapter_book_blocks', 'book_id', 'block_id');
        }


    public function contributors() {
        return $this->belongsToMany('App\Contributor', 'book_contributors', 'book_id', 'contributor_id');
    }

    public function subject(){
        return $this->belongsTo('App\Subject','subject','id');
    }

    public function education_programm_elements(){
        return $this->hasMany('App\Education_programm_element','book_id','id');
    }

    public function oreder_lists() {
        return $this->belongsToMany('App\Models\Order_infos', 'order_books', 'book_id', 'order_id');
    }
    

    public function excercises_lists() {
        return $this->hasMany('App\Book_exercise', 'book_id', 'id');
    }


}
