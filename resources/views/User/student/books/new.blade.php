<div class="alert dimension alert-danger" style="display: none;"><strong></strong>.</div>
@if(session()->has('chapstatus.status'))
    <div class="alert alert-{{ session('chapstatus.status') }}">
    {!! session('chapstatus.message') !!}
    </div>
@endif
<a data-toggle="collapse" href="#reading_nav" class="btn-open-options btn btn-primary button_expand_mobile" aria-expanded="false"><span class="fa fa-indent fa-w-16"></span></a>
    <div class="parent_header_reading">
        <div class="header_reading" style="background-color: #dcdcdc!important; z-index: 10; width: 100%;">
            <div class="row navbar_uncollapsed_desktop">
                <div class="col-md-8">
                    <h1 class="ml-2 mt-2 title_chapter_nav">Chapter : {{ isset($data['chapter_data']->title) ? $data['chapter_data']->title : ''  }}</h1>
                </div>
                <div class="col-md-2">
                    <a  href="{{ route('admin.title.manage_book_note',['id'=>$data['book']->id,'type'=>'add']) }}" class="btn btn-primary float-right m-2" aria-expanded="true">{{ __('messages.add_chapter')  }}</a>
                </div>
                <div class="col-md-2">
                    <a data-toggle="collapse" href="#reading_nav" class="btn btn-primary float-right m-2" aria-expanded="true">{{__('menus.book_navigation')}}</a>
                </div>
            </div>

            <div class="collapse row" id="reading_nav" style="">
                <div class="col-md-12">
                    <hr class="m-0">
                </div>
                <div class="col-md-8 pr-0 menu_separator">

                    <div class="card-body pr-0">

                        <h2 class="card-title">{{__('messages.table_of_contents')}}</h2>
                        <div class="scroll-area-sm">
                            <div class="scrollbar-container ps--active-y ps">
                                <ul class="book_index p-0">
                                    @foreach ($data['chapters'] as $k=>$chap)
                                     @if($chap->id == $data['chapter_id'])
                                     <li class="active_chapter pl-3">Chapter {{$k+1}}: {{ $chap->title}}</li>
                                     @else
                                     <li><a href="{{ route('admin.title.manage_book_note',['id'=>$data['book']->id,'type'=>'edit','chapter'=>$chap->id]) }}">Chapter {{$k+1}}: {{ $chap->title}}</a></li>
                                     @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="position-relative col-12">
                            <p class="d-inline-block  pl-2">{{__('messages.font_size')}} </p>
                            <a href="javascript:void(0);" class="manip bigger_font">A<sup>+</sup></a>
                            <a href="javascript:void(0);" class="manip lower_font">A<sup>-</sup></a>
                            <input type="hidden" name="selected_font_size" value="{{ $pref_setts->selected_font_size }}">
                        </div>
                    </div>
                    <div class="position-relative mb-1 row">
                        <div class="col-9">
                            <label class="form-check-label pl-2" for="note_editeur">{{__('messages.view_editors_note')}}</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_editeur" class="note_sett" name="view_editor_note" value="{{ ($pref_setts->view_editor_note == 1)?1:2 }}" @if($pref_setts->view_editor_note == 1) checked @endif checked data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                        </div>
                    </div>
                    <div class="position-relative mb-1 row">
                        <div class="col-9">
                            <label class="form-check-label  pl-2" for="note_teacher">{{__('messages.view_teachers_note')}}</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_teacher" class="note_sett" name="view_teacher_note" value="{{ ($pref_setts->view_teacher_note == 1)?1:2 }}" @if($pref_setts->view_teacher_note == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                        </div>
                    </div>
                    <div class="position-relative mb-1 row">
                        <div class="col-9">
                            <label class="form-check-label  pl-2" for="note_perso">{{__('messages.view_my_note')}}</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_perso" class="note_sett" name="view_student_note" value="{{ ($pref_setts->view_student_note == 1)?1:2 }}" @if($pref_setts->view_student_note == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                        </div>
                    </div>
                    <div class="position-relative mb-3 row">
                        <div class="col-9">
                            <label class="form-check-label  pl-2" for="note_add">{{__('messages.enable_note_editing')}}</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" id="note_add" class="note_sett" name="enable_note_edit" value="{{ ($pref_setts->enable_note_edit == 1)?1:2 }}" @if($pref_setts->enable_note_edit == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" id="reading_percentage">
                <div class="bar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;" role="progressbar"></div>
            </div>

        </div>
    </div>
  
  
    <div class="tab-content size_temoin">
        <div class="tab-pane tabs-animation fade show active">
            <div class="main-card mb-3 card">
                <div class="card-body">
                     <form class="" action="" method="POST" data-url="{{ route('admin.title.add_upnotes',['id'=> $data['book']->id]) }}" id="addnewNote" enctype="multipart/form-data"  data-parsley-validate>
                        <input type="hidden" name="book_entity" value="{{ $data['book']->id }}" readonly/>
                        <input type="hidden" name="chapter_entity" value="{{ $data['chapter_data']->id }}" readonly/>
                        <div class="position-relative form-group">
                        <h5 class="req">{{__('messages.chapter_name')}}</h5>
                        <input type="text" name="chapter_name" class="form-control" value="{{ $data['chapter_data']->title }}" data-parsley-required data-parsley-error-message="Chapter name is required">
                        </div>
                    <h2 class="title_chapter">Chapter : {{ isset($data['chapter_data']->title) ? $data['chapter_data']->title : ''  }}</h2>
                      @php $title=null; $subtitle=null; $note=null; $fileimg=null; $filecap=null; @endphp
                    @foreach ($data['chapter_blocks'] as $note_key=>$note_blocks)
                      @foreach ($note_blocks as $block)
                      @if($block->block_type == 1) <!--- 1 for title block--->
                      <h3>{{ ucfirst($block->content) }}</h3>@php $title=$block->content  @endphp
                      @endif
                      @if($block->block_type == 2) <!--- 2 for subtitle block--->
                      <h4>{{ ucfirst($block->content) }}</h4> @php $subtitle=$block->content  @endphp
                      @endif
                      @if($block->block_type == 3) <!--- 3 for note or description block--->
                      {!! $block->content !!} @php $note=$block->content  @endphp
                      @endif
                      @if($block->block_type == 4) <!--- 4 for image file block--->
                      <figure class="text-center">
                          <img src="{{ $block->content }}" title alt> @php  $fileimg=$block->content; $filecap=$block->metadatas;  @endphp
                          <figcaption>{{ $block->metadatas }}</figcaption>
                      </figure>
                      @endif
                      @endforeach
                      <a href="javascript:void(0);" class="btn-open-options btn btn-primary float-right addup_note"><span class="fa fa-plus fa-w-16"></span> {{__('menus.edit_note')}}</a>
                            <div class="collapse row">
                                <div class="col-12">
                                <div class="main-card note-card mb-3 card"><div class="card-body"><h4 class="covrd">Note {{$note_key}}</h4>
                                <input type="hidden" name="note_element[]" value="1" readonly/>
                              <div class="position-relative form-group">
                                 <h5>{{__('messages.title')}}</h5>
                                <input type="text" name="title[]" value="{{ $title }}" class="form-control">
                             </div>
                             <div class="position-relative form-group">
                               <h5>{{__('messages.subtitle')}}</h5>
                               <input type="text" name="subtitle[]" value="{{ $subtitle }}" class="form-control">
                            </div>
                            <div class="position-relative form-group">
                              <h5>{{__('messages.add_a_note')}}</h5>
                              <textarea class="textarea_block" name="note[]">{{ $note }}</textarea>
                              </div>
                           <div class="position-relative form-group">
                              <h5>{{__('messages.add_a_file')}}</h5>
                              <input type="hidden" name="contain_file[]" value="{{ ($fileimg) ? '1':'0' }}" readonly> <!--- check input file contains file or not --->
                              <span class="{{ ($fileimg) ? 'spns pe-7s-close-circle active':'spns pe-7s-close-circle' }}"></span>
                              <input name="notefile[]" class="exampleFile" accept="image/*" type="file" class="form-control-file mt-2">
                              <div class="imgPreview"></div>
                              @isset($fileimg) <img src="{{ $fileimg }}" style="width:100%"/>@endisset
                              <input type="text" class="imgCaption" name="filecaption[]" style="{{ ($fileimg) ? '':'display:none' }}" value="{{ $filecap }}" placeholder="Image Caption"/>
                            </div>
                            </div></div>
                             </div>
                              </div>
                              @php $title=null; $subtitle=null; $note=null; $fileimg=null; $filecap=null; @endphp
                   @endforeach

                        <div class="collapse row show">
                          <div class="col-12" id="notes_container">
                          </div>
                        </div>

                    <div class="row">
                        <div class="col-3 d-flex"></div>
                        <div class="col-3 d-flex"></div>
                        <div class="col-3 d-flex">
                         <a href="javascript:void(0);" class="mt-2 btn btn-primary float-right add_another_note">Add Another Note</a>
                        </div>
                        <div class="col-3 d-flex">
                        <button type="submit" class="mt-2 btn btn-primary float-right">{{__('menus.complete_chapter')}}</button>
                      </div>
                    </div>
                     </form>
                    <div class="row d-flex">
                        <div class="col-md-6 mx-auto">
                            <hr>
                        </div>
                    </div>
                    <div class="row ">
                        {{-- <div class="col-6 d-flex">
                            <a href="#" class="mt-2 ml-1 btn btn-primary mx-auto">&lt;&lt; {{__('menus.previos_chapter')}}</a>
                        </div> --}}
                        {{-- <div class="col-6 d-flex">
                            <a href="#" class="mt-2 ml-1 btn btn-primary mx-auto">{{__('menus.next_chapter')}} &gt;&gt;</a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
