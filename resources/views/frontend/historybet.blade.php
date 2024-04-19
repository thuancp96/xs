<?php

$now = \Carbon\Carbon::now();
    $yesterday = date('Y-m-d', time()-86400);
    $datepickerXS= date('d-m-Y', time()-86400);
    if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('i') )<30)){
        // $rs = xoso::getKetQua(1,$yesterday);
    }
    else{
        // $rs = xoso::getKetQua(1,date('Y-m-d'));
        $datepickerXS= date('d-m-Y');
    }
$gameList = GameHelpers::GetAllGameByParentID(0,$location->slug);
?>
@extends("frontend.frontend-template")
@section('sidebar-menu')
    @parent
        <style>
            button.btn.btn-danger.btn-xs.btn-betlist {
            font-size: 10px;
            text-decoration: none !important;
        }
 

    </style>
    <div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Thời gian còn lại</h3>
		</div>
		<div class="panel-body hidden" id="open_close_game_timer" >
			@foreach($gameList as $game)
			<div class="row">
				<!-- <div class="col-xs-4"><b>{{$game['name']}}: </b></div> -->
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
				<input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
				<!-- <div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div> -->
			</div>
			@endforeach
		</div>
		<div class="panel-body">
			@foreach($gameList as $game)
			<div class="row">
				<div class="col-xs-6"><b>{{$game['name']}}: </b></div>
				<!-- <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> -->
				<!-- <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> -->
				<div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
			</div>
			@endforeach
		</div>
	</div>

