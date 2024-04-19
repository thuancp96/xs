@extends("frontend.frontend-template_issue_submit")
<!-- resources/views/issues/create.blade.php -->
<!-- Add this to the head of your HTML file or layout -->
@section("content")
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="panel panel-color panel-inverse" style="max-width: 1500px;min-width: 350px;">
    <div class="panel-heading recent-heading">
        <h6 class="panel-title">Thông báo lỗi đến quản trị</h6>
    </div>
    
    @if(!empty(Session::get('message')))
        @if(Session::get('message') == "success")
            <div class="row" style="margin-top:10px;margin-bottom:10px;">
                <div class="col-11 text_bold"><label>Gửi thông báo lỗi thành công. Vui lòng chờ quản trị xử lý vấn đề của bạn. Xin cảm ơn.</label>  </div>
            </div>
            <br>
            <div class="row">
                    <div class="col-4"><a href="/" type="button"  style="width:120px" class="btn btn-success waves-effect waves-light">Trang chủ</a></div>
            </div>
            <br>
        @endif

        @if(Session::get('message') == "failed")
            <div class="row" style="margin-top:10px;margin-bottom:10px;">
                <div class="col-11 text_bold"><label>Mã captcha không chính xác. Vui lòng thử lại.</label></div>
            </div>
            <br>
            <div class="row">
                    <div class="col-6"><a href="/" type="button"  style="width:120px" class="btn btn-success waves-effect waves-light">Trang chủ</a>
                    <a href="/issues/create" type="button"  style="width:100px" class="btn btn-warning waves-effect waves-light">Báo lỗi</a> </div>
            </div>
            <br>
        @endif
        
    @else
        <div class="panel-body">
            <form action="{{ url('/issues') }}" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-3 text_bold">Mô tả lỗi <span style="color:red;">*</span></div>
                    <div class="col-9 "> <textarea style="width:100%; height:100px" name="description" id="description" required></textarea></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 text_bold">Hình ảnh đính kèm (Nếu có)</div>
                    <div class="col-9 "> <input type="file" name="image" accept="image/*" /></div>
                </div>
                <br>
                <!-- <div class="row">
                    <div class="col-3 text_bold">Xác thực người dùng <span style="color:red;">*</span></div>
                    <div class="col-9 "> <div class="g-recaptcha" data-sitekey="6Lf1KikpAAAAAFC6hH0uuu5-UfiC9x1ayvZiTDNr"></div></div>
                </div>
                <br> -->
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-9 "> <button type="submit" style="width:100px" class="btn btn-success waves-effect waves-light">Gửi</button>
                    <a href="/" type="button"  style="width:160px" class="btn btn-warning waves-effect waves-light">Về trang chủ</a> </div>
                </div>
            </form>
        </div>
    @endif
    </div>

@endsection