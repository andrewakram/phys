@extends('layouts.master')

@section('css')

    <link href="{{ URL::asset('assets/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
          type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box" >
                <div class="btn-group pull-left">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الاسئلة</li>
                    </ol>
                </div>
                <h4 class="page-title p-2">الاسئلة
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"><i
                            class="icon-plus"></i>
                        اضافة سؤال
                    </button>
                </h4>
            </div>
        </div>
    </div>
    <!-- end page title end breadcrumb -->
@endsection

@section('content')
    <div class="row" >


        <div class="col-lg-12">
            <div class="card m-b-20">
                <div class="card-body" >

                    <h4 class="mt-0 header-title">الاسئلة

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
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>الصورة</th>
                            <th>الاجابات</th>
                            <th>الامتحان</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                            <tr>
                                <th scope="row">{{$result->id}}</th>
                                <td>{{$result->title}}</td>
                                <td>{{$result->description}}</td>
                                <td>
                                    @if($result->image !== null)
                                        <button title="عرض" type="button" class="btn btn-success p-0" data-toggle="modal"
                                                data-target="#image{{$result->id}}">
                                            <img src="{{$result->image}}"
                                                 style="width: 50px; height: 50px;margin-top: 0px"/>
                                        </button>
                                        {{--==image==--}}
                                        <div class="modal fade" id="image{{$result->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <img src="{{$result->image}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        {{--==image==--}}
                                    @else
                                        <b> - </b>
                                    @endif
                                </td>
                                <td>
                                    @if($result->answers)

                                        <button title="عرض الاجابات" type="button" class="btn btn-success" data-toggle="modal" data-target="#answer{{$result->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        {{--==image==--}}
                                        <div class="modal fade" id="answer{{$result->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content p-2 mr-2">
                                                    @foreach($result->answers as $answer)
                                                        <span>#</span> <b
                                                            class="{{$answer->is_true == 1 ? 'badge badge-success' : ''}}">{{$answer->answer}}</b>
                                                        <br>
                                                        <span></span> <b
                                                            class="{{$answer->is_true == 1 ? 'badge badge-success' : ''}}">
                                                            ( {{$answer->description}} ) </b> <hr>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        {{--==image==--}}

{{--                                        @foreach($result->answers as $answer)--}}
{{--                                            <span>#</span> <b--}}
{{--                                                class="{{$answer->is_true == 1 ? 'badge badge-success' : ''}}">{{$answer->answer}}</b>--}}
{{--                                            <br>--}}
{{--                                            <span></span> <b--}}
{{--                                                class="{{$answer->is_true == 1 ? 'badge badge-success' : ''}}">--}}
{{--                                                ( {{$answer->description}} ) </b> <hr>--}}
{{--                                        @endforeach--}}
                                    @else
                                        <b> - </b>
                                    @endif
                                </td>
                                <td>
                                    @if($result->exam)
                                        <span>#: </span> <b>{{$result->exam->id}}</b> <br>
                                        <span>اسم الامتحان: </span> <b>{{$result->exam->name}}</b> <br>
                                        {{--                                        {{$result->group->exam_id}}--}}
                                    @else
                                        <b> - </b>
                                    @endif
                                </td>
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
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات السؤال</h5>
                                            {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                            {{--                                                <span aria-hidden="true">&times;</span>--}}
                                            {{--                                            </button>--}}
                                        </div>
                                        <form class="form-horizontal" method="post" action="{{route('editQuestion')}}"
                                              enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <input type="hidden" name="model_id" value="{{$result->id}}">


                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">العنوان </label>
                                                    <div class="col-lg-12">
                                                        <input id="name" name="title" type="text"
                                                               value="{{$result->title}}" placeholder="العنوان "
                                                               class="form-control btn-square">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">الوصف </label>
                                                    <div class="col-lg-12">
                                                        <textarea name="description" id="description"
                                                                  class="form-control btn-square">{{$result->description}}</textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group row ">
                                                    <label class="col-lg-12 control-label ">تحميل صورة</label>
                                                    <div class="col-lg-12">
                                                        <input id="inputImage" type="file" name="image"
                                                               accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                                                    </div>
                                                </div>
                                                <img src="{{$result->image}}" class="image_radius"><br>

                                                <div class="form-group row">
                                                    <label class="col-lg-12 control-label text-lg-right"
                                                           for="textinput">الامتحانات</label>
                                                    <div class="col-lg-12">
                                                        <select name="exam_id" class="btn form-control b-light digits"
                                                                required
                                                                oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            <option value="" disabled>اختر الامتحان</option>
                                                            @foreach($exams as $exam)
                                                                <option
                                                                    value="{{$exam->id}}" {{$exam->id == $result->exam->id ? "selected" : ""}}>{{$exam->name}}</option>
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
                                            <h5 class="modal-title" id="exampleModalLabel">حذف السؤال</h5>
                                            {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                            {{--                                                <span aria-hidden="true">&times;</span>--}}
                                            {{--                                            </button>--}}
                                        </div>
                                        <form method="post" action="{{route('deleteQuestion')}}" class="buttons">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <h4>هل انت متأكد ؟</h4>
                                                <h6>
                                                    انت علي وشك حذف السؤال
                                                    <br>رقم السؤال: ({{$result->id}})
                                                    <br>تابع للامتحان: ({{$result->exam->exam_num}}
                                                    / {{$result->exam->name}})

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


                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->




    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة سؤال</h5>
                    {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">--}}
                    {{--                        <span aria-hidden="true">&times;</span>--}}
                    {{--                    </button>--}}
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post"
                      action="{{route('addQuestion')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">العنوان </label>
                            <div class="col-lg-12">
                                <input id="name" name="title" type="text"
                                       placeholder="العنوان "
                                       class="form-control btn-square">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">الوصف </label>
                            <div class="col-lg-12">
                                <textarea name="description" id="description"
                                          class="form-control btn-square"></textarea>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-lg-12 control-label ">تحميل صورة</label>
                            <div class="col-lg-12">
                                <input id="inputImage" type="file" name="image"
                                       accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                            </div>
                        </div>

                        <div class="form-group row ">
                            <div class="col-lg-12">
                                <button type="button" class="addAnswer btn btn-success">
                                    <i class="icon-plus"></i>
                                    اضافة اجابة
                                </button>
                            </div>
                        </div>
                        <span class="answers">
                            <input type="hidden" name="trueValue" id="trueValue">
                            {{--//////////       append answers from jquery here        //////////--}}
                        </span>
{{--                        <div class="form-group row " style="display: inline-block">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <div class="col-md-2">--}}
{{--                                    <input name="is_true[]" type="checkbox" class="form-control btn-square" value="1">--}}
{{--                                </div>--}}
{{--                                <div class="col-md-8">--}}
{{--                                    <input name="answer[]" type="text" class="form-control btn-square answer">--}}
{{--                                </div>--}}
{{--                                <div class="col-md-2">--}}
{{--                                    <button type="button" class="removeAnswer btn btn-danger">--}}
{{--                                        <i class="icon-plus"></i>--}}
{{--                                        حذف--}}
{{--                                    </button>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right"
                                   for="textinput">الامتحانات</label>
                            <div class="col-lg-12">
                                <select name="exam_id" class="btn form-control b-light digits"
                                        required
                                        oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" disabled>اختر الامتحان</option>
                                    @foreach($exams as $exam)
                                        <option
                                            value="{{$exam->id}}">{{$exam->name}}</option>
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
    <script src="{{ URL::asset('assets/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"
            type="text/javascript"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).on('click', '.addAnswer', function () {
            $(".answers").append('<div class="row itemAnswer" >\n' +
                '                            <div class=" col-md-12">\n' +
                '                                <div class="form-group row check">\n' +
                '                                    <input name="answer[]" type="text" class="col-md-7 form-control btn-square answer" value="ج">\n' +
                '                                    <input name="is_true[]" type="radio" class="col-md-3 form-control btn-square is_check" value="1">\n' +
                '                                    <button type="button" class="col-md-2 removeAnswer btn btn-danger">\n' +
                '                                        <i class="icon-plus"></i>\n' +
                '                                        حذف\n' +
                '                                    </button>\n' +
                '                                </div>\n' +
                '\n' +
                '\n' +
                '                            </div>\n' +
                '                        </div>');
        });


        $(document).on('click', '.removeAnswer', function () {
            $(this).closest('.itemAnswer').remove();
        });
        $(document).on('click', '.is_check', function () {
            //alert($(this).closest('.check').find('input[type=text]').val());
            $("#trueValue").val($(this).closest('.check').find('input[type=text]').val());
        });
    </script>

@endsection

