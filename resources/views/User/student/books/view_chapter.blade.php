<div class="app-main__inner">
    <a data-toggle="collapse" href="#reading_nav" class="btn-open-options btn btn-primary button_expand_mobile" aria-expanded="false"><span class="fa fa-indent fa-w-16"></span></a>
        
        <div class="parent_header_reading">
            <div class="header_reading">
                <div class="row navbar_uncollapsed_desktop">
                    <div class="col-md-9">
                        <h1 class="ml-2 mt-2 title_chapter_nav">Chapter : {{ isset($data['chapter_data']->title) ? $data['chapter_data']->title : ''  }}</h1>
                    </div>
                    <div class="col-md-3">
                        <a data-toggle="collapse" href="#reading_nav" class="btn btn-primary float-right m-2" aria-expanded="true">{{__('menus.book_navigation')}}</a>
                    </div>
                </div>
                
                <div class="collapse row" id="reading_nav" style="">
                    <div class="col-md-12">
                        <hr class="m-0">
                    </div>
                    <div class="col-md-8 pr-0 menu_separator">
                    
                        <div class="card-body pr-0">
                            
                            <h2 class="card-title">{{__('messages.table_of_contents')}}</h2>
                            <div class="scroll-area-sm">
                                <div class="scrollbar-container ps--active-y ps">
                                    <ul class="book_index p-0">
                                        @foreach ($data['chapters'] as $k=>$chap)
                                         @if($chap->id == $data['chapter_id'])
                                         <li class="active_chapter pl-3">Chapter {{$k+1}}: {{ $chap->title}}</li>
                                         @else
                                         <li><a href="{{ route('student.book-reading',['id'=>$data['book']->id,'chapter'=>$chap->id]) }}">Chapter {{$k+1}}: {{ $chap->title}}</a></li>
                                         @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="pr-3">
                            <hr class="d-md-none">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative">
                            <div class="row">
                                <div class="col-6">
                                    <p class="d-inline-block  pl-2 pt-3">{{__('messages.font_size')}} </p>
                                </div>
                                <div class="col-5 text-right">
                                    <a href="javascript:void(0);" class="bigger_font pl-2 pr-2">A<sup>+</sup></a>
                                    <a href="javascript:void(0);" class="lower_font pl-2 pr-2">A<sup>-</sup></a>
                                    <input type="hidden" name="selected_font_size" value="{{ $pref_setts->selected_font_size }}">

                                </div>
                            </div>
                        </div>

                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label pl-2" for="note_editeur">{{__('messages.view_editors_note')}}</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_editeur" class="note_sett" name="view_editor_note" value="{{ ($pref_setts->view_editor_note == 1)?1:2 }}" @if($pref_setts->view_editor_note == 1) checked @endif checked data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_teacher">{{__('messages.view_teachers_note')}}</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_teacher" class="note_sett" name="view_teacher_note" value="{{ ($pref_setts->view_teacher_note == 1)?1:2 }}" @if($pref_setts->view_teacher_note == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_perso">{{__('messages.view_my_note')}}</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_perso" class="note_sett" name="view_student_note" value="{{ ($pref_setts->view_student_note == 1)?1:2 }}" @if($pref_setts->view_student_note == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                            </div>									
                        </div>
                        <div class="position-relative mb-3 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_add">{{__('messages.enable_note_editing')}}</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_add" class="note_sett" name="enable_note_edit" value="{{ ($pref_setts->enable_note_edit == 1)?1:2 }}" @if($pref_setts->enable_note_edit == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12" id="reading_percentage">
                    <div class="bar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;" role="progressbar"></div>
                </div>
                
            </div>
        </div>
        
       
        <div class="tab-content size_temoin">
            <div class="tab-pane tabs-animation fade show active">
                <div class="main-card mb-3 card">
                
                
                    <div class="card-body">
                         
                        <form class="" action="{{ route('student.title.add_upnotes',['id'=> $data['book']->id]) }}" method="POST" id="addnewNote" enctype="multipart/form-data"  data-parsley-validate>
                            @csrf   
                        <input type="hidden" name="book_entity" value="{{ $data['book']->id }}" readonly/>
                        <input type="hidden" name="chapter_entity" value="{{ $data['chapter_data']->id }}" readonly/>
           
                        <h2 class="title_chapter">Chapter :{{ isset($data['chapter_data']->title) ? $data['chapter_data']->title : ''  }}</h2>
                        
                        <h4> @php $title=null; $subtitle=null; $note=null; $fileimg=null; $filecap=null; @endphp</h4>
                        @php $i = 1; @endphp
                        @foreach ($data['chapter_blocks'] as $note_key=>$note_blocks)
                        @foreach ($note_blocks as $block)
                        @if($block->block_type == 1) <!--- 1 for title block--->
                        <h3>{{ ucfirst($block->content) }}</h3>@php $title=$block->content  @endphp
                      
                        @endif
                        @if($block->block_type == 2) <!--- 2 for subtitle block--->
                        <h4>{{ ucfirst($block->content) }}</h4> @php $substitle=$block->content  @endphp
                        @endif
                        @if($block->block_type == 3) <!--- 3 for note or description block--->
                        {!! $block->content !!}
                       
                        <a data-toggle="collapse" href="#collapse_note{{$i}}" class="btn-open-options btn btn-primary float-right add_note" aria-expanded="false"><span class="fa fa-plus fa-w-16"></span> Add a note</a>
                         
                        <div class="collapse row" id="collapse_note{{$i}}">
                                    <div class="col-12">
                                        <label for="add_note1" class="">Add a note</label>
                                        <textarea name="text" id="add_note1" class="form-control"></textarea>
                                    </div>
                                    <div class="col-4">
                                    </div>
                                    <div class="col-2">
                                        <label for="exampleFile1" class="float-right mt-2 label_file">Add a file</label>
                                    </div>
                                    <div class="col-3">
                                        <input name="file" id="exampleFile1" type="file" class="form-control-file mt-2">
                                    </div>	
                                    <div class="col-3">
                                        <button class="mt-2 btn btn-primary float-right" type="submit">Save</button>
                                    </div>
                            </div>
                            @php $i++; @endphp
                        
                        @php $note=$block->content  @endphp
                        @endif
                        @if($block->block_type == 4) <!--- 4 for image file block--->
                        <figure class="text-center">
                            <img src="{{ $block->content }}" title alt> @php  $fileimg=$block->content; $filecap=$block->metadatas;  @endphp
                            <figcaption>{{ $block->metadatas }}</figcaption>
                        </figure>
                        @endif
                      
                        @endforeach
                        @endforeach
                    
                       <div class="row">
                            <div class="col-md-12 d-flex">
                                <a href="{{route('student.title.chapter_exercise_show',['chapter' => $data['chapter_data']->id,'excer_type'=>'examination'])}}" class="mt-2 ml-1 btn btn-primary mx-auto">Complete the Chapter Test</a>
                            </div>
                        </div>
                        <div class="row d-flex">
                            <div class="col-md-6 mx-auto">
                                <hr>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-6 d-flex">
                                <a href="javascript:void(0);" class="prevbtn mt-2 ml-1 btn btn-primary mx-auto">&lt;&lt; Previous Chapter</a>
                            </div>
                            <div class="col-6 d-flex">
                                <a href="javascript:void(0);" class="nextbtn mt-2 ml-1 btn btn-primary mx-auto">Next Chapter &gt;&gt;</a>
                            </div>
                        </div>
                    </form>
                    </div>
            
            
            
                </div>
            </div>
        </div>
    </div>


@section('page-script')
<script>

    $(function(){
        //console.log("gr");
        //disEnblenote();
        $(document).on('change','.note_sett',function(){
                 if ($(this).is(':checked')) {
                     console.log("Gre");
                    $(this).val(1);
                   }
                else {
                 $(this).val(2);
                 }
                 changePrefsett();
                 disEnblenote();
               });
               
               var chp_objs={!! $data['chapters']->toJson() !!};
               //console.log(chp_objs);
               var curr_chap={!! $data['chapter_data']->id !!};
               
               var currURL="{{ route('student.title.reading_book',['title'=>$data['book']->id]) }}"; 
                //console.log(currURL);

               $.each(chp_objs, function(i, $val){ if($val['id'] === curr_chap){ $prevpag=chp_objs[i-1]; $nextpag=chp_objs[i+1]; 
               if($nextpag !== undefined){ $('.nextbtn').attr('href',currURL+"?chapter="+$nextpag['id']); $('.nextbtn').removeClass('disabled'); }
               if($prevpag !== undefined){ $('.prevbtn').attr('href',currURL+"?chapter="+$prevpag['id']); $('.prevbtn').removeClass('disabled'); }
               }});
    });
  

</script>
@stop