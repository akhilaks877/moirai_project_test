@extends('layouts.Student.app')
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
                                    <li><a href="{{ route('student.title.manage_notes',['title'=>$data['book']->id,'chapter'=>$chap->id]) }}">Chapter {{$k+1}}: {{ $chap->title}}</a></li>
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
                        <p><strong>Teacher's note : </strong>This note was typed by the teacher <strong>{!!$teacher_note[0]->last_name.' '.$teacher_note[0]->first_name!!}</strong><br /></p>
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
                     @endisset
                     <!---- teacher notes ----->

                     <!---- student notes ----->
                    
                     @isset($data['student_notes'][$note_key])
                     @php $note=null; $fileimg=null; $filecap=null; $student_text_block=null; $student_img_block=null; @endphp
                     @foreach ($data['student_notes'][$note_key] as $stud_key=>$student_note)
                      <div class="note_stdeignant p-2 card mb-3 note col-12" style="{{ ($pref_setts->view_student_note != 1) ? 'display:none;':'' }}">
                       <p><strong>Your note:</strong>This note was typed by the student to add comments to this paragraph.<br /></p>
                        @foreach ($student_note as $stud_block)
                           @if($stud_block->block_type == 3) <!--- 3 for note or description block--->
                           {!! $stud_block->content !!} @php $note=$stud_block->content; $student_text_block=$stud_block->id;  @endphp
                           @endif
                           @if($stud_block->block_type == 4) <!--- 4 for image file block--->
                           <figure class="text-center"><img src="{{ $stud_block->content }}" title alt>@php  $fileimg=$stud_block->content; $filecap=$stud_block->metadatas; $student_img_block=$stud_block->id;  @endphp
                           <figcaption>{{ $stud_block->metadatas }}</figcaption></figure>
                           @endif
                        @endforeach
                        <a href="javascript:void(0);" class="btn btn-primary float-right edit_notes col-2 offset-10"><span class="fa fa-pen"></span>Edit this note</a>
                        <div class="collapse row">
                           <div class="col-12">
                           <form class="new_student_note" data-url="{{ route('student.title.addup_notes',['title'=> $data['book']->id]) }}">
                             <div class="main-card note-card mb-3 card"><div class="card-body"><h4 class="covrd">Students Note {{$stud_key}}</h4>
                             <input type="hidden" name="up_technote" value="1" readonly/>
                             <input type="hidden" name="note_id" value="{{ $note_key }}" readonly/>
                         <div class="position-relative form-group">
                           <h5>{{__('messages.add_a_note')}}</h5>
                            @isset($student_text_block) <input type="hidden" name="student_text_block" value="{{ $student_text_block }}" readonly/> @endisset
                           <textarea class="form-control" name="note[]">{{ $note }}</textarea>
                           </div>
                         </div></div>
                         <div class="col-12">
                           <h5>{{__('messages.add_a_file')}}</h5>
                           <input type="hidden" name="contain_file[]" value="{{ ($fileimg) ? '1':'0' }}" readonly> <!--- check input file contains file or not --->
                           <span class="{{ ($fileimg) ? 'spns pe-7s-close-circle active':'spns pe-7s-close-circle' }}"></span>
                           @isset($student_img_block) <input type="hidden" name="student_img_block" value="{{ $student_img_block }}" readonly/> @endisset
                           <input name="notefile[]" class="exampleFile" accept="image/*" type="file" class="form-control-file mt-2">
                           <div class="imgPreview"></div>
                           @isset($fileimg) <img src="{{ url($fileimg) }}" class="notes_container_img" style="width:100%"/>@endisset
                           <input type="text" class="imgCaption" name="filecaption[]" style="{{ ($fileimg) ? '':'display:none' }}" value="{{ $filecap }}" placeholder="Image Caption"/>
                          </div>
                           <div class="col-12">
                           <button type="submit" class="mt-2 btn btn-primary float-right">Save</button>
                           </div>
                          </form>
                          </div>
                           </div>
                   </div>
                    @php $note=null; $fileimg=null; $filecap=null; $student_text_block=null; $student_img_block=null; @endphp
                    @endforeach
                    @endisset
                    <!---- student notes ----->

                      <a href="javascript:void(0);" class="btn-open-options btn btn-primary float-right add_note addup_note" aria-expanded="false">{{__('messages.add_a_note')}}</a>
                      <div class="collapse row">
                        <div class="col-12">
                        <form class="new_student_note" data-url="{{ route('student.title.addup_notes',['title'=> $data['book']->id]) }}">
                         
                          <label for="add_note1" class="">Add a note</label>
                          <div class="main-card note-card mb-3 card"><div class="card-body"><h4 class="covrd">Students Note {{$note_key}}</h4>
                          <input type="hidden" name="note_id" value="{{ $note_key }}" readonly/>
                      <div class="position-relative form-group">
                        <h5>{{__('messages.add_a_note')}}</h5>
                        <textarea class="form-control" name="note[]"></textarea>
                        </div>
                      </div></div>
                      <div class="col-12">
                        <h5>{{__('messages.add_a_file')}}</h5>
                        <input type="hidden" name="contain_file[]" value="" readonly> <!--- check input file contains file or not --->
                        <input name="notefile[]" class="exampleFile" accept="image/*" type="file" class="form-control-file mt-2">
                        <div class="imgPreview"></div>
                        <input type="text" class="imgCaption" name="filecaption[]" style="display:none" value="" placeholder="Image Caption"/>
                       </div>
                        <div class="col-12">
                        <button type="submit" class="mt-2 btn btn-primary float-right">Save</button>
                        </div>
                       </form>
                       </div>
                        </div>
                              @php $title=null; $subtitle=null; $note=null; $fileimg=null; $filecap=null; @endphp
                   @endforeach

                    <div class="row">
                        <div class="col-md-12 d-flex">
                            <a href="{{ ($data['chapter_data']->id) ? route('student.title.chapter_exercise_show',['chapter'=>$data['chapter_data']->id,'excer_type'=>'examination']) : 'javascript:void(0);' }}" class="mt-2 ml-1 btn btn-primary mx-auto">Complete the Chapter Test</a>
                        </div>
                    </div>
                    <div class="row d-flex">
                        <div class="col-md-6 mx-auto">
                            <hr>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-6 d-flex">
                            <a href="javascript:void(0);" class="prevbtn mt-2 ml-1 btn btn-primary mx-auto disabled">&lt;&lt; Previous Chapter</a>
                        </div>
                        <div class="col-6 d-flex">
                            <a href="javascript:void(0);" class="nextbtn mt-2 ml-1 btn btn-primary mx-auto disabled">Next Chapter &gt;&gt;</a>
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
   // addTinyMCE();
    disEnblenote();
    $('a.addup_note,a.edit_notes').on('click',function(e){
            e.preventDefault(); $(this).next('div.collapse').toggleClass("show");
        //   var newText = $(this).text().trim() == '{{__('messages.add_a_note')}}' ? '{{__('menus.collapse')}}' : '{{__('messages.add_a_note')}}'; $(this).text(newText);
        });

        $(document).on('change','.exampleFile',function (){ filePreview(this) });

        $(document).on('click','.spns',function (){
            $(this).next('input[type="file"]').val('');
            $(this).prev('input[type="hidden"][name="contain_file[]"]').val(0); // set to zero means file input doesn't contains file
            $(this).nextAll("img").remove();
            $(this).parent().find('.imgCaption').val('').hide(); $(this).removeClass('active');
            });

        $('form.new_student_note').on('submit',function(e){
            e.preventDefault(); var url =$(this).data('url');
            var note_element=$('input[name="note_id"]',this).val();
            var book_entity=$('input[name="book_entity"]').val();
            var chapter_entity=$('input[name="chapter_entity"]').val();
            var form_data=new FormData($(this)[0]);
            note_add_data={"note_element":note_element,"book_entity":book_entity,"chapter_entity":chapter_entity};
            for(var k of Object.keys(note_add_data)){
               form_data.append(k,note_add_data[k]);
            };
           // tinyMCE.triggerSave();

           $.ajax({
            url:url,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType:'JSON',
            data: form_data,
            processData: false,
            contentType: false,
           cache: false,
           success: function(response){
            if(response.status == 'success'){
                window.location.reload(true);
            }
            if(response.status == 'invalid'){

            }
          },
         fail: function(){}
           });
            });

            $(document).on('click','a.manip',function(){
             var selecteDfntsize=$('input[name="selected_font_size"]');
             var defaultfntsize="{{ $pref_setts->default_font_size }}";
             if($(this).hasClass('bigger_font') && Number(selecteDfntsize.val()) < 25 &&  Number(selecteDfntsize.val()) >= defaultfntsize){
              selecteDfntsize.val(Number(selecteDfntsize.val())+1);
              }
             if($(this).hasClass('lower_font') && Number(selecteDfntsize.val()) <= 25 && Number(selecteDfntsize.val()) > defaultfntsize){
              selecteDfntsize.val(Number(selecteDfntsize.val())-1);
              }
              changePrefsett();
           });

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
           var curr_chap={!! $data['chapter_data']->id !!};
           $currURL="{{ route('student.title.manage_notes',['title'=>$data['book']->id]) }}"; 
           $.each(chp_objs, function(i, $val){ if($val['id'] === curr_chap){ $prevpag=chp_objs[i-1]; $nextpag=chp_objs[i+1]; 
           if($nextpag !== undefined){ $('.nextbtn').attr('href',$currURL+"?chapter="+$nextpag['id']); $('.nextbtn').removeClass('disabled'); }
           if($prevpag !== undefined){ $('.prevbtn').attr('href',$currURL+"?chapter="+$prevpag['id']); $('.prevbtn').removeClass('disabled'); }
           }});
});
function addTinyMCE(){   // Initialize
  tinymce.init({
    selector: '.textarea_block',
    themes: 'modern',
    height: 300
  });
}

