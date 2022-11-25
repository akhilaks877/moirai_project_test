<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Preferrable_lang;
use App\Book;
use App\Block_content;
use App\Contributor_role;
use App\Contributor;
use App\Subject;
use App\Education_programm_element;
use Validator;
use DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\File;
use Illuminate\Support\Arr;

class EbooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];

        if ($request->ajax()) {
            $book_list = DB::table('books')->select('books.id', 'books.title', 'books.subtitle', 'books.cover_image', 'books.subject');
            if ($request->has('subject_val') && $request->input('subject_val') != '') {
                $book_list = $book_list->where('subject', (int) $request->input('subject_val'));
            }
            $book_list = $book_list->paginate(8);
            $data['book_lists'] = $book_list;
            $view = view('User.books.books_datas', compact('data'))->render();
            return response()->json(['status' => 'success', 'results' => $view]);
        }

        $data['book_lists'] = DB::table('books')->select('books.id', 'books.title', 'books.subtitle', 'books.cover_image', 'books.subject')->orderBy('created_at', 'DESC')->paginate(8);
        $data['subjects'] = DB::table('subjects')->get();
        return view('User.book_title_lists', compact('data'));
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
    public function show($id)
    {
        //
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

    public function add_book_details(Request $request)
    {
        $data = [];
        $data['langs'] = Preferrable_lang::get();
        //dd($data['langs']);
        return view('User.add_bibliographic_data', compact('data'));
    }

    public function add_book_generalContents(Request $request)
    {   // book general details
        $data = [];
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'subtitle' => 'required|string',
                'isbn' => 'required|regex:/^[0-9]+$/',
                'magento_sku' => 'required|string',
                'language' => 'required|integer',
                'country' => 'required|integer',
                'num_pages' => 'required|integer',
                'editor' => 'required|string',
                'cover_book'  => 'required_unless:decidesimg,1|mimes:jpeg,png,svg,webp|max:1040', //|dimensions:min_width=472,min_height=667 // decidesimg on book update
                'description' => 'nullable|string',
                'upbook_data' => 'sometimes|required', // this element is added only, when update book
                'book_entity' => 'sometimes|required', // this element is added only, when update book
            ], [
                'cover_book.required_unless' => 'Cover photo is required',
                'cover_book.mimes' => 'Should be in Image format',
                'cover_book.dimensions' => 'Image file dimensions should width 600px height 800px',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors();
                return response()->json(['status' => 'invalid', 'messages' => $error, 'message' => 'Invalid Attempt']);
            } else {
                DB::beginTransaction();
                try {
                    //dd($request->all());
                    $data = $request->all();
                    $book_id = $request->has('book_entity') ? (int) $request->input('book_entity') : null;
                    $book_data = Book::updateOrCreate(
                        ['id' => $book_id],
                        [
                            'title' => $data['title'],
                            'subtitle' => $data['subtitle'],
                            'isbn' => $data['isbn'],
                            'magento_sku' => $data['magento_sku'],
                            'language' => $data['language'],
                            'country' => $data['country'],
                            'page_nos' => $data['num_pages'],
                            'editor_name' => $data['editor'],
                            'description' => $data['description'],
                        ]
                    );

                    $cover_imgName = '';
                    if ($request->hasFile('cover_book')) {
                        $cover_img = $request->file('cover_book');
                        $destination_folder = 'public/ebooks/book_' . $book_data->id . '/cover_image';
                        $cover_imgName = time() . '_' . preg_replace('/\s+/', '_', $book_data->title) . '.' . $request->file('cover_book')->getClientOriginalExtension();
                        $cover_img->storeAs($destination_folder, $cover_imgName);
                        $filename = $cover_imgName;
                        $update_book = Book::find($book_data->id);
                        $update_book->cover_image = $filename;
                        $update_book->save();
                    }
                    /* $add_titleBlock=Block_content::updateOrCreate(['book_id'=>$book_data->id,'block_type'=>1],['block_type'=>1, 'block_content' =>$data['title'], 'book_id'=>$book_data->id]);  //1 block_type stands for title block
                  $add_subtitleBlock=Block_content::updateOrCreate(['book_id'=>$book_data->id,'block_type'=>2],['block_type'=>2, 'block_content' =>$data['subtitle'], 'book_id'=>$book_data->id]);  //2 block_type stands for subtitle block
                  $add_DescriptionBlock=Block_content::updateOrCreate(['book_id'=>$book_data->id,'block_type'=>3],['block_type'=>3, 'block_content' =>$data['description'], 'book_id'=>$book_data->id]);  //3 block_type stands for description block
                  $add_coverImgBlock=Block_content::updateOrCreate(['book_id'=>$book_data->id,'block_type'=>4],['block_type'=>4, 'block_content' => asset('storage/ebooks/book_'.$book_data->id.'/cover_image/'.$cover_imgName), 'book_id'=>$book_data->id]);  //4 block_type stands for cover image block
                  $book_data->block_contents()->detach([$add_titleBlock->id,$add_subtitleBlock->id,$add_DescriptionBlock->id,$add_coverImgBlock->id]);
                  $book_data->block_contents()->attach([$add_titleBlock->id,$add_subtitleBlock->id,$add_DescriptionBlock->id,$add_coverImgBlock->id]);
                  $add_coverImgBlock=''; */

                    DB::commit();
                } catch (\Exception $ex) {
                    DB::rollback();
                    return response()->json(['status' => 'error', 'message' => [$ex->getMessage()]], 500);
                }
                if ($request->has('book_entity')) {
                    $messge = 'Book details with Title &nbsp<strong>' . $book_data->title . '</strong> has been updated.';
                } else {
                    $messge = 'New Book with Title &nbsp<strong>' . $book_data->title . '</strong> has been added.';
                }
                $request->session()->flash('bookstatus.status', 'success');
                $request->session()->flash('bookstatus.message', $messge);
                return response()->json(['status' => 'success', 'results' => $book_data, 'message' => 'General Deatails Inserted.']);
            }
        }
        //    dd($request->all());
    }

    public function add_book_dimensionWeight(Request $request)
    { // book dimension details
        $data = [];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'size_unit_choice' => 'required|string',
                'length' => 'required|numeric',
                'width' => 'required|numeric',
                'thickness' => 'required|numeric',
                'weight' => 'required|numeric',
                'book_entity' => 'sometimes|required', // this element is added only, when update book
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return response()->json(['status' => 'invalid', 'messages' => $error, 'message' => 'Invalid Attempt']);
            } else {
                DB::beginTransaction();
                try {
                    $data = $request->all();
                    $book_id = $request->has('book_entity') ? (int) $request->input('book_entity') : null;
                    $physical_unit = ($data['size_unit_choice'] == 'cm') ? 0 : 1;
                    $book_data = Book::where('id', $book_id)->update([
                        'physical_unit' => $physical_unit,
                        'length' => $data['length'],
                        'width' => $data['width'],
                        'thickness' => $data['thickness'],
                        'weight' => $data['weight'],
                    ]);
                    DB::commit();
                } catch (\Exception $ex) {
                    DB::rollback();
                    return response()->json(['status' => 'error', 'message' => [$ex->getMessage()]], 500);
                }
                $messge = '';
                $book_details = Book::find($book_id);
                if ($request->has('book_entity')) {
                    $messge = 'Book details with Title &nbsp<strong>' . $book_details->title . '</strong> has been updated.';
                }
                $request->session()->flash('bookstatus.status', 'success');
                $request->session()->flash('bookstatus.message', $messge);
                return response()->json(['status' => 'success', 'results' => $book_data, 'message' => 'General Deatails Inserted.']);
            }
        }
    }

    public function add_book_salesinfo(Request $request)
    { // book sales details
        $data = [];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'availability' => 'required|integer',
                'inventory' => 'required|regex:/^[0-9]+$/',
                'sale_date' => 'required|date_format:d/m/Y',
                'price' => 'required|numeric',
                'book_entity' => 'sometimes|required', // this element is added only, when update book
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return response()->json(['status' => 'invalid', 'messages' => $error, 'message' => 'Invalid Attempt']);
            } else {
                DB::beginTransaction();
                try {
                    $data = $request->all();
                    $book_id = $request->has('book_entity') ? (int) $request->input('book_entity') : null;
                    $book_data = Book::where('id', $book_id)->update([
                        'availability' => $data['availability'],
                        'publication_date' => Carbon::createFromFormat('d/m/Y', $data['sale_date'])->format('Y-m-d'),
                        'inventory' => $data['inventory'],
                        'price' => $data['price'],
                    ]);
                    DB::commit();
                } catch (\Exception $ex) {
                    DB::rollback();
                    return response()->json(['status' => 'error', 'message' => [$ex->getMessage()]], 500);
                }
                $messge = '';
                $book_details = Book::find($book_id);
                if ($request->has('book_entity')) {
                    $messge = 'Book details with Title &nbsp<strong>' . $book_details->title . '</strong> has been updated.';
                }
                $request->session()->flash('bookstatus.status', 'success');
                $request->session()->flash('bookstatus.message', $messge);
                return response()->json(['status' => 'success', 'results' => $book_data, 'message' => 'General Deatails Inserted.']);
            }
        }
    }


    public function add_new_contributor(Request $request)
    { // adding new contributor
        $data = [];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'role' => 'required|integer',
                'biography_contributor' => 'nullable|string',
                'book_entity' => 'sometimes|required', // this element is added only, when update book
                'user_image'  => 'nullable|mimes:jpeg,png,svg,webp',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return response()->json(['status' => 'invalid', 'messages' => $error, 'message' => 'Invalid Attempt']);
            } else {
                DB::beginTransaction();
                try {
                    $data = $request->all();
                    $contributor = Contributor::create([
                        'firstname' => $data['firstname'],
                        'lastname' => $data['lastname'],
                        'role' => $data['role'],
                        'biography' => $data['biography_contributor'],
                    ]);

                    $user_imgName = '';
                    if ($request->hasFile('user_image')) {
                        $user_img = $request->file('user_image');
                        $destination_folder = 'public/book_contributors/contributor_' . $contributor->id;
                        $user_imgName = time() . '_' . $user_img->getClientOriginalName();
                        $user_img->storeAs($destination_folder, $user_imgName);
                        $filename = $user_imgName;
                        $update_con = Contributor::find($contributor->id);
                        $update_con->user_image = $filename;
                        $update_con->save();
                    }

                    DB::commit();
                } catch (\Exception $ex) {
                    DB::rollback();
                    return response()->json(['status' => 'error', 'message' => [$ex->getMessage()]], 500);
                }
                return response()->json(['status' => 'success', 'results' => $contributor, 'message' => 'New Contributor <strong>' . $contributor->firstname . ' ' . $contributor->lastname . '</strong> details added.']);
            }
        }
    }

    public function add_contributor_toThebook(Request $request)
    { // adding a contributor to a particular book
        if ($request->ajax()) {
            $book_id = (int) $request->input('book');
            $contrbtr = (int) $request->input('conbtr');
            $book_ent = Book::find($book_id);
            if (!$book_ent->contributors->contains($contrbtr)) {
                $attach_contrbtr = $book_ent->contributors()->attach($contrbtr); // adding contributor to book
            } else {
                return response()->json(['status' => 'invalid']);
            }
            return response()->json(['status' => 'success', 'results' => $book_ent]);
        }
    }

    public function remove_contributor_fromThebook(Request $request)
    { // removing a contributor from a particular book
        if ($request->ajax()) {
            $book_id = (int) $request->input('book_ent');
            $contrbtr_id = (int) $request->input('contrb_ent');
            $book_ent = Book::find($book_id);
            if ($book_ent->contributors()->detach($contrbtr_id)) {
                return response()->json(['status' => 'success', 'results' => $book_ent]);
            } else {
                return response()->json(['status' => 'invalid']);
            }
        }
    }

    public function add_subject_Tobook(Request $request)
    { // adding subject to book
        if ($request->ajax()) {
            $subject = (int) $request->input('subject');
            $book_id = (int) $request->input('book_nt');
            $book_ent = Book::find($book_id);
            $book_ent->subject = $subject;
            if ($book_ent->save()) {
                $subject_details = Subject::find($subject);
                $msg = "Subject <strong>" . $subject_details->name . "</strong> added to the book " . $book_ent->title . "";
                //return response()->json(['status'=>'success','results'=>$book_ent,'message'=>$msg]);
                $request->session()->flash('bookstatus.status', 'success');
                $request->session()->flash('bookstatus.message', $msg);
                return response()->json(['status' => 'success', 'results' => $book_ent, 'message' => 'General Deatails Inserted.']);
            } else {
                return response()->json(['status' => 'invalid']);
            }
        }
    }

    public function add_programmElements(Request $request)
    { // adding programm elements
        if ($request->ajax()) {
            $elems = $request->input('element_list');
            $book_id = (int) $request->input('book_nt');
            DB::beginTransaction();
            try {
                foreach ($elems as $k => $el) {
                    $data = Education_programm_element::updateOrCreate(
                        ['book_id' => $book_id, 'name' => filter_var($el, FILTER_SANITIZE_STRING)],
                        ['name' => filter_var($el, FILTER_SANITIZE_STRING), 'book_id' => $book_id, 'is_main' => 1, 'parent_element' => 0, 'order_no' => $k + 1]
                    );
                }
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['status' => 'invalid', 'message' => $ex->getMessage()]);
            }
            return response()->json(['status' => 'success', 'message' => 'General Deatails Inserted.']);
            //dd($request->input('element_list'));

        }
    }

    public function remov_programmElement(Request $request)
    { // removing programm element
        if ($request->ajax()) {
            $book_id = (int) $request->input('book_ent');
            $element_id = (int) $request->input('elem_ent');
            DB::beginTransaction();
            try {
                $data = Education_programm_element::where(['id' => $element_id, 'book_id' => $book_id])->delete();
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['status' => 'invalid', 'message' => $ex->getMessage()]);
            }
            return response()->json(['status' => 'success', 'message' => 'General Deatails Inserted.']);
        }
    }

    public function list_relatedContributors(Request $request)
    { // listing related contributors
        $data = [];
        $book_id = (int) $request->input('book');

        $data = DB::table('contributor_lists')
            ->join('book_contributors', 'contributor_lists.id', '=', 'book_contributors.contributor_id')
            ->join('contributor_roles', 'contributor_lists.role', '=', 'contributor_roles.id')
            ->select("contributor_lists.*", "book_contributors.book_id", "book_contributors.contributor_id", "contributor_roles.name as role_name")
            ->whereExists(function ($query) use ($request, $book_id) {
                $query->select("books.id")
                    ->from('books')
                    ->whereRaw('books.id = book_contributors.book_id')
                    ->whereRaw('books.id = ?', (array) $book_id);
            })
            ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('firstname', function ($data) {
                    $firstname = $data->firstname;
                    return $firstname;
                })
                ->addColumn('lastname', function ($data) {
                    $lastname = $data->lastname;
                    return $lastname;
                })
                ->addColumn('role', function ($data) {
                    $role = $data->role_name;
                    return $role;
                })
                ->addColumn('action', function ($data) {
                    $btn = '<button class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info rmConbok" data-bid="' . $data->id . '" data-person="' . $data->firstname . " " . $data->lastname . '"><span class="fa fa-trash"></span></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function list_allcontributors(Request $request)
    { // listing all contributors
        if ($request->ajax()) {
            $data = DB::table('contributor_lists')
                ->join('contributor_roles', 'contributor_lists.role', '=', 'contributor_roles.id')
                ->select("contributor_lists.*", "contributor_roles.name as role_name")
                ->get();
            return response()->json(['status' => 'success', 'results' => $data]);
        }
    }

    public function list_programmElements(Request $request)
    { // listing programm elements
        if ($request->ajax()) {
            $book_id = (int) $request->input('book_ent');
            $data = DB::table('education_programm_elements')->where('book_id', $book_id)->orderBy('order_no')->get();
            return response()->json(['status' => 'success', 'results' => $data]);
        }
    }

    public function edit_book_details($id)
    {
        $book_data = Book::find((int) $id);
        $data = [];
        $data['langs'] = Preferrable_lang::get();
        $data['contributor_roles'] = Contributor_role::get();
        return view('User.edit_bibliographic_data', compact('data', 'book_data'));
    }

    public function manage_subjects(Request $request)
    {
        $data = [];
        if ($request->ajax()) {
            $data = Subject::withCount(['books'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subjectname', function ($data) {
                    $subject = $data->name;
                    return $subject;
                })
                ->addColumn('book_nos', function ($data) {
                    $book_nos = $data->books_count;
                    return $book_nos;
                })
                ->addColumn('action', function ($data) {
                    $btn = '<button class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info rmSub" data-sid="' . $data->id . '" data-subject="' . $data->name . '"><span class="fa fa-trash"></span></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('User.subject_lists');
    }

    public function add_newSubject(Request $request)
    { // creating new subjects
        if ($request->ajax()) {
            $subject_name = (string) $request->input('subject');
            $subject_detals = Subject::firstOrCreate(['name' => $subject_name]);
            return response()->json(['status' => 'success', 'results' => $subject_detals]);
        }
    }

    public function lists_subjects(Request $request)
    { // drops subjects
        if ($request->ajax()) {
            $data = DB::table('subjects')->get();
            return response()->json(['status' => 'success', 'results' => $data]);
        }
    }

    public function remove_theSubject(Request $request)
    { // remove a subject
        if ($request->ajax()) {
            $subject_id = (int) $request->input('subject_ent');
            $sub_ent = Subject::find($subject_id);
            if ($sub_ent->delete()) {
                return response()->json(['status' => 'success']);
            }
        }
    }


    public function manage_book_note(Request $request)
    {
        return view('User.mangae_book_note');
    }

    public function manage_exercise(Request $request)
    {
        return view('User.manage_book_exercise');
    }

    public function addbook_exercise(Request $request)
    {
        return view('User.add_book_exercise');
    }

    public function manage_webcontent(Request $request)
    {
        return view('User.manage_book_webcontent');
    }

    public function uploadxml(Request $request)
    {
        if ($request->ajax()) {

            $book_data = Book::find($request['book_entity']);
            $cover_img = $request->file('cover_book');
            $destination_folder = 'public/ebooks/book_' . $request['book_entity'] . '/ebook';
            //need to take cover page
            //Store assets file

            $files = $request->file('book_asset');
            $assetDestination=$destination_folder.'/book_asset';
            if ($request->hasFile('book_asset')) {
                foreach ($files as $file) { 
                    $fileName=preg_replace('/\s+/', '', $file->getClientOriginalName());
                    //dd($fileName);
                    //$file->move($assetDestination, $fileName);
                     Storage::putFileAs($assetDestination,$file,$fileName);
                    // $file->store($destination_folder.'/book_asset');
                }
            }

            $validator = Validator::make($request->all(), [
                'import_file' => 'required|mimes:xml',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors();
                return response()->json(['status' => 'invalid', 'messages' => $error, 'message' => 'Invalid Attempt']);
            } else {
                $xmlString = file_get_contents($request->file('import_file'));
                $xmlObject = simplexml_load_string($xmlString);
                $json = json_encode($xmlObject);
                $data = json_decode($json, true);
                $dataCollection = collect($data);
                //dd($data);

                $introductionclassArray=array('preface_txt','preface_txt_principal','preface_txt_secondaire',
                'avantpropos_title','avantpropos_txt_X','avantpropos_citation_X');
                // 'partieX_color','partie1_color','partie1_image','partie1_title',
                $chapterclassArray=array('chapitre_no','chapitre_colour',
                'chapitre_title','chapitre_image','chapitre_head','chapitre_objectifX','chapitre_situation','chapitre_txtintroX',
                'chapitre_sectionX_title','chapitre_sectionX_intro','chapitre_sectionX_paragraphX_title','chapitre_section1_paragraphX_intro',
                'chapitre_section1_paragraphX_subtitle_X','chapitre_section1_paragraphX_txt_X','chapitre_section1_paragraph1_txt1_rappel',
                'chapitre_section1_paragraph3_sideimg','chapitre_section1_paragraph3_ensavoirplus_X','chapitre_section1_paragraph3_remarque_1',
                'chapitre_section2_paragraph1_fullimg','chapitre_section2_paragraph1_txt1_retenir','chapitre_sectionX_paragraphX_list_X_item_X',
                'chapitre_section2_paragraph2_table_1_title','chapitre_section2_paragraph2_table_1_entete','chapitre_section2_paragraph2_table_1_lineX',
                'chapitreX_fairelepoint_title','chapitre1_fairelepoint_intro','chapitreX_fairelepoint_colorcolor','chapitre1_fairelepoint_list');

                try {
                    $test = '';
                    $parties = $dataCollection['parties'];
                    $numberOfparties = count($parties);
                    $currentparties = 0;
                    $table_of_contant = collect();
                    $partieArray = collect();
                    $chapitresArray = collect();
                    $chapitreArray = collect();
                    $chapitrList = collect();
                    $finalHideClassCss='';
                    $finalShowClassCss='';
                    $hideClassCss='';
                    $ShowClassCss='';
                    foreach ($parties as $key => $partie) {
                        $finalHideClassCss=$hideClassCss;
                        $finalShowClassCss=$ShowClassCss;
                        $hideClassCss='';
                        $ShowClassCss='';
                        $currentparties = $currentparties + 1;
                        $partieArray->put('partie_title', $partie['partie_head'] . '' . $partie['partie_title']);
                        $partieArray->put('partie_colour', $partie['partie_colour']['@attributes']['color']);
                        $isStartNewpartie = true;
                        $numberOfchapitres = count($partie['chapitres']);
                        $currentchapitre = 1;

                        
                        

                        foreach ($partie['chapitres'] as $key => $chapitre) {
                            //Create chapittr list for include table of contant in all pages
                            foreach($chapterclassArray as $chapitreClass){
                                $hideClassCss=$hideClassCss.'.parties'.$currentparties.' '.'chapitre'.$currentchapitre.' .'.$chapitreClass.'{ display: none; }'.' ';
                                if (array_key_exists($chapitreClass,$chapitre)){
                                    $hideClassCss=$hideClassCss.'.parties'.$currentparties.' '.'chapitre'.$currentchapitre.' .'.$chapitreClass.'{ display: block; }'.' '; 
                                }
                            }
                            $chapitrList->put($chapitre['chapitre_no'], 'Chapitre ' . $chapitre['chapitre_no'] . ': ' . $chapitre['chapitre_title']);
                            //Create Table of countants
                            $chapitreArray->put('chapitre_title', $chapitre['chapitre_title']);
                            $chapitreArray->put('chapitre_colour', $chapitre['chapitre_colour']['@attributes']['color']);
                            $chapitreArray->put('chapitre_section1_title', $chapitre['chapitre_section1']['chapitre_section1_title']);
                            $chapitreArray->put('chapitre_section1_paragraph1_title', $chapitre['chapitre_section1']['chapitre_section1_paragraph1_title']);
                            $chapitreArray->put('chapitre_section1_paragraph2_title', $chapitre['chapitre_section1']['chapitre_section1_paragraph2_title']);
                            $chapitreArray->put('chapitre_section1_paragraph3_title', $chapitre['chapitre_section1']['chapitre_section1_paragraph3_title']);
                            $chapitreArray->put('chapitre_section2_title', $chapitre['chapitre_section2']['chapitre_section2_title']);
                            $chapitreArray->put('chapitre_section2_paragraph1_title', $chapitre['chapitre_section2']['chapitre_section2_paragraph1_title']);
                            $chapitreArray->put('chapitre_section2_paragraph2_title', $chapitre['chapitre_section2']['chapitre_section2_paragraph2_title']);

                            $chapitresArray->put('Chapitre' . $chapitre['chapitre_no'], $chapitreArray);
                            $previouschapitre = $currentchapitre - 1;
                            $nextchapitre = $currentchapitre + 1;
                            $previouschapitreflag = true;
                            $nextchapitreFlag = true;
                            if ($previouschapitre == 0 && $currentparties == 1) {
                                $previouschapitreflag = false;
                                $previousFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_tbl_of_cnt';
                            } else {
                                $previousFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre' . $previouschapitre;
                                $previouschapitreflag = true;
                            }

                            if ($nextchapitre > $numberOfchapitres && $currentparties == $numberOfparties) {
                                $nextchapitreFlag = false;
                                $nextFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_bibliography';
                            } else {
                                $nextFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre' . $nextchapitre;
                                $nextchapitreFlag = true;
                            }

                            $currentchapitre= $currentchapitre + 1;

                            $html = view('Template.chapters_with_Partie_template', compact('partie', 'chapitre', 'chapitrList', 'isStartNewpartie', 'previousFile', 'nextFile', 'previouschapitreflag', 'nextchapitreFlag','assetDestination'));

                            //$html = $html->render();
                            $filename = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre' . $chapitre['chapitre_no'] . '.blade.php';
                            $fullpath = $destination_folder . '/' . $filename;
                            Storage::put($fullpath, $html);
                            $isStartNewpartie = false;
                        }
                        $partieArray->put('chapitres', $chapitresArray);
                        $table_of_contant->put('partie' . $currentparties, $partieArray);
                    }
                    $introductionFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_introduction';
                    $tbl_of_cnt_File = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_tbl_of_cnt';
                    $chapitre1 = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre1';
                    $lastchapitre = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre' . $numberOfchapitres;
                    $bibliography = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_bibliography';
                    $iconography = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_iconography';

                    //Create Table of contents template.
                    $html = view('Template.table_of_contents_template', compact('table_of_contant', 'book_data', 'chapitrList', 'introductionFile', 'chapitre1'));
                    $fullpath = $destination_folder . '/' . $tbl_of_cnt_File . '.blade.php';
                    Storage::put($fullpath, $html);

                    //Create introduction part.
                    $html = view('Template.Introduction_template', compact('data', 'book_data', 'chapitrList', 'tbl_of_cnt_File'));
                    $fullpath = $destination_folder . '/' . $introductionFile . '.blade.php';
                    Storage::put($fullpath, $html);

                    //Create bibliography part.
                    $html = view('Template.bibliography_template', compact('data', 'book_data', 'chapitrList', 'lastchapitre', 'iconography'));
                    $fullpath = $destination_folder . '/' . $bibliography . '.blade.php';
                    Storage::put($fullpath, $html);

                    //Create bibliography part.
                    $html = view('Template.iconography_template', compact('data', 'book_data', 'chapitrList', 'bibliography'));
                    $fullpath = $destination_folder . '/' . $iconography . '.blade.php';
                    Storage::put($fullpath, $html);

                    //Create Hide Class Css.
                    $fullpath = $destination_folder . '/' . 'hide_class.css';
                    Storage::put($fullpath, $hideClassCss);
                    
                    //Create Show Class Css.
                    $fullpath = $destination_folder . '/' . 'Show_class.css';
                    Storage::put($fullpath, $ShowClassCss);

                    $messge = 'The &nbsp<strong>' . $book_data->title . '</strong> has been successfully generated..';
                    $request->session()->flash('bookstatus.status', 'success');
                    $request->session()->flash('bookstatus.message', $messge);

                    return response()->json(['status' => 'success', 'message' => 'Book generated successfully.']);
                } catch (\Exception $ex) {
                    return response()->json(['status' => 'invalid', 'message' => $ex->getMessage()]);
                }
            }
        }
    }



    public function wrkcpyofuploadxml(Request $request)
    {
        $book_data = Book::find($request['book_entity']);
        $cover_img = $request->file('cover_book');
        $destination_folder = 'public/ebooks/book_' . $request['book_entity'] . '/ebook';
        //need to take cover page
        $validator = Validator::make($request->all(), [
            'import_file' => 'required|mimes:xml',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors();
            return response()->json(['status' => 'invalid', 'messages' => $error, 'message' => 'Invalid Attempt']);
        } else {
            $xmlString = file_get_contents($request->file('import_file'));
            $xmlObject = simplexml_load_string($xmlString);
            $json = json_encode($xmlObject);
            $data = json_decode($json, true);
            $dataCollection = collect($data);
            //dd($dataCollection);

            try {
                $test = '';
                $parties = $dataCollection['parties'];
                $numberOfparties = count($parties);
                $currentparties = 0;
                $table_of_contant = collect();
                $partiesArray = [];
                $partieArray = [];
                $chapitresArray = [];
                $chapitreArray = [];
                $colorCode = collect();
                $chapitrList = collect();
                foreach ($parties as $key => $partie) {
                    $currentparties = $currentparties + 1;
                    $table_of_contant->put('partie' . $currentparties, $partie['partie_head'] . '' . $partie['partie_title']);
                    $colorCode->put($partie['partie_head'], $partie['partie_colour']['@attributes']['color']);
                    $isStartNewpartie = true;
                    $numberOfchapitres = count($partie['chapitres']);
                    $currentchapitre = 1;

                    foreach ($partie['chapitres'] as $key => $chapitre) {
                        //Create chapittr list for include table of contant in all pages
                        $chapitrList->put($chapitre['chapitre_no'], 'Chapitre ' . $chapitre['chapitre_no'] . ': ' . $chapitre['chapitre_title']);
                        //Create chapittr Colour 
                        $colorCode->put('chapitre' . $chapitre['chapitre_no'], $chapitre['chapitre_colour']['@attributes']['color']);
                        //Create Table of countants
                        $table_of_contant->put('partie' . $currentparties . '[]chapitres[]chapitre' . $chapitre['chapitre_no'] . '[]' . 'chapitre_title', $chapitre['chapitre_title']);
                        $table_of_contant->push($chapitre['chapitre_title']);
                        $table_of_contant->push($chapitre['chapitre_section1']['chapitre_section1_title']);
                        $table_of_contant->push($chapitre['chapitre_section1']['chapitre_section1_paragraph1_title']);
                        $table_of_contant->push($chapitre['chapitre_section1']['chapitre_section1_paragraph2_title']);
                        $table_of_contant->push($chapitre['chapitre_section1']['chapitre_section1_paragraph3_title']);
                        $table_of_contant->push($chapitre['chapitre_section2']['chapitre_section2_title']);
                        $table_of_contant->push($chapitre['chapitre_section2']['chapitre_section2_paragraph1_title']);
                        $table_of_contant->push($chapitre['chapitre_section2']['chapitre_section2_paragraph2_title']);

                        $previouschapitre = $currentchapitre - 1;
                        $nextchapitre = $currentchapitre + 1;
                        if ($previouschapitre == 0 && $currentparties == 1) {
                            $previousFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_introduction' . '.html';
                        } else {
                            $previousFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre' . $previouschapitre . '.html';
                        }

                        if ($nextchapitre == $numberOfchapitres && $currentchapitre == $numberOfparties) {
                            $nextFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_bibliography' . '.html';
                        } else {
                            $nextFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre' . $nextchapitre . '.html';
                        }

                        $html = view('Template.chapters_with_Partie_template', compact('partie', 'chapitre', 'chapitrList', 'isStartNewpartie'));

                        //$html = $html->render();
                        $filename = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_chapitre' . $chapitre['chapitre_no'] . '.blade.php';
                        $fullpath = $destination_folder . '/' . $filename;
                        Storage::put($fullpath, $html);
                        $isStartNewpartie = false;
                    }
                }
                //Create introduction part.
                $html = view('Template.Introduction_template', compact('data', 'book_data', 'chapitrList'));
                //$html = $html->render();
                $introductionFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_introduction' . '.blade.php';
                $fullpath = $destination_folder . '/' . $introductionFile;
                Storage::put($fullpath, $html);

                //Create introduction part.
                $html = view('Template.Introduction_template', compact('data', 'book_data', 'chapitrList'));
                //$html = $html->render();
                $introductionFile = $request['book_entity'] . '_' . preg_replace('/\s+/', '_', $book_data->title) . '_introduction' . '.blade.php';
                $fullpath = $destination_folder . '/' . $introductionFile;
                Storage::put($fullpath, $html);

                dd('success');
            } catch (\Exception $ex) {

                dd($ex);
            }
        }
    }

    public function readBook($page)
    {

        return view('book_17.ebook.' . $page);
    }
}