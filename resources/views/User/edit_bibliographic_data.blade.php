@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
    <style>
        .file-upload {
            display: block;
            width: 100%;
            text-align: center;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
        }

        .file-upload .file-select {
            display: block;
            border: 2px solid #dce4ec;
            color: #34495e;
            cursor: pointer;
            height: 40px;
            line-height: 40px;
            text-align: left;
            background: #FFFFFF;
            overflow: hidden;
            position: relative;
        }

        .file-upload .file-select .file-select-button {
            background: #dce4ec;
            padding: 0 10px;
            display: inline-block;
            height: 40px;
            line-height: 40px;
        }

        .file-upload .file-select .file-select-name {
            line-height: 40px;
            display: inline-block;
            padding: 0 10px;
        }

        .file-upload .file-select:hover {
            border-color: #34495e;
            transition: all .2s ease-in-out;
            -moz-transition: all .2s ease-in-out;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
        }

        .file-upload .file-select:hover .file-select-button {
            background: #34495e;
            color: #FFFFFF;
            transition: all .2s ease-in-out;
            -moz-transition: all .2s ease-in-out;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
        }

        .file-upload.active .file-select {
            border-color: #3fa46a;
            transition: all .2s ease-in-out;
            -moz-transition: all .2s ease-in-out;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
        }

        .file-upload.active .file-select .file-select-button {
            background: #3fa46a;
            color: #FFFFFF;
            transition: all .2s ease-in-out;
            -moz-transition: all .2s ease-in-out;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
        }

        .file-upload .file-select input[type=file] {
            z-index: 100;
            cursor: pointer;
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            filter: alpha(opacity=0);
        }

        .file-upload .file-select.file-select-disabled {
            opacity: 0.65;
        }

        .file-upload .file-select.file-select-disabled:hover {
            cursor: default;
            display: block;
            border: 2px solid #dce4ec;
            color: #34495e;
            cursor: pointer;
            height: 40px;
            line-height: 40px;
            margin-top: 5px;
            text-align: left;
            background: #FFFFFF;
            overflow: hidden;
            position: relative;
        }

        .file-upload .file-select.file-select-disabled:hover .file-select-button {
            background: #dce4ec;
            color: #666666;
            padding: 0 10px;
            display: inline-block;
            height: 40px;
            line-height: 40px;
        }

        .file-upload .file-select.file-select-disabled:hover .file-select-name {
            line-height: 40px;
            display: inline-block;
            padding: 0 10px;
        }

    </style>
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon" style="padding: 0px; width: inherit;">
                    @isset($book_data->cover_image)<img
                            src="{{ asset('storage/ebooks/book_' . $book_data->id . '/cover_image/' . $book_data->cover_image) }}"
                            alt="couverture du livre [titre du livre]" title="couverture du livre [titre du livre]"
                        style="height:60px" />@endisset
                </div>
                <div class="font-work">
                    <h4 class="mb-0 page-title">{{ $book_data->title }} - {{ __('messages.bibliographic_data') }}
                    </h4>
                    <span class="subtitle"> - {{ __('messages.bibliographic_data_message') }}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a> > <a
                                href="#">{{ __('messages.book_catalogue') }}</a> > <a
                                href="#">{{ $book_data->title }}</a> > Bibliographic Data
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('bookstatus.status'))
        <div class="alert alert-{{ session('bookstatus.status') }}">
            {!! session('bookstatus.message') !!}
        </div>
    @endif

    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav" role="tablist">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-general">
                <span>{{ __('menus.general') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-contributeur">
                <span>{{ __('menus.contributors') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-dimension">
                <span>{{ __('menus.dimensions_and_weight') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-vente">
                <span>{{ __('menus.sales_information') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-scolaire">
                <span>{{ __('menus.academic_information') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-5" data-toggle="tab" href="#tab-bookCreation">
                <span>{{ __('menus.book_creation') }}</span>
            </a>
        </li>

    </ul>

    <div class="tab-content">

        <!-- General Tab -->

        <div class="tab-pane tabs-animation fade show active" id="tab-general" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="alert general alert-danger" style="display: none;"><strong></strong>.</div>
                    <h5 class="card-title">{{ __('messages.general_information') }}</h5>
                    <form class="" action="" method="POST" enctype="multipart/form-data"
                        data-url="{{ route('admin.title.add_book_general') }}" id="generalForm"
                        data-parsley-validate>
                        <input type="hidden" name="upbook_data" value="1" readonly>
                        <input type="hidden" name="book_entity" value="{{ $book_data->id }}" readonly>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="titre_livre"
                                                class="req">{{ __('messages.title') }}</label>
                                            <input name="title" id="titre_livre" placeholder="Book Title 1" type="text"
                                                value="{{ $book_data->title }}" class="form-control"
                                                data-parsley-required data-parsley-error-message="Title is required">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="soustitre_livre"
                                                class="req">{{ __('messages.subtitle') }}</label>
                                            <input name="subtitle" id="soustitre_livre"
                                                placeholder="Example of a Subtitle" type="text"
                                                value="{{ $book_data->subtitle }}" class="form-control"
                                                data-parsley-required
                                                data-parsley-error-message="Sub-title is required">
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="ISBN"
                                                class="req">{{ __('messages.isbn') }}</label>
                                            <input name="isbn" id="ISBN" placeholder="5142545874582" type="text"
                                                value="{{ $book_data->isbn }}" class="form-control"
                                                data-parsley-required data-parsley-type="digits"
                                                data-parsley-error-message="ISBN should be digits">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="magento_sku"
                                                class="req">{{ __('messages.magento_sku') }}</label>
                                            <input name="magento_sku" id="magento_sku" placeholder="BOOK1" type="text"
                                                value="{{ $book_data->magento_sku }}" class="form-control"
                                                data-parsley-required data-parsley-error-message="SKU is required">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="Language"
                                                class="req">{{ __('messages.language') }}</label>
                                            <select name="language" id="Langue" class="form-control"
                                                data-parsley-required data-parsley-error-message="Language is required">
                                                <option value="">Select</option>
                                                @foreach ($data['langs'] as $k => $lan)
                                                    <option value="{{ $lan->id }}" @if ($book_data->language == $lan->id) selected @endif>
                                                        {{ $lan->lang_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="Country"
                                                class="req">{{ __('messages.country') }}</label>
                                            <select name="country" id="Pays" class="form-control"
                                                data-parsley-required data-parsley-error-message="Country is required">
                                                <option value="1" @if ($book_data->country == '1') selected @endif>Canada</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="Page"
                                                class="req">{{ __('messages.number_of_pages') }}</label>
                                            <input name="num_pages" id="Page" placeholder="124" type="text"
                                                value="{{ $book_data->page_nos }}" class="form-control"
                                                data-parsley-required data-parsley-type="integer"
                                                data-parsley-error-message="Pagenos. Should be digits">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="editeur"
                                                class="req">{{ __('messages.name_of_editor') }}</label>
                                            <input name="editor" id="editeur" placeholder="Moiraï Publishing"
                                                type="text" value="{{ $book_data->editor_name }}"
                                                class="form-control" data-parsley-required
                                                data-parsley-error-message="Name of editor is required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 offset-1">
                                @if ($book_data->id)<input type="hidden" name="decidesimg" value="1" readonly/>@endif
                                <div id="imgPreview"></div>
                                @isset($book_data->cover_image)<img
                                        src="{{ asset('storage/ebooks/book_' . $book_data->id . '/cover_image/' . $book_data->cover_image) }}"
                                        alt="Book cover [Book title]" title="Book cover [Book title]"
                                    style="width:100%" />@endisset
                                <label for="cover_book"
                                    class="req">{{ __('messages.cover_image') }}</label>
                                <input name="cover_book" id="cover_book" type="file" class="form-control-file">
                                <small class="form-text text-muted">{{ __('messages.cover_image_info') }}</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="description"
                                        class="">{{ __('messages.description') }}</label>
                                    <textarea name="description" id="description" class="form-control"
                                        rows="5">{{ $book_data->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="mt-2 btn btn-primary float-right">{{ __('messages.edit') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contributor Tab -->

        <div class="tab-pane tabs-animation fade" id="tab-contributeur" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.contributors') }}</h5>
                    <div class="form-row">
                        <div class="col-md-12">
                            <table class="mb-0 table table-hover dataTable table-custom data-table js-exportable"
                                id="contributorsTable" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.firstname') }}</th>
                                        <th>{{ __('messages.lastname') }}</th>
                                        <th>{{ __('messages.role') }}</th>
                                        <th>{{ __('messages.action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                            <hr>
                        </div>
                    </div>

                    <h5>{{ __('messages.add_contributor_to_book') }}</h5>
                    <div class="form-row">
                        <label for="add_contributeur"
                            class="col-md-3 col-form-label">{{ __('messages.add_contributor') }}</label>
                        <div class="col-md-6">
                            <select name="add_contributor" id="add_contributeur" class="form-control">
                                <option value="">Select Contributor</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="add_tobookcon" class="btn btn-primary float-right"><span
                                    class="fa fa-plus"></span>{{ __('messages.add_to_book') }}</button>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="alert alert-success" style="display: none;"></div>
                        </div>
                        <div class="col-md-4">
                            <hr>
                            <a data-toggle="collapse" href="#new_author"
                                class="btn btn-primary float-right">{{ __('messages.add_a_new_contributor') }}</a>
                        </div>

                    </div>

                    <div id="new_author" class="collapse">

                        <div class="form-row">
                            <div class="col-md-12">
                                <h5 class="card-title" style="padding: 20px 0px;">Add a contributor</h5>
                            </div>
                        </div>
                        <form class="" action="" method="POST" enctype="multipart/form-data"
                            id="contributorAddForm" data-url="{{ route('admin.title.add_new_contributor') }}"
                            data-parsley-validate>
                            <input type="hidden" name="upbook_data" value="1" readonly>
                            <input type="hidden" name="book_entity" value="{{ $book_data->id }}" readonly>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="prenom_contributeur" class="req">First Name</label>
                                        <input name="firstname" id="prenom_contributeur" placeholder="Jolly" type="text"
                                            class="form-control vanish" data-parsley-required
                                            data-parsley-error-message="Firstname is required">
                                    </div>
                                    <div class="position-relative form-group">
                                        <label for="nom_contributeur" class="req">Last Name</label>
                                        <input name="lastname" id="nom_contributeur" placeholder="Jumper" type="text"
                                            class="form-control vanish" data-parsley-required
                                            data-parsley-error-message="Lastname is required">
                                    </div>
                                    <div class="position-relative form-group">
                                        <label for="role_contributeur" class="req">Role</label>
                                        <select name="role" id="role_contributeur" class="form-control"
                                            data-parsley-required data-parsley-error-message="Role is required">
                                            <option value="">Select Role</option>
                                            @foreach ($data['contributor_roles'] as $k => $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="biographie_contributeur" class="">Biography</label>
                                        <textarea name="biography_contributor" id="biographie_contributeur" rows="8"
                                            class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <div id="conPreview"></div>
                                        <img src="{{ asset('assets/images/avatars/loic.marin.png') }}"
                                            alt="This contributor's current picture"
                                            title="This contributor's current picture" style="width:100%" />
                                    </div>
                                    <div class="col-md-12">
                                        <label for="photo_contributeur">Contributor Photo</label>
                                        <input name="user_image" id="photo_contributeur" type="file"
                                            class="form-control-file">
                                        <small class="form-text text-muted">Recommended size: 400x400 pixels. Max file
                                            size: 2 MB</small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="mt-2 btn btn-primary float-right">Add</button>
                        </form>
                    </div>



                </div>
            </div>
        </div>

        <!-- Dimensions Tab -->

        <div class="tab-pane tabs-animation fade" id="tab-dimension" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="alert dimension alert-danger" style="display: none;"><strong></strong>.</div>
                    <h5 class="card-title">Book Dimensions and Weight</h5>
                    <form class="" action="" method="POST" enctype="multipart/form-data"
                        data-url="{{ route('admin.title.add_book_dimensions') }}" id="dimensionForm"
                        data-parsley-validate>
                        <input type="hidden" name="book_entity" value="{{ $book_data->id }}" readonly>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="size_unit_choice" class="req">Choose units</label>
                                    <div class="input-group">
                                        <select name="size_unit_choice" id="size_unit_choice" class="form-control"
                                            data-parsley-required data-parsley-error-message="Units is required">
                                            <option value="cm" @if ($book_data->physical_unit == '0') selected @endif>Centimeters</option>
                                            <option value="in" @if ($book_data->physical_unit == '1') selected @endif>Inches</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="longueur_livre" class="req">Length (in <span
                                            class="size_unit">cm</span>)</label>
                                    <div class="input-group">
                                        <input name="length" id="longueur_livre" placeholder="16" type="text"
                                            class="form-control" value="{{ $book_data->length }}"
                                            data-parsley-required data-parsley-type="number"
                                            data-parsley-error-message="Length should be in numbers">
                                        <div class="input-group-append">
                                            <span class="input-group-text size_unit">cm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="largeur_livre" class="req">Width (in <span
                                            class="size_unit">cm</span>)</label>
                                    <div class="input-group">
                                        <input name="width" id="largeur_livre" placeholder="12" type="text"
                                            class="form-control" value="{{ $book_data->width }}"
                                            data-parsley-required data-parsley-type="number"
                                            data-parsley-error-message="Width should be in numbers">
                                        <div class="input-group-append">
                                            <span class="input-group-text size_unit">cm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="epaisseur_livre" class="req">Thickness (in <span
                                            class="size_unit">cm</span>)</label>
                                    <div class="input-group">
                                        <input name="thickness" id="epaisseur_livre" placeholder="4" type="text"
                                            class="form-control" value="{{ $book_data->thickness }}"
                                            data-parsley-required data-parsley-type="number"
                                            data-parsley-error-message="Thickness should be in numbers">
                                        <div class="input-group-append">
                                            <span class="input-group-text size_unit">cm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="poids_livre" class="req">Weight (in kg)</label>
                                    <div class="input-group">
                                        <input name="weight" id="poids_livre" placeholder="1.2" type="text"
                                            class="form-control" value="{{ $book_data->weight }}"
                                            data-parsley-required data-parsley-type="number"
                                            data-parsley-error-message="Weight should be in numbers">
                                        <div class="input-group-append">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-2 btn btn-primary float-right">Edit</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sales Tab -->

        <div class="tab-pane tabs-animation fade" id="tab-vente" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="alert sales alert-danger" style="display: none;"><strong></strong>.</div>
                    <h5 class="card-title">Sales Information</h5>
                    <form class="" action="" method="POST" enctype="multipart/form-data"
                        data-url="{{ route('admin.title.add_book_salesinfo') }}" id="salesForm"
                        data-parsley-validate>
                        <input type="hidden" name="book_entity" value="{{ $book_data->id }}" readonly>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="availability" class="req">Availability</label>
                                    <select name="availability" id="availability" class="form-control"
                                        data-parsley-required data-parsley-error-message="Availability is required">
                                        <option value="1" @if ($book_data->availability == '1') selected @endif>Available</option>
                                        <option value="2" @if ($book_data->availability == '2') selected @endif>In Reprint</option>
                                        <option value="3" @if ($book_data->availability == '3') selected @endif>Out of Stock</option>
                                    </select>
                                </div>
                                <div class="position-relative form-group">
                                    <label for="inventory" class="req">Inventory</label>
                                    <input name="inventory" id="inventory" placeholder="140" type="text"
                                        value="{{ $book_data->inventory }}" class="form-control"
                                        data-parsley-required data-parsley-type="digits"
                                        data-parsley-error-message="Inventory should be digits">
                                </div>
                            </div>


                            <div class="col-md-6">

                                <div class="position-relative form-group">
                                    <label for="sale_date" class="req">Publication Date</label>
                                    <div class='input-group date' id='datepicker1'>
                                        <input type='text' name='sale_date' class="form-control"
                                            value="{{ \Carbon\Carbon::parse($book_data->publication_date)->format('d/m/Y') }}"
                                            autocomplete="off" data-date-format="DD/MM/YYYY" data-parsley-required
                                            data-parsley-error-message="Publication Date is required" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    {{-- <input name="sale_date" id="sale_date" placeholder="20200301" type="text" class="form-control"> --}}
                                </div>
                                <div class="position-relative form-group">
                                    <label for="price" class="req">Price</label>
                                    <input name="price" id="price" placeholder="39.95"
                                        value="{{ $book_data->price }}" type="text" class="form-control"
                                        data-parsley-required data-parsley-type="number"
                                        data-parsley-error-message="Price should be valid">
                                </div>

                            </div>
                        </div>

                        <button type="submit" class="mt-2 btn btn-primary float-right">Edit</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Academic Tab -->

        <div class="tab-pane tabs-animation fade" id="tab-scolaire" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Academic Information for the Book</h5>
                    <div class="col-md-12 subject">
                        <div class="alert alert-success" style="display: none;"></div>
                    </div>
                    {{-- <form class=""> --}}
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="liste_matiere" class="">Subject</label>
                                <select name="subject" id="liste_matiere" class="form-control">
                                    <option value="">Select Subject</option>
                                    {{-- <option>Secretarial Studies</option>
                                        <option>Mathematics</option>
                                        <option>Engineering</option> --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">

                            <div class="position-relative form-group align-self-end">
                                <button class="mb-1 btn btn-primary" id="add">Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12">
                            Add or edit subjects: <a href="{{ route('admin.title.manage_subjects') }}" id="matiere"
                                class="mt-1 btn btn-primary">Manage subjects</a>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12" id="program_list">
                            <hr>
                            <h5 class="mb-4 mt-4">Official Ministry of Education program related to this book</h5>

                            <ul class="list-group">
                                {{-- <li class="list-group-item">
                                        1 - Cras justo odio
                                        <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                        <ul class="containable"></ul>
                                    </li>
                                    <li class="list-group-item">
                                        2 - Cras justo odio
                                        <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                        <ul class="containable">
                                            <li class="list-group-item">
                                                3 - Cras justo odio
                                                <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                            </li>
                                            <li class="list-group-item">
                                                4 - Cras justo odio
                                                <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                            </li>
                                            <li class="list-group-item">
                                                5 - Cras justo odio
                                                <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                            </li>
                                            <li class="list-group-item">
                                                6 - Cras justo odio
                                                <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                            </li>
                                            <li class="list-group-item">
                                                7 - Cras justo odio
                                                <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="list-group-item">
                                        8 - Cras justo odio
                                        <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                        <ul class="containable"></ul>
                                    </li>
                                    <li class="list-group-item">
                                        9 - Cras justo odio
                                        <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                        <ul class="containable"></ul>
                                    </li>
                                    <li class="list-group-item">
                                        10 - Cras justo odio
                                        <a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a>
                                        <ul class="containable"></ul>
                                    </li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="mt-2 btn btn-primary float-right" id="save_program_elems">Save the
                                program</button>
                        </div>
                    </div>

                    <div class="row d-flex">
                        <div class="col-6">
                            <h5 class="mt-4 mb-4">Add an element to the program</h5>
                            <label for="program_part" class="">Program element</label>
                            <input name="program_part" id="program_part" placeholder="Name of the program element"
                                type="text" class="form-control">
                        </div>

                        <div class="col-6 align-self-end mt-2">
                            <a href="javascript:void(0);" class="btn btn-primary float-right" id="new_program_addel">
                                <span class="fa fa-plus"></span>
                                Add element
                            </a>
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>

        <!-- Book Creation -->

        <div class="tab-pane tabs-animation fade" id="tab-bookCreation" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="alert bookcreation alert-danger" style="display: none;"><strong></strong>.</div>
                    <h5 class="card-title">{{ __('bookCreation') }}</h5>
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="header">
                                <div class="number">
                                    <h6><i class="fa fa-hand-o-down hand"></i>&nbsp;Follow The Instructions</h6>
                                </div>
                            </div>
                            <div class="list-group list-widget">
                                <a href="javascript:void(0);" class="list-group-item">
                                    <i class="fa fa-thumb-tack text-muted f_c"></i>&nbsp;Only XML files with
                                    extension <b>.xml </b>are allowed for upload.</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <form class="" action="" method="POST"
                                enctype="multipart/form-data" id="bookCreationFrm" data-url="{{ route('admin.title.uploadxml') }}"
                                data-parsley-validate>
                                @csrf()
                                <input type="hidden" name="book_entity" value="{{ $book_data->id }}" readonly>
                                {{-- <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="file-upload" style="margin-bottom: 5%;">
                                            <div class="file-select">
                                                <div class="file-select-button" id="fileName">Choose Book Asset Files</div>
                                                <div class="file-select-name" id="noFiles">No file chosen...</div>
                                                <input type="file" name="book_asset[]" id="chooseFiles"  multiple >
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="file-upload" style="margin-bottom: 5%;">
                                            <div class="file-select">
                                                <div class="file-select-button" id="fileName">Choose XML File</div>
                                                <div class="file-select-name" id="noFile">No file chosen...</div>
                                                <input type="file" name="import_file" id="chooseFile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <button type="submit" class="btn btn-success" id="upload_xml">Import
                                            xml</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- <div class="form-row">
                        <div class="col-md-12">
                            <table class="mb-0 table table-hover dataTable table-custom data-table js-exportable"
                                id="contributorsTable" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.chapter') }}</th>
                                        <th>{{ __('messages.progress') }}</th>
                                    </tr>
                                </thead>
                            </table>
                            <hr>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

    </div>
</div>

@stop
@section('page-script')
<script>
    $(function() {
        $('#datepicker1').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            startDate: new Date(),
        });
        $("#cover_book").change(function() {
            filePreview(this);
        });

        $('form#generalForm').on('submit', function(e) { // general
            e.preventDefault();
            $('.general.alert-danger ul').html('');
            $('.general.alert-danger').hide();
            $('form#generalForm button[type="submit"]').attr('disabled', true);
            var url = $(this).data('url');
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                dataType: 'JSON',
                data: new FormData($(this)[0]),
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if (response.status == 'success') {
                        $('form#generalForm button[type="submit"]').attr('disabled', false);
                        window.location.reload(true);
                    }
                    if (response.status == 'invalid') {
                        $('form#generalForm button[type="submit"]').attr('disabled', false);
                        var msg = '<ul class="display_error">';
                        $.each(response.messages, function(key, value) {
                            msg += '<li>' + value + '</li>';
                        });
                        msg += '</ul>';
                        $('.general.alert-danger').append(msg);
                        $('.general.alert-danger strong').html(response.message);
                        $('.general.alert-danger').show();
                        $('html, body').animate({
                            scrollTop: $("#tab-general").offset().top
                        }, 'fast');
                    }
                },
                fail: function() {}
            });
        });


        $('form#dimensionForm').on('submit', function(e) { // dimension
            e.preventDefault();
            $('.dimension.alert-danger ul').html('');
            $('.dimension.alert-danger').hide();
            $('form#dimensionForm button[type="submit"]').attr('disabled', true);
            var url = $(this).data('url');
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        $('form#dimensionForm button[type="submit"]').attr('disabled',
                            false);
                        window.location.reload(true);
                    }
                    if (response.status == 'invalid') {
                        $('form#dimensionForm button[type="submit"]').attr('disabled',
                            false);
                        var msg = '<ul class="display_error">';
                        $.each(response.messages, function(key, value) {
                            msg += '<li>' + value + '</li>';
                        });
                        msg += '</ul>';
                        $('.dimension.alert-danger').append(msg);
                        $('.dimension.alert-danger strong').html(response.message);
                        $('.dimension.alert-danger').show();
                        $('html, body').animate({
                            scrollTop: $("#tab-general").offset().top
                        }, 'fast');
                    }
                },
                fail: function() {}
            });
        });

        $('form#salesForm').on('submit', function(e) { // sales
            e.preventDefault();
            $('.sales.alert-danger ul').html('');
            $('.sales.alert-danger').hide();
            $('form#salesForm button[type="submit"]').attr('disabled', true);
            var url = $(this).data('url');
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        $('form#salesForm button[type="submit"]').attr('disabled', false);
                        window.location.reload(true);
                    }
                    if (response.status == 'invalid') {
                        $('form#salesForm button[type="submit"]').attr('disabled', false);
                        var msg = '<ul class="display_error">';
                        $.each(response.messages, function(key, value) {
                            msg += '<li>' + value + '</li>';
                        });
                        msg += '</ul>';
                        $('.sales.alert-danger').append(msg);
                        $('.sales.alert-danger strong').html(response.message);
                        $('.sales.alert-danger').show();
                        $('html, body').animate({
                            scrollTop: $("#tab-general").offset().top
                        }, 'fast');
                    }
                },
                fail: function() {}
            });
        });

        loadContribtrs(); //lising all contributors to dropdown
        var table = $("#contributorsTable").DataTable({ // tables listing contributors related to this book
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.title.list_related_contributors') }}',
                type: "get",
                data: function(d) {
                    d._token = '{{ csrf_token() }}';
                    d.book = "{{ $book_data->id }}";
                }
            },
            columns: [{
                    data: 'firstname',
                    name: 'firstname'
                },
                {
                    data: 'lastname',
                    name: 'lastname'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            fnDrawCallback: function(oSettings) {
                $('.rmConbok').on('click', function() {
                    var con_person = $(this).data('person');
                    var book_ent = "{{ $book_data->id }}";
                    var contrb_ent = $(this).data('bid');
                    if (confirm('Do you want to remove contribution from ' + con_person +
                            ' from this book?')) {
                        $.ajax({
                            url: '{{ route('admin.title.rmv_contributor_frmbook') }}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            type: "POST",
                            data: {
                                "book_ent": book_ent,
                                "contrb_ent": contrb_ent
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status == 'success') {
                                    $('#contributorsTable').DataTable().ajax
                                        .reload();
                                }
                            },
                            fail: function() {}
                        });
                    }
                    //   alert($(this).data('bid'));
                });
            },
            "createdRow": function(row, data, dataIndex) {},
        });

        $('form#contributorAddForm').on('submit', function(e) { // adding new contributor
            e.preventDefault();
            $('#tab-contributeur .alert-success').html('');
            $('#tab-contributeur .alert-success').hide();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                dataType: 'json',
                data: new FormData($(this)[0]),
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if (response.status == 'success') {
                        $('#conPreview + img').remove();
                        $('form#contributorAddForm .vanish').val('');
                        $('#tab-contributeur #new_author').removeClass('show');
                        loadContribtrs();
                        $('#tab-contributeur .alert-success').show();
                        $('#tab-contributeur .alert-success').html(response.message);
                    }
                },
                fail: function() {}
            });
        });

        $('#tab-contributeur #add_tobookcon').on('click', function() { // adding a contributor to this book
            var conbtr = $('select#add_contributeur').val();
            var book = "{{ $book_data->id }}";
            if (conbtr) {
                $.ajax({
                    url: '{{ route('admin.title.add_contributor_tobook') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    dataType: 'json',
                    data: {
                        "conbtr": conbtr,
                        "book": book
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            loadContribtrs();
                            table.draw();
                        }
                    },
                    fail: function() {}
                });
            } else {}
        });

        $('#tab-scolaire #add').on('click', function() { // adding subject to this book
            $('.subject .alert-success').html('');
            $('.subject .alert-success').hide();
            var subject = $("select#liste_matiere").val();
            var book_nt = "{{ $book_data->id }}";
            if (subject) {
                $.ajax({
                    url: '{{ route('admin.title.add_subjtobook') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    dataType: 'json',
                    data: {
                        "subject": subject,
                        "book_nt": book_nt
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            window.location.reload(true);
                            // loadsubjects(); $('.subject .alert-success').show(); $('.subject .alert-success').html(response.message);
                        }
                    },
                    fail: function() {}
                });

            } else {

            }
        });

        $("#tab-contributeur #photo_contributeur").change(function() {
            conPreview(this);
        });

        loadsubjects();

        loadprogrammElements();
        $("#new_program_addel").on('click', function(event) {
            var str = $("#program_part").val();
            if (str) {
                $('#program_list .list-group:last-child').append('<li class="list-group-item">' + str +
                    '<a href="javascript:void(0);" class="border-0 btn-transition btn btn-outline-info trash_programnew"><span class="fa fa-trash "></span></a><ul class="containable"></ul></li>'
                );
            }
        });

        $('#save_program_elems').on('click', function() {
            var li_lists = [];
            $('#program_list .list-group .list-group-item').each(function() {
                li_lists.push($(this).text());
            });
            var book_nt = "{{ $book_data->id }}";
            $.ajax({
                url: '{{ route('admin.title.addprogramms') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                dataType: 'json',
                data: {
                    "element_list": li_lists,
                    "book_nt": book_nt
                },
                success: function(response) {
                    if (response.status == 'success') {
                        loadprogrammElements();
                        window.location.reload(true);
                        // loadsubjects(); $('.subject .alert-success').show(); $('.subject .alert-success').html(response.message);
                    }
                },
                fail: function() {}
            });
        });

        $(document).on("click", ".trash_programnew", function(e) {
            if (this.hasAttribute("data-elems")) {
                var elemsid = $(this).data('elems');
                var book_ent = "{{ $book_data->id }}";
                var ele_txt = $(this).parent().text();
                if (confirm('Do you want to remove Element ' + ele_txt + ' from this book?')) {
                    $.ajax({
                        url: '{{ route('admin.title.rmvprogramm') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            "book_ent": book_ent,
                            "elem_ent": elemsid
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                loadprogrammElements();
                            }
                        },
                        fail: function() {}
                    });
                }
            } else {
                $(this).parent().remove();
            }
        });

        $('form#bookCreationFrm').on('submit', function(e) { // book Creation
            e.preventDefault();
            $('.bookcreation.alert-danger ul').html('');
            $('.bookcreation.alert-danger').hide();
            $('form#bookCreationFrm button[type="submit"]').attr('disabled', true);
            var url = $(this).data('url');
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                dataType: 'JSON',
                data: new FormData($(this)[0]),
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if (response.status == 'success') {
                        $('form#bookCreationFrm button[type="submit"]').attr('disabled', false);
                        window.location.reload(true);
                    }
                    if (response.status == 'invalid') {
                        $('form#bookCreationFrm button[type="submit"]').attr('disabled', false);
                        var msg = '<ul class="display_error">';
                        $.each(response.messages, function(key, value) {
                            msg += '<li>' + value + '</li>';
                        });
                        msg += '</ul>';
                        $('.bookcreation.alert-danger').append(msg);
                        $('.bookcreation.alert-danger strong').html(response.message);
                        $('.bookcreation.alert-danger').show();
                        $('html, body').animate({
                            scrollTop: $("#tab-general").offset().top
                        }, 'fast');
                    }
                },
                fail: function() {}
            });
        });


    });

    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imgPreview + img').remove();
                $('#imgPreview').after('<img src="' + e.target.result +
                    '" alt="Book cover [Book title]" title="Book cover [Book title]" style="width:100%"/>');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function conPreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#conPreview + img').remove();
                $('#conPreview').after('<img src="' + e.target.result +
                    '" alt="This contributors current picture" title="This contributors current picture" style="width:100%"/>'
                );
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function loadContribtrs() {
        $('#add_contributeur').empty().append('<option value="">Select Contributor</option>');
        $.ajax({
            url: '{{ route('admin.title.list_allcontributors') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            dataType: 'json',
            success: function(response) {
                $.each(response.results, function(key, value) {
                    $("#add_contributeur").append("<option value='" + value.id + "'>" + value
                        .firstname + " " + value.lastname + " - " + value.role_name +
                        "</option>");
                });
            },
            fail: function() {}
        });
    }

    function loadsubjects() {
        $('#liste_matiere').empty().append('<option value="">Select Subject</option>');
        var current_subject = "{{ $book_data->subject }}";
        $.ajax({
            url: '{{ route('admin.title.lists_subjects') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            dataType: 'json',
            success: function(response) {
                $.each(response.results, function(key, value) {
                    if (value.id == current_subject) {
                        optin = "<option value='" + value.id + "' selected>" + value.name +
                            "</option>";
                    } else {
                        optin = "<option value='" + value.id + "'>" + value.name + "</option>";
                    }
                    $("#liste_matiere").append(optin);
                });
            },
            fail: function() {}
        });
    }

    function loadprogrammElements() {
        $('#program_list ul.list-group').empty();
        var book_ent = "{{ $book_data->id }}";
        $.ajax({
            url: '{{ route('admin.title.list_allprogramm_elements') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            data: {
                "book_ent": book_ent
            },
            dataType: 'json',
            success: function(response) {
                $.each(response.results, function(key, value) {
                    li_optin = '<li class="list-group-item">' + value.name +
                        '<a href="javascript:void(0);" data-elems="' + value.id +
                        '" class="border-0 btn-transition btn btn-outline-info trash_programnew"><span class="fa fa-trash "></span></a><ul class="containable"></ul></li>';
                    $("#program_list ul.list-group").append(li_optin);
                });
            },
            fail: function() {}
        });
    }

    $('#chooseFile').bind('change', function() {
        var filename = $("#chooseFile").val();
        if (/^\s*$/.test(filename)) {
            $(".file-upload").removeClass('active');
            $("#noFile").text("No file chosen...");
        } else {
            $(".file-upload").addClass('active');
            $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
        }
    });

    $('#chooseFiles').bind('change', function() {
        var filename = $("#chooseFiles").val();
        if (/^\s*$/.test(filename)) {
            $(".file-uploads").removeClass('active');
            $("#noFiles").text("No files chosen...");
        } else {
            $(".file-uploads").addClass('active');
            $("#noFiles").text(filename.replace("C:\\fakepath\\", ""));
        }
    });


    $(document).ready(function(){
    $('#bookCreationFrm').submit(function(){
        $("#bulk-loader").show();
    });


});
</script>
@stop
