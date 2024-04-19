<?php
    $ids = explode(',', $item->rawBet->ids);
    $xosorecords = DB::table('xoso_record')
        ->orderBy('id', 'desc')
        // ->where('isDelete',false)
        ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        ->join('location', 'games.location_id', '=', 'location.id')
        ->whereIn('xoso_record.id', $ids)
        ->select(
            'xoso_record.*',
            'games.name as game',
            'location.name as location',
            'location.slug as locationslug'
        )
        ->get();
    // var_dump($xosorecords);
    // $stt = 0;
    $total_win_money = 0;
    foreach ($xosorecords as $key => $record) {
        if($record->game_id == 15 || $record->game_id == 16){
            $total_win_money+= $record->total_win_money;
        }else{
            if($record->total_win_money > 0)
                $total_win_money+= ($record->total_win_money-$record->total_bet_money);
            else
                $total_win_money+= $record->total_win_money;
        }
    }
    ?>
<div class="row" style="font-size: 0.85em !important;">
    <div class="col-sm-12">
        <div class="portlet"><!-- /primary heading -->
            <div class="col-lg-12 line-break" style="text-align: left !important;background: #3f86c3; color:white;" bis_skin_checked="1">
                <label style="display: flex; align-items: center;">
                    @if ($item->locationslug>20 && $item->locationslug !=50 && $item->locationslug !=60 && $item->locationslug !=70 && $item->locationslug !=80)
                    <label>{{GameHelpers::ChuyenDoiDaiByDate($item->locationslug,strtotime($item->created_at))}}</label>
                    @else
                    <label>{{$item->location}}</label>
                    @endif
                    <!-- <label>{{$item->game}}</label> -->

                    <div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">
                        <em style="font-weight:500; font-size:12px;">{{isset($item->created_at) ? $item->created_at : ""}}</em>

                    </div>
                </label>
            </div>
            @if(isset($item->rawBet->source_bet) && $item->rawBet->source_bet != 0)
            <div class="col-lg-12 line-break" style="text-align: left !important; color:#3f86c3; font-weight:600;" bis_skin_checked="1">
                <label style="display: flex; align-items: center;">
                    Cược tin
                    <!-- <div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">
                        <em style="font-weight:500; font-size:12px;">{{isset($item->id) ? $item->id : ""}}</em>
                    </div> -->
                </label>
            </div>
            @endif
            <div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
                <?php
                $maxLengthSplit = 51;
                $text = $item->content;
                $resultSplit = "";
                if (strlen($text) > $maxLengthSplit) {
                    $temp = $text;
                    $isShowName = true;
                    $countWhile = 0;
                    while (true) {
                        $countWhile++;
                        if ($countWhile > 100) break;
                        $sub_temp = substr($temp, 0, $maxLengthSplit);
                        $temp = substr($temp, $maxLengthSplit);
                        $resultSplit .= ($sub_temp . "<br>");
                        $isShowName = false;
                        if (strlen($temp) == 0) break;
                        if ($temp[0] == ",") $temp = substr($temp, 1);
                        if (strlen($temp) <= $maxLengthSplit) {
                            $sub_temp = substr($temp, 0, strlen($temp));
                            $resultSplit .= ($sub_temp);
                            break;
                        }
                    }
                } else
                    $resultSplit = $item->content;
                ?>
                <div style="text-align: left;">{!!$resultSplit!!}
                    <br>
                    @if(isset($item->rawBet->cancel) && $item->rawBet->cancel != "")
                    <span style="color:red;">Hủy: {{$item->rawBet->cancel}}</span>
                    <br>
                    @endif
                    <b>Đặt cược {{number_format($item->total_bet_money)}}</b>
                </div>
            </div>

            @if($total_win_money != 0)
                <div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
                <span class="flex text-sm " style="@if ($total_win_money < 0) color:red; @else color:#2596be; @endif font-weight:700;">Thắng thua: {{number_format($total_win_money)}}</span>
                </div>
            @endif
            
            <div class="col-lg-12 line-break" style="text-align: left !important; color:#3f86c3; font-weight:600;" bis_skin_checked="1">

                <details>
                    <summary>Chi tiết ({{isset($item->rawBet->id_inday) ? ("".$item->rawBet->id_inday) : ""}} {{isset($item->id) ? ("- ".$item->id) : ""}}) </summary>
                    <div class="row">
                        <div class="table-responsive" id="div_history">
                            <div class="table-rep-plugin">
                                <div class="table-responsive">
                                    <table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important;">
                                        <thead>
                                            <tr>
                                                <!--<th>Hội viên</th>-->
                                                <!-- <th>Đài</th> -->
                                                <!-- <th>Thể loại</th> -->
                                                <!-- <th>Thời gian</th> -->
                                                <th>Số cược</th>
                                                <th>Giá</th>
                                                <th>Trả thưởng</th>
                                                <th>Điểm</th>
                                                <!-- <th>Thực thu</th> -->
                                                @if($typeView == 2)
                                                <th>Ghi chú</th>
                                                @endif
                                                <!-- <th>Số trúng thưởng</th> -->
                                                <!-- <th>Tổng số tiền thắng</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($xosorecords as $stt => $xosorecord)
                                            <tr>
                                                <!-- <td>{{date("d-m H:i", strtotime($xosorecord->created_at))}} @if ($xosorecord->game_id >= 700 && $xosorecord->game_id < 800 && isset($xosorecord->xien_id)) ( Kỳ {{$xosorecord->xien_id}}) @endif</td> -->
                                                <!-- <td>{{GameHelpers::ChuyenDoiDaiByDate($xosorecord->locationslug,strtotime($xosorecord->created_at))}}</td> -->

                                                <td>


                                                    <?php

                                                    $highlightbet = $xosorecord->bet_number;

                                                    $arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
                                                    foreach ($arrbet as $bet) {
                                                        if (empty($bet)) continue;
                                                        if (strpos($xosorecord->win_number, $bet) !== false) {
                                                            $highlightbet = str_replace($bet, "<b>" . $bet . "</b>", $highlightbet);
                                                        }
                                                    }
                                                    echo $highlightbet;
                                                    if (
                                                        $xosorecord->game_id == 18 ||
                                                        $xosorecord->game_id == 9 ||
                                                        $xosorecord->game_id == 10 ||
                                                        $xosorecord->game_id == 11 ||
                                                        $xosorecord->game_id == 29
                                                    ) {
                                                        echo "(" . (27 - $xosorecord->xien_id) . ")";
                                                    }
                                                    if (
                                                        $xosorecord->game_id >= 100 &&
                                                        $xosorecord->game_id <= 200 &&
                                                        isset($xosorecord->xien_id) &&
                                                        $xosorecord->xien_id <= 24
                                                    ) {
                                                        echo "(Kỳ " . $xosorecord->xien_id . ")";
                                                    }
                                                    ?></td>

                                                <td class="text_center text-bold">{{number_format($xosorecord->exchange_rates,0)}}</td>
                                                <td class="text_center text-bold">{{number_format($xosorecord->odds,0)}}</td>

                                                <td class="text_center">
                                                    @if ( $xosorecord->game_id < 1000) @if ($xosorecord->exchange_rates != 0)
                                                        @if($xosorecord->game_id==29 || $xosorecord->game_id==329 || $xosorecord->game_id==429 || $xosorecord->game_id==529 || $xosorecord->game_id==629 || $xosorecord->game_id==9 || $xosorecord->game_id==309 ||$xosorecord->game_id==409 ||$xosorecord->game_id==509||$xosorecord->game_id==609||$xosorecord->game_id==709 || $xosorecord->game_id==10 || $xosorecord->game_id==310 ||$xosorecord->game_id==410 ||$xosorecord->game_id==510||$xosorecord->game_id==610||$xosorecord->game_id==710 || $xosorecord->game_id==11 || $xosorecord->game_id==311 ||$xosorecord->game_id==411 ||$xosorecord->game_id==511||$xosorecord->game_id==611||$xosorecord->game_id==711
                                                        || $xosorecord->game_id==21 || $xosorecord->game_id==20 || $xosorecord->game_id==19)
                                                        <?php
                                                        $fact = 1;
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
                                                                $fact = 2;
                                                                break;
                                                            case 10:
                                                            case 310:
                                                            case 410:
                                                            case 510:
                                                            case 610:
                                                            case 710:
                                                                $fact = 3;
                                                                break;
                                                            case 11:
                                                            case 311:
                                                            case 411:
                                                            case 511:
                                                            case 611:
                                                            case 711:
                                                                $fact = 4;
                                                                break;
                                                            case 21:
                                                                $fact = 10;
                                                                break;
                                                            case 20:
                                                                $fact = 8;
                                                                break;
                                                            case 19:
                                                                $fact = 4;
                                                                break;

                                                            default:
                                                                # code...

                                                                break;
                                                        }
                                                        $countbetnumber = count(
                                                            explode(
                                                                ",",
                                                                $xosorecord->bet_number
                                                            )
                                                        );
                                                        $Ank =
                                                            XoSoRecordHelpers::fact(
                                                                $countbetnumber
                                                            ) /
                                                            XoSoRecordHelpers::fact(
                                                                $fact
                                                            ) /
                                                            XoSoRecordHelpers::fact(
                                                                $countbetnumber - $fact
                                                            );
                                                        $betpoint =
                                                            $xosorecord->total_bet_money /
                                                            $xosorecord->exchange_rates /
                                                            $Ank;
                                                        ?>
                                                        {{number_format($betpoint,0)}}
                                                        @else
                                                        {{number_format($xosorecord->total_bet_money/$xosorecord->exchange_rates,0)}}
                                                        @endif

                                                        <?php
                                                        // $total_point += $xosorecord->total_bet_money/$xosorecord->exchange_rates;
                                                        ?>
                                                        @else
                                                        0
                                                        @endif
                                                        @endif
                                                </td>

                                                <!-- <td class=" text-bold">{{number_format($xosorecord->total_bet_money,0)}}</td> -->
                                                <?php
                                                $win_money = $xosorecord->total_win_money;
                                                if ($xosorecord->locationslug == 60)
                                                    $win_money = $xosorecord->total_win_money * 1000;
                                                else
                                                    $win_money = $xosorecord->total_win_money;

                                                // fix tra thuong
                                                if ($win_money > 0 &&  $xosorecord->game_id < 1000) {
                                                    if (
                                                        $xosorecord->game_id == 15  ||
                                                        $xosorecord->game_id == 16  ||
                                                        $xosorecord->game_id == 316 ||
                                                        $xosorecord->game_id == 416 ||
                                                        $xosorecord->game_id == 516 ||
                                                        $xosorecord->game_id == 616 ||
                                                        $xosorecord->game_id == 115 ||
                                                        $xosorecord->game_id == 116
                                                    ) {
                                                        //|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21  || $xosorecord->game_id == 16
                                                    } else {
                                                        $win_money -= $xosorecord->total_bet_money;
                                                    }
                                                }
                                                ?>

                                                @if($typeView == 2)
                                                @if ( $xosorecord->game_id < 1000) <td>{{isset($xosorecord->ipaddr)?$xosorecord->ipaddr:"" }}</td>@endif
                                                    @endif
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!isset($item->rawBet->is_done) || $item->rawBet->is_done == 0)
						<div class="col-lg-12 line-break" style="text-align: right !important;" bis_skin_checked="1">
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 30)) )
							@else
							@endif
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) )
							@else
                            @if($type == 0)
                            <button onclick="confirmCancel(this)" bet_user_name="{{$item->name}}" bet_id_inday='({{isset($item->rawBet->id_inday) ? ("".$item->rawBet->id_inday) : ""}} {{isset($item->id) ? ("- ".$item->id) : ""}})' bet_id="{{$item->rawBet->id}}" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" href="#full-width-modal-l-1" style="font-size: 1em;" control-id="ControlID-12">Hủy cược</button>
                            @endif
							@endif
						</div>
						@endif

						@if(isset($item->rawBet->is_done) && $item->rawBet->is_done == 1)
						<div class="col-lg-12 line-break" style="text-align: right !important;" bis_skin_checked="1">
							<label class="title-sub-card">Đã vào cược thành công: {{number_format($item->rawBet->money)}}</label>
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) )
							@else
                            @if($type == 0)
							<button onclick="confirmCancel(this)" bet_user_name="{{$item->name}}" bet_id_inday='({{isset($item->rawBet->id_inday) ? ("".$item->rawBet->id_inday) : ""}} {{isset($item->id) ? ("- ".$item->id) : ""}})' bet_id="{{$item->rawBet->id}}" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" href="#full-width-modal-l-1" style="font-size: 1em;" control-id="ControlID-12">Hủy cược</button>
                            @endif
							@endif
						</div>
						@endif

						@if(isset($item->rawBet->is_done) && $item->rawBet->is_done == -1)
						<div class="col-lg-12 line-break" style="color:red;text-align: right !important;" bis_skin_checked="1">
							<label class="title-sub-card">Đã hủy cược thành công</label>
                            
                            @if($item->rawBet->type_action == 6)
                            <br>
                            <label class="title-sub-card">Member tự hủy cược</label>
                            @else
                            <br>
                            <label class="title-sub-card">Hệ thống hủy cược</label>
                            @endif
						</div>
						@endif

                </details>

            </div>



        </div>
    </div>
</div>