@stop
@section("content")
    <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
        <div class="panel-body">
            <div class="row">
                <div class="input-group m-t-10 hidden">
                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="01/01/2015 - 01/31/2015" readonly="readonly" >
                    <span class="input-group-btn">
                    <!-- <button type="button" class="btn waves-effect waves-light btn-primary" id="enter_array">Xác nhận</button> -->
                    <a href="javascript:void(0)" class="btn waves-effect waves-light btn-primary" id="btn_view_history">Xem</a>
                    </span>
                </div>
                
                <!-- <div class="col-sm-4">
                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="01/01/2015 - 01/31/2015">
                </div>
                <div class="col-sm-2">
                    <a href="javascript:void(0)" class="btn btn-danger" id="btn_view_history">Xem</a>
                </div> -->
            </div>
            <br/>
            <div class="row">
                <div class="table-responsive" id="div_history">


                @if(count($xosorecords)>0)
                    <table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
                        <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Thời gian</th>
                            <th>Đài</th>
                            <th>Thể loại</th>
                            <th>Số cược</th>
                            <th>Giá</th>
                            <th>Trả thưởng</th>
                            <th>Tổng điểm</th>
                            <th>Thành tiền</th>
                            <th>Thắng/Thua</th>
                            <th>Ghi chú</th>
                            <th style="text-align: center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $total_bet_money = 0;
                            $total_win_money = 0;
                                    $total_point = 0;
                        ?>
                        @foreach($xosorecords as $xosorecord)
                        <?php
                                                        $betpoint = 1;
                                                        $y3 = 0;
                                                        if ($xosorecord->game_id >=31 and $xosorecord->game_id <= 55)
                                                            $y3 = GameHelpers::GetGameByGameCode(24)->y3;
                                                        else{
                                                            switch ($xosorecord->game_id) {
                                                                case 17: case 29: case 56: case 15:
                                                                    $y3 = GameHelpers::GetGameByGameCode(14)->y3;
                                                                    break;
                                                                case 19: case 20: case 21:
                                                                    $y3 = GameHelpers::GetGameByGameCode(9)->y3;
                                                                    break;
                                                                default:
                                                                $y3 = GameHelpers::GetGameByGameCode($xosorecord->game_id)->y3;
                                                                    break;
                                                            }
                                                        }
                                                    ?>
                            <tr>
                                <td>{{$xosorecord->id}}</td>
                                <td>{{date("H:i:s d-m-y", strtotime($xosorecord->created_at))}} @if ($xosorecord->game_id >= 700 && $xosorecord->game_id < 800 && isset($xosorecord->xien_id)) ( Kỳ {{$xosorecord->xien_id}}) @endif</td>
                                @if ($xosorecord->locationslug>20 && $xosorecord->locationslug!=70)
                                <td>{{GameHelpers::ChuyenDoiDai($xosorecord->locationslug)}}</td>
                                @else
                                <td>{{$xosorecord->location}}</td>
                                @endif
                                <td>{{$xosorecord->game}}</td>
                                <td>{{$xosorecord->bet_number}} @if ($xosorecord->game_id == 18 || $xosorecord->game_id == 9 || $xosorecord->game_id == 10 || $xosorecord->game_id == 11 || $xosorecord->game_id == 29) ({{27-$xosorecord->xien_id}}) @endif @if ($xosorecord->game_id >= 100 && $xosorecord->game_id <= 200 && isset($xosorecord->xien_id) && $xosorecord->xien_id <=24) ( Kỳ {{$xosorecord->xien_id}}) @endif</td>
                                <td>{{number_format($xosorecord->exchange_rates,0)}}</td>
                                <td>{{number_format($xosorecord->odds,0)}}</td>
                                <td class="text_right">
                                        @if ($xosorecord->exchange_rates != 0)
                                            @if($xosorecord->game_id==29 || $xosorecord->game_id==329 || $xosorecord->game_id==429 || $xosorecord->game_id==529 || $xosorecord->game_id==629 || $xosorecord->game_id==9 || $xosorecord->game_id==309 ||$xosorecord->game_id==409 ||$xosorecord->game_id==509||$xosorecord->game_id==609||$xosorecord->game_id==709 || $xosorecord->game_id==10 || $xosorecord->game_id==310 ||$xosorecord->game_id==410 ||$xosorecord->game_id==510||$xosorecord->game_id==610||$xosorecord->game_id==710 || $xosorecord->game_id==11 || $xosorecord->game_id==311 ||$xosorecord->game_id==411 ||$xosorecord->game_id==511||$xosorecord->game_id==611||$xosorecord->game_id==711
                                            || $xosorecord->game_id==21 || $xosorecord->game_id==20 || $xosorecord->game_id==19)
                                                <?php
                                                    $fact=1;
                                                    switch ($xosorecord->game_id) {
                                                        case 29:
                                                        case 329:
                                                        case 429:
                                                        case 529:
                                                        case 629:
                                                        case 9:
														case 309:
														case 409:
														case 509:
                                                        case 609:
                                                            case 709:
                                                            $fact=2;
                                                            break;
														case 10:
														case 310:
														case 410:
														case 510:
                                                        case 610:
                                                            case 710:
                                                            $fact=3;
                                                            break;
														case 11:
														case 311:
														case 411:
														case 511:
                                                        case 611:
                                                            case 711:
                                                            $fact=4;
                                                            break;
                                                        case 21:
                                                            $fact=10;
                                                            break;
                                                        case 20:
                                                            $fact=8;
                                                            break;
                                                        case 19:
                                                            $fact=4;
                                                            break;
                                                        
                                                        default:
                                                            # code...
                                                            break;
                                                    }
                                                    $countbetnumber = count(explode( ',', $xosorecord->bet_number));
                                                    $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact($fact)/XoSoRecordHelpers::fact($countbetnumber-$fact);
                                                    $betpoint=$xosorecord->total_bet_money/$xosorecord->exchange_rates; ///$Ank
                                                ?>
                                                {{number_format($betpoint,0)}}
                                            @else
                                                    <?php
                                                        $betpoint = $xosorecord->total_bet_money/$xosorecord->exchange_rates;
                                                    ?>
                                                {{number_format($xosorecord->total_bet_money/$xosorecord->exchange_rates,0)}}
                                            @endif
                                            <?php
                                    
                                    $total_point += $xosorecord->total_bet_money/$xosorecord->exchange_rates;
                                ?>
                                        @else
                                            0
                                        @endif
                                        </td>
                                <td class=" text_bold pr10 suminvoice">{{number_format($xosorecord->total_bet_money,0)}}</td>
                                <?php
                                // fix tra thuong
                                $win_money = $xosorecord->total_win_money;
                                if ( $win_money > 0)
                                {
                                    if ($xosorecord->game_id == 15){
                                    }else
                                        $win_money -= $xosorecord->total_bet_money;
                                }
                                ?>
                                @if($win_money>0)
                                            <td class=" text_bold">
                                                {{number_format($win_money,0)}}
                                            @elseif ($win_money<0)
                                            <td class=" text_bold" style=" color:red;">
                                                {{number_format($win_money,0)}}
                                            @elseif ($win_money==0)
                                            <td class=" text_bold">
                                                Chưa xử lý
                                            @endif
                                        </td>
                                        <td>{{isset($xosorecord->ipaddr1)?$xosorecord->ipaddr1:"" }}</td>
                                <td>
                                <button class="btn btn-danger btn-xs btn-betlist">
                                    <a style="color: white;" href="#" class="btn_huycuoc not-active hidden" onclick="setId('{{$xosorecord->id}}','{{$xosorecord->game_id}}')" id="btn_cancel_{{$xosorecord->id}}" game_bet_id="{{$xosorecord->id}}">Hủy</a>
                                </button>
                                <input type="hidden" class="time_bet" id="time_bet" gameid="btn_cancel_{{$xosorecord->id}}" point_bet="{{$betpoint}}" cancel_money="{{number_format($betpoint*$y3,0)}}" game_bet_id="{{$xosorecord->game_id}}" value="{{$xosorecord->created_at}}">
                                </td>
                            </tr>
                            <?php
                            $total_bet_money+=$xosorecord->total_bet_money;
                            // fix tra thuong
                            $total_win_money+=$win_money;
                            // if ( $win_money > 0)
                            // {
                            //     $total_win_money += ($xosorecord->total_win_money-$xosorecord->total_bet_money);
                            // }else
                            //     $total_win_money += $xosorecord->total_win_money;
                        ?>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <td colspan="8" class="text_right pr10">Tổng tiền đã cược</td>
                            <td class="text_right pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
                            <td class="text_right pr10 suminvoice"
                            @if ($total_win_money < 0)
                                style=" color:red;"
                            @endif
                            >{{number_format($total_win_money,0)}}</td>

                            </tr>
                        </tfoot>
                    </table>
                    @else
                        Chưa tham gia cược
                    @endif
                </div>
            </div>

        </div>

    </div>
    <input type="hidden" id="current_game" value="">
    <input type="hidden" id="loto-id-delete" value="">
    <input type="hidden" id="url" value="{{url('/')}}">
    <input type="hidden" id="urlH" value="{{url('/history')}}">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
    <a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã tạo thành công')"></a>
