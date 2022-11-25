@extends('layouts.Template.app')
@section('pageContant')
    <div class="app-main__inner">
        <a data-toggle="collapse" href="#reading_nav" class="btn-open-options btn btn-primary button_expand_mobile"
            aria-expanded="false"><span class="fa fa-indent fa-w-16"></span></a>

        <div class="parent_header_reading">
            <div class="header_reading">
                <div class="row navbar_uncollapsed_desktop">
                    <div class="col-md-9">
                        <h1 class="ml-2 mt-2 title_chapter_nav">
                            Table des matières
                        </h1>
                    </div>
                    <div class="col-md-3">
                        <a data-toggle="collapse" href="#reading_nav" class="btn btn-primary float-right m-2 book_navigation"
                            aria-expanded="true"><span class="sign">+</span> Book
                            Navigation</a>
                    </div>
                </div>

                <div class="collapse row" id="reading_nav">
                    <div class="col-md-12">
                        <hr class="m-0" />
                    </div>
                    <div class="col-md-8 pr-0 menu_separator">
                        @include('Template.common_table_of_content')
                        <div class="pr-3">
                            <hr class="d-md-none" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative">
                            <div class="row">
                                <div class="col-6">
                                    <p class="d-inline-block  pl-2 pt-3">
                                        Font size:
                                    </p>
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
                                    data-onstyle="primary" data-offstyle="light" />
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_teacher">View Teacher's
                                    Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_teacher" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light" />
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_perso">View My
                                    Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_perso" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light" />
                            </div>
                        </div>
                        <div class="position-relative mb-3 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_add">Enable Note
                                    Editing</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_add" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light" />
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
                    <div class=" pb-4 table_of_content">
                        <div class="row">
                            <div class="col-md-12 px-4">
                                <!-- The table of content is generated from the chapters uploaded through the xml
                      The colors also come from the XML with the field : -->
                                <!-- The title is always the same -->
                                <h2 class="standard_main_title">
                                    Table des matières
                                </h2>
                                <img class="img-fluid" src="assets/book/transp_for_size.png" />
                                @foreach ($table_of_contant as $item)
                                    <h3 class="partie_title">
                                        <strong><a href="#">{!! $item['partie_title'] !!}</a></strong>
                                    </h3>
                                    @php
                                        $curntchapitre = 0;
                                    @endphp
                                    @foreach ($item['chapitres'] as $chapitre)
                                        @php
                                            $curntchapitre = $curntchapitre + 1;
                                        @endphp
                                        <a href="#"><span class="chapter_number"
                                                style="background-color: {!! $chapitre['chapitre_colour'] !!}; ">{!! 'CHAPITRE' . $curntchapitre !!}
                                            </span>
                                            <h4 class="chapter_title">
                                                {!! $chapitre['chapitre_title'] !!}
                                            </h4>
                                        </a>

                                        <ol>
                                            <li class="parapraph_title">
                                                <a href="#">{!! $chapitre['chapitre_section1_title'] !!}</a>
                                            </li>
                                            <ol>
                                                <li>{!! $chapitre['chapitre_section1_paragraph1_title'] !!}</li>
                                                <li>{!! $chapitre['chapitre_section1_paragraph2_title'] !!}</li>
                                                <li>{!! $chapitre['chapitre_section1_paragraph3_title'] !!}</li>
                                            </ol>
                                            <li class="parapraph_title">{!! $chapitre['chapitre_section2_title'] !!}</li>
                                            <ol>
                                                <li>{!! $chapitre['chapitre_section2_paragraph1_title'] !!}</li>
                                                <li>{!! $chapitre['chapitre_section2_paragraph2_title'] !!}</li>
                                            </ol>
                                        </ol>
                                    @endforeach
                                @endforeach
                                <a href="#"><span class="chapter_number"
                                        style="background-color: #f26649; ">Tests</span></a>
                                <br />

                                <a href="#"><span class="chapter_number"
                                        style="background-color: #ebe9e1; ">Annexes</span></a>
                                <ol>
                                    <li class="parapraph_title">
                                        <a href="#">Bibliographie</a>
                                    </li>
                                    <li class="parapraph_title">
                                        <a href="#">Iconographie</a>
                                    </li>
                                </ol>
                            </div>
                        </div>

                        <div class="row d-flex">
                            <div class="col-md-6 mx-auto">
                                <hr />
                            </div>
                        </div>
                        <div class="row chapter_nav">
                            <div class="col-6 d-flex">
                                <a href="{{route('showBook',["page" => $introductionFile])}}" class="mt-2 ml-1 btn btn-primary mx-auto">&lt;&lt;
                                    Introduction</a>
                            </div>
                            <div class="col-6 d-flex">
                                <a href="{{route('showBook',["page" => $chapitre1])}}" class="mt-2 ml-1 btn btn-primary mx-auto">Chapter 1
                                    &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
