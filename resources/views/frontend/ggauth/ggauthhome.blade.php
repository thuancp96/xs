@extends('frontend.frontend-template')
@section('title','Quản lí OTP')
@section('content')

	<div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading" style="color:white;" >Cài đặt bảo mật 2 lớp</div>

                <div class="panel-body">
                    @if (Auth::user()->google2fa_secret)
                    <a href="{{ url('2fa/disable') }}" class="btn btn-warning">Hủy bảo mật 2 lớp</a>
                    @else
                    <a href="{{ url('2fa/enable') }}" class="btn btn-primary">Đăng ký bảo mật 2 lớp</a>
                    @endif
                </div>
            </div>
        </div>
    </div>	
		
@endsection


@section('js_call')

@endsection


