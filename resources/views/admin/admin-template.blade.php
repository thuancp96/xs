<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="{{env('APP_NAME','xs')}}">
	<meta name="author" content="Coderthemes">

	<link rel="shortcut icon" href="/assets/admin/images/logo.png">

	<title>{{env('APP_NAME','xs')}}</title>
	@section('css')
	<link href="/assets/admin/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<!-- <link href="/assets/admin/plugins/bootstrapvalidator/src/css/bootstrapValidator.css" rel="stylesheet" type="text/css" /><link href="/assets/admin/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen"> -->
	<link href="/assets/admin/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/jquery.selectBoxIt.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/plugins/switchery/dist/switchery.min.css" rel="stylesheet">
	<link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/core.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/components.css?v=1.000111" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/pages.css?v=1.001" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/custom.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/plugins/hopscotch/css/hopscotch.min.css" rel="stylesheet" type="text/css">
	<script src="/assets/admin/plugins/hopscotch/js/hopscotch.min.js"></script>
	<link href="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	<script src="/assets/admin/js/modernizr.min.js"></script>
	<script src="/assets/admin/js/jquery-1.11.1.min.js"></script>
	<style>
		.text-bold {
			font-weight: bold !important;
		}
		#wrapper.enlarged .left.side-menu {
			width: 10px;
			z-index: -1;
			visibility:hidden
			}
			.col-xs-2 {
				z-index: 1;
			}
			.col-xs-6{
				z-index:1;
			}

.container {
    padding-right:1px !important;
    padding-left:1px !important;
}
	</style>
	<style>
	.portlet-heading{
		display: flex;
    	align-items: center;
    	flex-direction: column;
	}
</style>
	@show
	<script type="text/javascript">
		window.setTimeout(function() {
			$(".alert").fadeTo(100, 0).slideUp(100, function() {
				$(this).remove();
			});
		}, 4000);

		function check(type) {
			$_token = "{{ csrf_token() }}";
			if (type == 'username') {
				key = $('#username').val();
			}
			if (type == 'email') {
				key = $('#email').val();
			}
			$.ajax({
				url: "{{url('/users/check-user')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					key: key,
					type: type,
					_token: $_token,
				},
				success: function(data) {
					if (data != true) {
						if (type == 'username') {
							$("#btn_checkuser").click();
							$('#username').val('');
							$('#check').val('false');
						}
						if (type == 'email') {
							$("#btn_checkemail").click();
							$('#email').val('');
							$('#check').val('false');

						}
						return false;
					}
					return true;
				},
				error: function(data) {
					console.log('Error:', data);
				}
			});
		}

		function refreshTable() {
			$('div.table-rep-plugin').fadeOut();
			$('div.table-rep-plugin').load("{{url('/users/refresh-data')}}", function() {
				$('div.table-rep-plugin').fadeIn();

				// var placementRight = 'right';
				//             var placementLeft = 'left';

				//             // Define the tour!
				//             var tour = {
				//                 id: "my-intro",
				//                 steps: [
				//                     {
				//                         target: "userpercent18",
				//                         title: "Logo Here",
				//                         content: "You can find here status of user who's currently online.",
				//                         placement: placementRight,
				//                         zindex:999
				//                         // yOffset: 10
				//                     }
				//                 ],
				//                 showPrevButton: true
				//             };

				//             // Start the tour!
				//             hopscotch.startTour(tour);
			});
		}
	</script>
	<style type="text/css">
		.not-active {
			pointer-events: none;
			cursor: default;
		}
		@media only screen and (max-width: 600px) {
			.topbar-left {
				margin-left: 20px !important;
			}

			.container-non-responsive {
				/* Set width to your desired site width */
				width: 1300px;
			}
		}

		.smallscreen .content-page{
			margin-left: 0px !important;
		}
	</style>
</head>


