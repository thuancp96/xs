<?php
$locations = LocationHelpers::getTopLocation();
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, initial-scale=1"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{env('APP_NAME','xs')}}</title>

  <meta property="og:image" content="/assets/admin/images/logo.png">
  <link href="/assets/css/app.css" rel="stylesheet">
  <link href="/assets/css/keno.css?v=1.06" rel="stylesheet">
  <link href="/assets/css/all.css" rel="stylesheet">
  <link href="/assets/css/select2.min.css" rel="stylesheet">
  <link href="/assets/admin/css/components.css?v=1.0011" rel="stylesheet" type="text/css" />
  <link href="/assets/admin/css/core.css?v=20230427_1" rel="stylesheet" type="text/css" />

  <link href="/assets/css/custom.css" rel="stylesheet" type="text/css" />
  <link href="/assets/admin/css/icons.css" rel="stylesheet" type="text/css" />
  <link href="/assets/admin/css/pages.css?v=1.001" rel="stylesheet" type="text/css" />
  <link href="/assets/admin/css/responsive.css" rel="stylesheet" type="text/css" />
  <link href="/assets/admin/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="/assets/newui/css/sweetalert2.min.css" id="theme-styles">
  <!-- <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" id="theme-styles"> -->
  <link rel="icon" type="image/x-icon" href="/assets/admin/images/logo.png">

  <link href="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

  <link rel="stylesheet" href="/assets/newui/bootstrap/css/bootstrap.min.css?h=a595ec6fc975b7a8576d7f6dc204a10b">
  <!--<link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />-->

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/newui/css/simple-line-icons.min.css">
  <link rel="stylesheet" href="/assets/newui/fonts/fontawesome5-overrides.min.css?h=a0e894d2f295b40fda5171460781b200">
  <link rel="stylesheet" href="/assets/newui/css/styles.min.css?v=20230427_32">

  <script href='first'>
    a = "first"
  </script>
  <script src="/assets/admin/js/jquery-1.11.1.min.js"></script>
  <style type="text/css">
    .not-active {
      pointer-events: none;
      cursor: default;
    }
  </style>
  <style>
    .badge {
      font-size: 14px;
      margin: 3px 0;
    }

    .container-non-responsive {
      /* Margin/padding copied from Bootstrap */
      margin-left: auto !important;
      margin-right: auto !important;
      /*padding-left: 15px !important;*/
      padding-right: 15px !important;

      /* Set width to your desired site width */
      width: 1280px !important;

      .sweet-alert {
        margin-top: -400px !important;
      }
    }
  </style>

</head>
<div class='loadere' style="display: block"></div>
<div class="menu-overlay"></div>
<div class="hidden" id="time-zone"></div>

