<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Book;
use App\Book_exercise;
use App\Question_list;
use Validator;
use DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ExercisesController extends Controller
{
    public function index($id,Request $request){
        $book_data=Book::find($id);
        if($request->ajax()){
            $data=[];
            $book_id=(int)$request->input('book');
            $data=DB::table('book_exercises')->select('book_exercises.*','chapters.id as chapid','chapters.title as chapname')
            ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
            ->where('book_exercises.book_id',$book_id)->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('chapter', function($data){  $chapter=$data->chapname; return $chapter;})
                    ->addColumn('exercise_title', function($data){ $title=$data->title; return $title;})
                    ->addColumn('action', function($data){
                         $btn='<a href="'.route('admin.title.add_bookexercise',['id'=>$data->book_id,'excer'=>$data->id]).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>';
                         $btn.='<a href="javascript:void(0);" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info delExcercse" data-chpid="'.$data->chapid.'" data-head="'.$data->title.'" data-eid="'.$data->id.'"><span class="fa fa-trash"></span></a>';
                         return $btn;
                        })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('User.manage_book_exercise',compact('book_data'));
    }


    public function create($id,Request $request){
        $data=[]; $excer_editdat=null;
        $book_data=Book::find($id);
        if($request->has('excer') && $request->input('excer') != ''){
            $par=$request->input('excer');
            $excer_editdat=DB::table('book_exercises')->whereRaw('id = ? and book_id = ?',array($par,$book_data->id))->first();
        }

        if($request->ajax()){
            $table_data=[];
            $excercise=(int)$request->input('excer');
            $table_data=DB::table('question_lists')
            ->select('question_lists.*','book_exercises.id as excercise_entity','book_exercises.book_id as book_id','question_types.id as type_id','question_types.slug as type_name')
            ->join('book_exercises', 'book_exercises.id', '=', 'question_lists.excercise_id')
            ->join('question_types', 'question_types.id', '=', 'question_lists.question_type')
            ->whereRaw('question_lists.excercise_id = ? and book_exercises.book_id = ?',array($excercise,$book_data->id))
            ->get();
            return DataTables::of($table_data)
                    ->addIndexColumn()
                    ->addColumn('title', function($table_data){  $title=$table_data->question_data; return $title;})
                    ->addColumn('type', function($table_data){ $question_type=__('menus.'.$table_data->type_name); return $question_type;})
                    ->addColumn('action', function($table_data){
                         $btn='<a href="'.route('admin.title.excercise-edit_question',['qid'=>$table_data->id]).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>';
                         $btn.='<a href="javascript:void(0);" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info delExcercseData" data-eid="'.$table_data->id.'"><span class="fa fa-trash"></span></a>';
                         return $btn;
                        })
                    ->rawColumns(['action'])
                    ->removeColumn('id')
                    ->make(true);
          }

        $data['chapters']=DB::table('chapters')->where('book_id',$book_data->id)->orderBy('order','ASC')->get();
        return view('User.add_book_exercise',compact('book_data','data','excer_editdat'));
    }


    public function addorUpexcercise($id,Request $request){
       $validator=Validator::make($request->all(),[
        'book_entity' => 'required|integer|exists:books,id|in:'.$id,
        'excercise_ent' => 'sometimes|required|exists:book_exercises,id',
        'type_exercice' => 'required|integer|in:1,0',
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
          $excer_data=Book_exercise::updateOrCreate([
            'book_id' => $data['book_entity'],
            'id' => $excercise_entity
            ],
            ['exercise_type' => $data['type_exercice'],
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
        return redirect()->route('admin.title.manage_book_exercise',['id'=>$data['book_entity']])->with('excer.success',$mssg);
        }catch(\Exception $ex){
                DB::rollback();
                return response()->json(['status'=>'error','message' => [ $ex->getMessage() ]], 500);
        }
      }

    }

    public function delExcercise(Request $request){
        if($request->ajax()){
            $book_id=(int)$request->input('book_ent');
            $excer_id=(int)$request->input('excer_ent');
            $chap_id=(int)$request->input('chapt_ent');
            $coarray=array('book_id'=>$book_id,'chapter_id'=>$chap_id,'id'=>$excer_id);
           // $rslt=Book_exercise::where($coarray)->delete();
            if(Book_exercise::where($coarray)->delete()){
                return response()->json(['status'=>'success']);
            }
        }
    }

    public function questionManagger($id,Request $request){
        $excer_entity=(int)$id;
        $complete_excerdetails=DB::table('book_exercises')
        ->select('book_exercises.*','chapters.id as chapid','chapters.title as chapname','books.id as bookid','books.title as bookname','books.cover_image as book_coverimg')
        ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
        ->join('books', 'books.id', '=', 'book_exercises.book_id')
        ->whereRaw('book_exercises.id = ?',array($excer_entity))->first();
        $question_types=DB::table('question_types')->get();
         if($complete_excerdetails){
            return view('User.question_typeseach',compact('complete_excerdetails','question_types'));
         }
         else{
            return redirect()->route('admin.title.index');
         }
    }

    public function addquestionType($id,$type=null,Request $request){
      //dd("fret");
        $question_type=(string)$request->input('type'); 
        $view='';
        $excercise=(int)$id;
        $complete_excerdetails=DB::table('book_exercises')
        ->select('book_exercises.*','chapters.id as chapid','chapters.title as chapname','books.id as bookid','books.title as bookname','books.cover_image as book_coverimg')
        ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
        ->join('books', 'books.id', '=', 'book_exercises.book_id')
        ->whereRaw('book_exercises.id = ?',array($excercise))->first();
        $programm_elements=Book::with('education_programm_elements')->find($complete_excerdetails->bookid);
        switch($question_type){
          case 'multiple_choice':
            $view='User.questions.multiple_choice';
          break;

          case 'word_pairs':
            $view='User.questions.word_pairs';
          break;

          case 'label_the_image':
            $view='User.questions.label_image';
          break;

          case 'short_answer_essay':
            $view='User.questions.essay_question';
          break;

          case 'true_or_false':
            $view='User.questions.true_or_falsequestion';
          break;

          case 'sort_the_elements':
            //dd($question_type);
            $view = 'User.questions.sort_the_elements';
          break;

          default:
           return redirect()->route('admin.title.index');
        }
        return view($view,compact('complete_excerdetails','programm_elements'));
    }

    public function addorUpquestions($id,Request $request){
      
//dd("hloo");
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
            //'sortfile.*' => 'sometimes|mimes:jpeg,jpg,png,svg,webp|max:2024',
            'sortfile.*' => 'required'
         ],[
            'image_to_caption.dimensions'=>'Image requires minimum width 600px.',
            'image_to_caption.max'=>'Maximum file size is 2MB.',
            'image_to_caption.mimes'=>'Invalid file format.'
         ]);
         if ($validator->fails()) {
           //dd("validation error");
            $error = $validator->errors();
            //dd($error);
            return redirect()->back()->withErrors($validator)->withInput();
          }
          else{
            //dd("worked");
            DB::beginTransaction();
          try{
            $data=$request->all();
            //dd($data);
            $question_type=$request->input('question_type');
            $question_entity=$request->has('question_ent') ? (int)$request->input('question_ent') : null;  // question_entity for update

            switch($question_type){
                case 1:
                 $answer_format=$this->multiple_choiceSerialize($request);
                 //dd($answer_format);
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
                  //dd($answer_format);
                break;

                default:
                 return redirect()->route('admin.title.index');
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

             return redirect()->route('admin.title.add_bookexercise',['id'=>$excer_data->book_id,'excer'=>$excer_data->id]);
            }catch(\Exception $ex){
                DB::rollback();
                return response()->json(['status'=>'error','message' => [ $ex->getMessage() ]], 500);
            }
          }
    }

    public function editQuestiondata($qid,Request $request){
      
        $ques_id=(int)$qid; $view=''; $answer_format=''; $answer_arrformat=array();
        //dd($ques_id);
        DB::beginTransaction();
        try{
            $question_data=DB::table('question_lists')
            ->select('question_lists.*','question_types.slug as slug_name')
            ->join('question_types', 'question_types.id', '=', 'question_lists.question_type')
            ->whereRaw('question_lists.id = ?',array($ques_id))->first();
            //dd($question_data);
            $excercise=$question_data->excercise_id;
            //dd($excercise);
            $question_type=$question_data->slug_name;
            //dd($question_type);
            $complete_excerdetails=DB::table('book_exercises')
            ->select('book_exercises.*','chapters.id as chapid','chapters.title as chapname','books.id as bookid','books.title as bookname','books.cover_image as book_coverimg')
            ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
            ->join('books', 'books.id', '=', 'book_exercises.book_id')
            ->whereRaw('book_exercises.id = ?',array($excercise))->first();
            $programm_elements=Book::with('education_programm_elements')->find($complete_excerdetails->bookid);
            DB::commit();

            switch($question_type){
                case 'multiple_choice':
                  $view='User.questions.multiple_choice';
                  $answer_format=unserialize($question_data->answer_format);
                  $answer_arrformat['correct_answers']=array_keys($answer_format,true);
                  $answer_arrformat['incorrect_answers']=array_keys($answer_format,false);
                break;

                case 'word_pairs':
                  $view='User.questions.word_pairs';
                  $answer_format=unserialize($question_data->answer_format);
                  $answer_arrformat['correct_answers']=$answer_format;
                break;

                case 'label_the_image':
                   $view='User.questions.label_image';
                   $answer_format=unserialize($question_data->answer_format);
                   $answer_arrformat['label_imgpath']=$answer_format['label_imgpath'];
                   $answer_arrformat['correct_answers']=$answer_format['label_cordinate_lists'];
                break;

                case 'short_answer_essay':
                    $view='User.questions.essay_question';
                    $answer_format=unserialize($question_data->answer_format);
                    //dd($answer_format);
                    $answer_arrformat['words_limit']=$answer_format['words_limit'];
                    $answer_arrformat['rating_scale']=$answer_format['rating_scale'];
                    $answer_arrformat['correct_answer']=$answer_format['correct_answer'];
                break;

                case 'true_or_false':
                  $view='User.questions.true_or_falsequestion';
                  $answer_format=unserialize($question_data->answer_format);
                  $answer_arrformat['correct_answers']=$answer_format['answer'];
                break;

                case 'sort_the_elements':
                  //dd($question_type);
                $view = 'User.questions.sort_the_elements';
                $answer_arrformat=unserialize($question_data->answer_format);

               // dd($answer_arrformat);
                break;

                default:
                 return redirect()->route('admin.title.index');
              }
              return view($view,compact('complete_excerdetails','programm_elements','question_data','answer_arrformat'));
         }catch(\Exception $ex){
             dd($ex->getMessage());
            DB::rollback();
            return redirect()->route('admin.title.index');
         }

    }

    public function deleteQuestiondata(Request $request)
    {
      //dd("hii");
       if($request->ajax()){
            
            $excer_question=(int)$request->input('excer_ent');
         // dd($excer_id);
           /* $coarray=array('book_id'=>$book_id,'chapter_id'=>$chap_id,'id'=>$excer_id);*/
           // $rslt=Book_exercise::where($coarray)->delete();
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
        if($request->hasFile('sortfile')){
      //dd("file und");
       foreach ($request->file('sortfile') as $files) {
          $destination_folder= public_path('/uploads/sortimages');
          $cap_imgName = time().'_'.preg_replace('/\s+/', '_', $files->getClientOriginalName());
          $files->move($destination_folder,$cap_imgName);
          $cap_imgpath = '/uploads/sortimages/'.$cap_imgName;
          $answer_list[]=$cap_imgpath;
          
      }
       
    }

     if(isset($data["edit-images"]))
     {
     
         foreach ($data["edit-image"] as $files) {

           $answer_list[]=$files;

         }
        
         
     }
     
  
      $answer_format=serialize($answer_list);
      return $answer_format;

     
     
}
}
