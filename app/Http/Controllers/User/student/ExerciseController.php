<?php

namespace App\Http\Controllers\User\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Book;
use App\Book_exercise;
use App\Question_list;
use Validator;
use DB;
use App\StudentExerciseMark;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Auth;
use Session;
use App\Models\Student;
use App\StudentExcerciseActivity;
use App\StudentExerciseAnswers;

class ExerciseController extends Controller
{
  

public function show_exercise_types($id,Request $request)
{
  //dd($id);
  return view('User.student.admin.exercises_types',compact('id'));
}
    

  public function show($id,Request $request)
    {
      //dd("kitty");
        $chap_data=DB::table('chapters')->select('chapters.*','books.id as book_ent','books.title as book_name','books.cover_image as book_coverimg')->join('books','chapters.book_id','=','books.id')->where('chapters.id',(int)$id)->first();
        //dd($chap_data);
        if(isset($chap_data) && Gate::allows('access-book',[Book::find($chap_data->book_id)])){ 
          //dd($request->input('excer_type'));
         if($request->has('excer_type') && $request->input('excer_type') !== ''){
             //dd('good');
             $data=[];
             $excer_type=(string)$request->input('excer_type');$test='';
             switch($excer_type){
               case 'examination':
                $exercise_datas=DB::table('book_exercises')->select('book_exercises.*','books.id as book_ent','chapters.id as chap_ent')
                 ->join('chapters','chapters.id','=','book_exercises.chapter_id')->join('books','books.id','=','book_exercises.book_id')
                 ->where([['book_exercises.chapter_id','=',$chap_data->id],['book_exercises.book_id','=',$chap_data->book_id],['book_exercises.exercise_type',1]])// exercise_type 1 for exam
                 ->get();
                 $data['exercise_datas']=$exercise_datas;
                 $data['chapter_data']=$chap_data;
                 $data['type']='Examination Exercise';
                break;

               case 'practise':
                $exercise_datas=DB::table('book_exercises')->select('book_exercises.*','books.id as book_ent','chapters.id as chap_ent')
                ->join('chapters','chapters.id','=','book_exercises.chapter_id')->join('books','books.id','=','book_exercises.book_id')
                ->where([['book_exercises.chapter_id','=',$chap_data->id],['book_exercises.book_id','=',$chap_data->book_id],['book_exercises.exercise_type',0]])// exercise_type 0 for practise
                ->get();
                $data['exercise_datas']=$exercise_datas;
                 $data['chapter_data']=$chap_data;
                 $data['type']='Practise Exercise';
                break;

               default:
               //dd("default");
                  return redirect()->back();
                  break;

             }
             return view('User.student.admin.excercises_lists_each',compact('data','id'));

         }else{
             //dd('not good');
            return redirect()->back();   
         }
        }else{
          //  dd('gate problem');
            return redirect()->back();
        }
       
    }