<body id="page-top" data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-offset="54" style="background: linear-gradient(180deg,#f5f7fd,#dce2fa);" onload="$('.container.white-bg').show(); $('.loadere').hide()">
  <div class='loadere'></div>

  <!-- Start: Navbar With Button -->
  <nav class="navbar navbar-light navbar-expand-xl fixed-top" style="background: #4C9EEA;">
    <div class="container ">
      <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1">
        <span class="visually-hidden">Toggle navigation</span>
        <i class="fa fa-list-ul" style="color: white !important"></i>
      </button>
      <a class="navbar-brand d-flex align-items-center" href="/">
        <span>
        <label style="color:white">{{env('APP_NAME','xs')}}</label>
        </span>
      </a>
      <div class="collapse navbar-collapse" id="navcol-1">
        <ul class="navbar-nav me-auto">
          <!--<li class="nav-item">-->
          <!--  <div class="nav-item dropdown nav-link">-->
          <!--    <a class="dropdown-toggle" style="color: #ffffff;text-decoration: none;" aria-expanded="false" data-bs-toggle="dropdown" href="#">-->
          <!--      <strong>ĐẶT CƯỢC XỔ SỐ <svg style=" transform: rotate(90deg); float: right; margin-right: 10px" data-v-91f0333e="" width="24" height="24" focusable="false" aria-hidden="true" fill="#ffffff" class="icon-arrow">-->
          <!--          <use data-v-91f0333e="" xlink:href="/assets/newui/img/svg-sprite.6a0f58d.svg?h=ecb1b4ab7e32e26cb43262916d95fc51#icon-arrow-right-small" class="svg-use"></use>-->
          <!--        </svg>-->
          <!--      </strong>-->
          <!--    </a>-->
          <!--    <div class="dropdown-menu">-->
          <!--      <a class="dropdown-item" href="/play/1">Miền bắc</a>-->
          <!--      <a class="dropdown-item" href="/play/4">Xổ số ảo</a>-->
          <!--      <a class="dropdown-item" href="/play/5">Keno Vietlott</a>-->
          <!--    </div>-->
          <!--  </div>-->
          <!--</li>-->
          <li class="nav-item"></li>
          @if(!Auth::guest() && Auth::user()->lock == 0)
          <li class="nav-item">
            <a class="nav-link" href="/xoso/mienbac" style="color: #ffffff;">
              <strong>XỔ SỐ MIỀN BẮC</strong>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link" href="/bbin/" style="color: #ffffff;">
              <strong>LIVE CASINO</strong>
            </a>
          </li>--}}
          {{-- <li class="nav-item">
              <a class="nav-link" href="/saba/" style="color: #ffffff;">
                <strong>SABA</strong>
              </a>
            </li> --}}

            <li class="nav-item">
              <a class="nav-link" href="/7zball/" style="color: #ffffff;">
                <strong>Bóng đá</strong>
              </a>
            </li> 
            <li class="nav-item">
              <a class="nav-link" href="/minigame/" style="color: #ffffff;">
                <strong>Minigame</strong>
              </a>
            </li> 
            {{--<li class="nav-item">
            <a class="nav-link" href="#" onclick="SABAComingsoon()" style="color: #ffffff;">
              <strong>SABA</strong>
            </a>
          </li>--}}
          @endif
          <script>
            function SABAComingsoon() {
              Swal.fire('Sắp ra mắt SABA sport!')
            }
          </script>
          <li class="nav-item" hidden>
            <a class="nav-link" href="/history/1" style="color: #ffffff;">
              <strong>BẢNG CƯỢC</strong>
            </a>
          </li>
          <li class="nav-item" hidden>
            <a class="nav-link" href="/history-sk/1" style="color: #ffffff;">
              <strong>SAO KÊ</strong>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/ketqua/<?php echo isset($location->slug) ? $location->slug : '' ?>" style="color: #ffffff;">
              <strong>KẾT QUẢ</strong>
            </a>
          </li>
          @if(!Auth::guest() && Auth::user()->lock == 0)
          <li class="nav-item">
            <a class="nav-link" href="/quickplay/1" style="color: #ffffff;">
              <strong>CƯỢC NHANH</strong>
            </a>
          </li>
          @endif
          <li class="nav-item" hidden>
            <a class="nav-link" href="/rule" style="color: #ffffff;">
              <strong>QUY TẮC</strong>
            </a>
          </li>
          <!--<li class="nav-item">-->
          <!--  <a class="nav-link" href="#bbin" style="color: #ffffff;">-->
          <!--  <strong id="total_money_bbin_menubar">0 bbin</strong>-->
          <!--  </a>-->
          <!--</li>-->
        </ul>
      </div>

      <?php

      use App\Helpers\NotifyHelpers;
      use Illuminate\Support\Facades\Auth;

      $total_message = NotifyHelpers::showNotificationByUserid();
      $countPin = 0;
      foreach($total_message as $message){
        if ($message->pin) $countPin++;
      }
      ?>

      <li class="nav-item ">
        <div class="nav-item dropdown nav-link ml-auto pull-right">
        @if (count($total_message) > 0)
          <a class="dropdown-toggle" style="color: #ffffff;text-decoration: none;" aria-expanded="false" data-bs-toggle="dropdown" href="#">
            <strong><i class="icon-bell" style="color:#ffbd4a;font-weight: normal !important;"></i> 
            @if($countPin > 0)
              <span class="badge badge-xs badge-danger" style="color:#ffbd4a;font-weight: normal !important;">{{count($total_message)}}</span>
            @endif
            </strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-lg toptop">
            <li class="notifi-title">Thông báo</li>
            <li class="list-group nicescroll notification-list" tabindex="5000" style="overflow-x: hidden;overflow-y: auto; outline: none;max-height: 468px !important;">
              <!-- list item-->
              @foreach($total_message as $message)
              <a href="/notification/member-detail?id={{$message->id}}" class="list-group-item">
                <div class="media">
                  <div class="media-body" style="margin-left: 0px !important;border-bottom: 1px solid gray;">
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
                    <h5 class="media-heading" style="margin-bottom:10px;font-weight:600px;font-size: 14px;">{{$message_type}}
                      <div class="pull-left p-r-10" bis_skin_checked="1"> @if ($message->pin)
                    <em class="fa fa-bell-o fa-2x text-danger" style="font-size: 14px;"></em>
                    @endif</div>
                    </h5>
                    <p class="m-0" style="white-space: normal; font-style: normal !important; font-weight:200;text-transform: capitalize !important;font-size: 16px;">
                      <small>{!!$message->message!!}</small>
                    </p>

                    <p class="m-0" style="text-align: right; font-style: normal !important; font-weight:200;font-size: 12px;">
                      <small>{{$message->created_at}}</small>
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
            @endif

          <a class="dropdown-toggle" style="color: #ffffff;text-decoration: none;" aria-expanded="false" data-bs-toggle="dropdown" href="#">
            <strong><i class="fas fa-user"></i>
              <svg style=" transform: rotate(90deg); float: right; margin-right: 10px; display: block" data-v-91f0333e="" width="24" height="24" focusable="false" aria-hidden="true" fill="#ffffff" class="icon-arrow">
                <use data-v-91f0333e="" xlink:href="/assets/newui/img/svg-sprite.6a0f58d.svg?h=ecb1b4ab7e32e26cb43262916d95fc51#icon-arrow-right-small" class="svg-use"></use>
              </svg>
            </strong>
          </a>

          <div class="dropdown-menu toptop" style="width: 260px;">
            <!-- <a class="dropdown-item" href="/thongtintk">Thông tin tài khoản</a> -->
            
            
            
            @if (!Auth::guest()) 
            <div class="panel-body" bis_skin_checked="1" style="margin-left: 15px; margin-right:15px;">
              <div class="row" bis_skin_checked="1">
                <div class="col-6" bis_skin_checked="1"><i class="fa fa-user"></i> Hội viên</div>
                <div class="col-6 text_bold" style="text-align:right;" bis_skin_checked="1">{{Auth::user()->name}}</div>
              </div>
              <div class="row" bis_skin_checked="1">
                <div class="col-6" bis_skin_checked="1"><i class="glyphicon glyphicon-credit-card"></i> Số dư</div>
                <div class="col-6 text_bold text_red" style="text-align:right;" id="remain" data-toggle="tooltip" title="996,558,602 Chips" bis_skin_checked="1">996,558,602</div>
              </div>
              <div class="row" bis_skin_checked="1">
                <div class="col-6 col-6" bis_skin_checked="1"><i class="glyphicon glyphicon-credit-card"></i> Đang cược</div>
                <div class="col-6 col-6 text_bold text_red" style="text-align:right;" id="inbet" data-toggle="tooltip" title="0 Chips" bis_skin_checked="1">996,000</div>
              </div>
              <div class="row" bis_skin_checked="1">
                <div class="col-6 col-6" bis_skin_checked="1"><i class="glyphicon glyphicon-th"></i> Thắng thua</div>
                <div class="col-6 col-6 text_bold text_red" style="text-align:right;" id="winlose" data-toggle="tooltip" title="0 Chips" bis_skin_checked="1">996</div>
              </div>
            </div>
            <div class="dropdown-divider" bis_skin_checked="1"></div>  
            @endif
            

            <a class="dropdown-item" href="/inbets" style="color:green !important;font-weight: normal !important;">Bảng cược</a>
            <a class="dropdown-item" href="/reports" style="color:green !important;font-weight: normal !important;">Sao kê</a>
            <a class="dropdown-item" href="/cancelbets" style="color:green !important;font-weight: normal !important;">Phiếu cược huỷ</a>

            <!-- <a class="dropdown-item" href="/history/1">Bảng cược</a> -->
            <!-- <a class="dropdown-item" href="/history-sk/1">Sao kê</a> -->
            
            <div class="dropdown-divider" bis_skin_checked="1"></div>  
            <a class="dropdown-item" href="/thongso">Thông số</a>
            <a class="dropdown-item" href="/rule">Quy tắc</a>
            <a class="dropdown-item" href="/notification/member">Thông báo</a>
            <a class="dropdown-item" href="/issues/create"><label style="color:red;">Báo lỗi *</label></a>

            <div class="dropdown-divider" bis_skin_checked="1"></div>  
            <a class="dropdown-item" href="/ggauth">OTP
              <label @if(isset(Auth::user()->google2fa_secret) && strlen(Auth::user()->google2fa_secret)>5)
                style="color:green;font-weight: normal !important;"
                @else
                style="color:red;font-weight: normal !important;"
                @endif
                >
                @if(isset(Auth::user()->google2fa_secret) && strlen(Auth::user()->google2fa_secret)>5)
                (Đã kích hoạt)
                @else
                (Chưa kích hoạt)
                @endif
              </label>
            </a>
            @if (!Auth::guest()) <a class="dropdown-item" id="changepassword" data-bs-toggle="modal" href="#changepass-modal" onclick="ShowLoadChangePass({{Auth::user()->id}})">Đổi mật khẩu</a>@endif
          </div>
        </div>

      </li>
    </div>
  </nav>
  <!-- End: Navbar With Button -->
