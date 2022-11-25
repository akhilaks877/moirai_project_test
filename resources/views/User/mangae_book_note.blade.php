@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
<div> 
 @if((!$data['chapters']->isEmpty()) && $data['type'] == '')
 @Include('User.books.edit_chapter')
 @elseif(($data['chapters']->isEmpty()) && $data['type'] == '')
 @Include('User.books.add_chapter')
 @elseif((!$data['chapters']->isEmpty()) && $data['type'] == 'edit')
 @Include('User.books.edit_chapter')
 @elseif((!$data['chapters']->isEmpty()) || $data['type'] == 'add')
 @Include('User.books.add_chapter')
 @endif
</div>
</div>
@stop
@section('page-script')
<script>
    $(function(){
        addTinyMCE();
        disEnblenote();
        $('a.addup_note').on('click',function(e){
            e.preventDefault(); $(this).next('div.collapse').toggleClass("show");
           var newText = $(this).text().trim() == '{{__('menus.edit_note')}}' ? '{{__('menus.collapse')}}' : '{{__('menus.edit_note')}}'; $(this).text(newText);
        });

        $(document).on('click','.add_another_note',function(){
            count =$('.note-card').length;
            var noteHtml='<div class="main-card note-card mb-3 card"><div class="card-body"><h4 class="covrd">Note '+(count+1)+'</h4><input type="hidden" name="note_element[]" value="1" readonly/><div class="position-relative form-group"><h5>{{__('messages.title')}}</h5><input type="text" name="title[]" class="form-control"></div><div class="position-relative form-group"><h5>{{__('messages.subtitle')}}</h5><input type="text" name="subtitle[]" class="form-control"></div><div class="position-relative form-group"><h5>{{__('messages.add_a_note')}}</h5><textarea class="textarea_block" name="note[]"></textarea>';
                noteHtml+='</div><div class="position-relative form-group"><h5>{{__('messages.add_a_file')}}</h5><input type="hidden" name="contain_file[]" value="0" readonly><span class="spns pe-7s-close-circle"></span><input name="notefile[]" class="exampleFile" accept="image/*" type="file" class="form-control-file mt-2"><div class="imgPreview"></div><input type="text" class="imgCaption" name="filecaption[]" style="display:none;" placeholder="Image Caption"/></div></div></div>';
          $('#notes_container').append(noteHtml);addTinyMCE();
        });
        $(document).on('change','.exampleFile',function (){ filePreview(this) });

        $('form#addnewNote').on('submit',function(e){
            e.preventDefault(); tinyMCE.triggerSave();  var url =$(this).data('url');
            $('.dimension.alert-danger ul').html(''); $('.dimension.alert-danger').hide();
            $('form#addnewNote button[type="submit"]').attr('disabled',true);
            $.ajax({
            url:url,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          type: "POST",
          dataType:'JSON',
          data: new FormData($(this)[0]),
          processData: false,
          contentType: false,
          cache: false,
          success: function(response){
            if(response.status == 'success'){
            $('form#addnewNote button[type="submit"]').attr('disabled',false);
            window.location.reload(true);
            }
            if(response.status == 'invalid'){
            $('form#addnewNote button[type="submit"]').attr('disabled',false);
            var msg = '<ul class="display_error">';
            $.each(response.messages, function( key,value) {
              msg += '<li>' + value + '</li>';
            });
            msg += '</ul>';
            $('.dimension.alert-danger').append(msg);
            $('.dimension.alert-danger strong').html(response.message);
            $('.dimension.alert-danger').show();
            $('html, body').animate({scrollTop: $(".app-main__inner").offset().top}, 'fast');
            }
          },
         fail: function(){}
           });
        });

        $(document).on('click','.spns',function (){
            $(this).next('input[type="file"]').val('');
            $(this).prev('input[type="hidden"][name="contain_file[]"]').val(0); // set to zero means file input doesn't contains file
            $(this).nextAll("img").remove();
            $(this).parent().find('.imgCaption').val('').hide(); $(this).removeClass('active');
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
        $(input).nextAll("img").remove(); $(input).next('.imgPreview').after('<img src="'+e.target.result+'"  style="width:100%"/>');
        $(input).prevAll('input[type="hidden"][name="contain_file[]"]').val(1); // set to one means file input contains file
        $(input).prev('span').addClass('active'); $(imgCap).val('').show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function changePrefsett(){
    uid = "{{ $pref_setts->id }}";
    url = "{{ route('admin.accessibility.update',":id") }}";
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
    if($('#note_add').val() !== '1'){ $('a.addup_note, a.add_another_note, button[type="submit"]').addClass('disabled'); $('button[type="submit"]').prop('disabled', true);}
    else{ $('a.addup_note, a.add_another_note, button[type="submit"]').removeClass('disabled'); $('button[type="submit"]').prop('disabled', false);}
}
</script>
@stop
