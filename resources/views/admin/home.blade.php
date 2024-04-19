@extends('admin.admin-template')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<?php
		ini_set('memory_limit', '-1');

		use App\Helpers\UserHelpers;
		use App\Helpers\XoSoRecordHelpers;
		use Illuminate\Support\Facades\Auth;
		use Illuminate\Support\Facades\DB;

		$user = Auth::user();
		$userChild = UserHelpers::GetAllUser($user);

		$counttkactive = [0, 0, 0, 0, 0, 0, 0];
		$counttkkhoa = [0, 0, 0, 0, 0, 0, 0];
		$counttkngungdat = [0, 0, 0, 0, 0, 0, 0];

		$counttiendadung = 0;
		$countsuper = 0;
		$countagent = 0;
		$countmaster = 0;
		$countmember = 0;


		foreach ($userChild as $child) {
			if ($child->lock == 0 && $child->active == 0)
				$counttkactive[$child->roleid]++;
			if ($child->lock == 1 && $child->active == 0)
				$counttkngungdat[$child->roleid]++;
			if ($child->lock == 2 && $child->active == 0)
				$counttkkhoa[$child->roleid]++;

			if ($child->user_create == $user->id)
				$counttiendadung += $child->credit;

			switch ($child->roleid) {
				case 2:
					$countsuper++;
					break;

				case 4:
					$countmaster++;
					break;

				case 5:
					$countagent++;
					break;

				case 6:
					$countmember++;
					break;

				default:
					# code...
					break;
			}
		}

		$newDate = date("Y-m-d");
		$newDateShow = date("d-m-Y");
		$rs = [];
		// $arrUser = [];
		// foreach($userChild as $item){
		// 	array_push($arrUser,$item->id);
		// }
		// $arrUser = Cache::remember('UserHelpers-GetAllUserV2'.$user->id, env('CACHE_TIME_SHORT', 0), function () use ($user) {
		// 	return UserHelpers::GetAllUserV2($user);
		// });
		$arrUser = UserHelpers::GetAllUserV2($user);
		if (date('H') < 11) {

			$newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));
			$newDateShow = date("d-m-Y", strtotime('-1 day', strtotime(date("d-m-Y"))));
		}

		$rs = Cache::remember('Homepage' . $user->id . '-' . $newDate, env('CACHE_TIME_SHORT', 0), function () use ($newDate, $arrUser) {
			return DB::table('xoso_record')
				// ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
				// ->orderBy('sumbet', 'desc')
				->where('isDelete', 0)
				// ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
				// ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
				->where('date', '>=', $newDate)
				->where('date', '<=', date("Y-m-d", strtotime('+1 day', strtotime($newDate))))
				// ->where('game_id', 7)
				->whereIn('user_id', $arrUser)
				// ->groupBy('game_id')
				->get();
		});
		// $bbin_record = DB::table('history_live_bet')
		// 	// ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
		// 	// ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
		// 	->where('createdate', '>=', date("Y-m-d", strtotime($newDate)) . ' 11:00:00')
		// 	->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($newDate))) . ' 11:00:00')
		// 	->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
		// 	// ->where('username',$user->name)
		// 	->join('users', 'users.name', '=', 'history_live_bet.username')
		// 	->whereIn('users.id', $arrUser)
		// 	->select('*', 'users.*', 'games.name as game')
		// 	->get();
		// // echo count($bbin_record);
		// foreach ($bbin_record as $value) {
		// 	array_push($rs, (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $user->name . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . json_decode($value->jsoninfo)[0]->SerialID . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
		// }

		$H_7zBall_record = DB::table('history_7zball_bet')
            ->where('createdate','>=',date("Y-m-d",strtotime($newDate)) .' 11:00:00')
            ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
			->join('users', 'users.name', '=', 'history_7zball_bet.username')
			->whereIn('users.id', $arrUser)
			->select('*', 'users.*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value){
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":'. $value->gametype .',"bonus":"'.$bonus.'","total_bet_money":'. $value->betamount .',"paid":'. (isset($value->paid) ? $value->paid : 0) .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"7zBall","locationslug":"70", "SerialID": "'. "" .'", "result": "'. "" .'"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($rs, $record7zBall);
        }
		
		$H_7zBall_record = DB::table('history_minigame_bet')
            ->where('createdate','>=',date("Y-m-d",strtotime($newDate)) .' 11:00:00')
            ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 11:00:00')
            ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
			->join('users', 'users.name', '=', 'history_minigame_bet.username')
			->whereIn('users.id', $arrUser)
			->select('*', 'users.*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value){
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":'. $value->gametype .',"bonus":"'.$bonus.'","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"7zBall","locationslug":"70", "SerialID": "'. "" .'", "result": "'. "" .'"}'));
            $record7zBall->rawBet = $dataResults;
			if ($record7zBall != null)
            	array_push($rs, $record7zBall);
			// else
			// 	var_dump($value);
        }

		$totalCXL = 0;
		$totalWinLose = 0;
		$totalBet = 0;
		foreach ($rs as $record) {
			if ($record->total_win_money == 0) {
				if ($record->game_id < 100)
					$totalCXL += $record->total_bet_money;
				else if (isset($record->paid) && $record->paid == 0){
					$totalCXL += $record->total_bet_money;
				}
			} else {
				if ($record->total_win_money > 0 && $record->game_id < 100) {
					if (
						$record->game_id == 15 || $record->game_id == 16 ||
						$record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
					) {
						$totalWinLose += ($record->total_win_money);
					} else
						$totalWinLose += ($record->total_win_money - $record->total_bet_money);
				} else {
					$totalWinLose += ($record->total_win_money);
				}

				$bonus = explode(',', $record->bonus);
				$totalWinLose += array_sum($bonus);
			}
			$totalBet += $record->total_bet_money;
		}
		?>

