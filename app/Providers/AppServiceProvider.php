<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Custom\Validator;
use View;
use DB;
use Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.Admin.sidebar','layouts.Teacher.sidebar','layouts.Student.sidebar'],function($view){
         if(auth()->user()->is_administrator === 1 ){  
           $random_books=DB::table('books')->orderBy('created_at','desc')->get()->take(3); 
          }else if($data=DB::table('teachers')->select('teachers.*','users.id as teachid')->join('users','teachers.user_id','=','users.id')->whereRaw('teachers.user_id = ?',array(auth()->user()->id))->first()){
            $random_books=DB::table('books')->orderBy('created_at','desc')->get()->take(3); 
         }
         else if($data=DB::table('students')->select('students.*','users.id as studid')->join('users','students.user_id','=','users.id')->whereRaw('students.user_id = ?',array(auth()->user()->id))->first()){
          $random_books=DB::table('books')->select('books.id', 'books.title', 'books.subtitle', 'books.cover_image', 'books.subject','order_informations.id as orderid','order_informations.bookitem_id','order_informations.status','order_informations.user_id')
          ->join('order_informations',function ($join){
             $join->on('order_informations.bookitem_id', '=' , 'books.id') ;
             $join->where('order_informations.status',1);
             $join->where('order_informations.user_id','=',auth()->user()->id);
           })
           ->whereExists(function ($query){
            $query->select(DB::raw(1))
               ->from('order_books')
               ->whereRaw('order_books.book_id = books.id')
               ->whereRaw('order_books.order_id = order_informations.id');
             })->get()->take(3);
         }
           
            $view->with('random_books',$random_books);
        });

        Blade::directive('lang_u', function ($s) {
			return "<?php echo ucfirst(trans($s)); ?>";
		});

		app('validator')->resolver(function ($translator, $data, $rules, $messages) {
		  return new Validator($translator, $data, $rules, $messages);
		});
		
		View::composer(['layouts.Admin.app'], function ($view) {
          $pre_settings = \DB::table('preference_settings')->select('*')->first();
          $image_preference = '';
          $image = $pre_settings->image_preference;
          switch($image){
            case($image===1):
              $image_preference = "default";
              break;
            case($image===2):
              $image_preference = "grayscale";
              break;
            case($image===3):
              $image_preference = "inverted";
              break;
          }
          $view->with('image_type',$image_preference);
        });
    }
}
