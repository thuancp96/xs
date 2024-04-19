<?php
function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>
<?php
if (strpos($_SERVER['HTTP_HOST'], 'ag') !== false) {
    echo view('admin.aglogin', ['playgame' => 0]);
    dd(1);
    die();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ env('APP_NAME', 'xs') }}</title>
    <link rel="stylesheet" href="/assets/newui/bootstrap/css/bootstrap.min.css?h=a595ec6fc975b7a8576d7f6dc204a10b">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="/assets/newui/fonts/fontawesome5-overrides.min.css?h=a0e894d2f295b40fda5171460781b200">
    <link rel="stylesheet" href="/assets/newui/css/styles.min.css?a=<?php echo rand(1, 100000000000) . ''; ?>">
    <link rel="icon" type="image/x-icon" href="/assets/admin/images/logo.png">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
</head>

<body id="page-top" data-bs-spy="scroll" data-bs-target="#mainNav" 1 data-bs-offset="54"
    style="background: url('/assets/images/bglasvegas.jpg') no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover; min-height: 100% ">

    <section id="banner">
        <div class="slider-content">

        </div>
    </section>
    {{-- End: Navbar With Button --}}
    <div class="menu-overlay"> &nbsp;</div>

    <section class="bg-light" id="portfolio"
        style="background: rgba(13,19,28,0) !important;padding: 20px;padding-bottom: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center" style="height: 50px;">
                    <h3 class="text-muted section-subheading" style="text-align: left;padding: 0px 0px 10px 0px;">
                        <br>
                        <i class="fas fa-home text-start"
                            style="border-color: rgb(255,255,255);color: rgb(147,172,211);"></i>
                        <strong>
                            <span style="color: rgb(147, 172, 211);">&nbsp;Trò chơi xổ số</span>
                        </strong>
                    </h3>
                </div>
            </div>
            <div class="row" style="padding-top: 20px;">
                <div class="col-sm-1 col-md-2 portfolio-item">
                    <a class="portfolio-link" href="#portfolioModal1" data-bs-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-play-circle fa-3x"></i>
                            </div>
                        </div>
                        <img class="img-fluid" src="/assets/newui/img/1.jpg?h=e5ce4c56b6a4fa36ad4280f2a02ae930" />
                    </a>
                    <div class="portfolio-caption"
                        style="border-bottom-right-radius: 14px;border-bottom-left-radius: 14px; background-image: linear-gradient(transparent 25%, #161f2c 95%); background-color: #161f2c; color: white">
                        <a>Miền Bắc</a>
                    </div>
                </div>
                </li>
                <div class="portfolio-item col-sm-1 col-md-2">
                    <a class="portfolio-link" href="#portfolioModal1" data-bs-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-play-circle fa-3x"></i>
                            </div>
                        </div>
                        <img class="img-fluid"
                            src="/assets/newui/img/pngtree-bingo-game-design-lotto-card-and-ball-png-image_2925312.jpg?h=3ffe2fed226fea9478450c4a1d41d432" />
                    </a>
                    <div class="portfolio-caption"
                        style="border-bottom-right-radius: 14px;border-bottom-left-radius: 14px; background-image: linear-gradient(transparent 25%, #161f2c 95%); background-color: #161f2c; color: white">
                        <a>Xổ Số Ảo</a>
                    </div>
                </div>
                <div class="col-sm-1 col-md-2 portfolio-item">
                    <a class="portfolio-link" href="#portfolioModal1" data-bs-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-play-circle fa-3x"></i>
                            </div>
                        </div>
                        <img class="img-fluid"
                            src="/assets/newui/img/tat-tan-tat-ve-xo-so-keno-vietlott-cho-tan-binh-moi-1.jpg?h=f8c994b5c3c2978a672179efe3233925" />
                    </a>
                    <div class="portfolio-caption"
                        style="border-bottom-right-radius: 14px;border-bottom-left-radius: 14px; background-image: linear-gradient(transparent 25%, #161f2c 95%); background-color: #161f2c; color: white">
                        <a>Keno</a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- Modal --}}


    @include('admin.user.newguest')

    <input type="hidden" id="urlipaddress" value="{{ $_SERVER['HTTP_HOST'] }}">
    <input type="hidden" id="ipaddress" value="{{ getUserIpAddr() }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
    <script src="/assets/newui/js/script.min.js?h=f943e89ea332fdb4be0c489512e63d1b"></script>
    <script>
        $("#cpa-form").submit(function(e) {
            return false;
        });

        jQuery(document).ready(function($) {

            localStorage.setItem('userConfirmBet', 0)
            localStorage.setItem('userConfirmBetSuccess', 0)

            $('#username').keyup(function(e) {
                if (e.keyCode == 13) {
                    $("#btn_login").click();
                }
            });
            $('#password').keyup(function(e) {
                if (e.keyCode == 13) {
                    $("#btn_login").click();
                }
            });

            $("#btn_login").click(function() {
                $("#nof").hide();
                $("#status").show();

                $_token = "{{ csrf_token() }}";
                $.ajax({
                    url: "{{ url('/auth/login') }}",
                    method: 'POST',
                    dataType: 'html',
                    data: {
                        do_login: true,
                        username: $('#username').val(),
                        passwd: $('#password').val(),
                        ipadr: $('#ipaddress').val(),
                        otp: $('#otp').val(),
                        _token: $_token,
                    },
                    success: function(data) {
                        $("#status").hide();
                        if (data == "true") {
                            window.location.href = "{{ url('/') }}";

                        }
                        if (data == "admin") {
                            window.location.href = "{{ url('admin') }}";
                        }
                        if (data == "false") {
                            $("#nof").html("Tài khoản hoặc mật khẩu của bạn không đúng.");
                            $("#nof").show();
                            console.log('False:', data);
                        }
                        if (data == "otp") {
                            if ($('#otp').val() == "") {
                                $('#otp_form_group').removeClass("hidden").focus()
                                $('#username_form_group').addClass("hidden")
                                $('#password_form_group').addClass("hidden")

                                $("#nof").html("Hãy nhập Mã OTP của bạn để đăng nhập.");
                                $("#nof").show();
                            } else {
                                $("#nof").html(
                                    "Mã OTP của bạn không đúng. F5 hoặc ctrl+F5 nếu thử lại không thành công."
                                );
                                $("#nof").show();
                                console.log('False:', data);
                            }

                        }

                        if (data == "lock") {
                            $("#nof").html("Tài khoản của bạn đã bị khóa.");
                            $("#nof").show();
                            console.log('False:', data);
                        }
                    },
                    error: function(data) {
                        $("#status").hide();
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>

</body>

</html>
