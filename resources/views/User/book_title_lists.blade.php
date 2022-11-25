@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="pe-7s-notebook text-success">
                    </span>
                </div>
                <div>

                    <h4 class="mb-0 page-title">Book Catalogue</h4>
                    <span class="subtitle"> - This page shows all the existing books on the platform and allows for the addition of new books.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">Home</a> > Book Catalogue
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">List of All Books</h5>

            <div class="row">
                <div class="col-lg-6">
                    <label for="tri_matiere" class="">Filter by Subject</label>
                    <select name="subject" id="tri_matiere" class="form-control">
                        <option value="">All Subjects</option>
                        @foreach($data['subjects'] as $k=>$subj)
                        <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-lg-6">
                    <a href="{{ route('admin.title.add_book_detail') }}" class="btn btn-primary float-right">
                    <span class="fa fa-plus"></span>
                    Add a book
                    </a>
                </div>
            </div>

            <div id="result_container">
            @Include('User.books.books_datas')
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

});


function fetch_lists(page,subject_val)
 {
    $.ajax({
      url:'{{ route('admin.title.index') }}',
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
