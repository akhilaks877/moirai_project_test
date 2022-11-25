<?php

namespace App\Http\Controllers\User\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Book;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Student_chap_block;
use Validator;
use DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Auth;
use App\Models\Student;
use Illuminate\Support\Facades\View;

class BooksController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function __construct()
  {
    //  $this->middleware('can:access-book',['post'=>Book::find(1)])->only(['show']);
  }

  public function index(Request $request)
  {
    $user_id = auth()->user()->id;
    $data = [];

    if ($request->ajax()) {
      $book_list = DB::table('books')->select('books.id', 'books.title', 'books.subtitle', 'books.cover_image', 'books.subject', 'order_informations.id as orderid', 'order_informations.bookitem_id', 'order_informations.status', 'order_informations.user_id')
        ->join('order_informations', function ($join) use ($user_id) {
          $join->on('order_informations.bookitem_id', '=', 'books.id');
          $join->where('order_informations.status', 1);
          $join->where('order_informations.user_id', '=', $user_id);
        })
        ->whereExists(function ($query) {
          $query->select(DB::raw(1))
            ->from('order_books')
            ->whereRaw('order_books.book_id = books.id')
            ->whereRaw('order_books.order_id = order_informations.id');
        });
      if ($request->has('subject_val') && $request->input('subject_val') != '') {
        $book_list = $book_list->where('subject', (int) $request->input('subject_val'));
      }
      $book_list = $book_list->paginate(8);
      $data['book_lists'] = $book_list;
      $view = view('User.student.admin.book_filters', compact('data'))->render();
      return response()->json(['status' => 'success', 'results' => $view]);
    }

    $data['book_lists'] = DB::table('books')->select('books.id', 'books.title', 'books.subtitle', 'books.cover_image', 'books.subject', 'order_informations.id as orderid', 'order_informations.bookitem_id', 'order_informations.status', 'order_informations.user_id')
      ->join('order_informations', function ($join) use ($user_id) {
        $join->on('order_informations.bookitem_id', '=', 'books.id');
        $join->where('order_informations.status', 1);
        $join->where('order_informations.user_id', '=', $user_id);
      })
      ->whereExists(function ($query) {
        $query->select(DB::raw(1))
          ->from('order_books')
          ->whereRaw('order_books.book_id = books.id')
          ->whereRaw('order_books.order_id = order_informations.id');
      })
      ->paginate(8);
    $data['subjects'] = DB::table('subjects')->get();
    return view('User.student.admin.alltitles', compact('data'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id, Request $request)
  {
    if (Gate::allows('access-book', [Book::find((int) $id)])) {  // check student has ordered book
      $data = [];
      $book = DB::table('books')->select('books.*', 'subjects.id as subject_id', 'subjects.name as subject_name')->join('subjects', 'subjects.id', '=', 'books.subject')->whereRaw('books.id = ?', array((int) $id))->first();
      $excercise_details = DB::table('book_exercises')->whereRaw('book_id = ?', array($book->id))->get();
      $chapters = DB::table('chapters')->where('book_id', $book->id)->orderBy('order', 'ASC')->get();

      if ($request->ajax()) {
        $book = DB::table('books')->select('books.*', 'subjects.id as subject_id', 'subjects.name as subject_name')->join('subjects', 'subjects.id', '=', 'books.subject')->whereRaw('books.id = ?', array((int) $id))->first();
        $data = [];
        if ($request->has('show_excercises') && $request->input('show_excercises') == 1) {
          $data = DB::table('book_exercises')->select('book_exercises.*', 'chapters.id as chapid', 'chapters.title as chapname', 'chapters.order as chapindex', 'users.id as userid', 'users.firstname as userfirstname', 'users.lastname as userlastname')
            ->join('chapters', 'chapters.id', '=', 'book_exercises.chapter_id')
            ->leftJoin('users', 'book_exercises.user_id', '=', 'users.id')
            ->where('book_exercises.book_id', $book->id)->orderBy('chapindex', 'ASC')->get();
          // dd($data);
          return DataTables::of($data)
            ->addColumn('chapter', function ($data) {
              $chapter = $data->chapindex;
              return $chapter;
            })
            ->addColumn('title', function ($data) {
              $title = $data->title;
              return $title;
            })
            ->addColumn('type', function ($data) {
              if ($data->exercise_type == 1) {
                $type = "Chapter Test";
              } else if ($data->exercise_type == 0) {
                $type = "Learning Exercise";
              } else {
                $type = "-";
              }
              return $type;
            })
            ->addColumn('grade', function ($data) {
              $grade = "3/6";
              return $grade;
            })
            ->addColumn('action', function ($data) {
              $btn = '<a href="javascript:void(0);" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-eye"></span>See My Results</a>';
              $btn .= '<a href="javascript:void(0);" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span>Redo Exercise</a>';
              return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
      }
      return view('User.student.admin.book_details', compact('book', 'excercise_details', 'chapters'));
    } else {
      return redirect()->route('student.title.index');
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }


  public function read_book($id, Request $request)
  {
    //dd("hii");
    $book = Book::find((int) $id);
    if (Gate::allows('access-book', [$book])) {
      $user_id = auth()->user()->id;
      $student_data = DB::table('students')->where('user_id', $user_id)->first();
      $pref_setts = DB::table('preference_settings')->where('user_id', $user_id)->first();
      $data['chapters'] = DB::table('chapters')->where('book_id', $book->id)->orderBy('order', 'ASC')->get();
      $data['chapter_blocks'] = '';
      $data['chapter_id'] = '';
      $data['chapter_data'] = '';
      $chapters_count = $data['chapters']->count();
      $data['prev_chapter'] = 0;
      $data['next_chapter'] = 0;
      if (!$data['chapters']->isEmpty()) {
        $chapter_entity = ($request->has('chapter') && $request->get('chapter') != '') ? (int) $request->get('chapter') : $data['chapters'][0]->id;
        $data['chapter_data'] = DB::table('chapters')->where('id', $chapter_entity)->first();
        $associated_teacherusers = DB::table('teachers')->select('teachers.*', 'classes.teacher_id', 'classes.id as classid', 'users.id as tusid')->join('classes', 'classes.teacher_id', '=', 'teachers.id')->join('users', 'users.id', '=', 'teachers.user_id')
          ->whereExists(function ($query) use ($student_data) {
            $query->select(DB::raw(1))
              ->from('student_class')
              ->whereRaw('student_class.student_id = ? AND student_class.status = ?', array($student_data->id, 1))
              ->whereRaw('student_class.class_id = classes.id');
          })
          ->whereExists(function ($query) use ($book) {
            $query->select(DB::raw(1))
              ->from('class_book_suggestions')
              ->whereRaw('class_book_suggestions.book_id = ?', array($book->id))
              ->whereRaw('class_book_suggestions.class_id = classes.id');
          })->get()->pluck('tusid')->toArray();

        $chapter_blocks = DB::table('chapter_blocks')
          ->where(array('book_id' => $book->id, 'chapter_id' => $chapter_entity, 'active' => 1))
          ->orderBy('note_id')->orderBy('order')->get()->groupBy('note_id');
        $data['chapter_blocks'] = $chapter_blocks;
        $data['chapter_id'] = $chapter_entity;

        $teacher_notes = DB::table('teacher_chapter_blocks')->select('teacher_chapter_blocks.*', 'teachers.user_id as tuid', 'teachers.first_name', 'teachers.last_name')
          ->join('teachers', 'teachers.user_id', '=', 'teacher_chapter_blocks.user_id')
          ->where(array('book_id' => $book->id, 'chapter_id' => $chapter_entity, 'active' => 1))
          ->whereIn('teacher_chapter_blocks.user_id', $associated_teacherusers)
          ->orderBy('note_id')->orderBy('order')->orderBy('user_id')->get()->groupBy(['note_id', 'order']);
        $data['teacher_notes'] = $teacher_notes;

        $student_notes = DB::table('student_chapter_blocks')
          ->where(array('book_id' => $book->id, 'chapter_id' => $chapter_entity, 'user_id' => $user_id, 'active' => 1))
          ->orderBy('note_id')->orderBy('order')->get()->groupBy(['note_id', 'order']);
        $data['student_notes'] = $student_notes;
      } else {
        return redirect()->back();
      }
      //Check book is exists
      $view = 'book_' . $book->id . '.ebook.' . $book->id . '_' . preg_replace('/\s+/', '_', $book->title) . '_introduction';
      if (View::exists($view)) {
        return view($view);
      } else {
        $data['book'] = $book;
        return view('User.student.admin.read_book_data', compact('data', 'pref_setts'));
      }
    } else {
      return redirect()->route('student.title.index');
    }
  }

  public function manage_notes($id, Request $request)
  {
    $book = Book::find((int) $id);
    if (Gate::allows('access-book', [$book])) {
      $user_id = auth()->user()->id;
      $student_data = DB::table('students')->where('user_id', $user_id)->first();
      $pref_setts = DB::table('preference_settings')->where('user_id', $user_id)->first();
      //dd($pref_setts);
      $data['chapters'] = DB::table('chapters')->where('book_id', $book->id)->orderBy('order', 'ASC')->get();
      $data['chapter_blocks'] = '';
      $data['chapter_id'] = '';
      $data['chapter_data'] = '';
      $chapters_count = $data['chapters']->count();
      $data['prev_chapter'] = 0;
      $data['next_chapter'] = 0;
      if (!$data['chapters']->isEmpty()) {
        $chapter_entity = ($request->has('chapter') && $request->get('chapter') != '') ? (int) $request->get('chapter') : $data['chapters'][0]->id;
        $data['chapter_data'] = DB::table('chapters')->where('id', $chapter_entity)->first();

        $associated_teacherusers = DB::table('teachers')->select('teachers.*', 'classes.teacher_id', 'classes.id as classid', 'users.id as tusid')->join('classes', 'classes.teacher_id', '=', 'teachers.id')->join('users', 'users.id', '=', 'teachers.user_id')
          ->whereExists(function ($query) use ($student_data) {
            $query->select(DB::raw(1))
              ->from('student_class')
              ->whereRaw('student_class.student_id = ? AND student_class.status = ?', array($student_data->id, 1))
              ->whereRaw('student_class.class_id = classes.id');
          })
          ->whereExists(function ($query) use ($book) {
            $query->select(DB::raw(1))
              ->from('class_book_suggestions')
              ->whereRaw('class_book_suggestions.book_id = ?', array($book->id))
              ->whereRaw('class_book_suggestions.class_id = classes.id');
          })->get()->pluck('tusid')->toArray();

        $chapter_blocks = DB::table('chapter_blocks')
          ->where(array('book_id' => $book->id, 'chapter_id' => $chapter_entity, 'active' => 1))
          ->orderBy('note_id')->orderBy('order')->get()->groupBy('note_id');
        $data['chapter_blocks'] = $chapter_blocks;
        $data['chapter_id'] = $chapter_entity;

        $teacher_notes = DB::table('teacher_chapter_blocks')->select('teacher_chapter_blocks.*', 'teachers.user_id as tuid', 'teachers.first_name', 'teachers.last_name')
          ->join('teachers', 'teachers.user_id', '=', 'teacher_chapter_blocks.user_id')
          ->where(array('book_id' => $book->id, 'chapter_id' => $chapter_entity, 'active' => 1))
          ->whereIn('teacher_chapter_blocks.user_id', $associated_teacherusers)
          ->orderBy('note_id')->orderBy('order')->orderBy('user_id')->get()->groupBy(['note_id', 'order']);
        $data['teacher_notes'] = $teacher_notes;

        $student_notes = DB::table('student_chapter_blocks')
          ->where(array('book_id' => $book->id, 'chapter_id' => $chapter_entity, 'user_id' => $user_id, 'active' => 1))
          ->orderBy('note_id')->orderBy('order')->get()->groupBy(['note_id', 'order']);
        $data['student_notes'] = $student_notes;
      } else {
        return redirect()->back();
      }
      $data['book'] = $book;

      return view('User.student.admin.add_notes', compact('data', 'pref_setts'));
    } else {
      return redirect()->route('student.title.index');
    }
  }

  public function addorUpdateStudentnotes($id, Request $request)
  {

    if ($request->ajax() && Gate::allows('access-book', [Book::find((int) $id)])) {
      //dd("kitty");
      $validator = Validator::make($request->all(), [
        'book_entity' => 'required|integer|in:' . $id,
        'chapter_entity' => 'sometimes|required|exists:chapters,id',
        'note_element' => 'required|integer',
        'up_technote' => 'sometimes|required|integer',
        'student_text_block' => 'sometimes|required|exists:student_chapter_blocks,id',
        'student_img_block' => 'sometimes|required|exists:student_chapter_blocks,id',
        // 'block_id' => 'sometimes|required|exists:student_chapter_blocks,id',
        'note.*' => 'nullable|string',
        'notefile.*'  => 'nullable|mimes:jpeg,png,svg,webp',
        'filecaption.*' => 'nullable|string',
      ], []);
      if ($validator->fails()) {
        $error = $validator->errors();
        return response()->json(['status' => 'invalid', 'messages' => $error, 'message' => 'Invalid Attempt']);
      } else {
        DB::beginTransaction();
        try {
          $data = $request->all();
          $arr = [];
          $user_id = auth()->user()->id;
          $book_id = (int) $data['book_entity'];
          $chapter_val = $request->has('chapter_entity') ? (int) $request->input('chapter_entity') : null;
          $student_text_block = $request->has('student_text_block') ? (int) $request->input('student_text_block') : null;
          $student_img_block = $request->has('student_img_block') ? (int) $request->input('student_img_block') : null;
          $note_id = $request->has('note_element') ? (int) $request->input('note_element') : null;
          if ($request->has('up_technote')) {
            $latest_note = DB::table('student_chapter_blocks')->where(array('book_id' => $book_id, 'chapter_id' => $chapter_val, 'note_id' => $note_id, 'id' => $student_text_block))->first();
            $order = ($latest_note) ? $latest_note->order : 1;
          } else {
            $latest_note = DB::table('student_chapter_blocks')->where(array('book_id' => $book_id, 'chapter_id' => $chapter_val, 'note_id' => $note_id))->orderByDesc('order')->first();
            $order = ($latest_note) ? $latest_note->order + 1 : 1;
          }

          if (isset($data['note'][0])) {  // 3 add description or note block
            $bdata['content'] = $data['note'][0];
            $bdata['block_type'] = 3;
            $bdata['block_id'] = $student_text_block;
            $bdata['chapter_id'] = $chapter_val;
            $bdata['book_id'] = $book_id;
            $bdata['note_id'] = $note_id;
            $bdata['order'] = $order;
            $metadata = null;
            $bdata['is_file'] = null; // it is set to null, since its not file type
            $bdata['active'] = 1;
            $this->addchapterBlock($bdata, $metadata);
          }
          if ($request->hasFile('notefile.0')) {    //4 add image block
            $block_img = $request->file('notefile.0');
            $destination_folder = 'public/ebooks/book_' . $book_id . '/chapter_' . $chapter_val . '/student_notes/student_' . $user_id;
            $blck_imgName = time() . '_' . preg_replace('/\s+/', '_', $request->file('notefile.0')->getClientOriginalName());
            $block_img->storeAs($destination_folder, $blck_imgName);

            $bdata['content'] = '/storage/ebooks/book_' . $book_id . '/chapter_' . $chapter_val . '/student_notes/student_' . $user_id . '/' . $blck_imgName;
            $bdata['block_type'] = 4;
            $bdata['block_id'] = $student_img_block;
            $bdata['chapter_id'] = $chapter_val;
            $bdata['book_id'] = $book_id;
            $bdata['note_id'] = $note_id;
            $bdata['order'] = $order;
            $metadata = $data['filecaption'][0]; // image caption
            $bdata['is_file'] = 1; // it is set to 1, since its a file_type block
            $bdata['active'] = 1;
            $this->addchapterBlock($bdata, $metadata);
          }
          if ($data['contain_file'][0] == "0" && $request->has('up_technote')) {  // if its 0 block_type 4 fille doesnot contain any file
            $bdata['content'] = null;
            $bdata['block_type'] = 4;
            $bdata['block_id'] = $student_img_block;
            $bdata['chapter_id'] = $chapter_val;
            $bdata['book_id'] = $book_id;
            $bdata['note_id'] = $note_id;
            $bdata['order'] = $order;
            $metadata = null;
            $bdata['is_file'] = 1; // it is set to null, since its not file type
            $bdata['active'] = 0; // it is set to zero becuase block_type 4 fille doesnot contain any file
            $this->addchapterBlock($bdata, $metadata);
          }
          DB::commit();
          $message = 'success';
          return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $ex) {
          DB::rollback();
          return response()->json(['status' => 'error', 'message' => [$ex->getMessage()]], 500);
        }
      }
    }
  }

  public function addchapterBlock(array $block_data, $metdata = null)
  {
    $table = DB::table('chapter_blocks');
    $insrt_array = array(
      'content' => isset($block_data['content']) ? $block_data['content'] : null,
      'block_type' => isset($block_data['block_type']) ? $block_data['block_type'] : null,
      'book_id' => isset($block_data['book_id']) ? $block_data['book_id'] : null,
      'chapter_id' => isset($block_data['chapter_id']) ? $block_data['chapter_id'] : null,
      'note_id' => isset($block_data['note_id']) ? $block_data['note_id'] : null,
      'order' => isset($block_data['order']) ? $block_data['order'] : null,
      'metadatas' => isset($metdata) ? $metdata : null,
      'is_file' => isset($block_data['is_file']) ? $block_data['is_file'] : null,
      'active' => isset($block_data['active']) ? $block_data['active'] : null,
      'user_id' => auth()->user()->id,
    );
    $check_array = array(
      'block_type' => $insrt_array['block_type'],
      'book_id' => $insrt_array['book_id'],
      'chapter_id' => $insrt_array['chapter_id'],
      'note_id' => $insrt_array['note_id'],
      'id' => isset($block_data['block_id']) ? $block_data['block_id'] : null,
    );
    $block_data = Student_chap_block::updateOrCreate($check_array, $insrt_array);
    return true;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }



  public function teachers()
  {
    //
  }
}