<body class="smallscreen fixed-left-void">
<!--  -->
	<!-- Begin page -->
	<div id="wrapper">

		<div class="topbar">

			<!-- <div class="topbar-left">

				<div class="" style="    margin-left: auto;
                        margin-right: auto;
                        padding-left: 15px;
                        padding-right: 15px;">
					<a href="/" class="logo"><i class="icon-c-logo">{{env('APP_NAME','xs')}}</i>
						<span><label style="color:white">{{env('APP_NAME','xs')}}</label></span></a>
				</div>

			</div> -->

			<!-- Button mobile view to collapse sidebar menu -->
			@if(Auth::user()->roleid == 1)
			<div class="navbar navbar-default" role="navigation" style="background-color:#690000">
				@endif
				<!-- Button mobile view to collapse sidebar menu -->
				@if(Auth::user()->roleid == 2)
				<div class="navbar navbar-default" role="navigation" style="background-color:#026900">
					@endif
					<!-- Button mobile view to collapse sidebar menu -->
					@if(Auth::user()->roleid == 4)
					<div class="navbar navbar-default" role="navigation" style="background-color:#003569">
						@endif
						<!-- Button mobile view to collapse sidebar menu -->
						@if(Auth::user()->roleid == 5)
						<div class="navbar navbar-default" role="navigation">
							@endif


							<div class="container">
								<div class="">
									<div class="pull-left">
										<button class="button-menu-mobile open-left">
											<i class="ion-navicon"></i>
										</button>
										<span class="clearfix"></span>
									</div>

									<div class="pull-right">
										<button class="button-menu-mobile" onclick="location.reload();">
											<i class="ion-refresh"></i>
										</button>
										<span class="clearfix"></span>
									</div>

									<?php

										use App\Helpers\NotifyHelpers;
										use Illuminate\Support\Facades\Auth;

										$total_message = NotifyHelpers::showNotificationByUserid();
										
									?>
									<ul class="nav navbar-nav navbar-right pull-right">
									@if(count($total_message) > 0 && Auth::user()->roleid > 0)
										<li class="dropdown hidden-xs">
											<a href="#" data-target="#" class="dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false">
												<i class="icon-bell"></i> 
													<span class="badge badge-xs badge-danger">{{count($total_message)}}</span>
											</a>
											<ul class="dropdown-menu dropdown-menu-lg">
												<li class="notifi-title"><span class="label label-default pull-right"></span>Thông báo</li>
												<li class="list-group nicescroll notification-list" tabindex="5000" style="overflow: hidden; outline: none;max-height: 468px !important;">
													<!-- list item-->
													@foreach($total_message as $message)
														<a href="javascript:void(0);" class="list-group-item">
															<div class="media">
																<div class="pull-left p-r-10">
																	@if ($message->pin)
																		<em class="fa fa-bell-o fa-2x text-danger" style="font-size: 18px;"></em>
																	@else
																		<em class="fa fa-bell-o fa-2x text-danger" style="font-size: 18px;"></em>
																	@endif
																</div>
																<div class="media-body">
																<?php
																	$message_type = '';
																	switch ($message->type) {
																		case 'system':
																			$message_type = 'Hệ thống';
																			break;
																		
																		case 'supers':
																		case 'masters':
																			case 'agents':
																			case 'members':
																			$message_type = 'Chung';
																			break;                    
														
																		case 'personal':
																			$message_type = 'Cá nhân';
																			break;
														
																		default:
																			break;
																	}
																	?>
																	<h5 class="media-heading" style="margin-bottom:10px;">{{$message_type}}</h5>
																	<p class="m-0" style="white-space: normal;">
																		<small>{{$message->message}}</small>
																	</p>

																	<p class="m-0" style="text-align: right;">
																		<small>{{$message->updated_at}}</small>
																	</p>
																</div>
															</div>
														</a>
													@endforeach
												</li>
												<!-- <li>
													<a href="javascript:void(0);" class="list-group-item text-right">
														<small class="font-600">See all notifications</small>
													</a>
												</li> -->
												<div id="ascrail2000" class="nicescroll-rails" style="width: 8px; z-index: 1000; cursor: default; position: absolute; top: 0px; left: -8px; height: 0px; display: none;">
													<div style="position: relative; top: 0px; float: right; width: 6px; height: 0px; background-color: rgb(152, 166, 173); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px;"></div>
												</div>
												<div id="ascrail2000-hr" class="nicescroll-rails" style="height: 8px; z-index: 1000; top: -8px; left: 0px; position: absolute; cursor: default; display: none;">
													<div style="position: relative; top: 0px; height: 6px; width: 0px; background-color: rgb(152, 166, 173); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px;"></div>
												</div>
											</ul>
										</li>
										@endif
										<!-- <li class="dropdown"> -->
											<!-- <a href="{{url('/admin/logout')}}"><i class="ti-power-off m-r-5"></i> Thoát</a> -->
											<!-- <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
								<i class="ti-user m-r-5"></i>{{Auth::user()->name}}
							</a>
							<ul class="dropdown-menu">
								<li><a  data-toggle="modal" data-target="#changepass-modal" onclick="ShowLoadChangePass()"><i class="ti-settings m-r-5"></i> Thay đổi mật khẩu</a></li>
								<li><a href="{{url('/admin/logout')}}"><i class="ti-power-off m-r-5"></i> Thoát</a></li>
							</ul> -->
										<!-- </li> -->
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div class="left side-menu" style="display:none;" >
						<div class="sidebar-inner slimscrollleft">
							<div class="user-details">
								<div class="pull-left">
									<img src="/assets/admin/images/users/avatar-1.jpg" alt="" class="thumb-md img-circle">
								</div>
								<div class="user-info">
									<div class="dropdown">
										<a class="dropdown-toggle"  disable>
											<!--href="#" data-toggle="dropdown" aria-expanded="false" -->
											@if (Session::get('usersecondper') == 1)
											{{Session::get('usersecondname')}}
											@else
											{{Auth::user()->name}}
											@endif
											<!-- <span class="caret"></span></a> -->
										<ul class="dropdown-menu" style="left: -5px;">
											<!-- <li><a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile<div class="ripple-wrapper"></div></a></li> -->
											<!-- <li><a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a></li> -->
											<!-- <li><a href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a></li> -->
											<!-- <li><a href="javascript:void(0)"><i class="md md-settings-power"></i> Logout</a></li> -->

											<li><a id="changepassword" data-toggle="modal" data-target="#changepass-modal" onclick="ShowLoadChangePass('{{Auth::user()->id}}')"><i class="ti-settings m-r-5"></i>Mật khẩu</a></li>
											<li><a href="/ggauth"><i class="ti-settings m-r-5"></i>OTP</a></li>
										</ul>
									</div>
									<p class="text-muted m-0">
										@if(Auth::user()->roleid == 1)

										@if (Session::get('usersecondper') == 1)
										Admin - {{UserHelpers::GetRole2Name(Session::get('usersecondrole2'))}}
										@else
										Admin
										@endif

										@else
										@foreach(RoleHelpers::getAllRole() as $role)
										@if($role->id==Auth::user()->roleid)
										{{$role->name}}
										@endif
										@endforeach
										@endif
									</p>
								</div>
							</div>

							<div id="sidebar-menu" style="padding-top: 0px;">
								<ul>
									<li class="text-muted menu-title"></li>

									@if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') >= 2)

									@else

									<li class="">
										<a href="/">
											<i class="fa fa-home"></i>
											<span>TRANG CHỦ</span>
										</a>
									</li>

									<!-- <li class="has_sub" >
						<a href="#" class="waves-effect" style="margin-top: 0px">
							<i class="fa fa-book"></i>
							<span>THỐNG KÊ</span>
						</a>
						<ul class="list-unstyled">
							<li class="">
								<a href="/admin/thongketheoma">
									<span>THEO MÃ MIỀN BẮC</span>
								</a>
							</li>

							<li class="">
								<a href="/admin/thongkehoatdong">
									<span>HOẠT ĐỘNG</span>
								</a>
							</li>
						</ul>
					</li> -->
									@endif



									@foreach ($chucnangs as $chucnang)
									@if(count($chucnang['children'])>0)
									<li class="has_sub">
										@else
									<li>
										@endif
										<a href="{{url($chucnang['url'])}}" class="waves-effect {{$chucnang['active']}}" style="margin-top: 0px">
											<i class="{{$chucnang['icon']}}"></i>
											<span>{{$chucnang['name']}}</span>
										</a>
										<ul class="list-unstyled">
											@foreach ($chucnang['children'] as $item)
											<li class="{{$item['active']}}"><a href="{{url($item['url'])}}">{{$item['name']}}</a></li>
											@endforeach
										</ul>
									</li>
									@endforeach
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
							@yield('footer_title')
						</footer>
					</div>

				</div>

				<div class="hidden" id="user_info">
				</div>
				{{-- @include('admin.changepass') --}}
				@include('admin.notification.pin-view')
				<script>
					var resizefunc = [];
				</script>
				<!-- jQuery  -->
				@section('jquery')
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
				<!-- <script src="/assets/admin/js/jquery.min.js"></script> -->
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
				<script src="/assets/admin/js/jquery.app.js?v=1.02"></script>
				<!-- Input-Mask  -->
				<script src="/assets/admin/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
				<script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>
				<!-- Sweet-Alert  -->
				<!-- <script src="/assets/admin/plugins/sweetalert/dist/sweetalert.min.js"></script> -->
				<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
				<script src="/assets/admin/js/sweetalert.min.js"></script>
				<!-- <script src="/assets/admin/pages/jquery.sweet-alert.init.js"></script> -->

				<script src="/assets/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
				<script src="/assets/admin/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.vi.min.js"></script>
				<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->

				<script src="/assets/admin/js/jquery.selectBoxIt.min.js"></script>
				<script src="/assets/admin/plugins/moment/moment.js"></script>
				<script src="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/vi.js"></script>
				<script src="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
				@show



				@yield('js_call')
				<script>
					document.addEventListener("DOMContentLoaded", function(event) {
						// 
						// $('.button-menu-mobile').click()
						// $('.button-menu-mobile').hide();
					});
					(function(i, s, o, g, r, a, m) {
						i['GoogleAnalyticsObject'] = r;
						i[r] = i[r] || function() {
							(i[r].q = i[r].q || []).push(arguments)
						}, i[r].l = 1 * new Date();
						a = s.createElement(o),
							m = s.getElementsByTagName(o)[0];
						a.async = 1;
						a.src = g;
						m.parentNode.insertBefore(a, m)
					})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

					ga('create', 'UA-89748821-1', 'auto');
					ga('send', 'pageview');
				</script>

				<script type="text/javascript">
					timeout = 1000
					$(document).ready(function() {
						
						g_count_new();
						setTimeout(
							function() 
							{
								$('.side-menu').show();
							}, 100);
					})

					function refreshUser_Info() {
						$('#user_info').load("/games/reload-user", function() {})
					}

					function g_count_new() {
						refreshUser_Info()
						setTimeout('g_count_new()', timeout)
					}

					$(document).on("click", function (event) {
						if ($(event.target).closest(".sidebar-inner").length === 0) {
							if($("#wrapper").hasClass("enlarged")==false)
								if(jQuery.browser.mobile === true){
									$('.button-menu-mobile').click()
								}
						}
					});

				</script>

				<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-252700338-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-252700338-1');
</script>

</body>

</html>