<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="{{env('APP_NAME','xs')}}">
	<meta name="author" content="Coderthemes">


	<title>{{env('APP_NAME','xs')}}</title>

	<link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/core.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/custom.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/components.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/pages.css?v=1.002" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
	    <link rel="icon" type="image/x-icon" href="/assets/admin/images/logo.png">

	<link href="/assets/admin/css/responsive.css" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script src="/assets/admin/js/jquery-1.11.1.min.js"></script>
</head>
<body>
<div class="container white-bg col-xs-12 container-non-responsive" style="background: url('/assets/images/bglasvegas.jpg') no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover; min-height: 100% ">
<!-- <div class="account-pages"></div> -->
<div class="clearfix"></div>
<!-- <div class="login_intro hidden-xs" style="background-image: url({{url('/assets/images/ios_intro.png')}});"> 
<div class="app_download">
<div class="app_icon"><a href="https://goo.gl/TZA7Of" target="_blank"><img src="assets/images/apple.png"></a></div>
<div class="app_icon"><a href="https://goo.gl/8rL7x1" target="_blank"><img src="assets/images/google.png"></a></div>
<div class="app_icon"><a href="https://goo.gl/clhpuE" target="_blank"><img src="assets/images/microsoft.png"></a></div>
</div>
</div>
-->
 <!-- style="margin: 5%;
position: relative;
width: 380px;
float: right;" -->
<center>
    <div class="wrapper-page login_container " style="
position: relative;
width: 380px;
float: unset !important;">

		</div>
	</div>
    
</center>
	
	<a id="btn_notify" href="javascript:;" onclick="$.Notification.notify('error','top left', 'Thông báo', 'Tài khoản và mật khẩu của bạn không đúng.')"></a>
	<a id="btn_notify_otp" href="javascript:;" onclick="$.Notification.notify('error','top left', 'Thông báo', 'Mã OTP của bạn không đúng. F5 hoặc ctrl+F5 nếu thử lại không thành công.')"></a>
	<a id="btn_notify2_otp" href="javascript:;" onclick="$.Notification.notify('error','top left', 'Thông báo', 'Hãy nhập Mã OTP của bạn để đăng nhập.')"></a>
	<a id="btn_lock" href="javascript:;" onclick="$.Notification.notify('error','top left', 'Thông báo', 'Tài khoản của bạn đã bị khóa')"></a>
</div>
</div>

<input type="hidden" id="urlipaddress" value="{{$_SERVER['HTTP_HOST']}}">
<input type="hidden" id="ipaddress" value="{{getUserIpAddr()}}">
<!-- jQuery  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/assets/admin/js/bootstrap.min.js"></script>

<script src="/assets/admin/plugins/notifyjs/dist/notify.min.js"></script>
<script src="/assets/admin/plugins/notifications/notify-metro.js"></script>
<script src="/assets/admin/js/jquery.core.js"></script>
<script src="/assets/admin/plugins/sweetalert/dist/sweetalert.min.js"></script>
	<script src="/assets/admin/pages/jquery.sweet-alert.init.js"></script>
<!-- <link rel="stylesheet" href="/assets/newui/css/sweetalert2.min.css" id="theme-styles"> -->
<!-- <script src="/assets/newui/js/sweetalert2.js"></script> -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89748821-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>