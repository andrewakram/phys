@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
          type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box" style="display: inline">
                <div class="btn-group pull-left">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">اختبارات المجموعات</li>
                    </ol>
                </div>
                <h4 class=" p-2">اختبارات المجموعات
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"><i
                            class="icon-plus"></i>
                        اضافة اختبار لمجموعة
                    </button>
                </h4>
                <form method="post" action="{{route('searchGroupExams')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <select name="group_id" class="btn btn-default border-dark form-control b-light digits">
                                <option value="" selected disabled>اختر المجموعة </option>
                                @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}} / {{$group->group_num}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-success col-lg-6" >
                                بحث
                            </button>
                        </div>
                    </div>
                </form>
                <br>
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

                    <h4 class="mt-0 header-title">اختبارات المجموعات

                    </h4>
                    {{--                    <p class="text-muted m-b-30 font-14">Add <code>.table-bordered</code> for--}}
                    {{--                        borders on all sides of the table and cells.--}}
                    {{--                    </p>--}}

                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table  table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>المجموعة</th>
                            <th>الامتحان</th>
                            <th> البدء</th>
                            <th> النتهاء</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                            <tr>
                                <th scope="row">{{$result->id}}</th>
                                <td>
                                    @if($result->group)
                                        <span>#: </span> <b>{{$result->group->id}}</b> <br>
                                        <span>رقم المجموعة: </span> <b>{{$result->group->group_num}}</b> <br>
                                        <span>اسم المجموعة: </span> <b>{{$result->group->name}}</b> <br>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($result->exam)
                                        <span>#: </span> <b>{{$result->exam->id}}</b> <br>
                                        <span>رقم الامتحان: </span> <b>{{$result->exam->exam_num}}</b> <br>
                                        <span>اسم الامتحان: </span> <b>{{$result->exam->name}}</b> <br>
                                        <span>المدة: </span> <b>{{$result->exam->duration}}</b> <br>
                                        <span> الدرجة: </span> <b>{{$result->exam->degree}}</b> <br>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{$result->start}}</td>
                                <td>{{$result->end}}</td>


                                <td>
                                    <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$result->id}}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button title="حذف" type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_{{$result->id}}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>


                            <div class="modal fade" id="edit_{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات الاختبار لمجموعة</h5>
{{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                                <span aria-hidden="true">&times;</span>--}}
{{--                                            </button>--}}
                                        </div>
                                        <form class="form-horizontal" method="post" action="{{route('editGroupExam')}}" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <input type="hidden" name="model_id" value="{{$result->id}}">

                                                <div class="form-group">
                                                    <label class="col-lg-12 control-label">وقت البدء</label>
                                                    <div class="col-lg-12">
                                                        <input type="datetime-local" class="form-control" name="start" value="{{$result->start}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" />
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-lg-12 control-label">وقت الانتهاء</label>
                                                    <div class="col-lg-12">
                                                        <input type="datetime-local" class="form-control" name="end" value="{{$result->end}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" />
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right" for="textinput">المحموعات</label>
                                                    <div class="col-lg-12">
                                                        <select name="group_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" >
                                                            <option value="" disabled>اختر المجموعة </option>
                                                            @foreach($groups as $group)
                                                                <option value="{{$group->id}}" {{$group->id == $result->group->id ? "selected" : ""}}>{{$group->name}} / {{$group->group_num}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right" for="textinput">الامتحانات</label>
                                                    <div class="col-lg-12">
                                                        <select name="exam_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" >
                                                            <option value="" selected disabled>اختر الامتحان</option>
                                                            @foreach($exams as $exam)
                                                                <option value="{{$exam->id}}" {{$exam->id == $result->exam->id ? "selected" : ""}}>{{$exam->name}} / {{$exam->exam_num}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="reset" class="btn btn-dark mr-1" data-dismiss="modal">اغلاق</button>
                                                <button class="btn btn-primary mr-1" type="submit">تعديل</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{--///////////////////////////--}}
                            <div class="modal animated fadeIn" id="delete_{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header btn-danger">
                                            <h5 class="modal-title" id="exampleModalLabel">حذف الاختبار لمجموعة</h5>
{{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                                <span aria-hidden="true">&times;</span>--}}
{{--                                            </button>--}}
                                        </div>
                                        <form method="post" action="{{route('deleteGroupExam')}}" class="buttons">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <h4>هل انت متأكد ؟</h4>
                                                <h6>
                                                    انت علي وشك حذف الاختبار لمجموعة
{{--                                                    <br>رقم الاختبار لمجموعة: ({{$result->group_num}})--}}
{{--                                                    <br>الاسم: ({{$result->name}})--}}

                                                </h6>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="model_id" value="{{$result->id}}">
                                                <button class="btn btn-dark" type="button" data-dismiss="modal">اغلاق</button>
                                                <button type="submit" class="btn btn-primary">تأكيد</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>{{$results->links()}}
                    </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة اختبار لمجموعة</h5>
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('addGroupExam')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-lg-12 control-label">وقت البدء</label>
                            <div class="col-lg-12">
                                <input type="datetime-local" class="form-control" name="start" required >
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-12 control-label">وقت الانتهاء</label>
                            <div class="col-lg-12">
                                <input type="datetime-local" class="form-control" name="end" required >
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">المجموعات</label>
                            <div class="col-lg-12">
                                <select name="group_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" >
                                    <option value="" selected disabled>اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}} / {{$group->group_num}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الامتحانات</label>
                            <div class="col-lg-12">
                                <select name="exam_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" >
                                    <option value="" selected disabled>اختر الامتحان</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exam->id}}">{{$exam->name}} / {{$exam->exam_num}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-dark mr-1" data-dismiss="modal">اغلاق</button>
                        <button class="btn btn-primary mr-1">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
@endsection

