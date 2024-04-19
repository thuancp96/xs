<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Luk79.net">
	<meta name="author" content="Coderthemes">

	<link rel="shortcut icon" href="/assets/admin/images/favicon_1.ico">

	<title>@yield('title')</title>
	<link href="/assets/admin/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/plugins/bootstrapvalidator/src/css/bootstrapValidator.css" rel="stylesheet" type="text/css" /><link href="/assets/admin/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
	<link href="/assets/admin/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
	<link href="/assets/admin/plugins/switchery/dist/switchery.min.css" rel="stylesheet">
	<link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/core.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/components.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/pages.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/responsive.css" rel="stylesheet" type="text/css" />
	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<script src="/assets/admin/js/modernizr.min.js"></script>
	<script src="/assets/admin/js/jquery-1.11.1.min.js"></script>
</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">
	<div class="topbar">
		<div class="topbar-left">
			<div class="text-center">
				<a href="#" class="logo"><i class="icon-magnet icon-c-logo"></i><span>L<i class="md md-album"></i>De</span></a>
			</div>
		</div>

		<!-- Button mobile view to collapse sidebar menu -->
		<div class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="">
					<div class="pull-left">
						<button class="button-menu-mobile open-left">
							<i class="ion-navicon"></i>
						</button>
						<span class="clearfix"></span>
					</div>

					<ul class="nav navbar-nav navbar-right pull-right">
						<li class="dropdown">
							<a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
								<i class="ti-user m-r-5"></i>{{Auth::user()->name}}
							</a>
							<ul class="dropdown-menu">
								<li><a  data-toggle="modal" data-target="#changepass-modal" onclick="ShowLoadChangePass()"><i class="ti-settings m-r-5"></i> Thay đổi mật khẩu</a></li>
								<li><a ><i class="ti-power-off m-r-5"></i> Thoát</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="left side-menu">
		<div class="sidebar-inner slimscrollleft">
			<!--- Divider -->
			<div id="sidebar-menu">
				<ul>
					<li>
						<a href="{{url('/mb')}}" class="waves-effect"><i class="ti-home"></i> <span>Trang chủ</span> </a>
					</li>

					<li>
						<a href="#" class="waves-effect"><i class="ti-game"></i><span>Đặt cược</span> </a>
					</li>
					<li>
						<a href="#" class="waves-effect"><i class="ti-clipboard"></i><span>Lịch sử cược</span> </a>
					</li>
					<li>
						<a href="#" class="waves-effect"><i class="ti-ruler-alt"></i><span>Quy tắc</span> </a>
					</li>
					<li>
						<a  data-toggle="modal" data-target="#changepass-modal" onclick="ShowLoadChangePass()"><i class="ti-settings m-r-5"></i><span>Thay đổi mật khẩu</span></a>
					</li>
					<li>
						<a href="{{url('/admin/logout')}}" class="waves-effect"><i class="ti-ruler-alt"></i><span>Thoát</span> </a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>

	</div>
	<div class="content-page">
		<div class="content">
			<div class="container">
				@yield('content')
			</div>
		</div>
		<footer class="footer text-right">

		</footer>
	</div>
	@include('admin.changepass')
</div>
<script>
	var resizefunc = [];
</script>
<!-- jQuery  -->
@section('jquery')
	<script src="/assets/admin/js/jquery.min.js"></script>
	<script src="/assets/admin/js/bootstrap.min.js"></script>
	<script src="/assets/admin/js/detect.js"></script>
	<script src="/assets/admin/js/fastclick.js"></script>
	<script src="/assets/admin/js/jquery.slimscroll.js"></script>
	<script src="/assets/admin/js/jquery.blockUI.js"></script>
	<script src="/assets/admin/js/waves.js"></script>
	<script src="/assets/admin/js/wow.min.js"></script>
	<script src="/assets/admin/js/jquery.nicescroll.js"></script>
	<script src="/assets/admin/js/jquery.scrollTo.min.js"></script>

	<script src="/assets/admin/plugins/moment/moment.js"></script>

	<script src="/assets/admin/plugins/peity/jquery.peity.min.js"></script>
	<script src="/assets/admin/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="/assets/admin/plugins/switchery/dist/switchery.min.js"></script>
	<!-- parsley  -->
	<script src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
	<!-- dataTables -->
	<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
	<script src="/assets/admin/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js" type="text/javascript"></script>
	<!-- notifications  -->
	<script src="/assets/admin/plugins/notifyjs/dist/notify.min.js"></script>
	<script src="/assets/admin/plugins/notifications/notify-metro.js"></script>
	<!-- core -->
	<script src="/assets/admin/js/jquery.core.js"></script>
	<script src="/assets/admin/js/jquery.app.js"></script>
	<!-- Input-Mask  -->
	<script src="/assets/admin/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
	<script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>
	<!-- Sweet-Alert  -->
	<script src="/assets/admin/plugins/sweetalert/dist/sweetalert.min.js"></script>
	<script src="/assets/admin/pages/jquery.sweet-alert.init.js"></script>

	<script src="/assets/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<script src="/assets/admin/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.vi.min.js"></script>
	<script src="/assets/js/script.js"></script>
@show
@yield('js_call')
</body>
</html>