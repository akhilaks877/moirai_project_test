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
                    <span class="metismenu-icon pe-7s-notebook"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">Subject Management</h4>
                    <span class="subtitle"> - This page allows the management of all subjects on the platform.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                         <a href="{{ route('admin.dashboard') }}">Home</a> > Subjects
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active">
            <div class="main-card mb-3 card">
                <div class="card-body">
                <h5 class="card-title">List of Subjects</h5>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <table class="mb-0 table table-hover dataTable table-custom data-table js-exportable" id="subjectssTable" style="width:100%;"">
                                <thead>
                                <tr>
                                    <th>Subject Name</th>
                                    <th>Number of Books</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                {{-- <tbody>
                                <tr>
                                    <td>Mathematics</td>
                                    <td>2</td>
                                    <td>
                                        <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-dark disabled"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Secretarial Studies</td>
                                    <td>0</td>
                                    <td>
                                        <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>History</td>
                                    <td>1</td>
                                    <td>
                                        <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-dark disabled"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>

                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex">
                        <div class="col-6">
                            <h5>Add a subject to the platform</h5>
                            <label for="add_matiere" class="">Enter the name for the subject</label>
                            <input name="add_subject" id="add_matiere" placeholder="Subject name" type="text" class="form-control">
                        </div>

                        <div class="col-6 align-self-end mt-2">
                            <a href="javascript:;" class="btn btn-primary float-right ml-3" id="newsubject_add">
                                <span class="fa fa-plus"></span>
                                Add subject
                            </a>
                            <a href="javascript:;" data-url={{ url()->previous() }} class="btn btn-secondary float-right" id="back_Book">
                                Return to book
                            </a>
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
      var table=$("#subjectssTable").DataTable({      // tables listing subjects
        processing: true,
        serverSide: true,
        ajax: {
         url: '{{ route('admin.title.manage_subjects') }}',
         type: "get",
         data: function(d) {d._token = '{{ csrf_token() }}';}
        },
        columns: [
            { data: 'subjectname', name: 'subjectname' },
            { data: 'book_nos', name: 'book_nos' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        fnDrawCallback: function( oSettings ) {
            $('.rmSub').on('click',function(){
                var sub_name=$(this).data('subject');  var subject_ent=$(this).data('sid');
                if (confirm('Do you want to remove Subject '+sub_name+' ?')){
                    $.ajax({
                       url:'{{ route('admin.title.rmve_subject') }}',
                      headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                      type: "POST",
                      data: {"subject_ent":subject_ent},
                      dataType: 'json',
                      success: function(response){
                        if(response.status == 'success'){
                        $('#subjectssTable').DataTable().ajax.reload();
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


     $('#newsubject_add').on('click',function(){
        var subject=$('input#add_matiere').val();
        if(subject){
            $.ajax({
           url:'{{ route('admin.title.add_subject') }}',
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
         type: "POST",
         dataType: 'json',
         data: { "subject":subject },
         success: function(response){
         if(response.status == 'success'){
            $('input#add_matiere').val(''); table.draw();
          }
         },
         fail: function() {
         }
        });
        }
        else{

        }
      });

      $('#back_Book').on('click',function(){
        var backUrl=$(this).data('url'); window.location.href =backUrl;
      });
});
</script>
@stop
