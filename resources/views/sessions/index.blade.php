@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box" style="display: inline">
                <div class="btn-group pull-left">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الحصص</li>
                    </ol>
                </div>
                <h4 class="p-2">الحصص
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"><i
                            class="icon-plus"></i>
                        اضافة حصة
                    </button>
                </h4>
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

                    <h4 class="mt-0 header-title">الحصص

                    </h4>
                    {{--                    <p class="text-muted m-b-30 font-14">Add <code>.table-bordered</code> for--}}
                    {{--                        borders on all sides of the table and cells.--}}
                    {{--                    </p>--}}

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>السعر</th>
                            <th>المرحلة</th>
{{--                            <th>البدء</th>--}}
{{--                            <th>الانتهاء</th>--}}
                            <th>الفيديو الاول</th>
                            <th>الفيديو التاني</th>
                            <th>الامتحان الاول</th>
                            <th>الامتحان التاني</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                            <tr>
                                <th scope="row">{{$result->id}}</th>
                                <td>{{$result->name}}</td>
                                <td>{{$result->price}}</td>
                                <td>{{$result->stage->id}} - {{$result->stage->name}}</td>
{{--                                <td>{{$result->from}}</td>--}}
{{--                                <td>{{$result->to}}</td>--}}

                                @if(sizeof($result->session_videos) > 0)
                                    @foreach($result->session_videos as $session_video)
                                    <td>
                                        <a href="{{$session_video->video->link}}" target="_blank">{{$session_video->video->name}}</a>
                                        <br><small>{{$session_video->video->description}}</small>
                                    </td>
                                    @endforeach
                                @else
                                    <td>-</td>
                                    <td>-</td>
                                @endif
                                @if(sizeof($result->session_tests) > 0)
                                    @foreach($result->session_tests as $session_test)
                                        <td>
                                            {{$session_test->test->id}} - {{$session_test->test->name}}
                                        </td>
                                    @endforeach
                                @else
                                    <td>-</td>
                                    <td>-</td>
                                @endif
                                <td>
                                    <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#edit_{{$result->id}}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button title="حذف" type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#delete_{{$result->id}}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="edit_{{$result->id}}" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات المرحلة
                                                دراسية</h5>
                                            {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                            {{--                                                <span aria-hidden="true">&times;</span>--}}
                                            {{--                                            </button>--}}
                                        </div>
                                        <form class="form-horizontal" method="post" action="{{route('editSession')}}"
                                              enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <input type="hidden" name="model_id" value="{{$result->id}}">


                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">الاسم </label>
                                                    <div class="col-lg-12">
                                                        <input id="name" name="name" type="text"
                                                               value="{{$result->name}}" placeholder="الاسم "
                                                               class="form-control btn-square" required
                                                               oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">السعر </label>
                                                    <div class="col-lg-12">
                                                        <input id="name" name="price" type="text"
                                                               value="{{$result->price}}" placeholder="السعر "
                                                               class="form-control btn-square" required
                                                               oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">المراحل الدراسية</label>
                                                    <div class="col-lg-12">
                                                        <select name="stage_id"
                                                                class="btn form-control border-dark digits" required
                                                                oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            <option value="" disabled>اختر المرحلة الدراسية
                                                            </option>
                                                            @foreach($stages as $stage)
                                                                <option
                                                                    value="{{$stage->id}}" {{$stage->id == $result->stage->id ? "selected" : ""}}>{{$stage->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">الفيديو الاول</label>
                                                    <div class="col-lg-12">
                                                        <select name="video_id1"
                                                                class="btn form-control border-dark digits" required
                                                                oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            <option value="" disabled>اختر الفيديو
                                                            </option>
                                                            @foreach($videos as $video)
                                                                <option value="{{$video->id}}"  @if(isset($result->session_videos[0])) {{$video->id == $result->session_videos[0]->video_id ? "selected" : ""}} @endif>{{$video->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">الفيديو التاني</label>
                                                    <div class="col-lg-12">
                                                        <select name="video_id2"
                                                                class="btn form-control border-dark digits" required
                                                                oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            <option value="" disabled>اختر الفيديو
                                                            </option>
                                                            @foreach($videos as $video)
                                                                <option
                                                                    value="{{$video->id}}" @if(isset($result->session_videos[0])) {{$video->id == $result->session_videos[1]->video_id ? "selected" : ""}} @endif>{{$video->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">الامتحان الاول</label>
                                                    <div class="col-lg-12">
                                                        <select name="test_id1"
                                                                class="btn form-control border-dark digits" required
                                                                oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            <option value="" disabled>اختر الامتحان
                                                            </option>
                                                            @foreach($tests as $test)
                                                                <option
                                                                    value="{{$test->id}}" {{$test->id == $result->session_tests[0]->test_id ? "selected" : ""}}>{{$test->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">الامتحان التاني</label>
                                                    <div class="col-lg-12">
                                                        <select name="test_id2"
                                                                class="btn form-control border-dark digits" required
                                                                oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            <option value="" disabled>اختر الامتحان
                                                            </option>
                                                            @foreach($tests as $test)
                                                                <option
                                                                    value="{{$test->id}}" {{$test->id == $result->session_tests[1]->test_id ? "selected" : ""}}>{{$test->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="reset" class="btn btn-dark mr-1" data-dismiss="modal">
                                                    اغلاق
                                                </button>
                                                <button class="btn btn-primary mr-1" type="submit">تعديل</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{--///////////////////////////--}}
                            <div class="modal animated fadeIn" id="delete_{{$result->id}}" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header btn-danger">
                                            <h5 class="modal-title" id="exampleModalLabel">حذف الحصة</h5>
                                            {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                            {{--                                                <span aria-hidden="true">&times;</span>--}}
                                            {{--                                            </button>--}}
                                        </div>
                                        <form method="post" action="{{route('deleteSession')}}" class="buttons">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <h4>هل انت متأكد ؟</h4>
                                                <h6>
                                                    انت علي وشك حذف الحصة
                                                    <br>الاسم: ({{$result->name}})
                                                </h6>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="model_id" value="{{$result->id}}">
                                                <button class="btn btn-dark" type="button" data-dismiss="modal">اغلاق
                                                </button>
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
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة مرحلة دراسية</h5>
                    {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">--}}
                    {{--                        <span aria-hidden="true">&times;</span>--}}
                    {{--                    </button>--}}
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post"
                      action="{{route('addSession')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم </label>
                            <div class="col-lg-12">
                                <input id="name" name="name" type="text" placeholder="الاسم "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">السعر </label>
                            <div class="col-lg-12">
                                <input id="name" name="price" type="text"
                                        placeholder="السعر "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">المراحل الدراسية</label>
                            <div class="col-lg-12">
                                <select name="stage_id"
                                        class="btn form-control b-light digits" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" disabled>اختر المرحلة الدراسية
                                    </option>
                                    @foreach($stages as $stage)
                                        <option
                                            value="{{$stage->id}}" {{$stage->id == $result->stage->id ? "selected" : ""}}>{{$stage->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">الفيديو الاول</label>
                            <div class="col-lg-12">
                                <select name="video_id1"
                                        class="btn form-control b-light digits" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" disabled>اختر الفيديو
                                    </option>
                                    @foreach($videos as $video)
                                        <option
                                            value="{{$video->id}}" >{{$video->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">الفيديو التاني</label>
                            <div class="col-lg-12">
                                <select name="video_id2"
                                        class="btn form-control b-light digits" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" disabled>اختر الفيديو
                                    </option>
                                    @foreach($videos as $video)
                                        <option
                                            value="{{$video->id}}" >{{$video->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">الامتحان الاول</label>
                            <div class="col-lg-12">
                                <select name="test_id1"
                                        class="btn form-control b-light digits" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" disabled>اختر الامتحان
                                    </option>
                                    @foreach($tests as $test)
                                        <option
                                            value="{{$test->id}}" >{{$test->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">الامتحان التاني</label>
                            <div class="col-lg-12">
                                <select name="test_id2"
                                        class="btn form-control b-light digits" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" disabled>اختر الامتحان
                                    </option>
                                    @foreach($tests as $test)
                                        <option
                                            value="{{$test->id}}" >{{$test->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                </div>
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

