@extends('layouts.Template.app')
@section('pageContant')
<div class="app-main__inner">
				
	<a data-toggle="collapse" href="#reading_nav" class="btn-open-options btn btn-primary button_expand_mobile" aria-expanded="false"><span class="fa fa-indent fa-w-16"></span></a>
	
	<div class="parent_header_reading">
		<div class="header_reading">
			<div class="row navbar_uncollapsed_desktop">
				<div class="col-md-9">
					<h1 class="ml-2 mt-2 title_chapter_nav">Bibliographie</h1>
				</div>
				<div class="col-md-3">
					<a data-toggle="collapse" href="#reading_nav" class="btn btn-primary float-right m-2 book_navigation" aria-expanded="true"><span class="sign">+</span> Book Navigation</a>
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
							<label class="form-check-label pl-2" for="note_editeur">View Editor's Notes</label>
						</div>
						<div class="col-2">
							<input type="checkbox" id="note_editeur" checked data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
						</div>
					</div>
					<div class="position-relative mb-1 row">
						<div class="col-9 col-sm-8 col-xl-9">
							<label class="form-check-label  pl-2" for="note_teacher">View Teacher's Notes</label>
						</div>
						<div class="col-2">
							<input type="checkbox" id="note_teacher" data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
						</div>
					</div>
					<div class="position-relative mb-1 row">
						<div class="col-9 col-sm-8 col-xl-9">
							<label class="form-check-label  pl-2" for="note_perso">View My Notes</label>
						</div>
						<div class="col-2">
							<input type="checkbox" id="note_perso" data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
						</div>										
					</div>
					<div class="position-relative mb-3 row">
						<div class="col-9 col-sm-8 col-xl-9">
							<label class="form-check-label  pl-2" for="note_add">Enable Note Editing</label>
						</div>
						<div class="col-2">
							<input type="checkbox" id="note_add" data-toggle="toggle" data-size="small" data-onstyle="primary" data-offstyle="light">
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
				<!-- the class here will change according to the part of the book : Introcution, table_of_content, chapter ...-->
				<div class=" pb-4 table_of_content">
					<div class="row">
						<div class="col-md-12 px-4">
							<!-- The table of content is generated from the chapters uploaded through the xml
							The colors also come from the XML with the field : -->
							<!-- The title is always the same -->
							<h2 class="standard_main_title">Bibliographie</h2>
							<img class="img-fluid" src="./assets/book/transp_for_size.png" />
						</div>	
					</div>

					<div class="row">
						@foreach ($data['biblio'] as $item)
							<div class="col-sm-6 col-lg-4 px-4 biblio_reference">
							<strong>{!! $item['biblio_titre'] !!}</strong>
							<span>{!! $item['biblio_date'] !!}</span>
							<p class="px-4">{!! $item['biblio_txt'] !!}</p>
						</div>	
						@endforeach
					</div>

					<div class="row d-flex">
						<div class="col-md-6 mx-auto">
							<hr>
						</div>
					</div>
					<div class="row chapter_nav">
						<div class="col-6 d-flex">
							<a href="{{route('showBook',["page" => $lastchapitre, "bookid" => $book_entity])}}" class="mt-2 ml-1 btn btn-primary mx-auto">&lt;&lt; Previous Chapter</a>
						</div>
						<div class="col-6 d-flex">
							<a href="{{route('showBook',["page" => $iconography, "bookid" => $book_entity])}}" class="mt-2 ml-1 btn btn-primary mx-auto">Iconographie &gt;&gt;</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection