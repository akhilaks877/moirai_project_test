<?php

namespace App\Http\Controllers\User\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Book;
use App\Book_exercise;
use App\Question_list;
use App\Models\Student;
use App\StudentExerciseMark;
use Validator;
use DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ExerciseController extends Controller
{


  public function index($id,Request $request){
     
    $chapter_id = $request->input('chapter_id');
    //dd($chapter_id);
        $data=[]; $excer_editdat=null;
        $book_data=DB::table('books')->select('books.*','subjects.id as subject_id','subjects.name as subject_name')->join('subjects','subjects.id','=','books.subject')->whereRaw('books.id = ?',array((int)$id))->first();
        //dd($book_data);
        $excer = Book_exercise::where(['book_id' => $id,'chapter_id' => $chapter_id])->pluck('id')->first(); 
        //dd($excer);

        if($excer!==''){
            $par=$excer;
            //dd($par);
            $excer_editdat=DB::table('book_exercises')->whereRaw('id = ? and book_id = ?',array($par,$book_data->id))->first();
           // dd($excer_editdat);
        }
        if($request->ajax()){
            $table_data=[];
            $excercise=(int)$excer;
            $table_data=DB::table('question_lists')
            ->select('question_lists.*','book_exercises.id as excercise_entity','book_exercises.book_id as book_id','book_exercises.chapter_id as chap_id','question_types.id as type_id','question_types.slug as type_name')
            ->join('book_exercises', 'book_exercises.id', '=', 'question_lists.excercise_id')
            ->join('question_types', 'question_types.id', '=', 'question_lists.question_type')
            ->whereRaw('question_lists.excercise_id = ? and book_exercises.book_id = ?',array($excercise,$book_data->id))
            ->get();
            return DataTables::of($table_data)
                    ->addIndexColumn()
                    ->addColumn('title', function($table_data){$title=$table_data->question_data; return $title;})
                    ->addColumn('type', function($table_data){ $question_type=__('menus.'.$table_data->type_name); return $question_type;})
                    ->addColumn('action', function($table_data){
                         $btn='<a href="'.route('teacher.title.excercise-edit_question',['qid'=>$table_data->id]).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>';
                         $btn.='<a href="javascript:void(0);"class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info delExcercseData" data-eid="'.$table_data->id.'"><span class="fa fa-trash"></span></a>';
                         return $btn;
                        })
                    ->rawColumns(['action'])
                    ->removeColumn('id')
                    ->make(true);
          }

        $data['chapters']=DB::table('chapters')->where('book_id',$book_data->id)->orderBy('order','ASC')->get();

        return view('User.teacher.admin.manages_book_exercises',compact('book_data','data','excer_editdat'));
    }

    public function addorUpexcercise($id,Request $request){
        $validator=Validator::make($request->all(),[
         'book_entity' => 'required|integer|exists:books,id|in:'.$id,
         'excercise_ent' => 'sometimes|required|exists:book_exercises,id',
         'exercice_title' => 'required|string',
         'chapter' => 'required|integer|exists:chapters,id',
         'exercice_time' => 'nullable|integer',
         'illufile' => 'nullable|mimes:jpeg,png,svg,webp',
      ],[
          'exercice_time.integer' => 'Exercise completion time should in numbers.'
      ]);
      if ($validator->fails()) {
         $error = $validator->errors();
         return redirect()->back()->withErrors($validator)->withInput();
       }
       else{
        DB::beginTransaction();
        try{
          $data=$request->all();
          $user_id=auth()->user()->id;
          $excercise_entity=$request->has('excercise_ent') ? (int)$request->input('excercise_ent') : null;  // excercise_entity for update
          $chapter_data=DB::table('chapters')->where('id',$data['chapter'])->first();
          $book_data=DB::table('books')->where('id',$data['book_entity'])->first();
          $time_mins=$request->input('exercice_time');
          $completion_minute_slot= isset($time_mins) ? $time_mins : null;
          $exercise_type=0; // 0 for Learning Exercise since teacher can only add training
          $excer_data=Book_exercise::updateOrCreate([
            'book_id' => $data['book_entity'],
            'id' => $excercise_entity
            ],
            ['exercise_type' => $exercise_type,
            'title' => $data['exercice_title'],
            'chapter_id' => $data['chapter'],
            'book_id' => $data['book_entity'],
            'completion_time'=>$completion_minute_slot,
            'user_id'=>$user_id
          ]);
          if($request->hasFile('illufile')){
            $illu_img=$request->file('illufile');
            $destination_folder='public/ebooks/book_'.$request->input('book_entity').'/chapter_'.$request->input('chapter').'/illustration_image';
            $illu_imgName = time().'_'.preg_replace('/\s+/', '_', $request->file('illufile')->getClientOriginalName());
            $illu_img->storeAs($destination_folder,$illu_imgName);

            $excercise_ent=Book_exercise::find($excer_data->id);
            $excercise_ent->illu_img=$illu_imgName;
            $excercise_ent->save();
         }
        DB::commit();
        if($request->has('excercise_ent')){  // excercise_entity for update
            $mssg='Excercise with title <strong>'.$excer_data->title.'</strong> in Chapter <strong>'.$chapter_data->title.'</strong> has been updated.';
           }
           else{
            $mssg='Excercise <strong>'.$excer_data->title.'</strong> added to Chapter <strong>'.$chapter_data->title.'</strong> in the Book <strong>'.$book_data->title.'</strong>';
           }
           return redirect()->route('teacher.title.manage_bookexercise',['id'=>$data['book_entity'],'excer'=>$excer_data->id])->with('excer.success',$mssg);
        }catch(\Exception $ex){
                DB::rollback();
                return response()->json(['status'=>'error','message' => [ $ex->getMessage() ]], 500);
        }
      }

     }

     public function questionManagger($id,Request $request){
        $excer_entity=(int)$id;
        $complete_excerdetails=DB::table('book_exercises')
        ->select('book_exercises.*','chapters.id as chapid','chapters.title as chapname','books.id as bookid','books.title as bookname','books.cover_image as book_coverimg','books.subject','books.description as book_description','subjects.id as subject_id','subjects.name as subject_name')
        ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
        ->join('books', 'books.id', '=', 'book_exercises.book_id')
        ->join('subjects','subjects.id','=','books.subject')
        ->whereRaw('book_exercises.id = ?',array($excer_entity))->first();
        $question_types=DB::table('question_types')->get();
         if($complete_excerdetails){
            return view('User.teacher.admin.question_typeseach',compact('complete_excerdetails','question_types'));
         }
         else{
            return redirect()->route('teacher.title.index');
         }
    }

    public function addquestionType($id,$type=null,Request $request){
        $question_type=(string)$request->input('type'); $view='';
        $excercise=(int)$id;
        $complete_excerdetails=DB::table('book_exercises')
        ->select('book_exercises.*','chapters.id as chapid','chapters.title as chapname','books.id as bookid','books.title as bookname','books.cover_image as book_coverimg','books.subject','books.description as book_description','subjects.id as subject_id','subjects.name as subject_name')
        ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
        ->join('books', 'books.id', '=', 'book_exercises.book_id')
        ->join('subjects','subjects.id','=','books.subject')
        ->whereRaw('book_exercises.id = ?',array($excercise))->first();
        $programm_elements=Book::with('education_programm_elements')->find($complete_excerdetails->bookid);
        switch($question_type){
          case 'multiple_choice':
            $view='User.teacher.admin.questions.multiple_choice';
          break;

          case 'word_pairs':
            $view='User.teacher.admin.questions.word_pairs';
          break;

          case 'label_the_image':
            $view='User.teacher.admin.questions.label_image';
          break;

          case 'short_answer_essay':
            $view='User.teacher.admin.questions.essay_question';
          break;

          case 'true_or_false':
            $view='User.teacher.admin.questions.true_or_falsequestion';
          break;

          case 'sort_the_elements':
           $view='User.teacher.admin.questions.sort_the_elements';
          break;

          default:
           return redirect()->route('teacher.title.index');
        }
        return view($view,compact('complete_excerdetails','programm_elements',''));
    }

    public function addorUpquestions($id,Request $request){
        $validator=Validator::make($request->all(),[
            'excercise_ent' => 'required|integer|exists:book_exercises,id|in:'.$id,
            'question_type' => 'required|integer|exists:question_types,id',
            'question_ent' =>  'sometimes|required|exists:question_lists,id',
            'question_title' => 'required|string',
            'correct_answer' => 'sometimes|required|string',
            'wrong_answer.*' => 'sometimes|required|string',
            'left_pair.*'  => 'sometimes|required|string',
            'right_pair.*'  => 'sometimes|required|string',
            'words_limit' => 'sometimes|required|integer',
            'rating_scale' => 'sometimes|required|integer',
            'label_text.*' => 'sometimes|required|string',
            'image_to_caption'  => 'required_if:newimg,1|mimes:jpeg,png,svg,webp|max:2080|dimensions:min_width=600', // decidesimg on book update
            'x_cord.*' => 'sometimes|required|numeric|between:1,100',
            'y_cord.*' => 'sometimes|required|numeric|between:1,100',
            'exercice_programme' => 'nullable|integer',
         ],[
            'image_to_caption.dimensions'=>'Image requires minimum width 600px.',
            'image_to_caption.max'=>'Maximum file size is 2MB.',
            'image_to_caption.mimes'=>'Invalid file format.'
         ]);
         if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->withErrors($validator)->withInput();
          }
          else{
            DB::beginTransaction();
          try{
            $data=$request->all();
            $question_type=$request->input('question_type');
            $question_entity=$request->has('question_ent') ? (int)$request->input('question_ent') : null;  // question_entity for update

            switch($question_type){
                case 1:
                 $answer_format=$this->multiple_choiceSerialize($request);
                break;

                case 2:
                 $answer_format=$this->word_pairsSerialize($request);
                break;

                case 3:
                 $answer_format=$this->label_imageSerialize($request);
                break;

                case 4:
                    $answer_format=$this->essay_questionSerialize($request);
                break;

                case 5:
                    $answer_format=$this->true_orRightSerialize($request);
                break;

                case 6:
                  //dd($question_type);
                  $answer_format=$this->sortTheElements($request);
                break;

                default:
                 return redirect()->route('teacher.title.index');
              }
              $ques_data=Question_list::updateOrCreate([
                'excercise_id' => $data['excercise_ent'],
                'id' => $question_entity
                ],
                ['question_data' => $data['question_title'],
                'excercise_id' => $data['excercise_ent'],
                'question_type' => $question_type,
                'education_element' => $data['exercice_programme'],
                'answer_format'=>$answer_format
              ]);
             DB::commit();
             $excer_data=DB::table('book_exercises')->whereRaw('id = ?',array($ques_data->excercise_id))->first();

             return redirect()->route('teacher.title.manage_bookexercise',['id'=>$excer_data->book_id,'chapter_id'=>$data['chapter_id']]);
            }catch(\Exception $ex){
                DB::rollback();
                return response()->json(['status'=>'error','message' => [ $ex->getMessage() ]], 500);
            }
          }
     }

     public function editQuestiondata($qid,Request $request){
        $ques_id=(int)$qid; $view=''; $answer_format=''; $answer_arrformat=array();
        DB::beginTransaction();
        try{
            $question_data=DB::table('question_lists')
            ->select('question_lists.*','question_types.slug as slug_name')
            ->join('question_types', 'question_types.id', '=', 'question_lists.question_type')
            ->whereRaw('question_lists.id = ?',array($ques_id))->first();
            $excercise=$question_data->excercise_id;
            $question_type=$question_data->slug_name;
            $complete_excerdetails=DB::table('book_exercises')
            ->select('book_exercises.*','chapters.id as chapid','chapters.title as chapname','books.id as bookid','books.title as bookname','books.cover_image as book_coverimg','books.subject','books.description as book_description','subjects.id as subject_id','subjects.name as subject_name')
            ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
            ->join('books', 'books.id', '=', 'book_exercises.book_id')
            ->join('subjects','subjects.id','=','books.subject')
            ->whereRaw('book_exercises.id = ?',array($excercise))->first();
            $programm_elements=Book::with('education_programm_elements')->find($complete_excerdetails->bookid);
            DB::commit();

            switch($question_type){
                case 'multiple_choice':
                  $view='User.teacher.admin.questions.multiple_choice';
                  $answer_format=unserialize($question_data->answer_format);
                  $answer_arrformat['correct_answers']=array_keys($answer_format,true);
                  $answer_arrformat['incorrect_answers']=array_keys($answer_format,false);
                break;

                case 'word_pairs':
                  $view='User.teacher.admin.questions.word_pairs';
                  $answer_format=unserialize($question_data->answer_format);
                  $answer_arrformat['correct_answers']=$answer_format;
                break;

                case 'label_the_image':
                   $view='User.teacher.admin.questions.label_image';
                   $answer_format=unserialize($question_data->answer_format);
                   $answer_arrformat['label_imgpath']=$answer_format['label_imgpath'];
                   $answer_arrformat['correct_answers']=$answer_format['label_cordinate_lists'];
                break;

                case 'short_answer_essay':
                    $view='User.teacher.admin.questions.essay_question';
                    $answer_format=unserialize($question_data->answer_format);
                    $answer_arrformat['words_limit']=$answer_format['words_limit'];
                    $answer_arrformat['rating_scale']=$answer_format['rating_scale'];
                    $answer_arrformat['correct_answer']=$answer_format['correct_answer'];
                break;

                case 'true_or_false':
                  $view='User.teacher.admin.questions.true_or_falsequestion';
                  $answer_format=unserialize($question_data->answer_format);
                  $answer_arrformat['correct_answers']=$answer_format['answer'];
                break;

                case 'sort_the_elements':
                  //dd($question_type);
                $view = 'User.teacher.admin.questions.sort_the_elements';
                $answer_arrformat=unserialize($question_data->answer_format);

                break;

                default:
                 return redirect()->route('teacher.title.index');
              }
              return view($view,compact('complete_excerdetails','programm_elements','question_data','answer_arrformat'));
         }catch(\Exception $ex){
             dd($ex->getMessage());
            DB::rollback();
            return redirect()->route('teacher.title.index');
         }

    }

    public function deleteQuestiondata(Request $request)
    {
       if($request->ajax()){
            
            $excer_question=(int)$request->input('excer_ent');
            if(Question_list::where('id',$excer_question)->delete()){
              //dd("deleted");
                return response()->json(['status'=>'success']);
            }
        }
    }

     public function multiple_choiceSerialize(Request $request){
        $data=$request->all();
        $answer_list=[];
        $answer_list[$data['correct_answer']]=TRUE;
        $wrong_answers=$data['wrong_answer'];
        foreach($wrong_answers as $wrnans){
            $answer_list[$wrnans]=FALSE;
        }
        $answer_format=serialize($answer_list);
        return $answer_format;
    }

    public function word_pairsSerialize(Request $request){
        $data=$request->all();
        $answer_list=[];
        $left_pairs=$data['left_pair'];
        foreach($left_pairs as $k=>$leftpr){
            $answer_list[$leftpr]=$data['right_pair'][$k];
        }
        $answer_format=serialize($answer_list);
        return $answer_format;
    }

    public function true_orRightSerialize(Request $request){
        $data=$request->all();
        $answer_list=[];
        $real_answer=$data['real_answer'];
        $answer_list['answer']=($real_answer == 1) ? TRUE : FALSE;
        $answer_format=serialize($answer_list);
        return $answer_format;
    }

    public function essay_questionSerialize(Request $request){
        $data=$request->all();
        $answer_list=[];
        $answer_list['words_limit']=$request->input('words_limit');
        $answer_list['rating_scale']=$request->input('rating_scale');
        $answer_list['correct_answer']=$request->input('correct_answer');
        $answer_format=serialize($answer_list);
        return $answer_format;
    }

    public function label_imageSerialize(Request $request){
        $data=$request->all();
        $excercise_ent=(int)$request->input('excercise_ent');
        $exer_details=DB::table('book_exercises')->select('book_exercises.*','books.id as bookid','books.title as booktitle','chapters.id as chapid','chapters.title as chaptitle')
        ->join('books','books.id','=','book_exercises.book_id')->join('chapters','chapters.id','=','book_exercises.chapter_id')->whereraw('book_exercises.id = ?',array($excercise_ent))->first();
        $answer_list=[];
        if($request->hasFile('image_to_caption')){
            $label_img=$request->file('image_to_caption');
            $destination_folder='public/ebooks/book_'.$exer_details->bookid.'/chapter_'.$exer_details->chapid.'/exercises/exercise_'.$exer_details->id;
            $cap_imgName = time().'_'.preg_replace('/\s+/', '_', $request->file('image_to_caption')->getClientOriginalName());;
            $label_img->storeAs($destination_folder,$cap_imgName);
            $cap_imgpath = '/storage/ebooks/book_'.$exer_details->bookid.'/chapter_'.$exer_details->chapid.'/exercises/exercise_'.$exer_details->id.'/'.$cap_imgName;
        }
        else if($request->has('question_ent') &&  $request->input('question_ent') != '' && (!$request->hasFile('image_to_caption'))){ // checking whether its updateing the question
          $question=DB::table('question_lists')->whereraw('question_lists.id = ? and question_lists.question_type = ?',array((int)$request->input('question_ent'),3))->first();
          $ans_format=unserialize($question->answer_format);
          $cap_imgpath =isset($ans_format['label_imgpath']) ? $ans_format['label_imgpath'] : '/assets/images/coeur_legende.png' ;
        }
        else{
            $cap_imgpath = '/assets/images/coeur_legende.png';
        }
        $answer_list['label_imgpath']=$cap_imgpath;
        $answer_list['label_cordinate_lists']=[];
        $xcordValues=$data['x_cord'];
        $inc=1;$arr=[];
        foreach($xcordValues as $k=>$xcordval){
        $arr['orderid']=$inc;
        $arr['xcordinate']=$xcordval;
        $arr['ycordinate']=$data['y_cord'][$k];
        $arr['label_text']=$data['label_text'][$k];
        $inc++;
        array_push($answer_list['label_cordinate_lists'],$arr);
        }
        $answer_format=serialize($answer_list);
        return $answer_format;
    }

    public function sortTheElements(Request $request)
    {
     $data = $request->all();
     //dd($data);
     $answer_list=[];

     //Edit section//

     if(isset($data["edit-images"]))
     {
     
         foreach ($data["edit-image"] as $files) {

           $answer_list[]=$files;

         }
         $answer_format=serialize($answer_list);
         
     }
     
     if($request->hasFile('sortfile')){
      //dd("file und");
       foreach ($request->file('sortfile') as $files) {
          $destination_folder= public_path('/uploads/sortimages');
          $cap_imgName = time().'_'.preg_replace('/\s+/', '_', $files->getClientOriginalName());
          $files->move($destination_folder,$cap_imgName);
          $cap_imgpath = '/uploads/sortimages/'.$cap_imgName;
          $answer_list[]=$cap_imgpath;
          
      }
        $answer_format=serialize($answer_list);
        //dd($answer_format);
        return  $answer_format;
    }
    
     return  $answer_format;
     }




