<?php

namespace App\Http\Controllers\User\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\ClassModel;
use App\Models\Teacher;
use DB;
use App\Models\Student;
use App\Models\StudentClass;
use App\Book;
use App\StudentRequest;
use App\Chapter;
use App\Chapter_block;
use App\Models\Order_infos;
use App\StudentExerciseMark;
use Validator;
class ClassController extends Controller
{
    //
    public function myclass(Request $request)
    {
       
    $teachers = DB::table('classes')
                    ->select('classes.class_name','classes.id AS classes_id','teachers.id AS teachers_id',DB::raw('concat(teachers.first_name," ",teachers.last_name) AS full_name'))
                    ->join('teachers','teachers.id','=','classes.teacher_id')
                    ->get();

    foreach($teachers as $t)
    {
        $t->full_name = $t->class_name.' - '.$t->full_name;
    }
    $teacher = $teachers->pluck('full_name','classes_id');
    
       
    $user_id = Auth::user()->id;
    $books = Order_infos::where('user_id',$user_id)->get();

    $student_id = Student::where('user_id',$user_id)->pluck('id')->first();

    $data = StudentClass::with(['class_name'])
                    ->where(['student_id'=>$student_id,'is_deleted'=>'0'])
                    ->get();
                    //dd($data->toArray());

   //dd($data->toArray());
    //echo "<pre>";print_r($data->toArray());die;
    if($request->ajax()){
       
         return datatables()->of($data)
                    ->addColumn('class_name', function($data){
                        return $data->class_name->class_name;
                    })
                    ->addColumn('school', function($data){
                        return $data->class_name->school->school_name;
                    })
                    ->addColumn('teacher', function($data){
                        return ucfirst($data->class_name->teacher->first_name).' '.$data->class_name->teacher->first_name;
                    })
                    ->addColumn('no_of_students', function($data){
                        return count($data->class_name->students);
                    })
                    ->addColumn('read_book', function($data){
                        $user_id = Auth::user()->id;
                        $links = '<ul>';
                        if(isset($data->class_name->book_suggestions) && !empty($data->class_name->book_suggestions))
                        foreach($data->class_name->book_suggestions as $k => $v){
                            
                            
                            $links .=  "<li><a target='_blank' href='".route('student.show-book',['id'=>$v->id])."'>".$v->title."</a></li>";
                        
                        }
                        $links .= '</ul>';
                        return $links;
                    })
                    ->addColumn('action', function($data){
                    $btn='<a href="'.route('student.leave-class',['id'=>$data->class_name->id]).'"  class="mb-2 mr-2 border-0 btn-transition btn btn-outline-secondary"><span class="fa fa-times-circle pr-2 "></span> Leave this class</a>
                    ';
                    return $btn;
                    })
                    ->rawColumns(['action','read_book'])
                    ->make(true);
                    }
    return view('User.student.my-class',compact('teacher'));
    }

    public function leaveClass($id)
    {
        //dd($id);
        $user_id = Auth::user()->id;
        $student_id = Student::where('user_id',$user_id)->pluck('id')->first();
        $leave_class = StudentClass::where(['student_id'=>$student_id,'class_id'=>$id])->update(['is_deleted' =>'1']);
        if($leave_class)
        {
            return redirect()->back()->with('success','You successfully leave the class');
        }
       


    }

    public function bookDetails($id)
    {
        //dd($id);
        $book_data = DB::table('books')
        ->select('books.id','books.title')
        ->join('class_book_suggestions','class_book_suggestions.book_id','=','books.id')
        ->where('class_book_suggestions.class_id','=',$id)->get();
        return response()->json($book_data);
        
        
    }

    public function showClasses()
    {
        //dd("");
        $teachers = DB::table('classes')
        ->select('classes.class_name',DB::raw('CONCAT(teachers.first_name," ",teachers.last_name) AS full_name'))
         ->join('teachers','teachers.id','=','classes.teacher_id')->get();
        return view('User.student.my-class',compact('teachers'));

    }