<style>
  marquee {
    display: flex;
    justify-content: space-between;
}
  </style>

  <div class="container white-bg" style="display: none;margin-top: 65px !important;">
  <marquee width="100%" direction="left" height="24px" style="font-size:14px;color:red;">
  <span>Công ty có quyền từ chối, hủy những mã cược bất thường và có biểu hiện gian lận mà không cần thông báo trước. Xin quý khách lưu ý!</span>
  <span>Trước khi vào cược xin quý khách đọc rõ nội quy cược. Khi quý khách vào cược thì sẽ theo luật chơi của công ty để trả thưởng. Công ty, đại lý sẽ không chấp nhận các luật chơi của bên nhà cái khác. Xin cảm ơn!</span>
</marquee>
    <div class="clearfix"></div>
    <div class="row">
      <?php
      $now = \Carbon\Carbon::now();
      $yesterday = date('Y-m-d', time() - 86400);
      if (intval(date('H')) < 19)
        $rs = xoso::getKetQua(1, $yesterday);
      else
        $rs = xoso::getKetQua(1, date('Y-m-d'));
      ?>
      <div class="col-lg-3 col-lg-4" id="info">
        <sidebar-menu>
          @section('sidebar-menu')
          @if(Auth::user()!=null)
          <div class="panel panel-color panel-inverse" id="user_info" hidden>
            <!-- <div class="panel-heading recent-heading">
              <h6 class="panel-title">Thông tin tài khoản</h6>
            </div>
            <div class="panel-body">

              <div class="row">
                <div class="col-6 col-6"><i class="fa fa-user"></i> Hội viên</div>
                <div class="col-6 col-6 text_bold">{{$user->name}}</div>
              </div>
              <div class="row">
                <div class="col-6 col-6"><i class="glyphicon glyphicon-credit-card"></i> Hạn mức còn lại</div>
                <div class="col-6 col-6 text_bold text_red" id="" data-toggle="tooltip" title="0 Chips">0</div>
              </div>
              <div class="row">
                <div class="col-6 col-6"><i class="glyphicon glyphicon-credit-card"></i> Đang cược</div>
                <div class="col-6 col-6 text_bold text_red" id="" data-toggle="tooltip" title="0 Chips">0</div>
              </div>
              <div class="row">
                <div class="col-6 col-6"><i class="glyphicon glyphicon-th"></i> Thắng thua</div>
                <div class="col-6 col-6 text_bold " id="" data-toggle="tooltip" title="0 Chips">0</div>
              </div>
            </div> -->
          </div>
          @endif
          @show
        </sidebar-menu>
      </div>
      <!--<div class="col-sm-2 order-first" id="tab" style="padding-left: 0; display: none">-->
      <!--	<sidebar-menu>-->

      <!--			<div class="panel panel-color panel-inverse" style="border-radius: 0 10px 10px 0">-->

      <!--				<div class="panel-body"  style="padding: 0 !important">-->
      <!-- 							<div class="tableft">-->
      <!--                     			<a>Xổ số truyền thống</a>-->
      <!--                     		</div>-->
      <!--                     		<div class="tableft activetab">-->
      <!--                     			<a>Xổ số</a>-->
      <!--                     		</div>-->
      <!--                     		<div class="tableft">-->
      <!--                     			<a>Xổ số</a>-->
      <!--                     		</div>-->
      <!--				</div>-->
      <!--			</div>-->

      <!--	</sidebar-menu>-->
      <!--</div>-->
      <div class="col-lg-7 order-first col-lg-8" id="main">
      
        <sidebar-menu>
          @yield('content')
        </sidebar-menu>
        <div>
        </div>
      </div>

