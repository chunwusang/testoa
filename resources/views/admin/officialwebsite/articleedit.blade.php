@extends('admin.public')

@section('content')
    <div class="container" align="center">
        <div align="left" style="max-width:550px">
            <div>
                <h3>{{ $pagetitle }}</h3>
                <div>{!! $helpstr !!}</div>
                <hr class="head-hr"/>
            </div>

            <form name="myform" class="form-horizontal">


                <input type="hidden" value="{{ $data->img }}" name="img">
                <input type="hidden" value="{{ $data->cat_id }}" name="cat_id">
                <input type="hidden" value="{{ $data->id }}" name="id">

                <div align="center" style="padding:20px">
                    <img style="background:white;border:1px #dddddd solid;border-radius:10px"
                         src="{{ Rock::replaceurl($data->img) }}" id="img" width="100"><br>
                    <input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="上传图片">
                </div>

                <div class="form-group" inputname="name">
                    <label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> 所属分类</label>
                    <div class="col-sm-8">
                        <select readonly="" name="cat_id" class="form-control">
                            @foreach ($data_cat as $cat)
                            <option @if($data->cat_id==$cat->id) selected = "selected" @endif value="{{$cat->id}}">{{$cat->cat_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group" inputname="name">
                    <label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> 标题</label>
                    <div class="col-sm-8">
                        <input class="form-control" onblur="this.value=strreplace(this.value)"
                               data-fields="标题" required
                               placeholder="请输入标题" value="{{ $data->title }}"
                               maxlength="100" id="input_title" name="title">
                    </div>
                </div>

                <div class="form-group" inputname="name">
                    <label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> 简介</label>
                    <div class="col-sm-8">
                        <input class="form-control" onblur="this.value=strreplace(this.value)"
                               data-fields="简介" required
                               placeholder="请输入简介" value="{{ $data->introduction }}"
                               maxlength="100" id="input_introduction" name="introduction">
                    </div>
                </div>

                <div class="form-group" inputname="name">
                    <label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> 内容</label>
                    <div class="col-sm-8">
                        <tr inputname="content">
                            <td class="ys2 ysb">
                                <textarea  id="AContent" class="inputs" temp="htmlediter" name="content" placeholder="">{{ $data->text }}</textarea>
                            </td>
                        </tr>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-8">
                        <button type="button" name="submitbtn" onclick="submitadd()"
                                class="btn btn-primary">{{ $pagetitle }}</button>
                        &nbsp;<span id="msgview"><a href="javascript:;"
                                                    onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
                    </div>
                </div>


            </form>

        </div>
    </div>
@endsection

@section('script')
    <style>
        .inputs {
            height: 36px;
            line-height: 24px;
            border: 1px #cccccc solid;
            padding: 0px 2px;
            border-radius: 5px;
            width: 99%;
            font-size: 16px
        }
    </style>

    <script src="/res/kindeditor/kindeditor-min.js"></script>
    <script type="text/javascript" src="/js/jsm.js"></script>
    <script type="text/javascript" src="/res/agent/input.js"></script>
    <script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
    <script>
        function initbody() {
            var ismobile = 0, isedit = 0, isinput = 1;
            var fieldsarr = [], mid = 0, isedit = 1, cnum = 'n6b4nr', agenhnum = 'work', ismobile = 0, isinput = 1;

            upbtn = $.rockupfile({
                'uptype': 'image',
                onsuccess: function (ret) {
                    get('img').src = ret.viewpath;
                    form('img').value = ret.viewpath;
                }
            });

        }
        KindEditor.ready(function (K) {

            window.editor = K.create('#AContent', {
                afterBlur: function () {
                    this.sync();
                }
            });
        });
        function xuantuan() {
            upbtn.changefile();
        }

        function submitadd(o) {
            $.rockvalidate({
                url: '{{ route('jctadmin_website_articledetail_post_data') }}',
                submitmsg: '{{ $pagetitle }}'
            });
        }
    </script>
@endsection