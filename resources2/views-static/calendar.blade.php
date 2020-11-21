@extends('layouts.master')

@section('css')
<!--calendar css-->
<link href="{{ URL::asset('assets/plugins/fullcalendar/css/fullcalendar.min.css') }}" rel="stylesheet" />
@endsection

@section('breadcrumb')
 <!-- Page-Title -->
 <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="btn-group pull-right">
                                <ol class="breadcrumb hide-phone p-0 m-0">
                                    <li class="breadcrumb-item"><a href="#">Admiria</a></li>
                                    <li class="breadcrumb-item active">Calendar</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Calendar</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title end breadcrumb -->
@endsection


@section('content')
           <div class="row">
                    <div class="col-12">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <div id='calendar'></div>

                                <div style='clear:both'></div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/moment/moment.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fullcalendar/js/fullcalendar.min.js') }}"></script>
<script src="{{ URL::asset('assets/pages/calendar-init.js') }}"></script>
@endsection