function filePreview(input){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        var imgCap=$(input).parent().find('.imgCaption');  $(imgCap).hide();
        $(input).nextAll("img").remove(); $(input).next('.imgPreview').after('<img src="'+e.target.result+'" class="notes_container_img" style="width:100%"/>');
        $(input).prevAll('input[type="hidden"][name="contain_file[]"]').val(1); // set to one means file input contains file
        $(input).prev('span').addClass('active'); $(imgCap).val('').show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function changePrefsett(){
    uid = "{{ $pref_setts->id }}";
    url = "{{ route('student.accessibility.update',":id") }}";
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
    if($('#note_add').val() !== '1'){ $('a.addup_note, a.edit_notes, form.new_student_note button[type="submit"]').addClass('disabled'); $('form.new_student_note button[type="submit"]').prop('disabled', true);}
    else{ $('a.addup_note, a.edit_notes, form.new_student_note button[type="submit"]').removeClass('disabled'); $('form.new_student_note button[type="submit"]').prop('disabled', false);}
    // view teacher's note
    if($('#note_teacher').val() !== '1'){ $('div.note_enseignant').hide(); }
    else{ $('div.note_enseignant').show(); }
    // view student's note
    if($('#note_perso').val() !== '1'){ $('div.note_stdeignant').hide(); }
    else{ $('div.note_stdeignant').show(); }
}
</script>
@stop
