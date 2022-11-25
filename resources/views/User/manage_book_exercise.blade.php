@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon" style="padding: 0px; width: inherit;">
                @isset($book_data->cover_image)<img src="{{ asset('storage/ebooks/book_'.$book_data->id.'/cover_image/'.$book_data->cover_image) }}" alt="Book cover [Book Title]" title="Book cover [Book Title]" style="height:60px" />@endisset
                </div>
                <div>
                    <h4 class="mb-0 page-title">{{ $book_data->title }} - {{__('messages.exercises')}}</h4>
                    <span class="subtitle"> - {{__('messages.exercises_message')}}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="javascript:void(0);">{{ $book_data->title }}</a> > {{__('messages.list_of_exercises')}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session()->has('excer.success'))
    <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {!! session('excer.success') !!}
    </div>
     @endif
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{__('messages.list_of_exercises')}}</h5>
            <div class="form-row">
                <div class="col-md-12">
                    <table class="mb-0 table table-hover dataTable table-custom data-table js-exportable" data-url="{{ route('admin.title.manage_book_exercise',['id'=>$book_data->id]) }}" id="exlistTable" style="width:100%;">
                        <thead>
                        <tr>
                            <th>{{__('messages.chapter')}}</th>
                            <th>{{__('messages.exercises_title')}}</th>
                            <th>{{__('messages.action')}}</th>
                        </tr>
                        </thead>
                    </table>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('admin.title.add_bookexercise',["id" => $book_data->id]) }}" class="btn btn-primary float-right">
                    <span class="fa fa-plus"></span>
                    {{__('menus.add_an_exercise')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
    var table=$("#exlistTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
         url: $(this).data('url'),
         type: "get",
         data: function(d) {
            d._token = '{{ csrf_token() }}';
            d.book="{{ $book_data->id }}";
          }
        },
        columns: [
            { data: 'chapter', name: 'chapter' },
            { data: 'exercise_title', name: 'exercise_title' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        fnDrawCallback: function( oSettings ) {
            $('.delExcercse').on('click',function(){
                var con_excer=$(this).data('head'); var book_ent="{{ $book_data->id }}";
                var excer_ent=$(this).data('eid'); var chapt_ent=$(this).data('chpid');
                if (confirm('Do you want to remove this excercise '+con_excer+' from this book?')){
                    $.ajax({
                       url:'{{ route('admin.title.remov_theexcecise') }}',
                      headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                      type: "POST",
                      data: { "book_ent":book_ent,"excer_ent":excer_ent,"chapt_ent":chapt_ent },
                      dataType: 'json',
                      success: function(response){
                        if(response.status == 'success'){
                        $('#exlistTable').DataTable().ajax.reload();
                        }
                       },
                      fail: function() {
                      }
                      });
                     }
            //   alert($(this).data('bid'));
            });
        },
        "createdRow": function(row, data, dataIndex){},
     });
});
</script>
@stop
