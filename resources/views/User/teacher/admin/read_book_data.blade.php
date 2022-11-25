@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">

    <a data-toggle="collapse" href="#reading_nav" class="btn-open-options btn btn-primary button_expand_mobile" aria-expanded="false"><span class="fa fa-indent fa-w-16"></span></a>

    <div class="parent_header_reading">
        <div class="header_reading">
            <div class="row navbar_uncollapsed_desktop">
                <div class="col-md-9">

                    <h1 class="ml-2 mt-2 title_chapter_nav">Chapter : {{ isset($data['chapter_data']->title) ? $data['chapter_data']->title : ''  }}</h1>
                </div>
                <div class="col-md-3">
                    <a data-toggle="collapse" href="#reading_nav" class="btn btn-primary float-right m-2" aria-expanded="true">Book Navigation</a>
                </div>
            </div>

            <div class="collapse row" id="reading_nav" style="">
                <div class="col-md-12">
                    <hr class="m-0">
                </div>
                <div class="col-md-8 pr-0 menu_separator">

                    <div class="card-body pr-0">

                        <h2 class="card-title">Table of Contents</h2>
                        <div class="scroll-area-sm">
                            <div class="scrollbar-container ps--active-y ps">
                                <ul class="book_index p-0">
                                    @foreach ($data['chapters'] as $k=>$chap)
                                    @if($chap->id == $data['chapter_id'])
                                    <li class="active_chapter pl-3">Chapter {{$k+1}}: {{ $chap->title}}</li>
                                    @else
                                    <li><a href="{{ route('teacher.title.reading_book',['title'=>$data['book']->id,'chapter'=>$chap->id]) }}">Chapter {{$k+1}}: {{ $chap->title}}</a></li>
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
                                <p class="d-inline-block  pl-2 pt-3">Font size: </p>
                            </div>
                            <div class="col-5 text-right">
                                <a href="javascript:void(0);" class="manip bigger_font">A<sup>+</sup></a>
                                <a href="javascript:void(0);" class="manip lower_font">A<sup>-</sup></a>
                                <input type="hidden" name="selected_font_size" value="{{ $pref_setts->selected_font_size }}">
                            </div>
                        </div>
                    </div>

                    <div class="position-relative mb-1 row">
                        <div class="col-9 col-sm-8 col-xl-9">
                            <label class="form-check-label pl-2" for="note_editeur">View Editor's Notes</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_editeur" class="note_sett" name="view_editor_note" value="{{ ($pref_setts->view_editor_note == 1)?1:2 }}" @if($pref_setts->view_editor_note == 1) checked @endif checked data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
                        </div>
                    </div>
                    <div class="position-relative mb-1 row">
                        <div class="col-9 col-sm-8 col-xl-9">
                            <label class="form-check-label  pl-2" for="note_teacher">View Teacher's Notes</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_teacher" class="note_sett" name="view_teacher_note" value="{{ ($pref_setts->view_teacher_note == 1)?1:2 }}" @if($pref_setts->view_teacher_note == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
                        </div>
                    </div>
                    <div class="position-relative mb-1 row">
                        <div class="col-9 col-sm-8 col-xl-9">
                            <label class="form-check-label  pl-2" for="note_perso">View My Notes</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_perso" class="note_sett" name="view_student_note" value="{{ ($pref_setts->view_student_note == 1)?1:2 }}" @if($pref_setts->view_student_note == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
                        </div>
                    </div>
                    <div class="position-relative mb-3 row">
                        <div class="col-9 col-sm-8 col-xl-9">
                            <label class="form-check-label  pl-2" for="note_add">Enable Note Editing</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_add" class="note_sett" name="enable_note_edit" value="{{ ($pref_setts->enable_note_edit == 1)?1:2 }}" @if($pref_setts->enable_note_edit == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
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
                    <input type="hidden" name="book_entity" value="{{ $data['book']->id }}" readonly/>
                    <input type="hidden" name="chapter_entity" value="{{ $data['chapter_data']->id }}" readonly/>
                    <h2 class="title_chapter">Chapter : {{ isset($data['chapter_data']->title) ? $data['chapter_data']->title : ''  }}</h2>
                    @php $title=null; $subtitle=null; $note=null; $fileimg=null; $filecap=null; @endphp
                    @foreach ($data['chapter_blocks'] as $note_key=>$note_blocks)
                      @foreach ($note_blocks as $block)
                      @if($block->block_type == 1) <!--- 1 for title block--->
                      <h3>{{ ucfirst($block->content) }}</h3>@php $title=$block->content  @endphp
                      @endif
                      @if($block->block_type == 2) <!--- 2 for subtitle block--->
                      <h4>{{ ucfirst($block->content) }}</h4> @php $subtitle=$block->content  @endphp
                      @endif
                      @if($block->block_type == 3) <!--- 3 for note or description block--->
                      {!! $block->content !!} @php $note=$block->content  @endphp
                      @endif
                      @if($block->block_type == 4) <!--- 4 for image file block--->
                      <figure class="text-center">
                          <img src="{{ $block->content }}" title alt> @php  $fileimg=$block->content; $filecap=$block->metadatas;  @endphp
                          <figcaption>{{ $block->metadatas }}</figcaption>
                      </figure>
                      @endif
                      @endforeach

                      <!---- teacher notes ----->
                      @isset($data['teacher_notes'][$note_key])
                      @php $note=null; $fileimg=null; $filecap=null; $teacher_text_block=null; $teacher_img_block=null; @endphp
                      @foreach ($data['teacher_notes'][$note_key] as $teach_key=>$teacher_note)
                       <div class="note_enseignant p-2 card mb-3 note col-12" style="{{ ($pref_setts->view_teacher_note != 1) ? 'display:none;':'' }}">
                        <p><strong>Your note:</strong>This note was typed by the teacher to add comments to this paragraph. It is only visible to that teacher and their students.<br /></p>
                         @foreach ($teacher_note as $teach_block)
                            @if($teach_block->block_type == 3) <!--- 3 for note or description block--->
                            {!! $teach_block->content !!} @php $note=$teach_block->content; $teacher_text_block=$teach_block->id;  @endphp
                            @endif
                            @if($teach_block->block_type == 4) <!--- 4 for image file block--->
                            <figure class="text-center"><img src="{{ $teach_block->content }}" title alt>@php  $fileimg=$teach_block->content; $filecap=$teach_block->metadatas; $teacher_img_block=$teach_block->id;  @endphp
                            <figcaption>{{ $teach_block->metadatas }}</figcaption></figure>
                            @endif
                         @endforeach
                    </div>
                     @php $note=null; $fileimg=null; $filecap=null; $teacher_text_block=null; $teacher_img_block=null; @endphp
                     @endforeach
                     <button type="button" class="btn btn-primary float-right show_note_enseignant" data-toggle="tooltip" data-placement="top" style="{{ ($pref_setts->view_teacher_note == 1) ? 'display:none;':'' }}" title="" data-original-title="Enable notes in the Book Navigation menu to see the teacher's note">
                        <span class="fa fa-exclamation "></span>
                    </button>
                     @endisset
                     <!---- teacher notes ----->

                              @php $title=null; $subtitle=null; $note=null; $fileimg=null; $filecap=null; @endphp
                   @endforeach

                  <form action="{{route('teacher.title.manage_bookexercise',['id' => $data['chapter_data']->book_id])}}" method="GET">
                    <div class="row">
                        <div class="col-md-12 d-flex">
                          
                            <button type="submit" class="mt-2 ml-1 btn btn-primary mx-auto">Complete the Chapter Test</button>
                            <input type="hidden" name="chapter_id" value="{{$data['chapter_data']->id}}">
                        </div>
                    </div>
                    </form>

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
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){







    disEnblenote();
    $(document).on('change','.note_sett',function(){
             if ($(this).is(':checked')) {
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
               
               var currURL="{{ route('teacher.title.reading_book',['title'=>$data['book']->id]) }}"; 
                //console.log(currURL);

               $.each(chp_objs, function(i, $val){ if($val['id'] === curr_chap){ $prevpag=chp_objs[i-1]; $nextpag=chp_objs[i+1]; 
               if($nextpag !== undefined){ $('.nextbtn').attr('href',currURL+"?chapter="+$nextpag['id']); $('.nextbtn').removeClass('disabled'); }
               if($prevpag !== undefined){ $('.prevbtn').attr('href',currURL+"?chapter="+$prevpag['id']); $('.prevbtn').removeClass('disabled'); }
               }});
});

function changePrefsett(){
    uid = "{{ $pref_setts->id }}";
    url = "{{ route('teacher.accessibility.update',":id") }}";
    url = url.replace(':id', uid);
    var selected_font_size=$('input[name="selected_font_size"]').val();
    var view_editor_note=$('input[name="view_editor_note"]').val();
    var view_teacher_note=$('input[name="view_teacher_note"]').val();
    var view_student_note=$('input[name="view_student_note"]').val();
    var enable_note_edit=$('input[name="enable_note_edit"]').val();
    var note_userpreff=1;
    $.ajax({
            url:url,
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
     type: "PATCH",
     data: {"selected_font_size":selected_font_size, "view_editor_note":view_editor_note, "view_teacher_note":view_teacher_note, "view_student_note":view_student_note, "enable_note_edit":enable_note_edit, "note_userpreff":note_userpreff},
     dataType: 'json',
     success: function(response){
        if(response.status == 'success'){

         }
        if(response.status == 'invalid'){
         }
     },
        });

}

function disEnblenote(){
    // enable note editing
    if($('#note_add').val() !== '1'){ $('a.addup_note, a.edit_notes, form.new_teacher_note button[type="submit"]').addClass('disabled'); $('form.new_teacher_note button[type="submit"]').prop('disabled', true);}
    else{ $('a.addup_note, a.edit_notes, form.new_teacher_note button[type="submit"]').removeClass('disabled'); $('form.new_teacher_note button[type="submit"]').prop('disabled', false);}
    // view teacher's note
    if($('#note_teacher').val() !== '1'){ $('div.note_enseignant').hide(); $('button.show_note_enseignant').show();}
    else{ $('div.note_enseignant').show(); $('button.show_note_enseignant').hide();}
}
</script>
@stop