</body>
@include('admin.changepass')
@include('admin.notification.pin-view')
@include('frontend.configHighlight')
<a id="btn_notify" href="javascript:;" onclick="$.Notification.notify('error','top left', 'Thông báo', 'Tài khoản và mật khẩu của bạn không đúng')"></a>
<input type="hidden" id="url_refresh_login" value="{{url('/refresh-login')}}">
<input type="hidden" id="url_refresh_time" value="{{url('/refresh-time')}}">
<input type="hidden" id="url_open_close_game_timer" value="{{url('/refresh-open-close-game-timer')}}">
<input type="hidden" id="url_refresh_bets_top5" value="{{url('/refresh-bets-top5')}}">
<input type="hidden" id="url_refresh_xsao" value="{{url('/xsaodiv')}}">
<input type="hidden" id="url_rss" value="{{$locations[0]->url_api}}">
<input type="hidden" id="time_result" value="{{$locations[0]->time}}">
<input type="hidden" id="ipaddress" value="undetected">
<script src="/assets/admin/js/fastclick.js"></script>
<!-- Scripts -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->
<!-- <script src="/assets/admin/js/jquery.min.js"></script> -->
<script src="/assets/admin/js/bootstrap.min.js"></script>
<script src="/assets/admin/plugins/notifyjs/dist/notify.min.js"></script>
<script src="/assets/admin/plugins/notifications/notify-metro.js"></script>
<!-- <script>
  $(function() {
	FastClick.attach(document.body);
}); -->
<!-- </script> -->
<!-- <script type="application/javascript">
	window.addEventListener('load', function() {
    new FastClick(document.body);
	}, false);
