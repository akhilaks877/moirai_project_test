<?php

namespace App\Http\Controllers;

use App\Book;
use App\Models\PreLang;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Order_infos;
use App\Models\StudentClass;
use App\User;
use App\Role;
use App\Preference_setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Storage;
use Codexshaper\WooCommerce\Facades\Customer;
use Codexshaper\WooCommerce\Facades\Webhook;
use Codexshaper\WooCommerce\Facades\Order;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;
use Illuminate\Auth\Events\Registered;
use PhpParser\Node\Stmt\TryCatch;

class WebHookController extends Controller
{
    //

    public function processWebhooksData(Request $request)
    {
        // $data = json_encode($request->all());
        // Storage::put('webhook.txt', $data);
        // $all_header = json_encode($request->headers->all());
        // Storage::put('webhookHeader.txt', $all_header);

        // $hmac_header = $request->header('x-wc-webhook-signature');
        // $website_domain = $request->header('x-wc-webhook-source');
        // //$webs = Webs::where(['website_domain' => $website_domain])->first();
        // // $webs = Webs::all();
        // // Storage::put('source.txt', $webs);
        // Storage::put('hmac_header.txt', $hmac_header);
        // Storage::put('website_domain.txt', $website_domain);


        // $secret = env('WOOCOMMERCE_CONSUMER_SECRET');
        // $payload = file_get_contents('php://input');
        // $receivedHash = $request->header('x-wc-webhook-signature');
        // $generatedHash = base64_encode(hash_hmac('sha256', $payload, $secret, true));
        // if ($receivedHash === $generatedHash) :
        //     $data = json_encode($request->all());
        //     Storage::put('webhook.txt', $data);
        // else :
        //     Storage::put('webhook.txt', $generatedHash);
        // endif;

        $secret = env('WOOCOMMERCE_CONSUMER_SECRET');
        $wp_signature = $request->header('x-wc-webhook-signature');
        $payload = $request->getContent();
        Storage::put('payload.txt', $payload);
        $get_hmac = base64_encode(hash_hmac('sha256', $payload, $secret, true));
        if ($wp_signature === $get_hmac) {
            $data = $request->all();
            $orderID = $data['id'];
            $studentId = $data['customer_id'];
            $student = $this->getStudentDetails($studentId);
            if ($student === null) {
                \Log::channel('Woocommurceactivityinfo')->info('Some error will be occured in student creation');
            } else {
                foreach ($data['line_items'] as $item) {
                    $ProductId = $item['product_id'];
                    $this->giveBookAccess($ProductId, $student, $orderID);
                }
            }
        } else {
            Storage::put('error.txt', $get_hmac);
        }
    }


    public function getStudentDetails($studentId)
    {
        $customer = Customer::find(2)->all();

        $StudentId = 2;
        $woocoummercestudentDetails = Customer::find($StudentId)->all();
        $studentEmail = $woocoummercestudentDetails['email'];

        $student = Student::where('email', '=', $studentEmail)->first();
        if ($student === null) {
            $student  = $this->createNewStudent($woocoummercestudentDetails);
        }

        return $student;
        //dd($customer['billing']->first_name);
    }

    public function createNewStudent($StudentDetails)
    {

        $role = Role::find(4); //woocommerce
        DB::beginTransaction();
        try {

            $currentDate = Carbon::now();
            $pwd = substr($StudentDetails['first_name'], 0, 4) . '@' . substr($currentDate, 0, 4);
            $newSave = Student::create([
                'email' => $StudentDetails['email'],
                'first_name' => $StudentDetails['first_name'],
                'last_name' => $StudentDetails['last_name'],
                'created_by' => 1,
            ]);

            if ($newSave) {
                $user = User::create([
                    'name' => $StudentDetails['first_name'],
                    'firstname' => $StudentDetails['first_name'],
                    'lastname' => $StudentDetails['last_name'],
                    'email' => $StudentDetails['email'],
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

    public function giveBookAccess($ProductId, $student, $orderID)
    {
        $book = Book::where('magento_sku', '=', $ProductId)->first();
        DB::beginTransaction();
        try {
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
                \Log::channel('Woocommurceactivityinfo')->info('book not found in moirai');
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return null;
        }
    }
}
