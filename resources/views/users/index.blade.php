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
                        <li class="breadcrumb-item active">الطلاب</li>
                    </ol>
                </div>
                <h4 class="page-title p-2">الطلاب
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"><i
                            class="icon-plus"></i>
                        اضافة طالب
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

                    <h4 class="mt-0 header-title">الطلاب

                    </h4>
                    {{--                    <p class="text-muted m-b-30 font-14">Add <code>.table-bordered</code> for--}}
                    {{--                        borders on all sides of the table and cells.--}}
                    {{--                    </p>--}}

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الموبايل</th>
                            <th>المجموعة</th>
                            <th>الاختبارات</th>
                            <th>الحالة</th>
                            <th>العمليات</th>
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
                                        <span>رقم المجموعة: </span> <b>{{$result->group->group_num}}</b> <br>
                                        <span>اسم المجموعة: </span> <b>{{$result->group->name}}</b> <br>
                                        {{--                                        {{$result->group->stage_id}}--}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($result->exams_passed)
                                        <b>{{sizeof($result->exams_passed)}}</b>
                                    @else
                                        <b>0</b>
                                    @endif
                                </td>
                                <td>
                                    {{--                                        <a href="{{route('editCountryStatus',$c->id)}}">--}}
                                    <div class=" col p-0">
                                        <div class="media">
                                            <div class="media-body  icon-state switch-outline">
                                                <label class="switch">
                                                    <input type="checkbox" id="partner-status"
                                                           user_id="{{$result->id}}"
                                                           @if($result->active == 1)  checked @endif><span
                                                        class="switch-state bg-success"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                        </a>--}}
                                </td>
                                <td>
                                    <button title="عرض نتائج الامتحانات" type="button" class="btn btn-primary"
                                            data-toggle="modal" data-target="#examResults{{$result->id}}">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    {{--==answer==--}}
                                    <div class="modal fade " id="examResults{{$result->id}}" tabindex="-1"
                                         role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">

                                            <div class="modal-content p-3">

                                                <div class="modal-header ">
                                                    <div class="row modal-title">
                                                        <div class="col-lg-12">
                                                            <h4>عرض نتائج الاختبارات</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class=" ">
                                                @if(sizeof($result->exams_passed) < 1)
                                                    <b class="col-md-12 form-control btn-square btn-danger text-center">لا يوجد اختبارات</b>
                                                @else
                                                    @if($result->exams_passed)
                                                        <div class="row ">
                                                            <div class="container col-md-12">
                                                                <div class="form-group row ">
                                                                    <div
                                                                        class="col-md-6 form-control btn-square text-center">
                                                                        <b>الاختبار</b></div>
                                                                    <div
                                                                        class="col-md-6 form-control btn-square text-center">
                                                                        <b>النتيجة</b></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>

                                                        @foreach($result->exams_passed as $answer)
                                                            <div class="row ">
                                                                <div class=" col-md-12">
                                                                    <div class="form-group row ">
                                                                        <div class="col-md-6   text-center">
                                                                            <b>#{{$answer->exam_id}}</b><br>
                                                                            <b> اسم الامتحان: {{$answer->exam($answer->exam_id)->name}}</b><br>
                                                                            <b> رقم الامتحان: {{$answer->exam($answer->exam_id)->exam_num}}</b><br>
                                                                            <b> مدة الامتحان: {{$answer->exam($answer->exam_id)->duration}}</b><br>
                                                                            <b> درجة الامتحان: {{$answer->exam($answer->exam_id)->degree}}</b><br>
                                                                        </div>
                                                                        <div class="col-md-6 form-control  text-center">
                                                                            <b> درحة الطالب :{{$answer->result}}</b></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                        @endforeach
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    {{--==answer==--}}
                                    {{--                                    <a href="" title="عرض نتائج الامتحانات" class="btn btn-primary">--}}
                                    {{--                                        <i class="fa fa-eye"></i>--}}
                                    {{--                                    </a>--}}
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
                                            <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات الطالب</h5>
                                            {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                            {{--                                                <span aria-hidden="true">&times;</span>--}}
                                            {{--                                            </button>--}}
                                        </div>
                                        <form class="form-horizontal" method="post" action="{{route('editUser')}}"
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
                                                           for="textinput"> الموبايل</label>
                                                    <div class="col-lg-12">
                                                        <input id="phone" name="phone" type="text"
                                                               placeholder=" الموبايل" value="{{$result->phone}}"
                                                               class="form-control btn-square" required
                                                               oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput"> كلمة المرور</label>
                                                    <div class="col-lg-12">
                                                        <input id="password" name="password" type="password"
                                                               placeholder=" كلمة المرور"
                                                               class="form-control btn-square">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">المجموعة</label>
                                                    <div class="col-lg-12">
                                                        <select name="group_id" class="btn form-control b-light digits"
                                                                required
                                                                oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            <option value="" disabled>اختر المجموعة</option>
                                                            @foreach($groups as $group)
                                                                <option
                                                                    value="{{$group->id}}" {{$group->id == $result->group->id ? "selected" : ""}}>{{$group->name}}
                                                                    / {{$group->group_num}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
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
                                            <h5 class="modal-title" id="exampleModalLabel">حذف الطالب</h5>
                                            {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                            {{--                                                <span aria-hidden="true">&times;</span>--}}
                                            {{--                                            </button>--}}
                                        </div>
                                        <form method="post" action="{{route('deleteUser')}}" class="buttons">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <h4>هل انت متأكد ؟</h4>
                                                <h6>
                                                    انت علي وشك حذف الطالب
                                                    <br>الاسم: ({{$result->name}})
                                                    <br>الموبايل: ({{$result->phone}})
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
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة دولة</h5>
                    {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">--}}
                    {{--                        <span aria-hidden="true">&times;</span>--}}
                    {{--                    </button>--}}
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('addUser')}}"
                      enctype="multipart/form-data">
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
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> الموبايل</label>
                            <div class="col-lg-12">
                                <input id="phone" name="phone" type="text" placeholder=" الموبايل"
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> كلمة المرور</label>
                            <div class="col-lg-12">
                                <input id="password" name="password" type="password" placeholder=" كلمة المرور"
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">المجموعة</label>
                            <div class="col-lg-12">
                                <select name="group_id" class="btn form-control b-light digits" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}} / {{$group->group_num}}</option>
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).on('change', '#partner-status', function (e) {

            var user_id = $(this).attr('user_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{URL::route('changeStatusUser')}}",
                data: {
                    user_id: user_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    location.reload();
                    // if (response.success){
                    //     toastr.success(response.success);
                    // }else if(response.warning){
                    //     toastr.warning(response.warning);
                    // }else{
                    //     toastr.error(response.error);
                    // }
                },
                error: function (jqXHR) {
                    // toastr.error(jqXHR.responseJSON.message);
                }
            });
        });
    </script>
@endsection

