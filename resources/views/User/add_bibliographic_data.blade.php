@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="font-work">
                    <h4 class="mb-0 page-title">Book Title -Add Bibliographic Data</h4>
                    <span class="subtitle"> - This page allows the management of the book's metadata.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">Home</a>  >Add Bibliographic Data
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav" role="tablist">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-general">
                <span>General</span>
            </a>
        </li>
    </ul> --}}

    <div class="tab-content">

        <!-- General Tab -->

        <div class="tab-pane tabs-animation fade show active" id="tab-general" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body"><div class="alert general alert-danger" style="display: none;"><strong></strong>.</div>
                <h5 class="card-title">General Information</h5>
                <form class="" action="" method="POST" enctype="multipart/form-data" data-url="{{ route('admin.title.add_book_general') }}" id="generalForm" data-parsley-validate>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="titre_livre" class="req">Title</label>
                                            <input name="title" id="titre_livre" placeholder="Book Title 1" type="text" class="form-control" data-parsley-required data-parsley-error-message="Title is required">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="soustitre_livre" class="req">Subtitle</label>
                                            <input name="subtitle" id="soustitre_livre" placeholder="Example of a Subtitle" type="text" class="form-control" data-parsley-required data-parsley-error-message="Sub-title is required">
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="ISBN" class="req">ISBN</label>
                                            <input name="isbn" id="ISBN" placeholder="5142545874582" type="text" class="form-control" data-parsley-required data-parsley-type="digits" data-parsley-error-message="ISBN should be digits">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="magento_sku" class="req">Magento SKU</label>
                                            <input name="magento_sku" id="magento_sku" placeholder="BOOK1" type="text" class="form-control" data-parsley-required data-parsley-error-message="SKU is required">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="Language" class="req">Language</label>
                                            <select name="language" id="Langue" class="form-control" data-parsley-required data-parsley-error-message="Language is required">
                                                <option value="">Select</option>
                                                @foreach($data['langs'] as $k=>$lan)
                                               <option value="{{ $lan->id }}">{{ $lan->lang_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="Country" class="req">Country</label>
                                            <select name="country" id="Pays"  class="form-control" data-parsley-required data-parsley-error-message="Country is required">
                                                <option value="1">Canada</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="Page" class="req">Number of Pages</label>
                                            <input name="num_pages" id="Page" placeholder="124" type="text" class="form-control" data-parsley-required data-parsley-type="integer" data-parsley-error-message="Pagenos. Should be digits">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="editeur" class="req">Name of Editor</label>
                                            <input name="editor" id="editeur" placeholder="Moiraï Publishing" type="text" class="form-control" data-parsley-required data-parsley-error-message="Name of editor is required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 offset-1">
                                    <div id="imgPreview"></div>
                                    <label for="cover_book" class="req">Cover Image</label>
                                    <input name="cover_book" id="cover_book" type="file" class="form-control-file">
                                    <small class="form-text text-muted">Recommended size: 600x800 pixels. Max file size: 1 MB</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="description" class="">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-2 btn btn-primary float-right">Add Book Data</button>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

@stop
@section('page-script')
<script>
$(function(){
    $("#cover_book").change(function () {
    filePreview(this);
   });

    $('form#generalForm').on('submit',function(e){  // general
      e.preventDefault();  $('.general.alert-danger ul').html(''); $('.general.alert-danger').hide();
      $('form#generalForm button[type="submit"]').attr('disabled',true); var url =$(this).data('url');
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
            var bid=response.results.id;
           var redirectLink="{{ route('admin.title.edit_book_detail',":id")}} "; redirectLink = redirectLink.replace(':id',bid);
            $('form#generalForm button[type="submit"]').attr('disabled',false);
         window.location.href =redirectLink;
         }
         if(response.status == 'invalid'){
            $('form#generalForm button[type="submit"]').attr('disabled',false);
            var msg = '<ul class="display_error">';
            $.each(response.messages, function( key,value) {
              msg += '<li>' + value + '</li>';
            });
            msg += '</ul>';
            $('.general.alert-danger').append(msg);
            $('.general.alert-danger strong').html(response.message);
            $('.general.alert-danger').show();
            $('html, body').animate({scrollTop: $("#tab-general").offset().top}, 'fast');
         }
     },
    fail: function() {
     }
     });
    });
});

function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imgPreview + img').remove();
            $('#imgPreview').after('<img src="'+e.target.result+'" alt="Book cover [Book title]" title="Book cover [Book title]" style="width:100%"/>');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@stop
