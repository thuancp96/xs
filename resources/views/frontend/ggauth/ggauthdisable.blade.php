@extends('frontend.frontend-template')
@section('title','Quản lí OTP')
@section('content')

      <div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Khóa bí mật 2FA</div>

                <div class="panel-body">
                    Bảo mật 2 lớp đã bị loại bỏ
                    <br /><br />
                    <a href="{{ url('/ggauth') }}" style="color:black;">Quay trở về</a>
                </div>
            </div>
        </div>
    </div>    
</div>

@endsection

@section('js_call')
@endsection

