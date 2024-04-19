<?php
	$gameList = GameHelpers::GetAllGameByParentID(0,1);
    $gameListKhac = GameHelpers::GetAllGameByParentID(24,1);
?>

<?php
    $now = date('Y-m-d');// 
    $hour = date('H');
    $min = date('i');
    $sec = date('s');
    $yesterday = date('Y-m-d', time()-86400);
    // if ($location->slug ==1){
        $yesterday = date('Y-m-d', time()-86400);
        $datepickerXS= date('d-m-Y', time()-86400);
        // if(18<18 || (18==18 && 16<14)){
        if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('i') )<14)){
            $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($yesterday) {
                // return 
                xoso::getKetQua(1,$yesterday);
            // });
            // $rs = xoso::getKetQua(1,$yesterday);
        }
        else{
            $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
                // return 
                xoso::getKetQua(1,date('Y-m-d'));
            // });
            $datepickerXS= date('d-m-Y');
        }
?>

<!-- (lô 2, lô trượt 3, đầu nhất 28, 3 càng nhất 56 như nhất) -->
@foreach($gameList as $game)
			<div class="row">
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
                @if ('2024-02-08' < $now && $now < '2024-02-13')
                    <input type="hidden" class="hd_clock_close" value="00:00">
                @else
                    <input type="hidden" class="hd_clock_close" value="{{date('H:i',XoSoRecordHelpers::TimeoutBet($rs,$game['game_code'],$game))}}">
                @endif
			</div>
@endforeach

@foreach($gameListKhac as $game)
			<div class="row">
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
                @if ('2024-02-08' < $now && $now < '2024-02-13')
                    <input type="hidden" class="hd_clock_close" value="00:00">
                @else
                    <input type="hidden" class="hd_clock_close" value="{{date('H:i',XoSoRecordHelpers::TimeoutBet($rs,$game['game_code'],$game))}}">
                @endif
			</div>

@endforeach