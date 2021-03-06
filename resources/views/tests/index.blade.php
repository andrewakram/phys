@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
          type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group pull-left">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الامتحانات</li>
                    </ol>
                </div>
                <h4 class=" p-2">الامتحانات
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"><i
                            class="icon-plus"></i>
                        اضافة امتحان
                    </button>
                </h4>
                <form method="post" action="{{route('searchTests')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <select name="stage_id" class="btn btn-default border-dark form-control b-light digits">
                                <option value="" selected disabled>اختر المرحلة الدراسية</option>
                                @foreach($stages as $stage)
                                    <option value="{{$stage->id}}">{{$stage->name}}</option>
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

                    <h4 class="mt-0 header-title">الامتحانات

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
                                    <th>رقم الامتحان</th>
                                    <th>الاسم</th>
                                    <th>المدة بالدقائق</th>
                                    <th>الدرجة</th>
                                    <th>درجة النجاح</th>
                                    <th>درجة النقاط</th>
                                    <th> النقاط</th>
                                    <th> المرحلة الدراسية</th>
                                    <th>عدد الاسئلة</th>
                                    <th>العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <th scope="row">{{$result->id}}</th>
                                        <td>{{$result->test_num}}</td>
                                        <td>{{$result->name}}</td>
                                        <td>{{$result->duration}}</td>
                                        <td>{{$result->degree}}</td>
                                        <td>{{$result->pass_degree}}</td>
                                        <td>{{$result->points_degree}}</td>
                                        <td>{{$result->points}}</td>

                                        <td>
                                            @if($result->stage)
                                                <span>#: </span> <b>{{$result->stage->id}}</b> <br>
                                                <span>اسم المرحلة: </span> <b>{{$result->stage->name}}</b> <br>
                                                {{--                                        {{$result->group->stage_id}}--}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($result->querries)
                                                <b>{{sizeof($result->querries)}}</b>
                                            @else
                                                <b>0</b>
                                            @endif
                                        </td>
                                        <td>
                                            <button title="تعديل" type="button" class="btn btn-warning"
                                                    data-toggle="modal" data-target="#edit_{{$result->id}}">
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
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات
                                                        الامتحان</h5>
                                                    {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                    {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                    {{--                                            </button>--}}
                                                </div>
                                                <form class="form-horizontal" method="post"
                                                      action="{{route('editTest')}}" enctype="multipart/form-data">
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
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput">المدة بالدقائق </label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="duration" type="text"
                                                                       value="{{$result->duration}}"
                                                                       placeholder="المدة بالدقائق "
                                                                       class="form-control btn-square" required
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput"> الدرجة </label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="degree" type="text"
                                                                       value="{{$result->degree}}"
                                                                       placeholder=" الدرجة "
                                                                       class="form-control btn-square" required
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> درجة النجاح </label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="pass_degree" type="text" placeholder=" درجة النجاح "
                                                                       class="form-control btn-square" required
                                                                       value="{{$result->pass_degree}}"
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> درجة النقاط </label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="points_degree" type="text" placeholder=" درجة النقاط "
                                                                       class="form-control btn-square" required
                                                                       value="{{$result->points_degree}}"
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> النقاط </label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="points" type="text" placeholder=" النقاط "
                                                                       class="form-control btn-square" required
                                                                       value="{{$result->points}}"
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
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="reset" class="btn btn-dark mr-1"
                                                                data-dismiss="modal">اغلاق
                                                        </button>
                                                        <button class="btn btn-primary mr-1" type="submit">تعديل
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{--///////////////////////////--}}
                                    <div class="modal animated fadeIn" id="delete_{{$result->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header btn-danger">
                                                    <h5 class="modal-title" id="exampleModalLabel">حذف الامتحان</h5>
                                                    {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                    {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                    {{--                                            </button>--}}
                                                </div>
                                                <form method="post" action="{{route('deleteTest')}}" class="buttons">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <h4>هل انت متأكد ؟</h4>
                                                        <h6>
                                                            انت علي وشك حذف الامتحان
                                                            <br>رقم الامتحان: ({{$result->test_num}})
                                                            <br>الاسم: ({{$result->name}})

                                                        </h6>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="model_id" value="{{$result->id}}">
                                                        <button class="btn btn-dark" type="button" data-dismiss="modal">
                                                            اغلاق
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

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة امتحان</h5>
                    {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">--}}
                    {{--                        <span aria-hidden="true">&times;</span>--}}
                    {{--                    </button>--}}
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('addTest')}}"
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
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">المدة بالدقائق </label>
                            <div class="col-lg-12">
                                <input id="name" name="duration" type="text" placeholder="المدة بالدقائق "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> الدرجة </label>
                            <div class="col-lg-12">
                                <input id="name" name="degree" type="text" placeholder=" الدرجة "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> درجة النجاح </label>
                            <div class="col-lg-12">
                                <input id="name" name="pass_degree" type="text" placeholder=" درجة النجاح "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> درجة النقاط </label>
                            <div class="col-lg-12">
                                <input id="name" name="points_degree" type="text" placeholder=" درجة النقاط "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> النقاط </label>
                            <div class="col-lg-12">
                                <input id="name" name="points" type="text" placeholder=" النقاط "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">المراحل
                                الدراسية</label>
                            <div class="col-lg-12">
                                <select name="stage_id" class="btn form-control b-light digits" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر المرحلة الدراسية</option>
                                    @foreach($stages as $stage)
                                        <option value="{{$stage->id}}">{{$stage->name}}</option>
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

