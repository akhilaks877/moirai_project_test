@extends('layouts.Template.app')
@section('pageContant')
    <div class="app-main__inner">
        <a data-toggle="collapse" href="#reading_nav" class="btn-open-options btn btn-primary button_expand_mobile"
            aria-expanded="false"><span class="fa fa-indent fa-w-16"></span></a>

        <div class="parent_header_reading">
            <div class="header_reading">
                <div class="row navbar_uncollapsed_desktop">
                    <div class="col-md-9">
                        <h1 class="ml-2 mt-2 title_chapter_nav">Introduction</h1>
                    </div>
                    <div class="col-md-3">
                        <a data-toggle="collapse" href="#reading_nav" class="btn btn-primary float-right m-2 book_navigation"
                            aria-expanded="true"><span class="sign">+</span> Book Navigation</a>
                    </div>
                </div>

                <div class="collapse row" id="reading_nav" style="">
                    <div class="col-md-12">
                        <hr class="m-0">
                    </div>
                    <div class="col-md-8 pr-0 menu_separator">
                        @include('Template.common_table_of_content')
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
                                    <a href="#" class="bigger_font pl-2 pr-2">A<sup>+</sup></a>
                                    <a href="#" class="lower_font pl-2 pr-2">A<sup>-</sup></a>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label pl-2" for="note_editeur">View Editor's
                                    Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_editeur" checked data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light">
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_teacher">View Teacher's
                                    Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_teacher" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light">
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_perso">View My
                                    Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_perso" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light">
                            </div>
                        </div>
                        <div class="position-relative mb-3 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_add">Enable Note
                                    Editing</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_add" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" id="reading_percentage">
                    <div class="bar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"
                        role="progressbar"></div>
                </div>

            </div>
        </div>
        <div class="tab-content size_temoin">
            <div class="tab-pane tabs-animation fade show active">
                <div class="main-card mb-3 card">
                    <!-- the class here will change according to the part of the book : Introcution, table_of_content, chapter ...-->
                    <div class="card-body introduction">

                        <!-- The cover is always the image taken from the database. We need to upload the cover when we create a new book-->
                        <img src="{!! asset('storage/ebooks/book_' . $book_data->id . '/cover_image/' . $book_data->cover_image) !!}" title="" alt="" class="img-fluid mx-auto d-block" />
                        <!-- The title taken from the database. A title is always added when we create a new book-->
                        <h2 class="standard_main_title">{!! $book_data->title !!}</h2>
                        <!-- This line will always be displayed after the title -->
                        <span class="copyright">&copy; Éditions Moirai</span><br /><br />

                        <!-- Those lines come from the xml -->
                        @foreach ($data['preface']['preface_txt'] as $preface_txt)
                            <span class="preface_txt">{!! $preface_txt !!}</span><br />
                        @endforeach

                        {{-- <span class="preface_txt_2">Conception éditoriale : blablablabla</span><br />
                        <span class="preface_txt_3">Conception éditoriale : blablablabla</span><br />
                        <span class="preface_txt_4">Conception éditoriale : blablablabla</span><br />
                        <span class="preface_txt_5">Conception éditoriale : blablablabla</span><br />
                        <span class="preface_txt_6">Conception éditoriale : blablablabla</span><br />
                        <span class="preface_txt_7">Conception éditoriale : blablablabla</span><br /> --}}

                        <p class="preface_txt_principal">{!! $data['preface']['preface_txt_principal'] !!}
                        </p>

                        <!-- This block will always be added after preface_txt_principal. It's not part of the XML as it won't change between books -->
                        <div class="row moirai_data">
                            <div class="col-md-3 d-flex align-items-center">
                                <img src="./assets/images/logo-inverse.png" class="img-fluid" />
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                5445 avenue de Gaspé<br />
                                Montréal (Québec) Canada H2T 3V7<br />
                                Téléphone : 000 000 0000<br />
                                info@editionsmoirai.com<br />
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="rights_policy">
                                    <strong>TOUS DROITS RÉSERVÉS.</strong><br />
                                    Toute reproduction, en tout ou en partie, sous toute forme ou média et
                                    par quelque procédé que ce soit, est interdite sans l’autorisation
                                    écrite des Éditions Moiraï inc.
                                </div>
                            </div>
                        </div>

                        <!-- Those lines come from the xml -->
                        <p class="preface_txt_secondaire">{!! $data['preface']['preface_txt_secondaire'] !!}</p>
                        <h2 class="avantpropos_title">{!! $data['avantpropos']['avantpropos_title'] !!}</h2>
                        <p class="avantpropos_txt_1">{!! $data['avantpropos']['avantpropos_txt_1'] !!}

                        </p>
                        <em class="avantpropos_citation_1">{!! $data['avantpropos']['avantpropos_txt_citation_1'] !!}</em>
                        <p class="avantpropos_txt_2">{!! $data['avantpropos']['avantpropos_txt_2'] !!}</p>




                        <div class="row d-flex">
                            <div class="col-md-6 mx-auto">
                                <hr>
                            </div>
                        </div>
                        <div class="row chapter_nav">
                            <div class="col-6 d-flex">
                            </div>
                            <div class="col-6 d-flex">
                                <a href="{{route('showBook',["page" => $tbl_of_cnt_File])}}" class="mt-2 ml-1 btn btn-primary mx-auto">Table des
                                    matières</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