</script>  -->
@if (env("APP_ENV") == "local")
<script src="/assets/js/script.js?v=20230427_3111111"></script>
@else
@include("frontend.script_patch")
@endif
<script src="/assets/js/select2.min.js"></script>
<script src="/assets/js/icheck.min.js"></script>
<script src="/assets/admin/js/jquery.core.js"></script>
<!-- Input-Mask  -->
<script src="/assets/admin/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
<!-- <script src="assets/admin/plugins/moment/min/moment-with-locales.min.js"></script> -->
<!-- <script src="assets/admin/plugins/moment/moment.js"></script> -->
<!-- <script src="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.js"></script> -->
<!-- <script src="assets/admin/plugins/moment/min/locales.min.js"></script> -->
<script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>
<script src="/assets/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/admin/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.vi.min.js"></script>

<!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script> -->
<script src="/assets/newui/js/sweetalert2.js"></script>

<script src="/assets/admin/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
<script src="/assets/admin/plugins/owl.carousel/dist/owl.carousel.min.js"></script>
<script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>
<script type="text/javascript" src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
<script src="/assets/admin/plugins/sweetalert/dist/sweetalert.min.js"></script>

<script src="/assets/admin/pages/jquery.sweet-alert.init.js"></script>
<script src="/assets/admin/plugins/moment/moment.js"></script>
<script src="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/newui/js/vi.js"></script>


