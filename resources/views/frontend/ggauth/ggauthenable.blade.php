@extends('frontend.frontend-template')
@section('title','Quản lí OTP')
@section('content')

<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading" style="color:white;" >Khóa bí mật 2FA</div>

                <div class="panel-body">
                    <b>Bước 1: Mở ứng dụng di động Google Authenticator của bạn và quét mã vạch QR sau:</b>
                    <br />
                    <img alt="Image of QR barcode" src="{{ $image }}" />

                    <br />
                    Nếu ứng dụng di động Google Authenticator của bạn không hỗ trợ mã vạch QR,
                    nhập số sau: <code>{{ $secret }}</code>
                    <br /><br />
                    <!-- <a href="{{ url('/ggauth') }}">Quay trở về</a> -->
                    <!-- <a href="{{ url('/ggauth/validate') }}">Kiểm tra OTP</a> -->
                    
                    <br />

                    <b>Bước 2: Mở ứng dụng di động Google Authenticator của bạn và lấy mã OTP nhập để xác nhận:</b>
                    <br />
                    <br />
                    <div class="form-group" id="otp_form_group">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}"/>
                        <div class="col-xs-8">
                            <input class="form-control" type="text" name="otp" id="otp" required="" placeholder="OTP">
                        </div>
                        <br />
                        <div class="col-xs-4">
                            <button class="btn btn-default btn-block text-uppercase waves-effect waves-light" onclick="doConfirm()" type="button">Xác nhận</button>
                        </div>
                    </div>
                    <br />

                </div>

                <div class="panel-body">
                    

                    <!-- <a href="{{ url('/ggauth') }}">Quay trở về</a> -->
                    <!-- <a href="{{ url('/ggauth/validate') }}">Kiểm tra OTP</a> -->
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function doConfirm(){
        $_token = "{{ csrf_token() }}";
        $.ajax({
            url: "{{url('/2fa/confirmTwoFactor')}}",
            method: 'POST',
            dataType: 'html',
            data: {
                do_confirm: true,
                otp: $('#otp').val(),
                _token: $_token,
            },
            success: function(data)
            {
                console.log(data)
                if (data=="true"){
                    swal({
                        title: "",
                        text: "Bạn đã cài đặt mật khẩu 2 lớp thành công",
                        type: "warning",
                        // showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        // cancelButtonText: "Hủy",
                        closeOnConfirm: true
                    }, function(isConfirm) {
                        location.href = "/ggauth"
                    });
                }

                if (data=="otp"){
                    swal({
                        title: "",
                        text: "Xác nhận mật khẩu cấp 2 không thành công !!! ",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Thử lại",
                        cancelButtonText: "Hủy bỏ",
                        closeOnConfirm: true
                    }, function(isConfirm) {
                        // console.log(isConfirm)
                        if (isConfirm==false){
                            location.href = "/ggauth"
                        }
                        // location.href = "/ggauth"
                    });
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
    $("#btn_login" ).click(function() {
        doLogin()
    });
</script>

@endsection

@section('js_call')
@endsection