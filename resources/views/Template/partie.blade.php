@extends('layouts.Template.app')
@section('pageContant')
    <div class="app-main__inner">

        <a data-toggle="collapse" href="#reading_nav" class="btn-open-options btn btn-primary button_expand_mobile"
            aria-expanded="false"><span class="fa fa-indent fa-w-16"></span></a>

        <div class="parent_header_reading">
            <div class="header_reading">
                <div class="row navbar_uncollapsed_desktop">
                    <div class="col-md-9">
                        <h1 class="ml-2 mt-2 title_chapter_nav">{!! 'Chapter ' . $chapitre['chapitre_no'] . ':' . $chapitre['chapitre_title'] !!}</h1>
                    </div>
                    <div class="col-md-3">
                        <a data-toggle="collapse" href="#reading_nav" class="btn btn-primary float-right m-2 book_navigation"
                            aria-expanded="true"><span class="sign">+</span> Book Navigation</a>
                    </div>
                </div>

                <div class="collapse row" id="reading_nav">
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
                                <label class="form-check-label pl-2" for="note_editeur">View Editor's Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_editeur" checked data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light">
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_teacher">View Teacher's Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_teacher" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light">
                            </div>
                        </div>
                        <div class="position-relative mb-1 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_perso">View My Notes</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" id="note_perso" data-toggle="toggle" data-size="small"
                                    data-onstyle="primary" data-offstyle="light">
                            </div>
                        </div>
                        <div class="position-relative mb-3 row">
                            <div class="col-9 col-sm-8 col-xl-9">
                                <label class="form-check-label  pl-2" for="note_add">Enable Note Editing</label>
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
                    <div class="chapter pb-4">

                        <!-- The table of content is generated from the chapters uploaded through the xml
                                                                     The colors also come from the XML with the field : -->
                        <!-- The title is always the same -->
                        @if ($isStartNewpartie == true)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="partie_block px-4">
                                        <h3 class="partie_title display-1 my-2 ">{!! $partie['partie_head'] !!}</h3>
                                        <span class="partie_subtitle display-4">{!! $partie['partie_title'] !!}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div style="background: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};">
                                    <div class="row chapter_title_container d-flex">
                                        <div class="col-12 col-md-6 text-right align-self-center py-5 py-md-0">
                                            <div class=""
                                                style="display: block; float: left; width: 20%;line-height: 0;">
                                                <span
                                                    class="chapter_label font-weight-bold text-uppercase">CHAPITRE</span><br />
                                                <span class="chapter_number display-1">{!! $chapitre['chapitre_no'] !!}</span>
                                            </div>
                                            <h4 class="chapter_title text-left"
                                                style="display: block; float: left; width: 80%; margin-top: 58px;">
                                                {!! $chapitre['chapitre_title'] !!}
                                            </h4>
                                        </div>
                                        <!-- Need to take from XML -->
                                        <div class="col-12 col-md-6"> <img src="{!! $chapitre['chapitre_image']['@attributes']['href'] !!}" alt=""
                                                title="" class="img-fluid"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row objectifs_aprentissage px-4 py-5">
                            <div class="col-12">
                                <h5 class="basic_subtitle mb-5">{!! $chapitre['chapitre_head'] !!}</h5>
                            </div>
                            @php
                                $totCount = count($chapitre['chapitre_objectifs']);
                                $firstCol = ceil($totCount / 2);
                            @endphp
                            <div class="col-md-6">
                                @php
                                    $count = 0;
                                @endphp
                                @foreach ($chapitre['chapitre_objectifs'] as $chap_obj)
                                    @php
                                        $count = $count + 1;
                                        
                                    @endphp

                                    <div class="objectif_counter" style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};">
                                        {!! $count !!}</div>
                                    <div class="objectif">
                                        <h6 class="chapitre_objectif_title font-weight-bold">{!! $chap_obj['chapitre_objectif_title'] !!}</h6>
                                        <span class="chapitre_objectif_txt">{!! $chap_obj['chapitre_objectif_txt'] !!}</span>
                                    </div>
                                    @if ($count == $firstCol)
                            </div>
                            <div class="col-md-6">
                                @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="row mt-4 mise-en-situation">
                            <div class="col-md-12">
                                <div style="background: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};">
                                    <div class="row px-4 py-5">
                                        <div class="col-12">
                                            <h5 class="basic_subtitle mb-4">Mise en situation</h5>
                                            <p>{!! $chapitre['chapitre_situation'] !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row chapter_content px-4 py-5">
                            <div class="col-12">
                                <p>{!! $chapitre['chapitre_txtintro1'] !!}</p>
                                <figure class="figure img-insert-txt col-12 col-md-6 col-lg-5">
                                    <img class="img-fluid" alt="" title="" src="{!! $chapitre['chapitre_section2']['chapitre_txtintro']['@attributes']['href'] !!}">
                                    <figcaption class="text-right">{!! $chapitre['chapitre_imgintro_legende'] !!}</figcaption>
                                </figure>
                                <p>{!! $chapitre['chapitre_txtintro2'] !!}</p>
                            </div>

                            <div class="col-12 chapitre_section">
                                <span class="section_number" style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!}; ">1.1</span>
                                <h5 class="section_title">{!! $chapitre['chapitre_section1']['chapitre_section1_title'] !!}</h5>
                                <p class="section_intro">{!! $chapitre['chapitre_section1']['chapitre_section1_intro'] !!}</p>

                                <h6 class="paragraph_title"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="number">1.1.1</span> {!! $chapitre['chapitre_section1']['chapitre_section1_paragraph1_title'] !!}</h6>
                                <p class="paragraph_intro">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph1_intro'] !!}

                                </p>
                                <h6 class="paragraph_subtitle">
                                    <span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};" class="puce"></span>
                                    {!! $chapitre['chapitre_section1']['chapitre_section1_paragraph1_subtitle_1'] !!}
                                </h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph1_txt_1'] !!}

                                </p>

                                <div class="rappel p-4">
                                    <strong>Rappel</strong><br />{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph1_txt1_rappel'] !!}

                                </div>

                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph1_subtitle_2'] !!}</h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph1_txt_2'] !!}
                                </p>

                                <h6 class="paragraph_title"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="number">1.1.2</span>{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph2_title'] !!}</h6>
                                <p class="paragraph_intro">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph2_intro'] !!}
                                </p>

                                <h6 class="paragraph_title"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="number">1.1.3</span>{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_title'] !!}</h6>

                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_subtitle_1'] !!}</h6>

                                <figure class="figure img-insert-txt col-12 col-md-6 col-lg-5">
                                    <img class="img-fluid" alt="" title="" src="{!!$chapitre['chapitre_section1']['chapitre_section1_paragraph3_sideimg_legend'][0]['@attributes']['href'] !!}">
                                    <figcaption class="text-right">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_sideimg_legend']['1'] !!}</figcaption>
                                </figure>

                                <p class="paragraph_txt">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_txt_1'] !!}
                                </p>

                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_subtitle_2'] !!}</h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_txt_2'] !!}
                                </p>


                                <p class="en-savoir-plus ml-sm-5 ml-1 p-5">
                                    <strong style="color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};">En savoir plus...</strong><br /><br />
                                    {!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_ensavoirplus_1'] !!}
                                </p>

                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_subtitle_3'] !!}</h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_txt_3'] !!}
                                </p>

                                <blockquote class="blockquote">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_citation_1'] !!}

                                </blockquote>

                                <p class="paragraph_txt">{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_txt_4'] !!}

                                </p>

                                <div class="remarque p-3">
                                    <strong class="text-uppercase">Remarque :</strong>{!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_remarque_1'] !!}

                                </div>

                                <p class="paragraph_txt">
                                    {!! $chapitre['chapitre_section1']['chapitre_section1_paragraph3_txt_5'] !!}
                                </p>
                            </div>

                            <div class="col-12 chapitre_section">
                                <span class="section_number"
                                    style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!}; ">1.2</span>
                                <h5 class="section_title">{!! $chapitre['chapitre_section2']['chapitre_section2_title'] !!}</h5>
                                <p class="section_intro">{!! $chapitre['chapitre_section2']['chapitre_section_intro'] !!}
                                </p>


                                <h6 class="paragraph_title"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="number">1.2.1</span>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1_title'] !!}</h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1_intro'] !!}
                                </p>

                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1'] !!}
                                </p>

                                <figure class="text-center">
                                    <img class="img-fluid" alt="" title="" src="{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1_fullimg_legend']['0'] !!}">
                                    <figcaption>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1_fullimg_legend'] !!}
                                    </figcaption>
                                </figure>

                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1_txt_1'] !!}
                                </p>

                                <div class="retenir">
                                    <strong class="text-uppercase pl-4">À retenir :</strong>
                                    <div class="content">
                                        <strong class="text-uppercase">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1_txt1_retenir']['chapitre_section2_paragraph1_txt1_retenir_title'] !!}</strong>
                                        <p>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph1_txt1_retenir']['chapitre_section2_paragraph1_txt1_retenir_txt'] !!}</p>
                                    </div>
                                </div>

                                <h6 class="paragraph_title"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="number">1.2.2</span>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_title'] !!}</h6>

                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_subtitle_1'] !!}</h6>

                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_txt_1'] !!}
                                </p>

                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_subtitle_2'] !!}</h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_txt_2'] !!}

                                </p>
                                <ul>
                                    <li>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_list_1_item_1'] !!}</li>
                                    <li>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_list_1_item_2'] !!}</li>
                                    <li>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_list_1_item_3'] !!}</li>
                                </ul>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_txt_3'] !!}

                                </p>

                                <div class="table-responsive"> 
                                @foreach ($chapitre['chapitre_section2']['chapitre_section2_paragraph2_table_1'] as $chapiter_table) 
                                <strong>{!! $chapiter_table['chapitre_section2_paragraph2_table_1_title'] !!}</strong>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                @foreach ($chapiter_table['thead']['chapitre_section2_paragraph2_table_1_entete'] as $item)
                                                    <th scope="col">{!! $item !!}</th>
                                                @endforeach
                                            </tr>
                                        </thead> 
                                         <tbody>
                                            @foreach ($chapiter_table['tbody'] as $row)
                                                <tr>
                                                    @foreach ($row as $item)
                                                        <td>{!! $item !!}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endforeach
                                </div>

                                {{-- <div class="table-responsive">
                                    <strong>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_table_2']['chapitre_section2_paragraph2_table_2_title'] !!}</strong>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                @foreach ($chapitre['chapitre_section2']['chapitre_section2_paragraph2_table_2']['thead']['chapitre_section2_paragraph2_table_2_entete'] as $item)
                                                    <th scope="col">{!! $item !!}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($chapitre['chapitre_section2']['chapitre_section2_paragraph2_table_2']['tbody'] as $row)
                                                <tr>
                                                    @foreach ($row as $item)
                                                        <td>{!! $item !!}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> --}}
                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_subtitle_3'] !!}</h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_txt_4'] !!}
                                </p>
                                <div class="figure_with_title col-12 col-sm-6 col-md-6 col-lg-5">
                                    <strong>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_sideimg_title'] !!}</strong>
                                    <figure>
                                        <img class="img-fluid" alt="" title="" src="{!!$chapitre['chapitre_section2']['chapitre_section2_paragraph2_sideimg']['@attributes']['href'] !!}">
                                        <figcaption class="text-right">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_sideimg_legend'] !!}</figcaption>
                                    </figure>
                                </div>
                                <p class="paragraph_txt">
                                    Git re corrovitas et, ipsam quiati sum sundantur, occabo. Cum sum que dit
                                    doluptae ped magnam, sus raturio remostiatet venditatur aut vollaborro idesed
                                    Occuptassima periamu sapides sumquianda volupta des alia nonse porrupta
                                    con eos a conserrum abo. Ut ut acerchi tatiorem. Ucipsaper.Acilignimus quuntio.
                                    Namenim fugit litatin nostibusam ducillaccus ellenduciist explaut.
                                    Accus acerfer chillant aut aut quaecti cum ut hilla perianti acit autae sum que
                                    simolor epelique quibusa eruntio ma ari nobis comnimo lorpore iusaped expeliati
                                    sequi sit volut alicaeptiame minctio. Ut ut acerchi tatiorem. Ucipsaper.
                                    Acilignimus quuntio. Namenim fugit litatin nostibusam ducillaccus ellenduciist
                                    explaut et a di blaborr ovidit ende nus doloritatis vent.
                                    Git re corrovitas et, ipsam quiati sum sundantur, occabo. Cum sum que dit
                                    doluptae ped magnam, sus raturio remostiatet venditatur aut vollaborro idesed
                                    Occuptassima periamu sapides sumquianda volupta des alia nonse porrupta
                                    con eos a conserrum abo. Ut ut acerchi tatiorem. Ucipsaper.Acilignimus quuntio.
                                    Namenim fugit litatin nostibusam ducillaccus ellenduciist explaut.
                                </p>
                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_subtitle_4'] !!}</h6>
                                <p class="paragraph_txt">{!! $chapitre['chapitre_section2']['chapitre_section2_paragraph2_txt_5'] !!}

                                </p>


                                <div class="exercice_insert mt-4">
                                    <strong class="exercice_position">Exercice 1.1.3</strong><strong
                                        class="type-qcm">&nbsp;</strong>
                                    <div class="content">
                                        <h6 class="exercie_subtitle"></span>Le titre de l'exercice</h6>
                                        <p>Adi optatiae non consequat repeliq uation nus ea quo bea perum etur,
                                            ea quis accae. Fugia nonestrum quaspero vollaborae prest, ipsam.Busam
                                            erspitiae voluptate quaes nempor autem quate.</p>
                                        <a class="btn" href="./student_exercice_solo.html">Accéder à
                                            l'exercice</a>
                                    </div>
                                </div>

                                <div class="exercice_insert mt-4">
                                    <strong class="exercice_position">Exercice 1.1.3</strong><strong
                                        class="type-sort">&nbsp;</strong>
                                    <div class="content">
                                        <h6 class="exercie_subtitle"></span>Le titre de l'exercice</h6>
                                        <p>Adi optatiae non consequat repeliq uation nus ea quo bea perum etur,
                                            ea quis accae. Fugia nonestrum quaspero vollaborae prest, ipsam.Busam
                                            erspitiae voluptate quaes nempor autem quate.</p>
                                        <a class="btn" href="./student_exercice_solo.html">Accéder à
                                            l'exercice</a>
                                    </div>
                                </div>

                                <div class="exercice_insert mt-4">
                                    <strong class="exercice_position">Exercice 1.1.3</strong><strong
                                        class="type-essay">&nbsp;</strong>
                                    <div class="content">
                                        <h6 class="exercie_subtitle"></span>Le titre de l'exercice</h6>
                                        <p>Adi optatiae non consequat repeliq uation nus ea quo bea perum etur,
                                            ea quis accae. Fugia nonestrum quaspero vollaborae prest, ipsam.Busam
                                            erspitiae voluptate quaes nempor autem quate.</p>
                                        <a class="btn" href="./student_exercice_solo.html">Accéder à
                                            l'exercice</a>
                                    </div>
                                </div>

                                <div class="exercice_insert mt-4">
                                    <strong class="exercice_position">Exercice 1.1.3</strong><strong
                                        class="type-label">&nbsp;</strong>
                                    <div class="content">
                                        <h6 class="exercie_subtitle"></span>Le titre de l'exercice</h6>
                                        <p>Adi optatiae non consequat repeliq uation nus ea quo bea perum etur,
                                            ea quis accae. Fugia nonestrum quaspero vollaborae prest, ipsam.Busam
                                            erspitiae voluptate quaes nempor autem quate.</p>
                                        <a class="btn" href="./student_exercice_solo.html">Accéder à
                                            l'exercice</a>
                                    </div>
                                </div>

                                <div class="exercice_insert mt-4">
                                    <strong class="exercice_position">Exercice 1.1.3</strong><strong
                                        class="type-pairs">&nbsp;</strong>
                                    <div class="content">
                                        <h6 class="exercie_subtitle"></span>Le titre de l'exercice</h6>
                                        <p>Adi optatiae non consequat repeliq uation nus ea quo bea perum etur,
                                            ea quis accae. Fugia nonestrum quaspero vollaborae prest, ipsam.Busam
                                            erspitiae voluptate quaes nempor autem quate.</p>
                                        <a class="btn" href="./student_exercice_solo.html">Accéder à
                                            l'exercice</a>
                                    </div>
                                </div>

                                <div class="exercice_insert mt-4">
                                    <strong class="exercice_position">Exercice 1.1.3</strong><strong
                                        class="type-true_false">&nbsp;</strong>
                                    <div class="content">
                                        <h6 class="exercie_subtitle"></span>Le titre de l'exercice</h6>
                                        <p>Adi optatiae non consequat repeliq uation nus ea quo bea perum etur,
                                            ea quis accae. Fugia nonestrum quaspero vollaborae prest, ipsam.Busam
                                            erspitiae voluptate quaes nempor autem quate.</p>
                                        <a class="btn" href="./student_exercice_solo.html">Accéder à
                                            l'exercice</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row faire_le_point px-4 py-5">
                            <div class="col-12">
                                <h5 class="basic_subtitle mb-5">FAIRE LE POINT</h5>
                                <h6 class="paragraph_subtitle"><span style="background-color: {!! $chapitre['chapitre_colour']['@attributes']['color'] !!};"
                                        class="puce"></span>{!! $chapitre['chapitre_fairelepoint']['chapitre_fairelepoint_title'] !!}</h6>
                                <p>{!! $chapitre['chapitre_fairelepoint']['chapitre_fairelepoint_intro'] !!}</p>
                                <div class="faire_le_point_content">
                                    <ul>
                                        @foreach ($chapitre['chapitre_fairelepoint']['chapitre_fairele_points'] as $item)
                                            <li>{!! $item['subtitle'] !!}
                                                <p>{!! $item['description'] !!}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-12 d-flex">
                                <a href="student_exercice.html" class="mt-2 ml-1 btn btn-primary mx-auto">Complete the
                                    Chapter Test</a>
                            </div>
                        </div>
                        <div class="row d-flex">
                            <div class="col-md-6 mx-auto">
                                <hr>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-6 d-flex">
                                <a href="{{ route('showBook', ['page' => $previousFile, 'bookid' => $book_entity]) }}"
                                    class="mt-2 ml-1 btn btn-primary mx-auto">&lt;&lt;
                                    {{ $previouschapitreflag ? 'Previous Chapter' : 'Table des matières' }}</a>
                            </div>
                            <div class="col-6 d-flex">
                                <a href="{{ route('showBook', ['page' => $nextFile, 'bookid' => $book_entity]) }}"
                                    class="mt-2 ml-1 btn btn-primary mx-auto">
                                    &gt;&gt;{{ $nextchapitreFlag ? 'Next Chapter' : 'Bibliographie' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