    public function display_exerciseform($id,$chap,$type,Request $request){
      //dd("fyfu");
      //dd($type);
      $exer_data=DB::table('book_exercises')->select('book_exercises.*','books.id as book_ent','books.title as book_name')->join('books','book_exercises.book_id','=','books.id')->where('book_exercises.id',(int)$id)->first();
       $question_type = Question_list::where('excercise_id',$exer_data->id)->pluck('question_type')->first();
     //dd($id);
     $user_id = Auth::user()->id;
     $student_id = Student::where('user_id',$user_id)->pluck('id')->first();
     $exam_status = StudentExcerciseActivity::where(['student_id' => $student_id,'exercise_id' => $id,'examination_status' => 1])->get();
     $exam_attended = count($exam_status);
     //dd(count($exam_status));
    //dd(count($exam_status));
      if(count($exam_status) == 0)
      {
     //   dd("illa");
     
     if(isset($exer_data) && Gate::allows('access-book',[Book::find($exer_data->book_id)])){ 
      //  dd("tdt");
            $question_sets=DB::table('question_lists')->select('question_lists.*','book_exercises.id as exerid')->join('book_exercises','book_exercises.id','=','question_lists.excercise_id')->where('question_lists.excercise_id',$exer_data->id)->get();
            $qhtml=''; $qdata=[];
            foreach($question_sets as $k=>$qset){
                $qhtml=''; $qtype=$qset->question_type;
                switch($qtype){
                  case 1: // multiple choice
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_multiple_choice_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break; 
                  case 2: // word pair
                    $qhtml='<div class="create_sortable_exercise" id="associate1"><div class="row">';
                    $qhtml.='<div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_word_pair_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 3: // label the image
                    $qhtml='<div class="create_sortable_exercise" id="sortable1"><div class="row">';
                    $qhtml.='<div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_labelimg_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 4: // essay question
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_essay_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 5: // true or false
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_true_orfalse_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;  

                  case 6: // sort the element
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_sort_images($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;  
                  default:
                   break; 
                }

            }
            return view('User.student.admin.show_exercise_form',compact('qdata','exer_data','question_type','chap','type','exam_attended'));
        }

}else{
  //dd("attended");
 if($type == 'Practise Exercise')
 {
//dd($type);
   $question_sets=DB::table('question_lists')->select('question_lists.*','book_exercises.id as exerid')->join('book_exercises','book_exercises.id','=','question_lists.excercise_id')->where('question_lists.excercise_id',$exer_data->id)->get();
            $qhtml=''; $qdata=[];
            foreach($question_sets as $k=>$qset){
                $qhtml=''; $qtype=$qset->question_type;
                switch($qtype){
                  case 1: // multiple choice
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_multiple_choice_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break; 
                  case 2: // word pair
                    $qhtml='<div class="create_sortable_exercise" id="associate1"><div class="row">';
                    $qhtml.='<div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_word_pair_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 3: // label the image
                    $qhtml='<div class="create_sortable_exercise" id="sortable1"><div class="row">';
                    $qhtml.='<div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_labelimg_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 4: // essay question
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_essay_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 5: // true or false
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_true_orfalse_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;  

                  case 6: // sort the element
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question_data.'</h6></div>';
                    $qhtml.=$this->show_sort_images($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;  
                  default:
                   break; 
                }

            }
            return view('User.student.admin.show_exercise_form',compact('qdata','exer_data','question_type','chap','type','exam_attended'));

 }else{
  return redirect()->back()->with('message','You already attended the test!!');
}
}
}




    public function show_multiple_choice_elmnt($obj_format,$up_stat=false){
    //dd($obj_format->student_answer);
        $mult='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
            $shuffled_array=array_keys($answer_format); shuffle($shuffled_array);
            foreach($shuffled_array as $shf){
              if(isset($obj_format->student_answer))
              {
              if($obj_format->student_answer == $shf )
              {
                $mult.='<div class="position-relative form-check"><input type="checkbox" onClick="checkChoiceclick(event)" name="multi_option[]" class="form-check-input check_inp_'.$obj_format->id.'" value="'.$shf.'" checked><label class="form-check-label" >'.$shf.'</label></div>';
              }else{
                $mult.='<div class="position-relative form-check"><input type="checkbox" onClick="checkChoiceclick(event)" name="multi_option[]" class="form-check-input check_inp_'.$obj_format->id.'" value="'.$shf.'"><label class="form-check-label" >'.$shf.'</label></div>';
              }
              
            }else{
               $mult.='<div class="position-relative form-check"><input type="checkbox" onClick="checkChoiceclick(event)" name="multi_option[]" class="form-check-input check_inp_'.$obj_format->id.'" value="'.$shf.'"><label class="form-check-label" >'.$shf.'</label></div>';
            }
          }
            $mult.='</div>';  
        }
        return $mult;


    
    }

    public function show_word_pair_elmnt($obj_format,$up_stat=false){
     $wrd='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
     if(!$up_stat){
        $answer_format=unserialize($obj_format->answer_format);
      //  print_r($answer_format);exit;
        $shuffled_array= array_keys($answer_format); shuffle($shuffled_array); $key=0;
        // print_r( $shuffled_array);
        $wrd.='<ul class="class_container_draggable init_draggable_list">';
        if(!isset($obj_format->student_answer))
        {
        foreach($answer_format as $ans){
          
            $wrd.='<li class="class_draggable btn mr-2 btn-primary sortable_tag"><input type="hidden" name="pair_val[]" value="'.$ans.'"><h6 class="Heading Heading--size4 text-no-select">'.$ans.'</h6></li>';
        }
        $wrd.='</ul></div></div><div class="position-relative">';

       
        foreach($shuffled_array as $shf){

         
            $wrd.='<div class="row"><div class="col-lg-3 col-5"><div class="word_for_association p-2"><input type="hidden" name="word_pair_left[]" value="'.$shf.'">'.$shf.'</div></div><div class="col-lg-2 col-2 text-center">goes with</div><div class="col-lg-3 col-5"><input type="hidden" name="word_pair_right[]" value=""><ul class="sortsing class_container_draggable class_container_limitation"></ul></div></div>';
        }

}else{

  //dd($obj_format->student_answer);
  $shuffled_array= array_keys($answer_format);
  $student_answer = unserialize($obj_format->student_answer);
  $i = 0;
   foreach($shuffled_array as $shf){

         
            $wrd.='<div class="row"><div class="col-lg-3 col-5"><div class="word_for_association p-2" style="margin-bottom: 16px;"><input type="hidden" name="word_pair_left[]" value="'.$shf.'">'.$shf.'</div></div><div class="col-lg-2 col-2 text-center">goes with</div><div class="col-lg-3 col-5"><input type="hidden" name="word_pair_right[]" value=""><li class="class_draggable btn mr-2 btn-primary sortable_tag"  style="min-height: 37px;">'.$student_answer[$i].'
            </div></div>';
            $i++;
        }
}

        $wrd.='</div>';
        return $wrd;
       // $shuffled_arr=$this->shuffle_assoc($word_match_real_arr);
      
     }
    }

    public function show_true_orfalse_elmnt($obj_format,$up_stat=false){
     
     //print_r($obj_format);
        $yesno='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
            //print_r($answer_format);
            if(isset($obj_format->student_answer))
            {
            $chkstatus1 = $chkstatus2 = '';
            if($obj_format->student_answer == 1){
              $chkstatus1 = 'checked';
            }
            if($obj_format->student_answer == 0){
              $chkstatus2 = 'checked';
            }

           
            $yesno.='<fieldset class="position-relative form-group">
            <div class="position-relative form-check"><label class="form-check-label"><input name="true_false_realanswer['.$obj_format->id.']" type="radio" value="1"
            class="form-check-input" '.$chkstatus1.'>True</label></div>
            <div class="position-relative form-check"><label class="form-check-label"><input name="true_false_realanswer['.$obj_format->id.']" type="radio" value="0" class="form-check-input" '.$chkstatus2.'>False</label></div></fieldset>';
          }else{
            $yesno.='<fieldset class="position-relative form-group">
            <div class="position-relative form-check"><label class="form-check-label"><input name="true_false_realanswer['.$obj_format->id.']" type="radio" value="1"
            class="form-check-input" >True</label></div>
            <div class="position-relative form-check"><label class="form-check-label"><input name="true_false_realanswer['.$obj_format->id.']" type="radio" value="0" class="form-check-input" >False</label></div></fieldset>';
          }
          
        
      }

        $yesno.='</div>';
        return $yesno;

    }

