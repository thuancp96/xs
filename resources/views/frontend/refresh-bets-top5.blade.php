
<?php

use App\Helpers\HistoryHelpers;

    $now = date('Y-m-d');
    $user = Auth::user();
    // $records = XoSoRecordHelpers::GetByUserByDateLimit5($user,$now);
    $records = HistoryHelpers::GetHistory($user,$now);
    $count = count($records);
    // print_r($user->id);
?>

<!-- (lô 2, lô trượt 3, đầu nhất 28, 3 càng nhất 56 như nhất) -->
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        @foreach($records as $record)
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="{{$record['id']*33}}" style="padding: 5px 5px 5px 10px;">
					<h4 class="panel-title" style="color:black !important">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$record['id']*33}}" aria-expanded="false" aria-controls="collapse{{$record['id']*33}}">
						{{$count--}}
						</a>
					</h4>
					</div>
					<div id="collapse{{$record['id']*33}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$record['id']*33}}">
					<div class="panel-body" style="padding: 10px !important">
                        <div class="row">
                            <div class="col-12"><b>{{$record['type']}}</b></div>
                            <!-- <div class="col-6">{{$record['created_at']}}</div> -->
                        </div>
                        <div class="row">
                            <div class="col-12" style="color:red; word-wrap: break-word; font-size: 16;"><b>{{$record['content']}}</b></div>
                            <!-- <div class="col-6" style="color:red; word-wrap: break-word"></div> -->
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-12"><b>Thành tiền</b></div>
                            <!-- <div class="col-6" style="font-weight: bold;">{{number_format($record['total_bet_money'], 0)}}</div> -->
                        </div>
						<div class="row">
                            <!-- <div class="col-6"><b>Thành tiền</b></div> -->
                            <div class="col-12" style="font-weight: bold;">{{number_format($record['money'], 0)}}</div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-6"><b>Time</b></div> -->
                            <div class="col-12" style="display: flex; justify-content: flex-end; color:gray; font-size:0.8em; font-style:italic;"><p>{{$record['created_at']}}</p></div>
                        </div>
                                                            <!-- {{$record['total_bet_money']}} -->
					</div>
					</div>
				</div>
				<!--  -->
			
        @endforeach
        </div>