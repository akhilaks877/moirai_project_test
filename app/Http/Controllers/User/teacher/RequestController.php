<?php

namespace App\Http\Controllers\User\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Teacher;
use DB;
use App\StudentRequest;
use App\Models\StudentClass;
class RequestController extends Controller
{
    //

    public function studentRequestList(Request $request)
    {
        $user_id = Auth::user()->id;
        $teacher_id = Teacher::where('user_id',$user_id)->pluck('id')->first();
        
            
        if($request->ajax()){

            $requests = DB::table('student_request')->select('student_request.id','student_request.status','classes.class_name',DB::raw('concat(students.first_name," ",students.last_name) AS full_name'))
            ->join('classes', 'classes.id', '=', 'student_request.class_id')
            ->join('students', 'students.id', '=', 'student_request.student_id')
            ->where('student_request.teacher_id', '=',  $teacher_id)
            ->get();
            //dd($request->toArray());
            
            return datatables()->of($requests)
        ->addIndexColumn()
        ->addColumn('student_name', function($requests){
            return $requests->full_name;
        })
        ->addColumn('class_name', function($requests){
            return $requests->class_name;
        })
        ->addColumn('date', function($requests){
            return date('d/m/Y');
        })
        ->addColumn('action', function($requests){
            $btn='';
            if($requests->status == '0'){
                $btn = 'Rejected';
            }
            elseif($requests->status == '1'){
                $btn = 'Approved';
            }
            else if($requests->status == '2'){
                // $btn = '<select class="select-class" data-id="'.$requests->id.'"><option value="" disabled selected>Select</option>';
                // $btn .= '<option value="2" '.(($requests->status==2)?'Selected':'').'>Pending</option>';
                // $btn .= '<option value="1" '.(($requests->status==1)?'Selected':'').'>Approved</option>';
                // $btn .= '<option value="0" '.(($requests->status==0)?'Selected':'').'>Rejected</option>';
                // $btn .= '</select>';
                $btn = '<button class="select-class mb-2 mr-2 border-0 btn-transition btn btn-outline-info" data-id="'.$requests->id.'" value="1"><span class="fa fa-user-plus pr-2"></span> Add this student to the class</button>';
				$btn.=	'<button class="select-class mb-2 mr-2 border-0 btn-transition btn btn-outline-info" data-id="'.$requests->id.'" value="0"><span class="fa fa-times-circle pr-2"></span> Decline request</button>';
										
            }
           
          return $btn;

        })
        ->rawColumns(['action'])
        ->make(true);
           
        }
       
        
       
        
    return view('User.teacher.student_request');


    }

    public function editRequest(Request $request)
    {
     //dd($request->input('id'));
        if($request->input('id')!=='')
        {
            $id = $request->input('id');
            $selected = $request->input('selected');
            $model = StudentRequest::findOrFail($id);
            if($model)
            {
                $model->status = $request->selected;
                $issave = $model->save();
               
            if($issave){
                if($model->status == 1){
                    $user_id = Auth::user()->id;
                    $teacher_id = Teacher::where('user_id',$user_id)->pluck('id')->first();
                    $student_class = new StudentClass;
                    $student_class->student_id = $model->student_id;
                    $student_class->class_id =   $model->class_id;
                    $student_class->created_by = $teacher_id;
                    $student_class->save();
                    return response()->json(['status'=>'accept-request','message'=>'Student has been added to the class successfully']);

                  
            }elseif($model->status == 0)
                return response()->json(['status'=>'reject-request','message'=>'Student request has been rejected']);
            }

          
        }


        }else{

        return response()->json(['status'=>'error','message'=>'Failed to change status.Please try again later.']);

        }
       

    }
}
