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
                    <img src="{{ asset('assets/images/cover_book.jpg') }}" alt="Book cover [Book title]" title="Book cover [Book title]" style="height:60px" />
                </div>
                <div>
                    <h4 class="mb-0 page-title">Book Title 1 - Web Content</h4>
                    <span class="subtitle"> - Manage the web-exclusive content for this book</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > <a href="donnees-biblio.html">Book Title 1</a> > Web Content
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Add content to the page</h5>
            <!--
            <div class="form-row" role="group">
                <div class="col-4 text-center">
                    <a data-toggle="tab" href="#block_exercice" class="mt-2 ml-1 btn btn-primary" id="add_bloc_exercice">Add an exercise</a>
                </div>
                <div class="col-4 text-center">
                    <a data-toggle="tab" href="#block_text"class="mt-2 ml-1 btn btn-primary" id="add_bloc_text">Add text</a>
                </div>
                <div class="col-4 text-center">
                    <a data-toggle="tab" href="#block_media"class="mt-2 ml-1 btn btn-primary" id="add_bloc_media">Add video or other media</a>
                </div>
            </div>
            -->
            <div class="nav">
                <a data-toggle="tab" href="#block_exercice" class="border-0 btn-transition active btn btn-outline-primary active">Add an exercise</a>
                <a data-toggle="tab" href="#block_text" class="mr-1 ml-1 border-0 btn-transition  btn btn-outline-primary">Add text</a>
                <a data-toggle="tab" href="#block_media" class="border-0 btn-transition  btn btn-outline-primary">Add video or other media</a>
            </div>





            <div class="form-row">
                <div class="col-12">
                <hr>
                </div>
            </div>

            <div class="form-row">
                <div class="col-12" id="add_a_block">
                    <div class="tab-content">

                        <div id="block_exercice" class="tab-pane active" role="tabpanel">
                            <h5>Choose exercises to add to the page</h5>
                            <form>
                                <label for="select_exercice" class="">Choose the exercise(s)</label>
                                <div class="select_exercice_parent">
                                  <select style="display:none"  name="select_exercice" multiple id="select_exercice">
                                    <option value="exercice1">Exercise 1: Lorem Ipsum Dolor sit amet</option>
                                    <option value="exercice2">Exercise 2: Lorem Ipsum Dolor sit amet</option>
                                    <option value="exercice3">Exercise 3: Lorem Ipsum Dolor sit amet</option>
                                    <option value="exercice4">Exercise 4: Lorem Ipsum Dolor sit amet</option>
                                    <option value="exercice5">Exercise 5: Lorem Ipsum Dolor sit amet</option>
                                    <option value="exercice6">Exercise 6: Lorem Ipsum Dolor sit amet</option>
                                    <option value="exercice7">Exercise 7: Lorem Ipsum Dolor sit amet</option>
                                    <option value="exercice8">Exercise 8: Lorem Ipsum Dolor sit amet</option>
                                  </select>
                                </div>
                                <a href="#nogo" class="mt-2 ml-1 btn btn-primary float-right" id="">Add exercise</a>
                            </form>
                        </div>

                        <div id="block_text" class="tab-pane" role="tabpanel">
                            <h5>Choose text to add to the page</h5>
                            <form>
                                <label for="textarea_block" class="">Enter the text to be added</label>
                                <textarea id="textarea_block" name="textarea_block">
                                Add text
                                </textarea>


                                <a href="#nogo" class="mt-2 ml-1 btn btn-primary float-right" id="">Add text</a>
                            </form>
                        </div>


                        <div id="block_media" class="tab-pane" role="tabpanel">
                            <h5>Choose video or other media to add to the page</h5>
                            <form>

                                <div class="position-relative form-group">
                                    <label for="add_media_file" class="">Add a media file</label>
                                    <input name="add_media_file" id="add_media_file" type="file" class="form-control-file">
                                    <small class="form-text text-muted">You can add a photo or video using this form.</small>
                                </div>

                                <a href="#nogo" class="mt-2 ml-1 btn btn-primary float-right" id="">Add media</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Web Content</h5>
            <div class="row">
                <div class="col-12" id="page_complement_preview">
                    <div class="block_draggable">
                        <p>This is text added by Tiny MCE.</p>
                    </div>
                    <div class="block_draggable">
                        <img src="{{ asset('assets/images/exemple_image.jpg') }}" title="Image Title" alt="Image Title" class="img-fluid" />
                    </div>
                    <div class="block_draggable">
                        <div class="row">
                            <div class="col-3">
                                <img src="{{ asset('assets/images/exemple_image.jpg') }}" title="Exercise 1 Title" alt="Exercise 1 Illustration" class="img-fluid" />
                            </div>
                            <div class="col-9">
                                <h5 class="pt-2">Exercise 1 Title</h5>
                                <a href="#" class="mt-2 ml-1 btn btn-primary">Go to Exercise 1</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                <hr>
                    <a href="#nogo" class="mt-2 ml-1 btn btn-primary float-right" id="">Save changes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$('.select_exercice_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
	searchNoData: '<li style="color:#ddd">No results found.</li>',
});
$(function(){
    tinymce.init({
  selector: '#textarea_block',
  plugins: "link",
  menubar: "insert"
  });
});
</script>
@stop
