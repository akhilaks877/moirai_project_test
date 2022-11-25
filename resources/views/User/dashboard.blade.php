@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="pe-7s-bookmarks icon-gradient bg-mean-fruit">
                    </span>
                </div>
                <div>
                    <h4 class="page-title">Welcome to the administrative platform for Moiraï Publishing</h4>
                    <div class="page-title-subheading">
                        This platform is intended for Moiraï Publishing administrators and allows them to:
                        <ul>
                            <li>Create new books on the reading platform</li>
                            <li>Edit existing content</li>
                            <li>Enrich books with exclusive web content</li>
                            <li>Manage users</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
});
</script>
@stop
