    <div class="row">
      @foreach ($data['book_lists'] as $book)
         <div class="col-md-3" style="padding: 10px 20px;">
            <div class="form-row cover-bg" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),url({{ asset('storage/ebooks/book_'.$book->id.'/cover_image/'.$book->cover_image) }});">
                <div class="col-md-12 text-center">
                    <a href="{{ route('admin.title.edit_book_detail',["id" => $book->id]) }}" class="mb-2 mr-2 btn btn-light">
                        Bibliographic Data
                    </a>
                </div>
                <div class="col-md-12 text-center">
                    <a href="{{ route('admin.title.manage_book_note',["id" => $book->id]) }}" class="mb-2 mr-2 btn btn-light">
                        Notes
                    </a>
                </div>
                <div class="col-md-12 text-center">
                    <a href="{{ route('admin.title.manage_book_exercise',["id" => $book->id]) }}" class="mb-2 mr-2 btn btn-light">
                        Exercises
                    </a>
                </div>
            </div>
            <br />
            <div class="text-center">
             <h4>{{ $book->title }}</h4>
            </div>
          </div>
      @endforeach
    </div>

    <div class="row">
        <div class="col-lg-12">
             {{ $data['book_lists']->links() }}
            {{-- <nav class="d-flex" aria-label="Page navigation example">
                <ul class="pagination mx-auto">
                    <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                    <li class="page-item"><a href="javascript:void(0);" class="page-link">1</a></li>
                    <li class="page-item active"><a href="javascript:void(0);" class="page-link">2</a></li>
                    <li class="page-item"><a href="javascript:void(0);" class="page-link">3</a></li>
                    <li class="page-item"><a href="javascript:void(0);" class="page-link">4</a></li>
                    <li class="page-item"><a href="javascript:void(0);" class="page-link">5</a></li>
                    <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                </ul>
            </nav> --}}
        </div>
    </div>
