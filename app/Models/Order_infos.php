<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_infos extends Model
{
  protected $guarded = [];


  protected $fillable = ['book_orderid','magentoshop_orderid','user_id','bookitem_id','status'];
    protected $table='order_informations';

    public function customer(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function book_items() {
        return $this->belongsToMany('App\Book', 'order_books', 'order_id', 'book_id');
    }
}
