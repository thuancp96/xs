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
  <link rel="stylesheet" href="/assets/newui/css/styles.min.css?v=20230427_2">

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

    .container.white-bg{
      margin-top: 56px !important;
      display: none; 
      overflow: hidden; 
      padding-left: 0; 
      padding-right: 0;
      width:100vw; 
      height: calc(100vh - 56px);
      position: relative;
      margin-bottom: -7px;
      min-height: 575px !important;
      max-height: calc(100dvh - 56px);
      min-width: 275px !important;
      max-width: 100dvw;
      background-color: white;
    }
  </style>

</head>
<div class='loadere' style="display: block"></div>
<div class="menu-overlay"></div>
<div class="hidden" id="time-zone"></div>

<body id="page-top" data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-offset="54" style="background: linear-gradient(180deg,#f5f7fd,#dce2fa);">
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

      ?>

      <li class="nav-item ">
        <div class="nav-item dropdown nav-link ml-auto pull-right">
        @if (count($total_message) > 0)
          <a class="dropdown-toggle" style="color: #ffffff;text-decoration: none;" aria-expanded="false" data-bs-toggle="dropdown" href="#">
            <strong><i class="icon-bell" style="color:red;font-weight: normal !important;"></i> <span class="badge badge-xs badge-danger" style="color:red;font-weight: normal !important;">{{count($total_message)}}</span>
            </strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-lg toptop">
            <li class="notifi-title">Thông báo</li>
            <li class="list-group nicescroll notification-list" tabindex="5000" style="overflow-x: hidden;overflow-y: auto; outline: none;max-height: 468px !important;">
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
                    <p class="m-0" style="white-space: normal; font-style: normal !important; font-weight:200;text-transform: capitalize !important;">
                      <small>{{$message->message}}</small>
                    </p>

                    <p class="m-0" style="text-align: right; font-style: normal !important; font-weight:200;">
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
          <?php $user = Auth::user(); ?>
        </div>

      </li>
    </div>
  </nav>
  <!-- End: Navbar With Button -->


  <div class="container white-bg">
    <div class="clearfix"></div>
    @yield('content')
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
<!-- Scripts -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->
<!-- <script src="/assets/admin/js/jquery.min.js"></script> -->
<script src="/assets/admin/js/bootstrap.min.js"></script>
<script src="/assets/admin/plugins/notifyjs/dist/notify.min.js"></script>
<script src="/assets/admin/plugins/notifications/notify-metro.js"></script>
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

</html>