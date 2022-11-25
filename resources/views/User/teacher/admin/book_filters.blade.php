<div class="row mb-3">
    @foreach ($data['book_lists'] as $book)
    <div class="col-6 col-sm-4 col-md-3 pl-3 pr-3 pt-4 book_thumbnail d-flex flex-column">
        <div class="card card_book_list p-2">
            <img src="{{ asset('storage/ebooks/book_'.$book->id.'/cover_image/'.$book->cover_image) }}" title="cover of book title 1" alt="cover of book title 1" class="img-fluid" />
            <div class="links_list text-center mb-3">
                <a href="{{ route('teacher.title.reading_book',['title'=>$book->id]) }}" class="mb-2 btn btn-light">Lire le livre</a><br />
                <a href="{{ route('teacher.title.show',['title'=>$book->id]) }}" class="mb-2 btn btn-light">Détail du livre</a>
            </div>
            <div class="text-center mt-auto pt-2 booklink">
                <a href="{{ route('teacher.title.reading_book',['title'=>$book->id]) }}"><h4>{{ $book->title }}</h4></a>
                <a href="{{ route('teacher.title.reading_book',['title'=>$book->id]) }}" class="subtitle"><h5>{{ $book->subtitle }}</h5></a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <div class="col-lg-12">
        {{ $data['book_lists']->links() }}
    </div>
</div>

