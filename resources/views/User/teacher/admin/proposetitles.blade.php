@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="pe-7s-notebook icon-gradient bg-green">
                    </span>
                </div>
                <div>

                    <h4 class="mb-0 page-title">Suggest a book to {!!  $data['class_info']->class_name !!}</h4>
                    <span class="subtitle"> - This page shows all the existing books that can be suggested to {!!  $data['class_info']->class_name !!}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">Home</a> > <a href="{{ route('teacher.my-classes.index') }}">List of Classes</a> > Suggest a book
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card" id="book_list">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h5 class="card-title">List of all the books you can study with Class</h5>
                </div>

                <div class="col-4">
                    <div class="float-right">
                        <label for="tri_matiere" class="">Filter by Subject</label>
                        <select name="subject" id="tri_matiere" class="form-control">
                            <option value="">All Subjects</option>
                            @foreach($data['subjects'] as $k=>$subj)
                            <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div id="result_container">
                @Include('User.teacher.admin.book_proposes')
            </div>

        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
    $('select#tri_matiere').on('change',function(){
    var subject_val=$(this).val();  var url=window.location.href;
    var page=url.split('page=')[1];
    fetch_lists(page,subject_val);
});

$(document).on('click', '.pagination a', function(event){
event.preventDefault(); var subject_val=$('select#tri_matiere').val();
var page = $(this).attr('href').split('page=')[1];
fetch_lists(page,subject_val);
});

$(document).on('click', 'a.make_suggest', function(event){
event.preventDefault(); var bid=$(this).parent().data('pbook'); var cid="{!! $data['class_info']->id !!}";
aj_url= "{{ route('teacher.my-classes.addpropose_book',":my_class") }}";
aj_url= aj_url.replace(':my_class', cid);
$.ajax({
  url:aj_url,
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
 type: "POST",
 dataType: 'json',
 data: { "class_entity":cid,"book_entity":bid },
 success: function(response){
    if(response.status == 'success'){
     window.location.reload(true);
    }
 },
fail: function() {
 }
 });
});

});


function fetch_lists(page,subject_val)
{
cid = "{!! $data['class_info']->id !!}";
url = "{{ route('teacher.my-classes.propose_book',":my_class") }}";
url = url.replace(':my_class', cid);
$.ajax({
  url:url,
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
 type: "GET",
 dataType: 'json',
 data: { "page":page,"subject_val":subject_val },
 success: function(response){
    if(response.status == 'success'){
       $('#result_container').html(response.results);
    }
 },
fail: function() {
 }
 });
}
</script>
@stop
