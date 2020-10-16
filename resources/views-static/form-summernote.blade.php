@extends('layouts.master')

@section('css')
<!-- Summernote css -->
<link href="{{ URL::asset('assets/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet" />

@endsection

@section('breadcrumb')
<!-- Page-Title -->
<div class="page-title-box">
                            <div class="btn-group pull-right">
                                <ol class="breadcrumb hide-phone p-0 m-0">
                                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                                    <li class="breadcrumb-item active">Summernote</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Summernote</h4>
                        </div>
@endsection

@section('content')
                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Examples</h4>
                                <p class="text-muted m-b-30 font-14">Super simple wysiwyg editor on bootstrap</p>
                                <div class="summernote">Hello Summernote</div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->    
@endsection

@section('script')
<!--Summernote js-->
<script src="{{ URL::asset('assets/plugins/summernote/summernote-bs4.js') }}"></script>
@endsection

@section('script-bottom')
<script>
            jQuery(document).ready(function(){
                $('.summernote').summernote({
                    height: 300,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: true                 // set focus to editable area after initializing summernote
                });
            });
        </script>
@endsection