<div class="row">
	<div class="col-sm-12">
		<div class="portlet"><!-- /primary heading -->
			<div class="portlet-heading">
				<h3 class="portlet-title text-dark text-uppercase">
				Thông tin {{XoSoRecordHelpers::GetRoleName($user->roleid)}}
				</h3>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>

		<div class="card-box" style="text-align: center;">
			<table class="table table-bordered mails m-0 table-actions-bar table-hover">
				<col width="50">
				<col width="50">
				<thead>
					<tr>
						<th>Thông tin Chi tiết</th>
						<th>Giá trị</th>
					</tr>
				</thead>
				<tbody>

					<tr>
						<td class="text_center">Tổng hạn mức tín dụng</td>
						<td class="text_center">{{number_format($user->credit)}}</td>
					</tr>

					<tr>
						<td class="text_center">Tổng tín dụng @if($user->roleid == 1) Super @endif @if($user->roleid == 2) Master @endif @if($user->roleid == 4) Agent @endif @if($user->roleid == 5) Member @endif</td>
						<td class="text_center">{{number_format($counttiendadung)}}</td>
					</tr>

					<tr>
						<td class="text_center">Tổng tài khoản(TK)</td>
						<td class="text_center">
							@if($user->roleid <= 1)<a href="#" ref="popover2" style="color:blue"> {{$countsuper}} Super</a> |@endif
								@if($user->roleid <= 2)<a href="#" ref="popover4" style="color:red"> {{$countmaster}} Master</a> |@endif
									@if($user->roleid <= 4)<a href="#" ref="popover5" style="color:brown"> {{$countagent}} Agent</a> |@endif
										@if($user->roleid <= 5)<a href="#" ref="popover6" style="color:pink"> {{$countmember}} Member</a>@endif
						</td>
					</tr>


					<tr>
						<td class="text_center">Tổng tiền đang cược</td>
						<td class="text_center"> <a href="/rp/bettoday">{{number_format($totalCXL)}}</a> </td>
					</tr>

					<tr>
						<td class="text_center">Thắng thua</td>
						<td class="text_center"> <a href="/rp/winlose" style="color: 
								@if ($totalWinLose >= 0) 
									green
								@else
									red
								@endif
								">{{number_format($totalWinLose)}}</a></td>
					</tr>

				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		$('[ref="popover2"]').popover({
			trigger: 'hover',
			html: 'true',
			placement: 'top',
			content: '<div style="width:180px;"> <label for="" style="color:aqua">1. Super hoạt động: {{number_format($counttkactive[2])}}</label> <br> <label for="" style="color:bisque">2. Super ngừng đặt:  {{number_format($counttkngungdat[2])}}</label> <br><label for="" style="color:brown">3. Super khoá: {{number_format($counttkkhoa[2])}}</label> </div>'
		});

		$('[ref="popover4"]').popover({
			trigger: 'hover',
			html: 'true',
			placement: 'top',
			content: '<div style="width:180px;"> <label for="" style="color:aqua">1. Master hoạt động: {{number_format($counttkactive[4])}}</label> <br> <label for="" style="color:bisque">2. Master ngừng đặt:  {{number_format($counttkngungdat[4])}}</label> <br><label for="" style="color:brown">3. Master khoá: {{number_format($counttkkhoa[4])}}</label> </div>'
		});

		$('[ref="popover5"]').popover({
			trigger: 'hover',
			html: 'true',
			placement: 'top',
			content: '<div style="width:180px;"> <label for="" style="color:aqua">1. Agent hoạt động: {{number_format($counttkactive[5])}}</label> <br> <label for="" style="color:bisque">2. Agent ngừng đặt:  {{number_format($counttkngungdat[5])}}</label> <br><label for="" style="color:brown">3. Agent khoá: {{number_format($counttkkhoa[5])}}</label> </div>'
		});

		$('[ref="popover6"]').popover({
			trigger: 'hover',
			html: 'true',
			placement: 'top',
			content: '<div style="width:180px;"> <label for="" style="color:aqua">1. Member hoạt động: {{number_format($counttkactive[6])}}</label> <br> <label for="" style="color:bisque">2. Member ngừng đặt:  {{number_format($counttkngungdat[6])}}</label> <br><label for="" style="color:brown">3. Member khoá: {{number_format($counttkkhoa[6])}}</label> </div>'
		});
	});
</script>

@endsection