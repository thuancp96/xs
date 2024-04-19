@if(count($xosorecords)>0)
    <table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover"" style="font-size: 12px !important;">
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
                                <td>
                                <p>
                                <?php
                                    $highlightbet = $xosorecord->bet_number;
                                    
                                    $arrbet = explode(",",str_replace(" ","",$xosorecord->bet_number));
                                    foreach($arrbet as $bet){
                                        if (strpos($xosorecord->win_number, $bet) !== false) {
                                            $highlightbet = str_replace($bet,'<b>'.$bet.'</b>',$highlightbet);
                                        }
                                    }
                                    echo($highlightbet);
                                ?>
                                <!--{{$xosorecord->bet_number}}-->
                                </td>
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
                                $win_money = $xosorecord->total_win_money;
                                // fix tra thuong
                                if ( $win_money > 0)
                                {
                                    if ($xosorecord->game_id == 15){// || $xosorecord->game_id == 16 
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
                                    <a style="color: white;" href="#" class="btn_huycuoc not-active hidden" onclick="setId('{{$xosorecord->id}}','{{$xosorecord->game_id}}')" id="btn_cancel_{{$xosorecord->id}}">Hủy</a>
                                </button>
                                <input type="hidden" class="time_bet" id="time_bet" gameid="btn_cancel_{{$xosorecord->id}}" game_bet_id="{{$xosorecord->game_id}}"  value="{{$xosorecord->created_at}}">
                                </td>
                            </tr>
                            <?php
                            $total_bet_money+=$xosorecord->total_bet_money;
                            // fix tra thuong
                            $total_win_money+=$win_money;
                            // if ( $xosorecord->total_win_money > 0)
                            // {
                            //     if ($xosorecord->game_id == 15){// || $xosorecord->game_id == 16 
                            //     $total_win_money += ($xosorecord->total_win_money);
							// 				}else
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

