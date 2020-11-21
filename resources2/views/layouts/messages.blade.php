
    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <!--<li>{{ $error }}</li>-->
                                    <li>رقم الهاتف موجود من قبل</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session()->has('insert_message'))
                        <div class="alert alert-success">
                            <ul>
                                {{session()->get('insert_message')}}
                            </ul>
                        </div>
                    @endif