@endsection

<script type="text/javascript">

document.addEventListener("DOMContentLoaded", function(event) { 
      $('.modal').each(function() {
            $(this).insertAfter($('#game-play'));
      });
      
      $(document).ready(function() {
                // $('#btn_view_history_sk').click();
      });
      
    });
    
    function refreshHistory() {
        $('#history').fadeOut();
        $('#history').load("{{url('/refresh-history')}}", function() {
            $('#history').fadeIn();
            $('[data-toggle="tooltip"]').tooltip();
        });
    }

    /**
 * Hàm set giá trị id cho biến hidden đế thực hiện xóa
 * @param id
 */
function setId(id,gameid) {
    // clock_{{$game['game_code']}}
    if ($('#clock_'+gameid).html() == "00:00:00")
    {
        alert("Hết giờ hủy cược");
    }else{
    $('#loto-id-delete').val(id);
    swal({
        title: "Bạn có muốn huỷ mã cược "+ id +"? Tự đóng sau 10 giây!",
        timer: 10000,
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Hủy",
        cancelButtonText: "Bỏ qua",
        closeOnConfirm: false
    },function(isConfirm){
        if (isConfirm) {
            $_token = $('#token').val();
            $.ajax({
                url: $('#url').val()+"/destroy/"+$('#loto-id-delete').val(),
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: $_token,
                },
                success: function(data)
                {
                    swal("Đã Hủy!", "Bạn đã hủy thành công", "success");
                    var s = $('#time-zone').html();
                    if (!(!s || 0 === s.length))
                    {
                    var time_result = $('#time_result').val();
                    var d = s.split(' ');
                    var ddate = d[0].split('-');

                    // refreshHistory(d[0]);
                    location.reload();
                    refreshUser_Info();

                }
                },
                error: function (data) {
                }
            });
        } else {
           swal.close();
        }
    });
    }
}
</script>
