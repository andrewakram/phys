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
                        <li class="breadcrumb-item active">اكواد الخصم</li>
                    </ol>
                </div>
                <h4 class=" p-2">اكواد الخصم
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"><i
                            class="icon-plus"></i>
                        اضافة كود
                    </button>
                </h4>
                <form method="post" action="{{route('searchPromocodes')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <select name="group_id" class="btn btn-default border-dark form-control b-light digits">
                                <option value="" selected disabled>اختر المجموعة</option>
                                @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
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

                    <h4 class="mt-0 header-title">اكواد الخصم

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
                                    <th>الاسم</th>
                                    <th>الكود</th>
                                    <th>قيمة الخصم</th>
                                    <th>الطالب</th>
                                    <th>العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <th scope="row">{{$result->id}}</th>
                                        <td>{{$result->name}}</td>
                                        <td>{{$result->code}}</td>
                                        <td>{{$result->value}}</td>

                                        <td>
                                            @if($result->user)
                                                <span>#: </span> <b>{{$result->user->id}}</b> <br>
                                                <span>اسم الطالب: </span> <b>{{$result->user->name}}</b> <br>
                                                <span>موبايل الطالب: </span> <b>{{$result->user->phone}}</b> <br>
                                            @else
                                                -
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
                                                        الكود</h5>
                                                    {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                    {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                    {{--                                            </button>--}}
                                                </div>
                                                <form class="form-horizontal" method="post"
                                                      action="{{route('editPromocode')}}" enctype="multipart/form-data">
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
                                                                   for="textinput">الكود </label>
                                                            <div class="col-lg-12">
                                                                <input id="code" name="code" type="text"
                                                                       value="{{$result->code}}"
                                                                       placeholder="الكود "
                                                                       class="form-control btn-square" required
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> قيمة الخصم </label>
                                                            <div class="col-lg-12">
                                                                <input id="value" name="value" type="text" placeholder="قيمة الخصم "
                                                                       class="form-control btn-square" required
                                                                       value="{{$result->value}}"
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput">المراحل الدراسية</label>
                                                            <div class="col-lg-12">
                                                                <select name="stage_id"
                                                                        class="btn form-control b-light digits stage_id" required
                                                                >
                                                                    <option value="" disabled selected>اختر المرحلة الدراسية
                                                                    </option>
                                                                    @foreach($stages as $stage)
                                                                        <option
                                                                            value="{{$stage->id}}" >{{$stage->name}}</option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">المجموعات
                                                            </label>
                                                            <div class="col-lg-12">
                                                                <select name="group_id" class="btn form-control b-light digits group_id" required
                                                                        >
                                                                    <option value="" selected disabled>اختر المجموعة</option>
                                                                    @foreach($groups as $group)
                                                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الطلاب
                                                            </label>
                                                            <div class="col-lg-12">
                                                                <select name="user_id" class="btn form-control b-light digits user_id" required
                                                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                    <option value="" disabled>اختر الطالب</option>
                                                                    @foreach($users as $user)
                                                                        <option value="{{$user->id}}" {{$user->id == $result->user->id ? 'selected' : ''}}>{{$user->name}} / {{$user->phone}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
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
                                                    <h5 class="modal-title" id="exampleModalLabel">حذف الكود</h5>
                                                    {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                    {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                    {{--                                            </button>--}}
                                                </div>
                                                <form method="post" action="{{route('deletePromocode')}}" class="buttons">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <h4>هل انت متأكد ؟</h4>
                                                        <h6>
                                                            انت علي وشك حذف الكود
                                                            <br>رقم الكود: ({{$result->code}})
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
                    <h5 class="modal-title" id="exampleModalLabel">اضافة كود</h5>
                    {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">--}}
                    {{--                        <span aria-hidden="true">&times;</span>--}}
                    {{--                    </button>--}}
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('addPromocode')}}"
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
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> الكود </label>
                            <div class="col-lg-12">
                                <input id="code" name="code" type="text" placeholder="الكود "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> قيمة الخصم </label>
                            <div class="col-lg-12">
                                <input id="value" name="value" type="text" placeholder="قيمة الخصم "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">المراحل
                                الدراسية</label>
                            <div class="col-lg-12">
                                <select name="stage_id" class="btn form-control b-light digits stage_id" required
                                        >
                                    <option value="" selected disabled>اختر المرحلة الدراسية</option>
                                    @foreach($stages as $stage)
                                        <option value="{{$stage->id}}">{{$stage->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">المجموعات
                                </label>
                            <div class="col-lg-12">
                                <select name="group_id" class="btn form-control b-light digits group_id" required
                                        >
                                    <option value="" selected disabled>اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الطلاب
                            </label>
                            <div class="col-lg-12">
                                <select name="user_id" class="btn form-control b-light digits user_id" required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر الطالب</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}} / {{$user->phone}}</option>
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
        $(document).on('change','.stage_id', function(e) {
            var stage_id = $(this).val();
            //console.log(country_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{URL::route('getGroups')}}",
                data: {
                    stage_id: stage_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response){
//console.log(response);
                    $(".group_id").empty();
                    $(".user_id").empty();
                    if(response.length > 0){
                        $('.group_id').append('<option disabled selected> اختر</option>');
                        $.each(response,function(key,value){
                            if(value.name !=null)
                                $(".group_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
                        });
                    }

                    //console.log(response);
                },
                error: function(jqXHR){
                    // toastr.error(jqXHR.responseJSON.message);
                }
            });
        });
    </script>
    <script>
        $(document).on('change','.group_id', function(e) {

            var group_id = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{URL::route('getUsers')}}",
                data: {
                    group_id: group_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response){
                    //console.log(response);
                    $(".user_id").empty();
                    if(response.length > 0){
                        $('.user_id').append('<option disabled selected> اختر</option>');
                        $.each(response,function(key,value){
                            if(value.name !=null)
                                $(".user_id").append('<option value="'+value.id+'" >'+value.name+' / '+value.phone+'</option>');
                        });
                    }
                },
                error: function(jqXHR){
                    // toastr.error(jqXHR.responseJSON.message);
                }
            });
        });

    </script>
@endsection

