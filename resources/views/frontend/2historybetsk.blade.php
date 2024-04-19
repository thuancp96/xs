<?php
$now = \Carbon\Carbon::now();
    $yesterday = date('Y-m-d', time()-86400);
    $datepickerXS= date('d-m-Y', time()-86400);
    if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('i') )<30)){
        $rs = xoso::getKetQua(1,$yesterday);
    }
    else{
        $rs = xoso::getKetQua(1,date('Y-m-d'));
        $datepickerXS= date('d-m-Y');
    }
$gameList = GameHelpers::GetAllGameByParentID(0);
?>
@extends("frontend.frontend-template")
@section('sidebar-menu')
    @parent
    <div class="panel panel-color panel-inverse hidden-xs">
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
                        <div class="col-md-3 fourth4th"><span class="badge badge-blue">{{$item}}</span></div>
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
                        <div class="col-md-3 seventh4th"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-color panel-inverse">
        <div class="panel-heading recent-heading">
            <h3 class="panel-title">Thời gian còn lại</h3>
        </div>
        <div class="panel-body">
            @foreach($gameList as $game)
                <div class="row">
                    <div class="col-6"><b>{{$game['name']}}: </b></div>
                    <input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
                    <input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
                    <div class="col-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
                </div>
            @endforeach
        </div>
    </div>
@stop
@section("content")
    <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
        <div class="panel-body">
            <div class="row">
                <div class="input-group m-t-10">
                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="01/01/2015 - 01/31/2015" readonly="readonly" >
                    <span class="input-group-btn">
                    <!-- <button type="button" class="btn waves-effect waves-light btn-primary" id="enter_array">Xác nhận</button> -->
                    <a href="javascript:void(0)" class="btn waves-effect waves-light btn-primary" id="btn_view_history_sk">Xem</a>
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
                            <th>Tổng điểm</th>
                            <th>Thành tiền</th>
                            <th>Thắng/Thua</th>
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
                            <tr>
                                <td>{{$xosorecord->xien_id}}</td>
                                <td>{{date("h:i:s d-m-y", strtotime($xosorecord->created_at))}}</td>
                                <td>MB1</td>
                                <td>{{$xosorecord->game}}</td>
                                <td>{{$xosorecord->bet_number}}</td>
                                <td>{{number_format($xosorecord->exchange_rates,0)}}</td>
                                <td class="text_right">
                                        @if ($xosorecord->exchange_rates != 0)
                                            {{number_format($xosorecord->total_bet_money/$xosorecord->exchange_rates,0)}}
                                            <?php
                                    
                                    $total_point += $xosorecord->total_bet_money/$xosorecord->exchange_rates;
                                ?>
                                        @else
                                            0
                                        @endif
                                        </td>
                                <td class="text_right text_bold pr10 suminvoice">{{number_format($xosorecord->total_bet_money,0)}}</td>
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
                                            <td class="text_right text_bold">
                                                {{number_format($win_money,0)}}
                                            @elseif ($win_money<0)
                                            <td class="text_right text_bold" style=" color:red;">
                                                {{number_format($win_money,0)}}
                                            @elseif ($win_money==0)
                                            <td class="text_right text_bold">
                                                Chưa xử lý
                                            @endif
                                        </td>
                                <td>
                                <button class="btn btn-danger btn-xs btn-betlist">
                                    <a style="color: white;" href="#" class="btn_huycuoc not-active hidden" onclick="setId('{{$xosorecord->id}}','{{$xosorecord->game_id}}')" id="btn_cancel_{{$xosorecord->id}}" game_bet_id="{{$xosorecord->id}}">Hủy</a>
                                </button>
                                <input type="hidden" class="time_bet" id="time_bet" gameid="btn_cancel_{{$xosorecord->id}}" game_bet_id="{{$xosorecord->game_id}}" value="{{$xosorecord->created_at}}">
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
                            <td colspan="7" class="text_right pr10">Tổng tiền đã cược</td>
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
    <input type="hidden" id="urlH" value="{{url('/history-sk')}}">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
    <a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã tạo thành công')"></a>
@endsection

<script type="text/javascript">

    function refreshHistorySk() {
        $('#history').fadeOut();
        $('#history').load("{{url('/refresh-history-sk')}}", function() {
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
        title: "Bạn có chắc chắn hủy?",
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
           // swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
    });
    }
}
</script>
