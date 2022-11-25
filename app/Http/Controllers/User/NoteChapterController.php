<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Book;
use App\Chapter;
use App\Chapter_block;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoteChapterController extends Controller
{

    public function manage_notes($id,Request $request)
    {
        $book=Book::find((int)$id);
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
        $data['book']=$book;  $data['type']='';
        if($request->has('type') && (string)$request->get('type') == 'add'){
            $data['type']='add';
        }else if($request->has('type') && (string)$request->get('type') == 'edit' && $request->has('chapter')){
            $data['type']='edit';
        }
        // dd($data['type']);
        return view('User.mangae_book_note',compact('data','pref_setts'));
    }

    public function addorUpdateNotes($id, Request $request){
        if($request->ajax()){
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
         ],[
            'chapter_name.required' => 'Chapter name is required',
            'cover_book.mimes' => 'Should be in Image format',
         ]);
         if ($validator->fails()) {
            $error = $validator->errors();
            return response()->json(['status'=>'invalid','messages'=>$error,'message'=>'Invalid Attempt']);
          }
          else{
            DB::beginTransaction();
            try{
              $data=$request->all(); $arr=[];
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

        }
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
}
