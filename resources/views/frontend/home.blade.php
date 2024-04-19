<?php
// header('location: /play/1');
$now = \Carbon\Carbon::now();
$yesterday = date('Y-m-d', time() - 86400);
$datepickerXS = date('d-m-Y', time() - 86400);
if (intval(date('H')) < 18 || (intval(date('H')) == 18 && intval(date('i')) < 30)) {
    $rs = xoso::getKetQua(1, $yesterday);
} else {
    $rs = xoso::getKetQua(1, date('Y-m-d'));
    $datepickerXS = date('d-m-Y');
}

$gameList = GameHelpers::GetAllGameByParentID(0);
?>

@extends('frontend.frontend-template')

@section('sidebar-menu')
@parent
<!-- <div class="panel panel-color panel-inverse hidden-xs">
        <div class="panel-heading recent-heading">
            <h3 class="panel-title">Kết quả ngày <span class="badge badge-blue">{{$rs['date']}}</span></h3>
        </div>
        <div class="panel-body">
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>ĐB</b></div>
                <div class="col-md-10">
                    <div class="col-md-12 jackpot"><span class="badge badge-blue">{{$rs['DB']}}</span></div>
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G1</b></div>
                <div class="col-md-10">
                    <div class="col-md-12 first"><span class="badge badge-blue">{{$rs['1']}}</span></div>
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G2</b></div>
                <div class="col-md-10">
                    @foreach($rs['2'] as $item)
                        <div class="col-md-6 second1st"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G3</b></div>
                <div class="col-md-10">
                    @foreach($rs['3'] as $item)
                        <div class="col-md-4 third3rd"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G4</b></div>
                <div class="col-md-10">
                    @foreach($rs['4'] as $item)
                        <div class="col-md-3 fourth5th"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G5</b></div>
                <div class="col-md-10">
                    @foreach($rs['5'] as $item)
                        <div class="col-md-4 fiveth6th"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach

                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G6</b></div>
                <div class="col-md-10">
                    @foreach($rs['6'] as $item)
                        <div class="col-md-4 sixth3rd"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G7</b></div>
                <div class="col-md-10">
                    @foreach($rs['7'] as $item)
                        <div class="col-md-3 seventh5th"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div> -->
<div class="panel panel-color panel-inverse">
    <div class="panel-heading recent-heading">
        <h3 class="panel-title">Thời gian còn lại</h3>
    </div>
    <div class="panel-body hidden" id="open_close_game_timer">
        @foreach($gameList as $game)
        <div class="row">
            <!-- <div class="col-xs-6"><b>{{$game['name']}}: </b></div> -->
            <input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
            <input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
            <!-- <div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div> -->
        </div>
        @endforeach
    </div>
    <div class="panel-body">
        @foreach($gameList as $game)
        @if ($game['game_code'] == 2 || $game['game_code'] == 17 || $game['game_code'] == 56 || $game['game_code'] == 27 || $game['game_code'] == 28 || $game['game_code'] == 26 || $game['game_code'] == 15 || $game['game_code'] == 3)
        <div class="row" hidden>
            <div class="col-xs-6"><b>{{$game['name']}}: </b></div>
            <!-- <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> -->
            <!-- <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> -->
            <div class="col-xs-6">
                <p class="clock" id="clock_{{$game['game_code']}}"></p>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-xs-6"><b>{{$game['game_code'] == 25 ? 'Thần tài' : $game['name']}}: </b></div>
            <!-- <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> -->
            <!-- <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> -->
            <div class="col-xs-6">
                <p class="clock" id="clock_{{$game['game_code']}}"></p>
            </div>
        </div>
        @endif

        @endforeach
    </div>
</div>
@stop

@section('content')
<!--  -->
<style>
    li, ul {
    margin: 0;
    padding: 0;
    color: black !important;
    text-decoration: none !important;
}
div#game-play li{
  /* list-style-image: url(/right-arrow.svg); */
  /* OR */
  /* 🚩 */
  list-style-type: '👉';
  padding-inline-start: 1ch;
  /* margin-right: 3px; */
}
div.ml-15 li{
  /* list-style-image: url(/right-arrow.svg); */
  /* OR */
  /*  */
  list-style-type: '🚩' !important;
  padding-inline-start: 1ch;
  /* margin-right: 3px; */
}
.ml-15{
    margin-left: 20px !important;
}
    </style>
<div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
    <div class="panel-body">
        
        <!-- <h3>Thông báo</h3> -->
        <!-- @if(isset($thongbao1))
					<h5>{{$thongbao1}}</h5>
				@endif
            <p>
            - Người chơi đọc kỹ luật chơi trước khi cược.
            </br>
            - Người chơi hãy xem kỹ bảng cược sau khi cược tránh cược nhầm hoặc cược lại những mã đã cược. 
            </br>
            - Khi xảy ra lỗi, sai phạm xin hãy báo cho quản lý, admin để xử lý.
            </br>
            - Nếu biết sai phạm còn cố tình vào cược để ăn tiền bị phát hiện sẽ xử lý mã cược đó là thua.
            </p> -->
        <h5>Quy định cho người chơi khi tham gia đặt cược: </h5>
        <p>Các cá cược của 99luckey là rõ ràng và minh bạch. Để đảm bảo theo đúng quy định của tổ chức cá cược thế giới nhà
            cái đã đặt ra một số quy định đối với người chơi khi đăng ký tham gia cược như sau:</p>
        <li> Người chưa đủ 18 tuổi sẽ không đủ điều kiện tham gia.</li>
        <li> Người có bệnh lý về thần kinh, không có khả năng tự kiểm soát được hành vi của mình sẽ không được tham gia đặt
            cược tại đây.</li>
        <li> Nhân viên làm việc trong đội ngũ nhà cái và các nhà cái khác sẽ không được tham gia.</li>
        <br>
        <h5>Các điều khoản trong đặt cược: </h5>
        <!-- <ul> -->
            <li> Tất cả các mã cược có thông tin cược rõ ràng.</li>
            <li style="color:red !important; font-weight:bold;">Tất cả cả mã cược không hợp lệ sẽ được trả lại:</li>
            <div class="ml-15">
                <li> Quá thời gian quy định, hoặc đã ra kết quả.</li>
                <li> Khi đánh vào dàn số báo, mua
                    <br>
                    Ví dụ: Hôm quay kết quả lô có tổng đầu với đít không ra trên 3 thì :
                    <br>
                    - Thể loại Lô có nhiều số vào dàn thì sẽ huỷ.
                    <br>
                    - Thể Loại Xiên2, Xiên3, Xiên4, Xiên Nháy có 100% số trong dàn
                    <br>
                    - Thể Loại trượt không có số nào trong dàn
                </li>
            </div>
            <!-- </ul> -->
        <!-- </ul> -->
        <br>
        <h5>Quyền lợi của người chơi:</h5>
        <li>Các thông tin đặt cược đều được giữ bí mật.</li>
        <li>Người chơi có quyền yêu cầu nhà cái cho biết số tiền cược thắng thua của mình.</li>
        <li>Nhà cái cần đảm bảo cách tính tiền cược thắng thua cho mỗi thành viên một cách minh bạch và rõ ràng.</li>
        <li>Nhà cái không được theo dõi ip người chơi và chia sẻ cho bên thứ 3</li>
    </div>
</div>

<input type="hidden" id="url_kqsx" value="{{url('/kqsx-by-day')}}">
<input type="hidden" id="url" value="{{url('/')}}">

@endsection