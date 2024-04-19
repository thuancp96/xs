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
                    {{$item->rawBet->gameName}}
                    <br>
                    {{isset($item->rawBet->game_result_id) ? ("Mã ván " . $item->rawBet->game_result_id) : ""}}
                    <br>
                    {{$item->contentShow}} @ {{$item->rawBet->odd}}
                    <br>
                    {{$item->rawBet->resultTxt}}
                    <br>
                    <b>Đặt cược {{number_format($item->total_bet_money)}}</b>
                </div>
            </div>

            @if($item->rawBet->paid == 1)
                <div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
                <span class="flex text-sm " style="@if ($item->total_win_money < 0) color:red; @else color:#2596be; @endif font-weight:700;">Thắng thua: {{number_format($item->total_win_money)}}</span>
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