<script src="/assets/newui/js/bootstrap.bundle.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
<script src="/assets/newui/js/script.min.js?h=f943e89ea332fdb4be0c489512e63d1b"></script>



<script href='last'>
  // $('.loadere').hide();
  loopApp();

  function loopApp() {

    $('a[href^="/"]').click(function(event) {
      if ($(this).attr('href') == '/assets/huongdannhaptin.html' || $(this).attr('href') == 'https://{{env("APP_URL","xs")}}/quickplayhistory/1' || $(this).attr('class') == 'hdcuoc') {
        return;
      }
      $('.loadere').show();
      $(".container.white-bg").hide();



      return;
      event.preventDefault();
      seft = this;
      $.ajax({
        url: $(this).attr('href'),
        success: function(response) {
          $('.loadere').hide();
          window.history.pushState('{{env("APP_URL","xs")}}', '{{env("APP_URL","xs")}}', $(seft).attr('href'));

          var matched = response.match(/<body[^>]*>([\w|\W]*)<\/html>/im);
          // $(".container.white-bg").html(matched[1]);
          $(".container.white-bg").show();
          document.body.innerHTML = (matched[1]);

          $('.loadere').hide();

          var scripttext = response;

          var re = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;

          var match;

          var n = 0;
          while (match = re.exec(scripttext)) {
            if (match[1] == 'a = "first"') {
              break;
            }
            if (n == 0 && $(seft).attr('href').indexOf('/play') != -1) {
              textblock = match[1]
              lines = textblock.split('\n');
              lines[1] = lines[35] = "";
              eval(lines.join('\n'));
              console.log("error:" + lines.join('\n'));
              continue;
            }
            console.log(match[1]);
            eval(match[1]);
            n++;
          }


          //     try{

          // $.getScript("/assets/js/script.js", function(data, textStatus, jqxhr) {
          //   console.log('Load was performed.');
          // });

          // //     }catch(e){
          // //     }

          // //     try{

          //     $.getScript("/assets/admin/js/customertype.js?v=1.01", function(data, textStatus, jqxhr) {

          //     });
          // //     }catch(e){
          // //     }
          // // }
          // // try{
          //     $.getScript("/assets/newui/js/script.min.js", function(data, textStatus, jqxhr) {
          //   console.log('Load was performed.');
          // });

          // }catch(e){
          // }







          loopApp();
        }
      });
      // return false; // for good measure
    });
  }
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-252700338-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-252700338-1');
</script>

@section('jquery')
@show
@yield('js_call')

<!-- <script type="text/javascript">

$.getJSON("https://api.ipstack.com/check?access_key=13476c7da2ae5d094e1a4eccf2271af8", function (data) {
	try{
    	console.log(data);
    	// alert(data.ip);
		$('#ipaddress').val(data.ip);
	}catch(err){
		console.log(err);
	}
});

</script> -->
<!--Start of Tawk.to Script-->
<!--<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/586bd51585a24d0d6c844ed2/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>-->
<!--End of Tawk.to Script-->

</html>