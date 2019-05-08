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
                <input type="hidden" value="{{ $data->id }}" name="id">

                <div align="center" style="padding:20px">
                    <img style="background:white;border:1px #dddddd solid;border-radius:10px"
                         src="{{ Rock::replaceurl($data->img) }}" id="img" width="800" height="100"><br>
                    <input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="添加图片">
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
    <script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
    <script>
        function initbody() {
            upbtn = $.rockupfile({
                'uptype': 'image',
                onsuccess: function (ret) {
                    get('img').src = ret.viewpath;
                    form('img').value = ret.viewpath;
                }
            });
        }

        function xuantuan() {
            upbtn.changefile();
        }

        function submitadd(o) {
            $.rockvalidate({
                url: '{{ route('jctadmin_website_postbanner') }}',
                submitmsg: '{{ $pagetitle }}'
            });
        }
    </script>
@endsection