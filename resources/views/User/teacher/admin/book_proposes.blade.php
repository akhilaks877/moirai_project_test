<div class="row mb-3">
    @foreach ($data['book_lists'] as $book)
    <div class="col-6 col-sm-4 col-md-3 pl-3 pr-3 pt-4 book_thumbnail d-flex flex-column">
        <div class="card card_book_list p-2">
            <img src="{{ asset('storage/ebooks/book_'.$book->id.'/cover_image/'.$book->cover_image) }}" title="cover of book title 1" alt="cover of book title 1" class="img-fluid" />
           <div class="links_list text-center mb-3" data-pbook="{{$book->id}}">
            <a href="javascript:void(0);" class="mb-2 btn btn-light make_suggest {{ ($book->is_attached) ? 'disabled' : ''}}">{{ ($book->is_attached) ? 'Already Suggested' : 'Suggest this book'}}</a>
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