    public function show_essay_elmnt($obj_format,$up_stat=false){
        $essay='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
            // $essay.='';
            if(!isset($obj_format->student_answer))
            {
            $essay.='<div class="position-relative"><label class="form-check-label" for="textarea1"><span class="textarea1_max">'.($answer_format['words_limit'] ? $answer_format['words_limit']: '').'</span> words maximum. Number of words remaining: <span class="textarea1_count">0</span></label><textarea name="essay_answer[]" data-maxims="'.$answer_format['words_limit'].'"  class="form-control word_count" style="min-height: 150px;"></textarea></div>';
        }else{
          //dd($obj_format->student_answer);
           $essay.='<div class="position-relative"><label class="form-check-label" for="textarea1"><span class="textarea1_max">'.($answer_format['words_limit'] ? $answer_format['words_limit']: '').'</span> words maximum. Number of words remaining: <span class="textarea1_count">0</span></label><textarea readonly name="essay_answer[]" data-maxims="'.$answer_format['words_limit'].'"  class="form-control word_count" style="min-height: 150px;">'.$obj_format->student_answer.'</textarea></div>';
        }
         $essay.='</div>';
         return $essay;
         
      }

      
    }

    public function show_labelimg_elmnt($obj_format,$up_stat=false){
     
      //dd(unserialize($obj_format->student_answer));
        $imgl='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        $imgl.='<ul class="class_container_draggable init_draggable_list">';
        $i=0;
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
           // dd($answer_format);

            $source_img=($answer_format['label_imgpath'])?$answer_format['label_imgpath']:null;
            $cord_vals=!empty($answer_format['label_cordinate_lists']) ? $answer_format['label_cordinate_lists'] : [];
            $cord_array=!empty($answer_format['label_cordinate_lists']) ? array_map(function($value) {return $value['label_text'];}, $answer_format['label_cordinate_lists']) : []; shuffle($cord_array);


            if(!isset($obj_format->student_answer))
            {
            foreach($cord_array as $lbl){
             $imgl.='<li class="class_draggable btn mr-2 btn-primary sortable_tag"><input type="hidden" class="store_label_text" name="hold_label_'.$obj_format->id.'[]" value="'.$lbl.'"><h6 class="Heading Heading--size4 text-no-select">'.$lbl.'</h6></li>';
            }
            $imgl.='</ul></div></div><div class="position-relative"><img src="'.$source_img.'" alt="Image to label" title="Image to label" style="width: 100%;">';
            foreach($cord_vals as $cv){
                $imgl.='<ul class="labeling class_container_draggable class_container_limitation position-absolute" style="top: '.$cv['ycordinate'].'%; left: '.$cv['xcordinate'].'%"><input type="hidden" name="x_cord_'.$obj_format->id.'[]" value="'.$cv['xcordinate'].'"><input type="hidden" name="y_cord_'.$obj_format->id.'[]" value="'.$cv['ycordinate'].'"></ul>';
            // $imgl.='<ul class="class_container_draggable class_container_limitation position-absolute" style="top: "'.$cv['ycordinate'].'"%; left: "'.$cv['xcordinate'].'"%"></ul>'; 
          $i++; 
          }
         
            $imgl.='</div>';
            return $imgl;
           
        }else{

          $student_answer = unserialize($obj_format->student_answer);
          $i = 2;
          $j=0;
          //dd($student_answer);
          $imgl.='</ul></div></div><div class="position-relative"><img src="'.$source_img.'" alt="Image to label" title="Image to label" style="width: 100%;">';
            foreach($cord_vals as $cv){
               $student_answer = unserialize($obj_format->student_answer);
                $imgl.='<ul class="labeling class_container_draggable class_container_limitation position-absolute" style="top: '.$cv['ycordinate'].'%; left: '.$cv['xcordinate'].'%"><li class="class_draggable btn mr-2 btn-primary sortable_tag" style="min-height:41px;">'.$student_answer[$i][$j].'<input type="hidden" name="x_cord_'.$obj_format->id.'[]" value="'.$cv['xcordinate'].'"><input type="hidden" name="y_cord_'.$obj_format->id.'[]" value="'.$cv['ycordinate'].'"></ul>';
            // $imgl.='<ul class="class_container_draggable class_container_limitation position-absolute" style="top: "'.$cv['ycordinate'].'"%; left: "'.$cv['xcordinate'].'"%"></ul>'; 
          $j++; 
          }

            $imgl.='</div>';
            return $imgl;
        }

    
    }
  }

    public function show_sort_images($obj_format,$up_stat=false)
    {
      // dd($obj_format);
      //dd("in sort the element");
      $imgl='<input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
      $imgl.='<ul class="class_container_draggable init_draggable_list">';

      if(!$up_stat){
        $answer_format=unserialize($obj_format->answer_format);
        $shuffled_array= array_keys($answer_format); shuffle($shuffled_array); $key=0;
        shuffle($answer_format);
        //$shuffled_array= array_keys($answer_format); shuffle($shuffled_array); $key=0;
         $imgl.='<ul class="class_container_draggable init_draggable_list">';
        foreach($answer_format as $img)
        {
          // $imgl.='<li style="float:left" class="class_draggable btn mr-2 btn-primary sortable_tag">
          //  <img src="'.$img.'" height=100px width=100px></li>';
          $imgl.='<li class="class_draggable btn mr-2  sortable_tag"><img src="'.$img.'" height=100 width=130><input type="hidden" name="sort_image[]" value="'.$img.'"></li>';
        }
        $imgl.='</ul>';
        /*foreach($shuffled_array as $shf){
          
           
             $imgl.='<div class="row"><div class="col-lg-3 col-5"><div class="word_for_association p-2"></div></div><div class="col-lg-3 col-5"><input type="hidden" name="image_drag[]" value=""><ul class="sortsing class_container_draggable class_container_limitation"></ul></div></div>';
        }
*/
          //  $imgl.='<section>
          //  <div class="container">
          //  <div class="row">
          //  <div class="col-md-2 sortsing class_container_draggable class_container_limitation">adfasdfa</div>
          //  <div class="col-md-2">adfasdfa</div>
          //  <div class="col-md-2">adfasdfa</div>
          //  <div class="col-md-2">adfasdfa</div>

          //  </div>
           
           
           
           
           
           
          //  </div>

           
           
           
           
           
           
          //  </section>';




        //    $imgl.='<div class="row"><div class="col-lg-3 col-5">
        //    <ul class="sortsing class_container_draggable class_container_limitation"></ul></div></div>';
         /*}*/

        /* foreach($shuffled_array as $shf){
         
            $imgl.='<input type="hidden" name="sort_image[]" value="'.$shf.'">';
        }
*/

      
       /* $imgl.='</div>';*/
        return $imgl;

      }
    }

    function shuffle_assoc($list) { 
     // dd("jjj");
        if (!is_array($list)) return $list; 
      
        $keys = array_keys($list); 
       
        $random = array(); 
        foreach ($keys as $key) { 
          $random[$key] = $list[$key]; 
        }
        return $random; 
         
       
      }
      
      protected function check_exerciseflow(Request $request){
          //dd("hii");
          
          if($request->ajax()){
              //dd("hii not inserted");
            $exercise_id=$request->input('exercise'); 
            
            $student=DB::table('students')->whereRaw('user_id = ?',array(auth()->user()->id))->first();
           if($sess_rec=DB::table('student_exercise_activity_sessions')->where([['student_id',$student->id],['exercise_id',$exercise_id],['examination_status',1]])->first()){
               //dd("attended");
            return response()->json(['data'=>$sess_rec,'status'=>false,'messages'=>'success']);
           }
           else{
            //dd("hii inserted");
      

            
            $sess_rec=DB::table('student_exercise_activity_sessions')->insert(['student_id'=>$student->id,'exercise_id'=>$exercise_id,'started_at'=>Carbon::parse($request->input('start_time'))->format('Y-m-d H:i:s'),'examination_status'=>1]);
            $sess_rec=DB::table('student_exercise_activity_sessions')->where([['student_id',$student->id],['exercise_id',$exercise_id],['examination_status',1]])->first();
           ;
            return response()->json(['data'=>$sess_rec,'status'=>true,'messages'=>'success']);
           }
          }

      }

      public function check_answers(Request $request)
      {
        $data = $request->all();
        //dd(count($data));
        $exam_status = $data["exam-status"];
       
        $exer_id = $data['exer_ent'];
        $exer_data=DB::table('book_exercises')->select('book_exercises.*','books.id as book_ent','books.title as book_name')->join('books','book_exercises.book_id','=','books.id')->where('book_exercises.id',(int) $exer_id)->first();
        // dd($exer_data);
        if(Gate::allows('access-book',[Book::find($exer_data->book_id)])){
          
        $user_id = Auth::user()->id;
        $student_id = Student::where('user_id',$user_id)->pluck('id')->first();
        //dd($student_id);
      
        $marks = 0;

        $qtype = $data["question_type"];
        $question_count = count($qtype);
        $question_attended_count = 0;
       //dd($question_count);
       $multioption = 0;
       $truefalse = 0;
       $wordpair = 0;
       $shortessay = 0;
       $labelimage = 0;
       $sortimage = 0;

        for($i=0;$i<count($qtype);$i++)
        {

          //print_r($i);
       
        switch($qtype[$i])
        {
          
          case "1";//Multiple Choice
         if($multioption == 0)
         {
          $data = $request->all();
          //dd($data["multi_option"]);
          $answers = Question_list::where(['excercise_id' => $exer_id,'question_type' => $qtype[$i]])->get();
          $answer = $answers->toArray();
         // dd($answer);
         foreach($answer as $k => $value)
          {
            $answer_list =  unserialize($answer[$k]["answer_format"]);
            //dd($data["multi_option"][$k]);
            $question = $answer[$k]["question_data"];
            $student_answer = new StudentExerciseAnswers();
            $student_answer->student_id = $student_id;
            $student_answer->exercise_id = $exer_id;
            $student_answer->question = $question;
            $student_answer->question_type = $qtype[$i];
            $student_answer->answer_format = $answer[$k]["answer_format"];
            $student_answer->student_answer = $data["multi_option"][$k];
            $student_answer->save();
            //dd($answer_list[$i]);
            if($answer_list[$data["multi_option"][$k]] == true){
                $marks = $marks + 5;
                $question_attended_count++;
                
               
            }
        }
        $multioption ++;
       // dd($marks);
      }

        break;
        case "2"; //word pairs
        //dd("in case 2");
        if($wordpair == 0)
        {
          $data = $request->all();
          //dd($data);
          $answers = Question_list::where(['excercise_id' => $exer_id,'question_type' => $qtype[$i]])->get();
          $answer = $answers->toArray();
         // dd(shuffle($answer));
          foreach($answer as $k => $value)
          {
           
            $answer_list = unserialize($answer[$k]["answer_format"]);
            //dd(count($answer_list));
            //dd($data["pair_val"][$k]);
            /*$correct_answer = [];*/
           // dd(serialize($data["pair_val"]));
            $question = $answer[$k]["question_data"];
            $student_answer = new StudentExerciseAnswers();
            $student_answer->student_id = $student_id;
            $student_answer->exercise_id = $exer_id;
            $student_answer->question = $question;
            $student_answer->question_type = $qtype[$i];
            $student_answer->answer_format = $answer[$k]["answer_format"];
            $student_answer->student_answer = serialize($data["pair_val"]);
            $student_answer->save();
         
            for($s=0;$s<count($answer_list);$s++)
            {
             // dd($data["pair_val"][$s]);
             /*array_push($data["pair_val"][$s],$correct_answer);*/
             
             if($answer_list[$data["word_pair_left"][$s]] == $data["pair_val"][$s])
             {
             
             $marks = $marks + 5;

             //$wordpair++;
             
            }
           
           
          }
           $question_attended_count++;

           
         
          }
          $wordpair++;
          
        }
       
        break;


        case "3";//label the image
        //dd("label the image");
        if($labelimage == 0)
        {
          $data = $request->all();
          //dd($data);
          $answers = Question_list::where(['excercise_id' => $exer_id,'question_type' => $qtype[$i]])->get();
          $answer = $answers->toArray();
         
          foreach($answer as $k => $value)
          {
            //dd($answer[$k]["id"]);
            $answer_list = unserialize($answer[$k]["answer_format"]);
            $correct_answer = [];
            $qid = $answer[$k]["id"];
            $question = $answer[$k]["question_data"];
            $student_answer = new StudentExerciseAnswers();
            $student_answer->student_id = $student_id;
            $student_answer->exercise_id = $exer_id;
            $student_answer->question = $question;
            $student_answer->question_type = $qtype[$i];

            $student_answer->answer_format = $answer[$k]["answer_format"];
            //dd($data["x_cord_$qid"]);
            array_push($correct_answer,$data["x_cord_$qid"],$data["y_cord_$qid"],$data["hold_label_$qid"]);
            //dd($correct_answer);
            $student_answer->student_answer = serialize($correct_answer);
            $student_answer->save();
           
            $label_cordinate_lists= $answer_list["label_cordinate_lists"];
          
            for($s=0;$s<count($label_cordinate_lists);$s++)
            {
             // dd($data["pair_val"][$s]);
             $x_cord = $label_cordinate_lists[$s]["xcordinate"];
             $y_cord = $label_cordinate_lists[$s]["ycordinate"];
             $label_text = $label_cordinate_lists[$s]["label_text"];
             $qid = $answer[$k]["id"];
             if($x_cord == $data["x_cord_$qid"][$s] && $y_cord ==  $data["y_cord_$qid"][$s] && $label_text ==  $data["hold_label_$qid"][$s])
             {
               $marks = $marks + 5;
             }
             //dd($data["x_cord_$qid"][$s]);
             //dd($x_cord);
            
           
           
          }
           $question_attended_count++;
          }
          $labelimage++;

        }
       
        break;

      
        case "4";//short essay
       //dd("in case 4");
       if($shortessay == 0)
       {
        $data = $request->all();
        $v = 0;
       // dd($data);
        //dd($qtype[$i]);
        $answers = Question_list::where(['excercise_id' => $exer_id,'question_type' => $qtype[$i]])->get();
        $answer = $answers->toArray();
        //dd($answer);
        foreach($answer as $k => $value)
        {
          //dd($answer[$k]);
         $answer_list = unserialize($answer[$k]["answer_format"]);
         //dd($answer_list);
         //dd($data["essay_answer"]);
         $correct_answer = $answer_list["correct_answer"];
         //dd($correct_answer);
         $word_limit = $answer_list["words_limit"];
         $myanswer = $data["essay_answer"][$k];
         $word_count = str_word_count($myanswer);
            $qid = $answer[$k]["id"];
            $question = $answer[$k]["question_data"];
            $student_answer = new StudentExerciseAnswers();
            $student_answer->student_id = $student_id;
            $student_answer->exercise_id = $exer_id;
            $student_answer->question = $question;
            $student_answer->question_type = $qtype[$i];
            $student_answer->answer_format = $answer[$k]["answer_format"];
            $student_answer->student_answer = $data['essay_answer'][$v];
            $student_answer->save();
            $v++;
         //dd($word_count);
         //dd($myanswer);
         //dd(str_word_count($myanswer));
        $words =  explode(' ',trim($myanswer));
        $match = 0;
        for($k=0;$k<$word_count;$k++)
        {
          //dd($words[$k]);
          //dd($correct_answer);
          $word_found = strpos($correct_answer,$words[$k]);
          if($word_found !==false)
          {
           $match++;
          }
        }
        if($match == $word_limit)
        {
          $marks = $marks + 5;
          //$shortessay++;
        }else{

        }
       
        $question_attended_count++;
        }
        $shortessay++;
       }

        break;

        case "5";//true or false
        //dd("in case 5");
        $data = $request->all();
        //dd($qtype[$i]);
      if($truefalse == 0){

        $answers = Question_list::where(['excercise_id' => $exer_id,'question_type' => $qtype[$i]])->get();
        $answer = $answers->toArray();
       
        //dd($data["true_false_realanswer"]);
        foreach($answer as $k => $value)
        {
          //dd($k);
          //dd($answer[$k]);
          $answer_list = unserialize($answer[$k]["answer_format"]);
          //dd($answer_list);
          $correct_answer = $answer_list["answer"];

            $question = $answer[$k]["question_data"];
            $student_answer = new StudentExerciseAnswers();
            $student_answer->student_id = $student_id;
            $student_answer->exercise_id = $exer_id;
            $student_answer->question = $question;
            $student_answer->question_type = $qtype[$i];
            $student_answer->answer_format = $answer[$k]["answer_format"];
            $student_answer->student_answer = $correct_answer;
            $student_answer->save();
         //dd($correct_answer);
         dd($data["true_false_realanswer"][$answer[$k]]);
          if($data["true_false_realanswer"][$answer[$k]["id"]] == $correct_answer){
             //dd("true");
             $marks = $marks + 5;
             //$truefalse++;
              //dd($marks);
          }else{
            //dd("false");
             //$marks = $marks;
          }
           $question_attended_count++;
      }
      $truefalse++;
    }
    

        break;


        case "6";//sort the image
        if($sortimage == 0)
        {
          

        $data = $request->all();
       
        //dd($data);
        //dd(serialize($data['sort_image'][0]));
        $answers = Question_list::where(['excercise_id' => $exer_id,'question_type' => $qtype[$i]])->get();
        $answer = $answers->toArray();

     foreach($answer as $k => $value)
     {
        $check_answer = 0;
        $answer_list = unserialize($answer[$k]["answer_format"]);
        //dd($answer_list);
        //dd(serialize($data['sort_image']));
        $answer_count = count($answer_list);
        //dd($answer_count);

            $question = $answer[$k]["question_data"];
            $student_answer = new StudentExerciseAnswers();
            $student_answer->student_id = $student_id;
            $student_answer->exercise_id = $exer_id;
            $student_answer->question = $question;
            $student_answer->question_type = $qtype[$i];
            $student_answer->answer_format = $answer[$k]["answer_format"];
            $student_answer->student_answer = serialize($data['sort_image']);
            $student_answer->save();
           
        foreach($answer_list as $k => $value)
        {
          if($answer_list[$k] == $data['sort_image'][$k])
          {
         
           $check_answer++;

          }
        }
        if($check_answer == $answer_count)
        {
          $marks = $marks + 5;
        }else{

        }

         $question_attended_count++;

     }
     $sortimage++;
     
   }
 //dd($question_attented_count);

  break;
}
  continue; 

}
   
   if($exam_status)
   {
    $update_mark = StudentExerciseMark::where(['student_id'=>$student_id,'exercise_id'=>$exer_id])->update(['mark'=>$marks]);
    if($update_mark)
    {
      //dd("update aay");
        return redirect()->route('student.show-book',$exer_data->book_ent)->with('message','Exercise submitted successfully!!');
    }
   }else{
    $studentmark = new StudentExerciseMark();
    $studentmark->student_id = $student_id;
    $studentmark->exercise_id = $exer_id;
    $studentmark->status = '1';
    $studentmark->mark = $marks;
    $studentmark->question_count = $question_count;
    $studentmark->question_attended_count = $question_attended_count;

    if($studentmark->save())
    {
      return redirect()->route('student.show-book',$exer_data->book_ent)->with('message','Exercise submitted successfully!!');

      //return redirect()->back()->with('message',"Exercise successfully published!!");
    }else{
      return redirect()->back()->with('message',"Something went wrong!!");
    }
    }
   
   

}
        
}