    public function studentRequest(Request $request)
    {
        //dd("wefew");
        //dd($request->all());
        if($request->has('select_class_parent')){
           if(isset($request->select_class_parent))
           {
            $user_id = Auth::user()->id;
            //dd($user_id);
            $student_id = Student::where('user_id',$user_id)->pluck('id')->first();
          
           // dd($class_id);
            // $teacher = ClassModel::whereIn('id', $class_id)->first();
            // dd($teacher->toArray());

            $i=0;
            foreach($request->select_class_parent as $k => $value){
                $class_id = $request->select_class_parent;
                $teacher = ClassModel::find($class_id);
                $teacher_id = $teacher[$i]->teacher_id;
                $studentRequest = new StudentRequest();
                $studentRequest->class_id = $value;
                $studentRequest->student_id =  $student_id;
                $studentRequest->teacher_id =  $teacher_id ;
                $studentRequest->status = '2';
                $studentRequest->save();
                $i++;
           }

           if($studentRequest)
           {
               return redirect()->back()->with('success','You request has been send!!');
           }
        }

            
        }
    }

    public function test()
    {
        return view('test');
    }

    public function showBook($id)
    {
       // dd($id);
        //$bookData = Book::findOrFail($id);
      $user_id = Auth::user()->id;
      $student_id = Student::where('user_id',$user_id)->pluck('id')->first();
      $bookData = DB::table('books')
                    ->select('books.id','books.title AS book_title','books.subtitle AS book_subtitle','books.description AS book_description','books.cover_image','book_exercises.title AS excercise_title','book_exercises.id AS excercise_id','book_exercises.exercise_type','chapters.id AS chapter_id','chapters.title AS chapter_title','student_exercise_mark.mark AS student_mark','student_exercise_mark.status AS attended_status')
                ->leftJoin('book_exercises','book_exercises.book_id','=','books.id')
                ->leftJoin('chapters','book_exercises.chapter_id','=','chapters.id')
                ->leftJoin('student_exercise_mark','student_exercise_mark.exercise_id','=','book_exercises.id')
                ->where('books.id','=',$id)
                ->orWhere('student_exercise_mark.student_id','=',$student_id)
                ->get();

    
        $bk = Book::with('excercises_lists')->findOrFail($id);
        //dd($bk->toArray());
        //dd($bookData->toArray());

        //echo "<pre>";print_r($bk->toArray());die;
        return view('User.student.book-details',compact('bookData','bk','student_id'));


    }
    public function readBook($id,Request $request)
    {
        //dd($id);
        $book=Book::findOrFail($id);
      
         $user_id=auth()->user()->id;
         $pref_setts=DB::table('preference_settings')->where('user_id',$user_id)->first();
         $data['chapters']=DB::table('chapters')->where('book_id',$book->id)->orderBy('order','ASC')->get();
         $data['chapter_blocks']='';
         $data['chapter_id']='';
         $data['chapter_data']='';
         $chapters_count=$data['chapters']->count();
         $data['prev_chapter']=0;
         $data['next_chapter']=0;
         if(!$data['chapters']->isEmpty()){
            $chapter_entity=($request->has('chapter') && $request->get('chapter') != '') ? (int)$request->get('chapter') :$data['chapters'][0]->id;
            $data['chapter_data']=DB::table('chapters')->where('id',$chapter_entity)->first();
            $chapter_blocks=DB::table('chapter_blocks')
                           ->where(array('book_id'=>$book->id,'chapter_id'=>$chapter_entity,'active'=>1))
                           ->orderBy('note_id')->orderBy('order')->get()->groupBy('note_id');
                           $data['chapter_blocks']=$chapter_blocks;
                           $data['chapter_id']=$chapter_entity;
       }
      // dd($chapter_blocks);
     $data['book']=$book; 
     $data['type']='';
    //  if($request->has('type') && (string)$request->get('type') == 'add'){
    //      $data['type']='add';
    //  }else if($request->has('type') && (string)$request->get('type') == 'edit' && $request->has('chapter')){
    //      $data['type']='edit';
    //  }
        
         //echo "<pre>";print_r($data);die;
        return view('User.student.book-read',compact('data','pref_setts'));
}

public function addorUpdateNotes($id, Request $request)
{
    //dd($id);
    //dd($request->all());
    // if($request->ajax()){
       
        $validator=Validator::make($request->all(),[
          'book_entity'=> 'required|integer|in:'.$id,
          'chapter_entity' => 'sometimes|required|exists:chapters,id',
          'chapter_name' => 'required|string',
          'note_element.*' => 'required|integer',
          'title.*' => 'nullable|string',
          'subtitle.*' => 'nullable|string',
          'note.*' => 'nullable|string',
          'notefile.*'  => 'nullable|mimes:jpeg,png,svg,webp',
          'filecaption.*' => 'nullable|string',
          
       ]);
       if ($validator->fails()) {
      
          $error = $validator->errors();
          return response()->json(['status'=>'invalid','messages'=>$error,'message'=>'Invalid Attempt']);
        }
        else{
         // dd("worked");
          DB::beginTransaction();
          try{
            
            $data=$request->all();
            //dd($data);
            $arr=[];
            $book_id=(int)$data['book_entity'];
            $chap_title=$data['chapter_name'];
            $chapter_val=$request->has('chapter_entity') ? (int)$request->input('chapter_entity') : null;  // chapter_entity for update
            if($request->has('chapter_entity')){
              $latest_note=DB::table('chapters')->where(array('book_id'=>$book_id,'id'=>$chapter_val))->first();
              $order=($latest_note) ? $latest_note->order : 1;
            }
           else{
              $latest_note=DB::table('chapters')->where(array('book_id'=>$book_id))->orderByDesc('order')->first();
              $order=($latest_note) ? $latest_note->order+1 : 1;
            }

            $chapter=Chapter::updateOrCreate(['book_id'=>$book_id,'id'=>$chapter_val],['book_id'=>$book_id,'title'=>$chap_title,'order'=>$order]);
            $chapter_id=$chapter->id;
            $order=1;
            $this->deactivatecurrentChapblocks($chapter_id,$book_id);
            foreach($data['note_element'] as $k=>$val){
              $note_id=$k+1;
                if(isset($data['title'][$k])){  // 1 add title block
                  $bdata['content']=$data['title'][$k];  $bdata['block_type']=1; $bdata['chapter_id']=$chapter_id;
                  $bdata['book_id']=$book_id;  $bdata['note_id']=$note_id; $bdata['order']=$order;   $metadata=null;
                  $bdata['is_file']=null; // it is set to null, since its not file type
                  $bdata['active']=1;
                  $order++; // increasing order for each each block
                  $this->addchapterBlock($bdata,$metadata);
                }

                if(isset($data['subtitle'][$k])){  // 2 add subtitle block
                  $bdata['content']=$data['subtitle'][$k];  $bdata['block_type']=2; $bdata['chapter_id']=$chapter_id;
                  $bdata['book_id']=$book_id;  $bdata['note_id']=$note_id; $bdata['order']=$order;   $metadata=null;
                  $bdata['is_file']=null; // it is set to null, since its not file type
                  $bdata['active']=1;
                  $order++; // increasing order for each each block
                  $this->addchapterBlock($bdata,$metadata);
                }

                if(isset($data['note'][$k])){  // 3 add description or note block
                  $bdata['content']=$data['note'][$k];  $bdata['block_type']=3; $bdata['chapter_id']=$chapter_id;
                  $bdata['book_id']=$book_id;  $bdata['note_id']=$note_id; $bdata['order']=$order;   $metadata=null;
                  $bdata['is_file']=null; // it is set to null, since its not file type
                  $bdata['active']=1;
                  $order++; // increasing order for each each block
                  $this->addchapterBlock($bdata,$metadata);
                }
                if($request->hasFile('notefile.'.$k)){    //4 add image block
                    $block_img=$request->file('notefile.'.$k);
                  $destination_folder='public/ebooks/book_'.$book_id.'/chapter_'.$chapter_id.'/files';
                  $blck_imgName = time().'_'.preg_replace('/\s+/', '_', $request->file('notefile.'.$k)->getClientOriginalName());
                  $block_img->storeAs($destination_folder,$blck_imgName);

                  $bdata['content']='/storage/ebooks/book_'.$book_id.'/chapter_'.$chapter_id.'/files/'.$blck_imgName;
                  $bdata['block_type']=4; $bdata['chapter_id']=$chapter_id;
                  $bdata['book_id']=$book_id;  $bdata['note_id']=$note_id; $bdata['order']=$order;
                  $metadata=$data['filecaption'][$k]; // image caption
                  $bdata['is_file']=1; // it is set to 1, since its a file_type block
                  $bdata['active']=1;
                  $order++; // increasing order for each each block
                  $this->addchapterBlock($bdata,$metadata);
                }
                if($data['contain_file'][$k] == "0"){ // if its 0 block_type 4 fille doesnot contain any file
                  $bdata['content']=null;  $bdata['block_type']=4; $bdata['chapter_id']=$chapter_id;
                  $bdata['book_id']=$book_id;  $bdata['note_id']=$note_id; $bdata['order']=$order;   $metadata=null;
                  $bdata['is_file']=1; // it is set to null, since its not file type
                  $bdata['active']=0; // it is set to zero becuase block_type 4 fille doesnot contain any file
                  $this->addchapterBlock($bdata,$metadata);
                }
            }
            DB::commit();
            if($request->has('chapter_entity')){  // chapter_entity for update
              $message='Chapter notes with Title &nbsp<strong>'.$chapter->title.'</strong> has been updated.';
             }
             else{
              $message="New Chapter <strong>".$chapter->title."</strong> has created.";
             }
             $request->session()->flash('chapstatus.status', 'success');
             $request->session()->flash('chapstatus.message', $message);
            return response()->json(['status'=>'success','message'=>$message]);
           }catch(\Exception $ex){
              DB::rollback();
              return response()->json(['status'=>'error','message' => [ $ex->getMessage() ]], 500);
          }
        }

      // }else{
      //   dd("error");
      // }
    
}

public function addchapterBlock(array $block_data,$metdata=null){
  $table=DB::table('chapter_blocks');
  $insrt_array=array('content'=>isset($block_data['content']) ? $block_data['content'] : null,
  'block_type'=>isset($block_data['block_type']) ? $block_data['block_type'] : null,
  'book_id'=>isset($block_data['book_id']) ? $block_data['book_id'] : null,
  'chapter_id'=>isset($block_data['chapter_id']) ? $block_data['chapter_id'] : null,
  'note_id'=>isset($block_data['note_id']) ? $block_data['note_id'] : null,
  'order'=>isset($block_data['order']) ? $block_data['order'] : null,
  'metadatas'=> isset($metdata) ? $metdata : null,
  'is_file'=> isset($block_data['is_file']) ? $block_data['is_file'] : null,
  'active'=>isset($block_data['active']) ? $block_data['active'] : null,
  'user_id'=>auth()->user()->id,
   );
   $check_array=array(
   'block_type'=>$insrt_array['block_type'],
   'book_id'=>$insrt_array['book_id'],
   'chapter_id'=>$insrt_array['chapter_id'],
   'note_id'=>$insrt_array['note_id'],
    );
    $block_data=Chapter_block::updateOrCreate($check_array,$insrt_array);
    $status=($this->attach_book_block($insrt_array['book_id'],$block_data->id,$insrt_array['chapter_id'],$insrt_array['order'])) ? true : false;
    return $status;
    // if($table->where($check_array)->exists()){
  //     $dt=$table->where($check_array)->update($insrt_array);  // if specific block exists update block
  //     return $dt;
  //     $status=($this->attach_book_block($insrt_array['book_id'],$insrt_array['block_type'],$insrt_array['chapter_id'],$insrt_array['order'])) ? true : false;
  // }else{
  //     $dt=$table->insert($insrt_array);    // else add as new block
  //     return $dt;
  //     $status=($this->attach_book_block()) ? true : false;
  // }
 // return $status;
}


public function attach_book_block($book_id=null,$block_id=null,$chapter_id=null,$order=null){
  $book_ent=Book::find($book_id);
  $book_ent->block_contents()->detach($block_id);
  $book_ent->block_contents()->attach($block_id , ['chapter_id' =>$chapter_id,'order_id'=>$order]);
  return true;
}

public function deactivatecurrentChapblocks(int $chapter,int $book){
  $deactivate=DB::table('chapter_blocks')->where(['book_id'=>$book,'chapter_id'=>$chapter])->where('is_file','=',NULL)->update(['active'=>0]);
  return true;
}

public function invitation_lists()
{
  $user_id = Auth::user()->id;
  //dd($user_id);
  $student_id = Student::where('user_id',$user_id)->pluck('id')->first();
  //dd($student_id);
  
  //dd($invitation_lists->toArray());
 $invitation_lists= DB::table('students')->select('students.id AS student_id','classes.id AS class_id','classes.class_name')->join('student_class','student_class.student_id','=','students.id')->join('classes','classes.id','=','student_class.class_id')->where(['student_class.student_id'=>$student_id,'student_class.status' => '0'])->get();
  //dd($invitation_lists);
 return view('User.student.student_invitation_list',compact('invitation_lists','student_id'));

}

public function accept_invitation($id,$class_id)
{
  $accept_invite = StudentClass::where(['student_id' => $id,'class_id' => $class_id])->update(['status' => '1']);
  if($accept_invite)
  {
    return redirect()->back()->with('message','You successfully accepted the invite');
  }
}


}
