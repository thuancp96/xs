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
            @if(isset($item->rawBet->id_inday))
            <div class="col-lg-12 line-break" style="text-align: left !important; color:#3f86c3; font-weight:600;" bis_skin_checked="1">
                <label style="display: flex; align-items: center;">
                    {{isset($item->rawBet->id_inday) && $item->rawBet->id_inday != 0 ? "Cược tin" : explode(" ",$item->content)[0]}}
                    <!-- <label>{{$item->game}}</label> -->

                    <!-- <div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">
                        <em style="font-weight:500; font-size:12px;">{{isset($item->created_at) ? $item->created_at : ""}}</em>

                    </div> -->
                </label>
            </div>
            @endif
            <div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
                <div style="text-align: left;">
                    @if(isset($item->rawBet))
                    @if($item->rawBet->bet_type != "parlay")
                    @if($item->rawBet->bet_type == "outright")
                    <span style="color:black; font-weight:700;">Chung cuộc</span>
                    <br>
                    @endif
                    <span style="color:#2596be; font-weight:700;"> {{$item->rawBet->bet_type_txt}}</span>
                    <br>
                    <b>{{$item->rawBet->bet_on_txt}}</b> <b @if($item->rawBet->bet_odd <= 0) style="color:red;" @endif>{{"@"}}{{number_format(isset($item->rawBet->bet_odd) ? $item->rawBet->bet_odd : 0, 2)}} @if(str_contains($item->rawBet->bet_type,"#my")) MY @else DEC @endif </b> <b> @if (isset($item->rawBet->bet_match_current)) ({{XoSoRecordHelpers::converScoreMatch($item->rawBet->bet_type,$item->rawBet->bet_match_current)}}) @endif</b>
                    <br>
                    @if($item->rawBet->bet_type != "outright")
                    {{$item->rawBet->m_tnHomeName}} vs {{$item->rawBet->m_tnAwayName}}
                    <br>
                    <b>{{isset($item->rawBet->m_tnName) ? $item->rawBet->m_tnName : ""}}</b>
                    <br>
                    {{isset($item->betTime) && $item->betTime != "" ? $item->betTime : (isset($item->rawBet->kickoffVN) ? $item->rawBet->kickoffVN : "")}}
                    <br>
                    @endif
                    <b>Đặt cược {{number_format($item->total_bet_money)}}</b>
                    @else
                    <?php
                    $parlay = json_decode($item->rawBet->parlay);
                    $detailMatchOnBetLst = isset($item->rawBet->bet_match_current) ? json_decode($item->rawBet->bet_match_current) : null;
                    // var_dump($parlay);
                    $bet_data_lst = json_decode($item->rawBet->bet_data);
                    $bet_ons = json_decode($item->rawBet->parlay_money);
                    $countBet = 0;
                    $strBet_on = "";
                    foreach ($bet_ons as $bet_on) {
                        if ($bet_on->money == 0) continue;
                        $countBet++;
                        if (isset($bet_on->nameParlay))
                            $strBet_on .= ($bet_on->nameParlay . " ");
                        // var_dump($bet_on);
                        // echo $bet_on->nameParlay;
                    }
                    ?>
                    <span style="color:#2596be; font-weight:700;"> {{$item->rawBet->bet_type_txt}} {{$strBet_on}}</span>
                    <br>
                    @foreach($parlay as $parlayOne)
                    <?php
                    $match_id = $parlayOne->match_id;
                    $bet_type = $parlayOne->betting_type_id;

                    $detailMatchOnBet = isset($detailMatchOnBetLst) ? json_decode($detailMatchOnBetLst->$match_id) : null;
                    ?>
                    <span>{{$parlayOne->betting_type}}</span>
                    <br>
                    <span>{{$parlayOne->betting_tournament}}</span>
                    <br>
                    <span>{{$parlayOne->betting_homeName}} vs {{$parlayOne->betting_awayName}}</span>
                    <br>
                    <span>{{XoSoRecordHelpers::converBetOnParlay($parlayOne,$bet_data_lst->$match_id)}}</span>
                    <span>@if(isset($parlayOne->betting_odd))
                        {{"@".(isset($parlayOne->betting_odd) ? $parlayOne->betting_odd : "")}}
                        @else
                        <?php
                        if (isset($parlayOne->betting_k_id)) {
                            switch ($parlayOne->betting_k_id) {
                                case 'od':
                                    echo "Lẻ";
                                    break;
                                case 'ev':
                                    echo "Chẵn";
                                    break;
                                default:
                                    echo $parlayOne->betting_k_id;
                                    break;
                            }
                        }
                        ?>
                        @endif</span>
                    <span>({{isset($detailMatchOnBet) ? ( (str_contains($bet_type,"#cr") ? ("Phạt góc " . str_replace("-"," vs ", $detailMatchOnBet->cr)) : str_replace("-"," vs ", $detailMatchOnBet->score))) : (str_contains($bet_type,"#cr") ? "Phạt góc 0 vs 0" : "0 vs 0" )}})</span>
                    <br>
                    @if(isset($detailMatchOnBet))
                    <span>Thời Gian Đặt Cược {{isset($detailMatchOnBet) ? XoSoRecordHelpers::converTimeMatch($detailMatchOnBet) : "Hiệp 1 00:00"}} </span>
                    @endif
                    <br>
                    <span>-----</span>
                    <br>
                    @endforeach

                    @foreach ($bet_ons as $bet_on)
                    <?php if ($bet_on->money == 0) continue; ?>
                    <span class="flex items-center space-x-[5px] whitespace-nowrap"><span>Đặt Cược: {{$bet_on->nameParlay}}</span>
                        <span class="flex text-sm text-primary">{{number_format($bet_on->money)}}</span>
                    </span>
                    <br>
                    @endforeach
                    @endif
                    @endif
                </div>
            </div>
            @if($item->rawBet->paid == 1)
                <div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
                <span class="flex text-sm" style="@if ($item->total_win_money < 0) color:red; @else color:#2596be; @endif font-weight:700;">Thắng thua: {{number_format($item->total_win_money)}}</span>
                <br>
                <?php 
                    $com = explode(",",$item->bonus)[9];
                ?>
                <span class="flex text-sm">Hoa hồng: {{number_format($com)}}</span>
                </div>
            @endif
        </div>
    </div>
</div>