//show questions//


    public function show_multiple_choice_elmnt($obj_format,$up_stat=false){
        $mult='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
            $shuffled_array=array_keys($answer_format); shuffle($shuffled_array);
            foreach($shuffled_array as $shf){
                $mult.='<div class="position-relative form-check"><input type="checkbox" onClick="checkChoiceclick(event)" name="multi_option[]" class="form-check-input check_inp_'.$obj_format->id.'" value="'.$shf.'"><label class="form-check-label" >'.$shf.'</label></div>';
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
        foreach($answer_format as $ans){
          
            $wrd.='<li class="class_draggable btn mr-2 btn-primary sortable_tag"><input type="hidden" name="pair_val[]" value="'.$ans.'"><h6 class="Heading Heading--size4 text-no-select">'.$ans.'</h6></li>';
        }
        $wrd.='</ul></div></div><div class="position-relative">';
        foreach($shuffled_array as $shf){
         
            $wrd.='<div class="row"><div class="col-lg-3 col-5"><div class="word_for_association p-2"><input type="hidden" name="word_pair_left[]" value="'.$shf.'">'.$shf.'</div></div><div class="col-lg-2 col-2 text-center">goes with</div><div class="col-lg-3 col-5"><input type="hidden" name="word_pair_right[]" value=""><ul class="sortsing class_container_draggable class_container_limitation"></ul></div></div>';
        }
        $wrd.='</div>';
        return $wrd;
       // $shuffled_arr=$this->shuffle_assoc($word_match_real_arr);
      
     }
    }

    public function show_true_orfalse_elmnt($obj_format,$up_stat=false){
        $yesno='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
            $yesno.='<fieldset class="position-relative form-group"><div class="position-relative form-check"><label class="form-check-label" ><input name="true_false_realanswer['.$obj_format->id.']" type="radio" value="1"  class="form-check-input"> True</label></div><div class="position-relative form-check"><label class="form-check-label"><input name="true_false_realanswer['.$obj_format->id.']" type="radio" value="0" class="form-check-input"> False</label></div></fieldset>';
        }
        $yesno.='</div>';
        return $yesno;

    }

    public function show_essay_elmnt($obj_format,$up_stat=false){
        $essay='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
            // $essay.='';
            $essay.='<div class="position-relative"><label class="form-check-label" for="textarea1"><span class="textarea1_max">'.($answer_format['words_limit'] ? $answer_format['words_limit']: '').'</span> words maximum. Number of words remaining: <span class="textarea1_count">0</span></label><textarea name="essay_answer[]" data-maxims="'.$answer_format['words_limit'].'"  class="form-control word_count" style="min-height: 150px;"></textarea></div>';
        }
        $essay.='</div>';
        return $essay;
    }

    public function show_labelimg_elmnt($obj_format,$up_stat=false){
     
        $imgl='<div class="col-12 mt-1"><input type="hidden" name="question_id[]" value="'.$obj_format->id.'"><input type="hidden" name="question_type[]" value="'.$obj_format->question_type.'">';
        $imgl.='<ul class="class_container_draggable init_draggable_list">';
        $i=0;
        if(!$up_stat){
            $answer_format=unserialize($obj_format->answer_format);
           // dd($answer_format);

            $source_img=($answer_format['label_imgpath'])?$answer_format['label_imgpath']:null;
            $cord_vals=!empty($answer_format['label_cordinate_lists']) ? $answer_format['label_cordinate_lists'] : [];
            $cord_array=!empty($answer_format['label_cordinate_lists']) ? array_map(function($value) {return $value['label_text'];}, $answer_format['label_cordinate_lists']) : []; shuffle($cord_array);
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
       
        return $imgl;

      }
    }


    public function viewExerciseResult($exer_id,$book_id,$student_id)
    {
  
      $student_name = Student::select(DB::raw('CONCAT(first_name, last_name) AS full_name'))->where('id',$student_id)->pluck('full_name')->first();

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

  $question_sets=DB::table('question_lists')->select('question_lists.*','book_exercises.id as exerid')->join('book_exercises','book_exercises.id','=','question_lists.excercise_id')->where('question_lists.excercise_id',$exer_id)->get();
  //dd($question_sets);
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
  return view('User.teacher.admin.exercise_result_per_student',compact('book_id','book_cover_img','book_title','exercise_title','student_name','book_subtitle','book_description','qdata','studentmark','question_count','question_attended_count','student_id','exer_id'));
    }
     
}
