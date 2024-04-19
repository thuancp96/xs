@if(Auth::user())
    <br/>
    <?php $user = Auth::user();?>
    <div class="col-md-9">
    </div>
    <div class="col-md-3">
        Chào bạn: <a>{{$user->name}}  </a><a id="btn_logout">Đăng xuất</a>
    </div>
@else
    <br/>
    <div class="col-md-4">
        <div class="form-group">
            <label class="sr-only" for="account">Tài khoản</label>
            <input type="text" class="form-control"  readonly onfocus="this.removeAttribute('readonly');" id="username" placeholder="Nhập tài khoản">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group m-l-10">
            <label class="sr-only" for="pass">Mật khẩu</label>
            <input type="password" class="form-control" readonly onfocus="this.removeAttribute('readonly');" id="passwd" placeholder="Nhập mật khẩu">
        </div>
    </div>
    <div class="col-md-3">
        <a id="btn_login" class="btn btn-success waves-effect waves-light m-l-10 btn-md">Đăng nhập</a>
    </div>
@endif