@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group pull-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item active">Basic Tables</li>
                    </ol>
                </div>
                <h4 class="page-title">Users</h4>
            </div>
        </div>
    </div>
    <!-- end page title end breadcrumb -->
@endsection

@section('content')
    <div class="row">


        <div class="col-lg-12">
            <div class="card m-b-20">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Students</h4>
{{--                    <p class="text-muted m-b-30 font-14">Add <code>.table-bordered</code> for--}}
{{--                        borders on all sides of the table and cells.--}}
{{--                    </p>--}}

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>phone</th>
                            <th>group</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                            <tr>
                                <th scope="row">{{$result->id}}</th>
                                <td>{{$result->name}}</td>
                                <td>{{$result->phone}}</td>
                                <td>
                                    @if($result->group)
                                        <span>#: </span> <b>{{$result->group->id}}</b> <br>
                                        <span>group number: </span> <b>{{$result->group->group_num}}</b> <br>
                                        <span>group name: </span> <b>{{$result->group->name}}</b> <br>
{{--                                        {{$result->group->stage_id}}--}}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->



@endsection

@section('script')
@endsection

