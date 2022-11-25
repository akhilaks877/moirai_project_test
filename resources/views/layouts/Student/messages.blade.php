<div class="row clearfix">
    <div class="col-lg-12">
      @if(Session::has('success'))
       <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
          <i class="fa fa-check-circle"></i>&nbsp;{{ Session::get('success') }}
       </div>
       @endif
       @if($errors->any())<!--error messages-->
       <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
          @foreach ($errors->all() as $error)
          <i class="fa fa-info-circle"></i>&nbsp;{{ $error }}<br>
          @endforeach
       </div>
       @endif

    </div>
 </div>