public function viewExerciseResult($exer_id,$book_id,$type)
{
  //dd($type);
//  dd($book_id);
  $user_id = Auth::user()->id;
  $student_id = Student::where('user_id',$user_id)->pluck('id')->first();
  $student_name = Student::select(DB::raw('CONCAT(first_name, last_name) AS full_name'))->where('user_id',$user_id)->pluck('full_name')->first();
//dd($student_name);
  $book_datas = Book::find($book_id)->toArray();
  $book_id = $book_datas['id'];
  $book_title = $book_datas['title'];
  $book_subtitle = $book_datas['subtitle'];
  $book_description = $book_datas['description'];
  $book_cover_img = $book_datas['cover_image'];

  $exercise_datas = Book_exercise::find($exer_id);
  $exercise_title = $exercise_datas['title'];
  $student_activity = StudentExerciseMark::where(['student_id'=>$student_id,'exercise_id'=>$exer_id])->get();
  $studentmark = $student_activity[0]->mark;
  //dd($studentmark);
  $question_count = $student_activity[0]->question_count;
  $question_attended_count = $student_activity[0]->question_attended_count;

  $question_sets=DB::table('student_excercise_answers')->select('student_excercise_answers.*','book_exercises.id as exerid')->join('book_exercises','book_exercises.id','=','student_excercise_answers.exercise_id')->where('student_excercise_answers.exercise_id',$exer_id)->get();
  //dd($question_sets);
            $qhtml=''; $qdata=[];
            foreach($question_sets as $k=>$qset){
                $qhtml=''; 
                $qtype=$qset->question_type;
                switch($qtype){
                  case 1: // multiple choice
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question.'</h6></div>';
                    $qhtml.=$this->show_multiple_choice_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break; 
                  case 2: // word pair
                    $qhtml='<div class="create_sortable_exercise" id="associate1"><div class="row">';
                    $qhtml.='<div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question.'</h6></div>';
                    $qhtml.=$this->show_word_pair_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 3: // label the image
                    $qhtml='<div class="create_sortable_exercise" id="sortable1"><div class="row">';
                    $qhtml.='<div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question.'</h6></div>';
                    $qhtml.=$this->show_labelimg_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;
                  case 4: // essay question

                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question.'</h6></div>';
                    $qhtml.=$this->show_essay_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;

                  break;
                  case 5: // true or false
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question.'</h6></div>';
                    $qhtml.=$this->show_true_orfalse_elmnt($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                   // dd($qdata);
                  break;  

                  case 6: // sort the element
                    $qhtml='<div class="row"><div class="col-12"><hr><h6>Question '.($k+1).':  '.$qset->question.'</h6></div>';
                    $qhtml.=$this->show_sort_images($qset,false);
                    $qhtml.='</div>';
                    $qdata[]=$qhtml;
                  break;  
                  default:
                   break; 
              }
        }
    
      
  return view('User.student.exercise_result_per_student',compact('book_id','book_cover_img','book_title','exercise_title','student_name','book_subtitle','book_description','qdata','studentmark','question_count','question_attended_count','type','exer_id','student_id'));
    }


    public function resetExercise(Request $request)
    {
      //dd($exer_id,$student_id);
       $student_id = $request->studentId;
       $exercise_id = $request->exerciseId;
      $query1 = StudentExcerciseActivity::where(['student_id' => $student_id,'exercise_id' => $exercise_id,'examination_status' => '1'])->delete();
    //dd($data);
    //$query1->examination_status = '0';
    $query2 = StudentExerciseMark::where(['student_id' => $student_id,'exercise_id' => $exercise_id,'status' => '1'])->delete();
   // $query2->status = '0';

     if($query1 && $query2)
    {
        return response()->json(['status' => 'success']);
    }else{
        dd("not");
    }
    }







}
