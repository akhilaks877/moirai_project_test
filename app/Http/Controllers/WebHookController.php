<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Mail\CloudHostingProduct;
use App\Book;
use App\Mail\DemoMail;

use App\Models\PreLang;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Order_infos;
use App\Models\StudentClass;
use App\User;

use App\Role;
use App\Preference_setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Storage;
use DB;
use Codexshaper\WooCommerce\Facades\WooCommerce;
use Codexshaper\WooCommerce\Facades\Webhook;
use Codexshaper\WooCommerce\Facades\Order;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;
use Illuminate\Auth\Events\Registered;
use PhpParser\Node\Stmt\TryCatch;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;


// use Illuminate\Support\Facades\Http;

class WebHookController extends Controller
{


  public function processWebhooksData(Request $request)
  {
    $data = array('name'=>"akhila");

    Mail::send(['text'=>'mail'], $data, function($message) {
      $message->to('akhiammu75@gmail.com', 'Tutorials Point')->subject
      ('Laravel Basic Testing Mail');
      $message->from('akhilasasik@gmail.com','Virat Gandhi');
    });


    $client = new \GuzzleHttp\Client(['verify' => false]);
    $response = $client->request('GET', 'https://editionsmoirai.ca//wp-json/wc/v3/orders?consumer_key=ck_185000f02916f2e8115e6786649baaa2aad7dd79&consumer_secret=cs_af96b4f486f4fe09ce4bfb9d8b505f403b0ef9fb');

    $data = json_decode($response->getBody()->getContents(),true);


    $client =new Client(['verify' => false]);
    $response=$client->get('https://editionsmoirai.ca//wp-json/wc/v3/orders?consumer_key=ck_185000f02916f2e8115e6786649baaa2aad7dd79&consumer_secret=cs_af96b4f486f4fe09ce4bfb9d8b505f403b0ef9fb');
    $datas = json_decode($response->getBody()->getContents(),true);

    foreach($datas as $data){


      $orderID =  $data['id'];
      $studentId = $data['customer_id'];
      $student = $this->getStudentDetails($studentId);
      if ($student ===null) {

        //\Log::channel('Woocommurceactivityinfo')->info('Some error will be occured in student creation');
      } else {
        foreach ($data['line_items'] as $item) {

          $ProductId = $item['product_id'];
          $sku = $item['sku'];
        }

        $this->giveBookAccess($ProductId, $student, $orderID,$sku);

      }
    }
  }

  public function getStudentDetails($studentId)
  {
    $client =new Client(['verify' => false]);
    $response=$client->get('https://editionsmoirai.ca//wp-json/wc/v3/customers/'.$studentId.'?consumer_key=ck_185000f02916f2e8115e6786649baaa2aad7dd79&consumer_secret=cs_af96b4f486f4fe09ce4bfb9d8b505f403b0ef9fb');
    $woocoummercestudentDetails= json_decode($response->getBody()->getContents(),true);

    $studentEmail =$woocoummercestudentDetails['email'];

    $student = Student::where('email', '=', $studentEmail)->first();


    if ($student === null) {

      $student  = $this->createNewStudent($woocoummercestudentDetails);
    }

    return $student;
    //dd($customer['billing']->first_name);
  }

  public function createNewStudent($woocoummercestudentDetails)
  {

    $role = Role::find(4); //woocommerce

    $currentDate = Carbon::now();

    $pwd = substr('akhilasasik@gmail.com', 0, 4) . '@' . substr($currentDate, 0, 4);

    $newSave = Student::Create([
      'email' => 'akhilasasik@gmail.com',
      'first_name' => $woocoummercestudentDetails['first_name'],
      'last_name' => $woocoummercestudentDetails['last_name'],
      'created_by' =>'1' ,

    ]);


    // DB::beginTransaction();

    try {

      if ($newSave) {


        $user = User::Create([
          'name' =>$woocoummercestudentDetails['first_name'],
          'firstname' =>$woocoummercestudentDetails['first_name'],
          'lastname' =>$woocoummercestudentDetails['last_name'],
          'email' => 'akhilasasik@gmail.com',
          'password' => Hash::make($pwd),
        ]);

        $user->roles()->attach($role);
        $newSave->update(['user_id' => $user->id]);
      }
      $check_array = array('user_id' => $user->id);
      $insrt_array = array(
        'user_id' => $user->id,
        'show_notification' => 1,
        'default_font_size' => 10,
        'selected_font_size' => null,
        'menu_back_color' => null,
        'menu_text_color' => null,
        'readng_back_color' => null,
        'readng_text_color' => null,
        'image_preference' => null,
        'notes_font_size' => null,
        'view_editor_note' => null,
        'view_teacher_note' => null,
        'view_student_note' => null,
        'enable_note_edit' => null,
      );

      $add_preffsett = Preference_setting::updateOrCreate($check_array, $insrt_array);
      DB::commit();
    } catch (\Exception $ex) {
      DB::rollback();
      return null;
    }
  }


  public function giveBookAccess($ProductId, $student, $orderID,$sku)
  {


    $book = Book::where('magento_sku', '=', $sku)->first();


    if ($book) {


      $newSave = Order_infos::create([
        'book_orderid' => $orderID,
        'magentoshop_orderid' => $orderID,
        'user_id' => $student->id,
        'bookitem_id' => $book->id,
        'status' => 1
      ]);

      if ($newSave) {
        DB::insert('insert into order_books values (book_id, order_id)', [$book->id, $newSave->id]);
      }
    }else{
      //\Log::channel('Woocommurceactivityinfo')->info('book not found in moirai');
    }
    // DB::commit();
  }

  public function sendMail()
  {
    $info = array(
         'name' => "Alex"
     );
      Mail::to('mail', $info, function ($message)
     {
         $message->to('akhiammu75@gmail.com', 'w3schools')
             ->subject('HTML test eMail from W3schools.');
         $message->from('akhilasasik@gmail.com', 'Alex');
     });
    dd("zff");
  }


}
