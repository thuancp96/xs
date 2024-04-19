<?php
// 	if ($location->slug == 1){
// 		$now = \Carbon\Carbon::now();
// 		$yesterday = date('Y-m-d', time()-86400);
// 		$datepickerXS= date('d-m-Y', time()-86400);

// 		// if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('i') )<30)){
// 		if(18<18 || (18==18 && 16<30)){
// 			// Cache::tags('authors')->add('kq'.$yesterday,xoso::getKetQua(1,$yesterday),env('CACHE_TIME', 0));
// 			$rs = 
// 			// Cache::tags('kqxs')->remember('kq1-'.$yesterday, env('CACHE_TIME', 0), function () use ($yesterday) {
// 				// return 
// 				xoso::getKetQua(1,$yesterday);
// 			// });
// 		}
// 		else{
// 			// $rs = xoso::getKetQua(1,date('Y-m-d'));
// 			$rs = 
// 			// Cache::tags('kqxs')->remember('kq1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
// 				// return 
// 				xoso::getKetQua(1,date('Y-m-d'));
// 			// });
// 			$datepickerXS= date('d-m-Y');
// 		}
// 	}

	if ($location->slug == 5){
		$now = date('Y-m-d'); // date('Y-m-d');
		$hour = date('H');
		$min = date('i');
		$sec = date('s');
		$rs = xoso::getKetQuaKeno(5,$hour,$min-$min%10,$now);
	}

	$gameList = GameHelpers::GetAllGameByParentID(0,$location->slug);
	$gameListKhac = GameHelpers::GetAllGameByParentID(24,$location->slug);
?>
<style>
	/*li.active{
	pointer-events: none;
	cursor: default;
	}*/
	.label_game .exchange{
		background-color: rgba(242, 248, 255, 0) !important;
	}
	li{
	margin:2px;
	}

	.panel-title{
		font-size: 12px !important;
	}

	.checkbox.checkbox-single label {
    height: 13px !important;
	}
	@media (max-width: 992px) {
		.new {
			max-height: 41px;
			transition: 0.5s;
			width: 84vw;
		}
	}
</style>
@extends("frontend.frontend-template")
@section('sidebar-menu')
	@parent

	{{-- <div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h6 class="panel-title">Chọn khu vực</h6>
		</div>
		<div class="panel-body">
			<button type="button" class="btn btn-primary  waves-effect waves-light btn-xs">Miền Bắc</button>
			<button type="button" class="btn btn-primary btn-custom waves-effect waves-light btn-xs not-active">Miền Trung</button>
			<button type="button" class="btn btn-primary btn-custom waves-effect waves-light btn-xs not-active">Miền Nam</button>
		</div>
	</div> --}}

	<!-- <div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h6 class="panel-title">Chọn loại</h6>
		</div>
		<div class="panel-body">
			<div class="tab-content br-n pn">
				

				
					
			</div>
		</div>
	</div> -->
        <style>

        .col-lg-4 {
            display: block;
        }
        .col-lg-8 {
            width: 66.66666667%;
        }

    </style>
    
    

    
	<div class="panel panel-color panel-inverse hidden" style="display: block;" id="panel_bet">
		<div class="panel-heading recent-heading">
			<h6 class="panel-title" style="display: flex; align-items: center;">Đặt cược 
				<div style="flex: 1; display: flex; justify-content: flex-end;">
					<label class="form-control-static" id="game_name"></label>-<label class="form-control-static" id="local_name">{{$location->name}}</label> 
				</div>
			</h6> 
		</div>
		<div class="panel-body">
			

			<div class="row">
				<form class="form-horizontal" role="form">
					<!-- <div class="form-group" style="text-align: center"> -->

						<div class="">
		                    <input type="tel" id="input_point" name="example-input2-group2" class="form-control" placeholder="Đặt cược điểm cho mỗi mã" autocomplete="off" onkeyup="ChangeTotalPoint(this)" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
		                    <!-- <span class="input-group-btn">
		                    <button type="button" class="btn waves-effect waves-light btn-primary" id="enter_array">Xác nhận</button>
		                    </span> -->
                		</div>

						<!-- <div class="col-md-4 hidden-xss">
							<label class="control-label">Đặt cược</label>
							
						</div>
						<div class="col-md-5 col-5">
							<input style="text-align: center" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="input_point" placeholder="Số điểm cho mỗi con" onkeyup="ChangeTotalPoint(this)" autocomplete="off">
						</div>
						<div class="col-md-3 hidden-xss">
							<label class="control-label">Điểm</label>
							
						</div> -->

					<!-- </div> -->

					<div class="row" id="number_select_div">
						<label class="col-md-5 control-label form-control-static col-5">Số đánh:</label>
						<div class="col-md-7 col-7 col-6" >
							<p class="form-control-static" id="number_select"></p>
						</div>
					</div>
					<div id="box-cuoc-lo-truot-xien" class="hidden">
					<div class="row">
						<div class="col-md-5 control-label form-control-static col-5">Số xiên</div>
						<div class="col-md-7 col-7 col-6">
							<p class="form-control-static" id="number_select_xien">0</p>
						</div>
					</div>
					</div>

					<div id="box-cuoc-lo-xien" class="hidden">
					<!--<div class="row">-->
					<!--	<label class="col-md-5 control-label form-control-static col-5"></label>-->
					<!--	<div class="col-md-2 col-2" style="width: 65px !important;">Số xiên</div>-->
					<!--	<div class="col-md-7 col-7 col-6" style="width: 65px !important;">Điểm</div>-->
						<!--<div class="col-md-2 col-2" style="width: 65px !important;">Xiên 4</div>-->
					<!--</div>-->
					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Điểm Xiên 2</label>
						<div class="col-md-3 col-3" style=";">
							<p class="form-control-static" id="number_select_xien2">0</p>
						</div>
						<div class="col-md-4 col-4">
							<input style=" height:30px;" type="tel" id="input_point2" name="example-input2-group2" class="form-control" placeholder="" autocomplete="off" onkeyup="ChangeTotalPoint2(this)" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
						</div>
					</div>

					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Điểm Xiên 3</label>
						<div class="col-md-3 col-3" style=";">
							<p class="form-control-static" id="number_select_xien3">0</p>
						</div>
						<div class="col-md-4 col-4">
							<input style=" height:30px;" type="tel" id="input_point3" name="example-input2-group2" class="form-control" placeholder="" autocomplete="off" onkeyup="ChangeTotalPoint3(this)" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
						</div>
					</div>

					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Điểm Xiên 4</label>
						<div class="col-md-3 col-3" style=";">
							<p class="form-control-static" id="number_select_xien4">0</p>
						</div>
						<div class="col-md-4 col-4">
							<input style=" height:30px;" type="tel" id="input_point4" name="example-input2-group2" class="form-control" placeholder="" autocomplete="off" onkeyup="ChangeTotalPoint4(this)" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
						</div>
					</div>

					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Điểm Xiên Nháy</label>
						<div class="col-md-3 col-3" style=";">
							<p class="form-control-static" id="number_select_xiennhay">0</p>
						</div>
						<div class="col-md-4 col-4">
							<input style=" height:30px;" type="tel" id="input_pointxn" name="example-input2-group2" class="form-control" placeholder="" autocomplete="off" onkeyup="ChangeTotalPointXN(this)" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
						</div>
					</div>

					<!--<div class="row" id="tongsoxien">-->
					<!--	<label class="col-md-5 control-label form-control-static col-5">Số xiên:</label>-->
					<!--	<div class="col-md-7 col-7 col-6">-->
					<!--		<p class="form-control-static" id="number_select_xien"></p>-->
					<!--	</div>-->
					<!--</div>-->
					<!--<div class="row">-->
					<!--	<label class="col-md-5 control-label form-control-static col-5">Thành tiền</label>-->
					<!--</div>-->
					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Tiền Xiên 2:</label>
						<div class="col-md-7 col-7 col-6">
							<p class="form-control-static" id="total2">0</p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Tiền Xiên 3:</label>
						<div class="col-md-7 col-7 col-6">
							<p class="form-control-static" id="total3">0</p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Tiền Xiên 4:</label>
						<div class="col-md-7 col-7 col-6">
							<p class="form-control-static" id="total4">0</p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Tiền Xiên Nháy:</label>
						<div class="col-md-7 col-7 col-6">
							<p class="form-control-static" id="totalxn">0</p>
						</div>
					</div>
					</div>
					<div class="row" id="tongdiem">
						<label class="col-md-5 control-label form-control-static col-5">Tổng điểm:</label>
						<div class="col-md-7 col-7 col-6">
							<p class="form-control-static" id="point">0</p>
							<p class="form-control-static hidden" id="point2">0</p>
							<p class="form-control-static hidden" id="point3">0</p>
							<p class="form-control-static hidden" id="point4">0</p>
							<p class="form-control-static hidden" id="pointxn">0</p>
						</div>
						
					</div>
					<div class="row" id="tongtien">
						<label class="col-md-5 control-label form-control-static col-5">Thành tiền:</label>
						<div class="col-md-7 col-7 col-5">
							<p class="form-control-static" id="total">0</p>
						</div>
					</div>
					<div class="row hidden">
						<label class="col-md-5 control-label form-control-static col-5">Tối đa :</label>
						<div class="col-md-7 col-7 col-5">
							<p class="form-control-static" id="max_point"></p>
							<p class="form-control-static" id="max_point2"></p>
							<p class="form-control-static" id="max_point3"></p>
							<p class="form-control-static" id="max_point4"></p>
							<p class="form-control-static" id="max_pointxn"></p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Tối đa :</label>
						<div class="col-md-7 col-7 col-5">
							<p> <label class="form-control-static" id="max_point_one"></label></p>
							<p class="form-control-static hidden" id="max_point_one2"></p>
							<p class="form-control-static hidden" id="max_point_one3"></p>
							<p class="form-control-static hidden" id="max_point_one4"></p>
							<p class="form-control-static hidden" id="max_point_onexn"></p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Trả thưởng:</label>
						<div class="col-md-7 col-7">
							<p class="form-control-static" id="odds"></p>
							<p class="form-control-static hidden" id="odds2"></p>
							<p class="form-control-static hidden" id="odds3"></p>
							<p class="form-control-static hidden" id="odds4"></p>
							<p class="form-control-static hidden" id="oddsxn"></p>
						</div>
					</div>

					<div class="row">
						<label class="col-md-5 control-label form-control-static col-5">Số dư:</label>
						<div class="col-md-7 col-7">
							<p class="form-control-static text_red text_bold" id="remain_gameplay"></p>
						</div>
					</div>
					<br>
					<div class="row">
						<button type="button" id="btn_Delete" onclick="Huy()" class="btn btn-danger btn-success waves-effect waves-light"disabled>Hủy</button>
						<button type="button" id="btn_OK" onclick="DatCuoc()" class="btn btn-success waves-effect waves-light" disabled>Đặt</button>
					</div>

					<!-- <div class="form-group" style="text-align: center">
						<div class="col-md-7 col-7 col-6">
							
						</div>
						<div class="col-md-7 col-7 col-6">

							
						</div>
					</div> -->
				</form>
			</div>
		</div>
	</div>
	


	<?php

use App\Helpers\HistoryHelpers;
use Illuminate\Support\Facades\Auth;
		$now = date('Y-m-d');
		$user = Auth::user();
		$records = HistoryHelpers::GetHistory($user,$now);
    	$count = count($records);
		// print_r($records);
	?>

	<div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Mã cược gần đây<a class="btn btn-app" onClick="g_refresh_bets_top5();"><i class="fa fa-repeat"></i></a></h3>
		</div>
		<div class="panel-body" id="refresh_bets_top5" style="padding: 10px !important">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        @foreach($records as $record)
			@if(count($records) - $count <= 7)
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

						@if(isset($record['cancel']) && $record['cancel'] != "")
						<div class="row">
                            <div class="col-12" style="color:red; word-wrap: break-word; font-size: 16;"><b>Hủy {{$record['cancel']}}</b></div>
                            <!-- <div class="col-6" style="color:red; word-wrap: break-word"></div> -->
                        </div>
						@endif
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
			@endif
        @endforeach
        </div>
		</div>
	</div>

	<div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Thời gian còn lại</h3>
		</div>
		<div class="panel-body hidden" id="open_close_game_timer" >
			@foreach($gameList as $game)
			<div class="row">
				 <div class="col-6"><b>{{$game['name']}}: </b></div> 
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
				<input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
				 <div class="col-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div> 
			</div>
			@endforeach

			@foreach($gameListKhac as $game)
			<div class="row">
				 <div class="col-6"><b>{{$game['name']}}: </b></div> 
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
				<input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
				 <div class="col-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div> 
			</div>
			@endforeach

		</div>
		<div class="panel-body">
			@foreach($gameList as $game)
			@if ($game['game_code'] == 2 || $game['game_code'] == 17 || $game['game_code'] == 56 || $game['game_code'] == 27 || $game['game_code'] == 28 || $game['game_code'] == 26 || $game['game_code'] == 15 || $game['game_code'] == 3)  
				<div class="row" hidden>
					<div class="col-6"><b>{{$game['name']}}: </b></div>
					 <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> 
					 <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> 
					<div class="col-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
				</div>
			@else
				<div class="row">
					<div class="col-6"><b>{{$game['game_code'] == 25 ? 'Thần tài' : $game['name']}}: </b></div>
					 <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> 
					 <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> 
					<div class="col-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
				</div>
			@endif
			
			@endforeach

			@foreach($gameListKhac as $game)
			<div class="row hidden" id="row_clock_giaikhac_{{$game['game_code']}}">
					<div class="col-6"><b>{{$game['name']}}</b></div>
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
			<div class="row hidden" id="quick_input_gameplay">
				
				<div class="input-group">
                        <input style=" height: 25px;" type="tel" id="number_select_text" name="example-input2-group2" class="form-control" placeholder="Nhập nhanh số cược" id="number_select_text" autocomplete="off">
                    	<!--<a style="  height: 39px!important " type="button" class="btn waves-effect waves-light btn-primary" id="enter_array">Nhập số</a>-->
                    	<!--<button style="  height: 39px!important " type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" style="overflow: hidden; position: relative; height:25px" aria-expanded="false"><i class="fa fa-caret-down"></i>-->
                     <!--   </button>-->
                        
                        <a style="margin-right:5px;" href="javascript:void(0)" class="btn waves-effect waves-light btn-primary"  id="enter_array">Nhập số</a>
                        <button type="button" style="height: 38px;margin-left: -5px; !important; border-radius: 0px 10px 10px 0" class="btn waves-effect waves-light btn-primary" data-bs-toggle="dropdown">
                            <i class="fa fa-caret-down"></i>
                        </button>

						<a class=" btn waves-effect waves-light btn-primary" 
						style="height: 38px;margin-left: 15px; !important; border-radius: 10px" 
						data-bs-toggle="modal" id="buttonShowChangeHighlight2" href="#highlight-price-modal" onclick="Show_Change_Highlight()"><i style="margin-top:3px;" class="fa fa-star"></i></a>

						<!-- <button onclick="configHighlight()"
						type="button" style="height: 38px;margin-left: 15px; !important; border-radius: 10px" class="btn waves-effect waves-light btn-primary">
                            <i class="fa fa-star"></i>
                        </button>	 -->
                        
                        <ul class="dropdown-menu" role="menu" id="dropdown-temp-numb">
							<li><a data-target="#tonghieu" data-toggle="tab" >Tổng hiệu</a></li>
							<li><a data-target="#daucuoi" data-toggle="tab" >Đầu đuôi</a></li>
							<li><a data-target="#boso" data-toggle="tab">Bộ số</a></li>
							<li><a data-target="#cham" data-toggle="tab">Chạm</a></li>
							<li><a data-target="#dan" data-toggle="tab">Dàn</a></li>
							<li><a data-target="#kep" data-toggle="tab">Kép</a></li>
							<li><a data-target="#bochanle" data-toggle="tab">Chẵn lẻ</a></li>
							<li><a data-target="#tonhopa" data-toggle="tab" >To nhỏ</a></li>
							<li><a data-target="#congiap" data-toggle="tab">Con giáp</a></li>
						</ul>

                       
                </div>
                

				</br>	
			</div>	
						<div class="row">
				<div class="panel-body panel-select" style=" padding-bottom: 0px; padding-top: 5px; font-size: 12px" id="panel-number-temp">
					<div class="tabbable tabs-below">
						<div class="tab-content">
							<div class="tab-pane tabbable-pane " id="tonghieu">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong0" onclick="SelectNumberByReq('tong0')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="tong0" class="">Tổng 0</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong1" onclick="SelectNumberByReq('tong1')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong1">Tổng 1</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong2" onclick="SelectNumberByReq('tong2')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong2">Tổng 2</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong3" onclick="SelectNumberByReq('tong3')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong3">Tổng 3</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong4" onclick="SelectNumberByReq('tong4')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong4">Tổng 4</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong5" onclick="SelectNumberByReq('tong5')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong5">Tổng 5</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong6" onclick="SelectNumberByReq('tong6')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong6">Tổng 6</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong7" onclick="SelectNumberByReq('tong7')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong7">Tổng 7</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong8" onclick="SelectNumberByReq('tong8')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong8">Tổng 8</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tong9" onclick="SelectNumberByReq('tong9')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tong9">Tổng 9</label>
									</div>
									</div>
									<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="hieu0" onclick="SelectNumberByReq('hieu0')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="hieu0">Hiệu 0 (10 số)</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="hieu1" onclick="SelectNumberByReq('hieu1')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="hieu1">Hiệu 1 (20 số)</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="hieu2" onclick="SelectNumberByReq('hieu2')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="hieu2">Hiệu 2 (20 số)</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="hieu3" onclick="SelectNumberByReq('hieu3')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="hieu3">Hiệu 3 (20 số)</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="hieu4" onclick="SelectNumberByReq('hieu4')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="hieu4">Hiệu 4 (20 số)</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="hieu5" onclick="SelectNumberByReq('hieu5')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="hieu5">Hiệu 5 (20 số)</label>
									</div>
									</div>
							</div>

							<div class="tab-pane tabbable-pane " id="daucuoi">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau0" onclick="SelectNumberByReq('dau0')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="dau0" class="">Đầu 0</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau1" onclick="SelectNumberByReq('dau1')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau1">Đầu 1</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau2" onclick="SelectNumberByReq('dau2')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau2">Đầu 2</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau3" onclick="SelectNumberByReq('dau3')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau3">Đầu 3</label>
									</div>
									

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau4" onclick="SelectNumberByReq('dau4')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau4">Đầu 4</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau5" onclick="SelectNumberByReq('dau5')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau5">Đầu 5</label>
									</div>

									<div class="form-group col-sm-3 col-3">										
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau6" onclick="SelectNumberByReq('dau6')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau6">Đầu 6</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau7" onclick="SelectNumberByReq('dau7')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau7">Đầu 7</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau8" onclick="SelectNumberByReq('dau8')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau8">Đầu 8</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau9" onclick="SelectNumberByReq('dau9')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau9">Đầu 9</label>
									</div>
									</div>
									<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi0" onclick="SelectNumberByReq('duoi0')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="duoi0" class="">Đuôi 0</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi1" onclick="SelectNumberByReq('duoi1')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi1">Đuôi 1</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi2" onclick="SelectNumberByReq('duoi2')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi2">Đuôi 2</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi3" onclick="SelectNumberByReq('duoi3')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi3">Đuôi 3</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi4" onclick="SelectNumberByReq('duoi4')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi4">Đuôi 4</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi5" onclick="SelectNumberByReq('duoi5')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi5">Đuôi 5</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi6" onclick="SelectNumberByReq('duoi6')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi6">Đuôi 6</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi7" onclick="SelectNumberByReq('duoi7')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi7">Đuôi 7</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi8" onclick="SelectNumberByReq('duoi8')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi8">Đuôi 8</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="duoi9" onclick="SelectNumberByReq('duoi9')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="duoi9">Đuôi 9</label>
									</div>
									</div>
							</div>

							<div class="tab-pane tabbable-pane " id="boso">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso00" onclick="SelectNumberByReq('boso00')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="bo0" class="">Bộ 00</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso11" onclick="SelectNumberByReq('boso11')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo11">Bộ 11</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso22" onclick="SelectNumberByReq('boso22')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo22">Bộ 22</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso33" onclick="SelectNumberByReq('boso33')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo33">Bộ 33</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso44" onclick="SelectNumberByReq('boso44')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo44">Bộ 44</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso01" onclick="SelectNumberByReq('boso01')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo01">Bộ 01</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso02" onclick="SelectNumberByReq('boso02')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo02">Bộ 02</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso03" onclick="SelectNumberByReq('boso03')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo03">Bộ 03</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso04" onclick="SelectNumberByReq('boso04')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo04">Bộ 04</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso12" onclick="SelectNumberByReq('boso12')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo12">Bộ 12</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso13" onclick="SelectNumberByReq('boso13')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo13">Bộ 13</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso14" onclick="SelectNumberByReq('boso14')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo14">Bộ 14</label>
									</div>
									</div>
									<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso23" onclick="SelectNumberByReq('boso23')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo23">Bộ 23</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso24" onclick="SelectNumberByReq('boso24')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo24">Bộ 24</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="boso34" onclick="SelectNumberByReq('boso34')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bo34">Bộ 34</label>
									</div>
									</div>
									
							</div>

							<div class="tab-pane tabbable-pane " id="cham">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham0" onclick="SelectNumberByReq('cham0')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="cham0" class="">Chạm 0</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham1" onclick="SelectNumberByReq('cham1')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham1">Chạm 1</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham2" onclick="SelectNumberByReq('cham2')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham2">Chạm 2</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham3" onclick="SelectNumberByReq('cham3')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham3">Chạm 3</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham4" onclick="SelectNumberByReq('cham4')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham4">Chạm 4</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham5" onclick="SelectNumberByReq('cham5')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham5">Chạm 5</label>
									</div>

									<div class="form-group col-sm-3 col-3">										
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham6" onclick="SelectNumberByReq('cham6')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham6">Chạm 6</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham7" onclick="SelectNumberByReq('cham7')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham7">Chạm 7</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham8" onclick="SelectNumberByReq('cham8')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham8">Chạm 8</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="cham9" onclick="SelectNumberByReq('cham9')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="cham9">Chạm 9</label>
									</div>
									</div>
									
							</div>

							<div class="tab-pane tabbable-pane " id="dan">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="danchia3" onclick="SelectNumberByReq('danchia3')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label style="" for="danchia3" class="">Dàn chia Ba (34 số)</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="danchia3du1" onclick="SelectNumberByReq('danchia3du1')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label style="" for="danchia3du1">Dàn chia Ba - Dư 1 (33 số)</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="danchia3du2" onclick="SelectNumberByReq('danchia3du2')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label style="" for="danchia3du2">Dàn chia Ba - Dư 2 (33 số)</label>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan05" onclick="SelectNumberByReq('dan05')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label style="" for="dan05">Dàn 0-5 (36 số)</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan06" onclick="SelectNumberByReq('dan06')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label style="" for="dan06">Dàn 0-6</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan07" onclick="SelectNumberByReq('dan07')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label style="" for="dan07">Dàn 0-7</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan08" onclick="SelectNumberByReq('dan08')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label style="" for="dan08">Dàn 0-8</label>
									</div>

								</div>
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan15" onclick="SelectNumberByReq('dan15')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan15">Dàn 1-5</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan16" onclick="SelectNumberByReq('dan16')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan16">Dàn 1-6 (36 số)</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan17" onclick="SelectNumberByReq('dan17')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan17">Dàn 1-7</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan18" onclick="SelectNumberByReq('dan18')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan18">Dàn 1-8</label>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan19" onclick="SelectNumberByReq('dan19')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan19">Dàn 1-9</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan26" onclick="SelectNumberByReq('dan26')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan26">Dàn 2-6</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan27" onclick="SelectNumberByReq('dan27')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan27">Dàn 2-7</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan28" onclick="SelectNumberByReq('dan28')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan28">Dàn 2-8</label>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan29" onclick="SelectNumberByReq('dan29')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan29">Dàn 2-9</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan38" onclick="SelectNumberByReq('dan38')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan38">Dàn 3-8 (36 số)</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan39" onclick="SelectNumberByReq('dan39')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan39">Dàn 3-9</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan49" onclick="SelectNumberByReq('dan49')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan49">Dàn 4-9 (36 số)</label>
									</div>
								</div>
									
							</div>

							<div class="tab-pane tabbable-pane " id="kep">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="sokeplech" onclick="SelectNumberByReq('sokeplech')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="sokeplech" class="">Kép lệch</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="sokepbang" onclick="SelectNumberByReq('sokepbang')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="sokepbang">Kép bằng</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="sokepam" onclick="SelectNumberByReq('sokepam')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="sokepam">Kép âm</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="satkepbang" onclick="SelectNumberByReq('satkepbang')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="satkepbang">Sát kép bằng</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="satkeplech" onclick="SelectNumberByReq('satkeplech')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="satkeplech">Sát kép lệch</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="bokep" onclick="SelectNumberByReq('bokep')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="bokep">Bỏ kép</label>
									</div>

								</div>
									
							</div>

							<div class="tab-pane tabbable-pane " id="bochanle">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="chanchan" onclick="SelectNumberByReq('chanchan')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="chanchan" class="">Chẵn Chẵn (25 số)</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="lele" onclick="SelectNumberByReq('lele')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="lele">Lẻ Lẻ (25 số)</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="chanle" onclick="SelectNumberByReq('chanle')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="chanle">Chẵn Lẻ (25 số)</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="lechan" onclick="SelectNumberByReq('lechan')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="lechan">Lẻ Chẵn (25 số)</label>
									</div>

								</div>
							</div>

							<div class="tab-pane tabbable-pane " id="tonhopa">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="nhonho" onclick="SelectNumberByReq('nhonho')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="nhonho" class="">Nhỏ Nhỏ (25 số)</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="toto" onclick="SelectNumberByReq('toto')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="toto">To To (25 số)</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="nhoto" onclick="SelectNumberByReq('nhoto')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="nhoto">Nhỏ To (25 số)</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tonho" onclick="SelectNumberByReq('tonho')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tonho">To Nhỏ (25 số)</label>
									</div>

								</div>
							</div>

							<div class="tab-pane tabbable-pane " id="congiap">
								<div class="row">
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="ty" onclick="SelectNumberByReq('ty')" aria-label="Single checkbox One">
								                <label></label>
								            </div>
            							</div>
										<label for="ty" class="">Tý ( 9 số )</label>
									</div>
								
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="suu" onclick="SelectNumberByReq('suu')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="suu">Sửu ( 9 số )</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dan9" onclick="SelectNumberByReq('dan9')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dan9">Dần ( 9 số )</label>
									</div>

									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="mao" onclick="SelectNumberByReq('mao')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="mao">Mão ( 9 số )</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="thin" onclick="SelectNumberByReq('thin')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="thin">Thìn ( 8 số )</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="ty8" onclick="SelectNumberByReq('ty8')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="ty8">Tỵ ( 8 số )</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="ngo" onclick="SelectNumberByReq('ngo')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="ngo">Ngọ ( 8 số )</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="mui" onclick="SelectNumberByReq('mui')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="mui">Mùi: ( 8 số )</label>
									</div>
									
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="than" onclick="SelectNumberByReq('than')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="than">Thân: ( 8 số )</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="dau" onclick="SelectNumberByReq('dau')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="dau">Dậu ( 8 số )</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="tuat" onclick="SelectNumberByReq('tuat')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="tuat">Tuất ( 8 số )</label>
									</div>
									<div class="form-group col-sm-3 col-3">
										<div class="" style="position: relative;display: inline-block;">
											<div class="checkbox checkbox-single">
								                <input type="checkbox" id="hoi" onclick="SelectNumberByReq('hoi')" aria-label="Single checkbox One">
								                <label></label>
							            	</div>
							        	</div>
										<label for="hoi">Hợi ( 8 số )</label>
									</div>
									</div>
									
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-2 order-first " id="tabnew" style=" display: none">
				<!-- <label>Xổ số truyền thống</label> accordion-custom-->
				<!-- <div class="accordion-wrapper">
				<div class="accordion">
					<input type="radio" name="radio-a" id="check1" checked>
					<label class="accordion-label" for="check1">Miền bắc 1</label>
					<div class="accordion-content"> -->
					<sidebar-menu>
						<div class="panel panel-color panel-inverse" style="border-radius: 10px;display: inline-flex;">
						    
							<div class="panel-body"  style="padding: 0 !important;width: 100%;" id="sideBetCate">

							
                        		<ul class="nav nav-pills new " >
									
                					@foreach($gameList as $game)
                						<?php
                							if ($game['game_code']==700) $gamechilderList = [];
                							else
                								$gamechilderList = GameHelpers::GetAllGameByParentID($game['game_code'],$location->slug);
                						?>
                							@if(count($gamechilderList)>0 && $game['game_code']!=2 && $game['game_code']!=302 && $game['game_code']!=402 && $game['game_code']!=502 && $game['game_code']!=602)
                								<li class="tableft" id="tableft{{$game['game_code']}}">
                								    
                								    <i class="fa fa-list-ul bacham" style="color:white;" ></i>
                									<a style="line-height: 24px !important;"class="" href="#{{$game['game_code']}}"  data-toggle="tab" aria-expanded="false"
                									onclick="ClickTabGame('{{$game['game_code']}}','{{$game['alias']}}')"
                									>{{$game['name']}}</a>
													<div class="spinner-border text-light" style="height: 15px; width: 15px;display: none" id="spinnerBet{{$game['game_code']}}">
                								</li>
                							@elseif ($game['game_code']!=2 && $game['game_code']!=302 && $game['game_code']!=402 && $game['game_code']!=502 && $game['game_code']!=602)
                									
                									<?php
                									$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,$game['game_code']);
													$gamecurT = null;
													if ($game['game_code'] == 18)
														$gamecurT = GameHelpers::GetGameByCusType($user->customer_type,$user->id,7);
                									?>
                									<li class="tableft" id="tableft{{$gamecur['game_code']}}">
                									   <i class="fa fa-list-ul bacham" style="color:white;" ></i>
														@if (isset($gamecurT))
															<a style="line-height: 24px !important;" class="" href="#{{$gamecur['game_code']}}" onclick="LoadContentGameParent('{{$gamecur['game_code']}}','{{$gamecur['name']}}','{{$gamecur['alias']}}','{{$gamecurT['max_point']}}','{{$gamecurT['max_point_one']}}','{{$gamecur['odds']}}','{{$gamecur['open']}}','{{$gamecur['close']}}','{{$gamecur['exchange_rates']}}')" data-toggle="tab" aria-expanded="false"
														@else
                										<a style="line-height: 24px !important;" class="" href="#{{$gamecur['game_code']}}" onclick="LoadContentGameParent('{{$gamecur['game_code']}}','{{$gamecur['name']}}','{{$gamecur['alias']}}','{{$gamecur['max_point']}}','{{$gamecur['max_point_one']}}','{{$gamecur['odds']}}','{{$gamecur['open']}}','{{$gamecur['close']}}','{{$gamecur['exchange_rates']}}')" data-toggle="tab" aria-expanded="false"
														@endif
                										>{{$gamecur['name']}}</a>
														<div class="spinner-border text-light" style="height: 15px; width: 15px;display: none" id="spinnerBet{{$game['game_code']}}">
                									</li>
                								@else
												<?php

												if($game['game_code']==2){
													$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,2);
													$gamecur2 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,9);
													$gamecur3 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,10);
													$gamecur4 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,11);
													$gamecurxn = GameHelpers::GetGameByCusType($user->customer_type,$user->id,29);
												}
												if($game['game_code']==302){
													$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,302);
													$gamecur2 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,309);
													$gamecur3 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,310);
													$gamecur4 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,311);
												}
												if($game['game_code']==402){
													$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,402);
													$gamecur2 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,409);
													$gamecur3 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,410);
													$gamecur4 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,411);
												}
												if($game['game_code']==502){
													$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,502);
													$gamecur2 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,509);
													$gamecur3 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,510);
													$gamecur4 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,511);
												}
												if($game['game_code']==602){
													$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,602);
													$gamecur2 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,609);
													$gamecur3 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,610);
													$gamecur4 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,611);
												}

												if($game['game_code']==702){
													$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,702);
													$gamecur2 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,709);
													$gamecur3 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,710);
													$gamecur4 = GameHelpers::GetGameByCusType($user->customer_type,$user->id,711);
												}
												

												?>
                									<li class="tableft" id="tableft{{$game['game_code']}}">
                									 <i class="fa fa-list-ul bacham" style="color:white;" ></i>

                									<a style="line-height: 24px !important;"class="" href="#{{$game['game_code']}}"  data-toggle="tab" aria-expanded="false"
                									onclick="LoadContentGameXien('{{$game['game_code']}}','Lô xiên','{{$game['alias']}}','{{$gamecur2['max_point']}}/{{$gamecur3['max_point']}}/{{$gamecur4['max_point']}}/{{$gamecurxn['max_point']}}','{{$gamecur2['max_point_one']}}/{{$gamecur3['max_point_one']}}/{{$gamecur4['max_point_one']}}/{{$gamecurxn['max_point_one']}}','{{$gamecur2['odds']}}','{{$gamecur3['odds']}}','{{$gamecur4['odds']}}','{{$gamecurxn['odds']}}','{{$gamecur2['open']}}','{{$gamecur2['close']}}')"
                									>{{$game['name']}}</a>
													<div class="spinner-border text-light" style="height: 15px; width: 15px;display: none" id="spinnerBet{{$game['game_code']}}">
                									</li>
                								@endif
                							
                
                					@endforeach
                				</ul>
							</div>
							<a class=" btn waves-effect waves-light btn-primary" style="height: 38px; border-radius: 10px; width: 40px; margin-top: 2px;" id="buttonShowChangeHighlight1" data-bs-toggle="modal" href="#highlight-price-modal" onclick="Show_Change_Highlight()"><i style="margin-top:3px;" class="fa fa-star"></i></a>
						</div>
						</sidebar-menu>
				<!-- </div>
				</div>
				<div class="accordion">
					<input type="radio" name="radio-a" id="check2">
					<label class="accordion-label" for="check2">Miền bắc 2</label>
					<div class="accordion-content">
					<p>Thông tin cược Miền bắc 2</p>
					</div>
				</div>
				<div class="accordion">
					<input type="radio" name="radio-a" id="check3">
					<label class="accordion-label" for="check3">Miền bắc 3</label>
					<div class="accordion-content">
					<p>Thông tin cược Miền bắc 3</p>
					</div>
				</div> -->
				</div>
					
					<style>
						@media screen and (min-width: 992px) {
							#buttonShowChangeHighlight1 {
								display:none;
							}
							#sideBetCate {
								width: 100% !important;
							}
							
						}
							@media screen and (max-width: 992px) {
								#buttonShowChangeHighlight2 {
								display:none;
							}
							}

												
						h1{
							text-align:center;
						}
						.accordion-custom input {
							position: absolute;
							opacity: 0;
							z-index: -1;
						}
						.accordion-wrapper {
							border-radius: 8px;
							overflow: hidden;
							box-shadow: 0 4px 4px -2px rgba(0, 0, 0, 0.5);
							width: 100%;
							margin:0 auto;
						}
						.accordion {
							width: 100%;
							color: white;
							overflow: hidden;
							margin-bottom: 16px;
						}
						.accordion:last-child{
							margin-bottom: 0;
						}
						.accordion-label {
							display: flex;
							-webkit-box-pack: justify;
							justify-content: space-between;
							padding: 16px;
							background: rgba(4,57,94,.8);
							font-weight: bold;
							cursor: pointer;
							font-size: 11px;
							text-transform: uppercase;
						}
						.accordion-label:hover {
							background: rgba(4,57,94,1);
						}
						.accordion-label::after {
							content: "\276F";
							width: 16px;
							height: 16px;
							text-align: center;
							-webkit-transition: all 0.3s;
							transition: all 0.3s;
						}
						.accordion-content {
							max-height: 0;
							/* padding: 0 16px; */
							color: rgba(4,57,94,1);
							background: white;
							-webkit-transition: all 0.3s;
							transition: all 0.3s;
						}
						.accordion-content p{
							margin: 0;
							color: rgba(4,57,94,.7);
							font-size: 0.8rem;
						}
						.accordion-custom input:checked + .accordion-label {
							background: rgba(4,57,94,1);
						}
						.accordion-custom input:checked + .accordion-label::after {
							-webkit-transform: rotate(90deg);
							transform: rotate(90deg);
						}
						.accordion-custom input:checked ~ .accordion-content {
							max-height: 100vh;
							/* padding: 16px; */
						}
  
					</style>	

				
			</div>


			    

			
					@if ($location->slug == 5)
					
					<div class="keno_tructiep row" id="kqkenomin">
					<div class="keno_TK_KQ row">
						@if (isset($rs) && count($rs) < 1 )

							<div class=" col-sm-6 row">
							    <div class="boxTotal row">
    							<div class="col-6">
									<div class="rowKenoTK rowKenoTop leftTK"><span class="icKeno icChan"></span>CHẲN: <span id="tk_chan" class="tk">0</span></div>
									<div class="rowKenoTK rowKenoBot leftTK"><span class="icKeno icLe"></span>LẺ: <span id="tk_le" class="tk">0</span></div>
    							</div>
    							<div class="col-6">
									<div class="rowKenoTK rowKenoTop rightTK"><span class="icKeno icLon"></span>LỚN: <span id="tk_lon" class="tk">0</span></div>
									<div class="rowKenoTK rowKenoBot rightTK"><span class="icKeno icBe"></span>BÉ: <span id="tk_be" class="tk">0</span></div>
    							</div>
    							</div>
								<div class="clear"></div>
								<div class="boxTotal row">
									<div class="col-6">
										<div class="totalKeno leftTK">Tổng: <span id="tk_total" class="tk">0</span></div>
									</div>
									<div class="col-6">
										<div class="totalKeno rightTK">Kỳ sau: <span id="tk_countdown" class="tk">00:00</span></div>
									</div>
									<div class="clear" style="height:0"></div>
								</div>
							</div>
							<div class="boxKQKeno col-sm-6" id="kq">
								<div class="rowKQKeno"><span class="keno_ball" id="ball_1"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_2"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_3"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_4"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_5"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_6"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_7"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_8"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_9"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_10"><img src="/assets/images/wait_keno.svg"></span></div><div class="rowKQKeno"><span class="keno_ball" id="ball_11"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_12"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_13"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_14"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_15"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_16"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_17"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_18"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_19"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_20"><img src="/assets/images/wait_keno.svg"></span></div>
							</div>
							<div class="keno_waiting"><div class="keno_time_title">KỲ XỔ TIẾP THEO</div><div class="keno_time_waiting">00:01</div></div>

						@else
						<?php
							$kq = $rs['DB'];
							$kq1 = array_slice($kq,0,10);
							$kq2 = array_slice($kq,10,10);
							$time = explode(" ",$rs['updated_at'] . '');
							$time[1] = substr($time[1],0,5);
							$chan = 0;
							$be = 0;
							foreach($kq as $item){
								if ($item <= 40)
									$be++;
								if ($item %2 == 0)
									$chan++;
							}
						?>
						<div class=" col-sm-6  row">
						    
						    <div class="boxTotal row">
							<div class="col-6">
								<div class="rowKenoTK rowKenoTop leftTK"><span class="icKeno icChan"></span>CHẲN: <span id="tk_chan" class="tk">{{$chan}}</span></div>
								<div class="rowKenoTK rowKenoBot leftTK"><span class="icKeno icLe"></span>LẺ: <span id="tk_le" class="tk">{{20-$chan}}</span></div>
							</div>
							<div class="col-6">
								<div class="rowKenoTK rowKenoTop rightTK"><span class="icKeno icLon"></span>LỚN: <span id="tk_lon" class="tk">{{20-$be}}</span></div>
								<div class="rowKenoTK rowKenoBot rightTK"><span class="icKeno icBe"></span>BÉ: <span id="tk_be" class="tk">{{$be}}</span></div>
							</div>
							</div>

							<div class="clear"></div>
							<div class="boxTotal row">
								<div class="col-6">
									<div class="totalKeno leftTK">Tổng: <span id="tk_total" class="tk">{{$rs['1']}}</span></div>
								</div>
								<div class="col-6">
									<div class="totalKeno rightTK">Kỳ: <span id="tk_countdown" class="tk"> {{$rs['8']}} {{$time[1]}}</span></div>
								</div>
								<div class="clear" style="height:0"></div>
							</div>
						</div>
						<div class="boxKQKeno col-sm-6" id="kq">
							<div class="rowKQKeno">
										@foreach($kq1 as $item)
											<span class="keno_ball" id="ball_1">{{$item}}</span>
										@endforeach
							</div>

							<div class="rowKQKeno">
										@foreach($kq2 as $item)
											<span class="keno_ball" id="ball_1">{{$item}}</span>
										@endforeach
							</div>
						</div>
						@endif
						
						
        			</div>

					
					</br>
					<a href="http://viet2020.com/assets/huong_dan_keno.html" target="_blank">CÁCH CHƠI KENO Vietlott</a>
					</br>
					</br>
						
	
			</div>
			@endif

			<div class="tab-content br-n pn" id="tabgameContent">
				

				@foreach($gameList as $game)
					<?php
						if ($game['game_code'] == 24){
							// $gamechilderList24 = GameHelpers::GetAllGameListByCusType($user->customer_type,$user->id,24);
							$gamechilderList = GameHelpers::GetAllGameListByCusType($user->customer_type,$user->id,$game['game_code']);
							$count = 0;
							$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,24);
						}else{
							if ($game['game_code']==700) $gamechilderList = [];
							else
								$gamechilderList = GameHelpers::GetAllGameListByCusType($user->customer_type,$user->id,$game['game_code']);
							$count = 0;
							// $gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,$game['id']);
						}
					?>
					@if(count($gamechilderList)>0 && $game['game_code']!=2 && $game['game_code']!=302 && $game['game_code']!=402 && $game['game_code']!=502 && $game['game_code']!=602 && $game['game_code']!=702)
					<div id="{{$game['game_code']}}" class="tab-pane">
						<!-- <div class="panel-body"> -->
							<div class="row">
								<!-- <div class="row"> -->
									<ul class="nav nav-pills m-b-30" style="margin-left: 25px">
										@foreach($gamechilderList as $children)
											<li>
											@if ($children['game_code'] >= 31 && $children['game_code'] <=55)
												<a style="line-height: 24px !important;" class="btn btn-warning btn-custom waves-effect waves-light btn-xs" href="#{{$children['game_code']}}" onclick="LoadContentGame('{{$children['game_code']}}','{{$children['name']}}','{{$children['alias']}}','{{$gamecur['max_point']}}','{{$gamecur['max_point_one']}}','{{$gamecur['odds']}}','{{$gamecur['open']}}','{{$gamecur['close']}}','{{$children['exchange_rates']}}')" data-toggle="tab" aria-expanded="true"
												 id="gamecode{{$children['game_code']}}">{{$children['name']}}</a>
											@else
												<a style="line-height: 24px !important;" class="btn btn-warning btn-custom waves-effect waves-light btn-xs" href="#{{$children['game_code']}}" onclick="LoadContentGame('{{$children['game_code']}}','{{$children['name']}}','{{$children['alias']}}','{{$children['max_point']}}','{{$children['max_point_one']}}','{{$children['odds']}}','{{$children['open']}}','{{$children['close']}}','{{$children['exchange_rates']}}')" data-toggle="tab" aria-expanded="true"
													id="gamecode{{$children['game_code']}}">{{$children['name']}}</a>
											@endif
											</li>
											<?php
												$count++;
											?>
										@endforeach
									</ul>
								<!-- </div> -->
								<div class="tab-content br-n pn hidden-xss">
									@foreach($gamechilderList as $children)
										<div id="{{$children['game_code']}}" class="tab-pane">
											<div class="col-12 col-12 col-12 {{$children['game_code']}} game_content" >
											</div>
										</div>
									@endforeach
								</div>
							<!-- </div> -->
						</div>

					</div>
					@else

					
						@if ($game['game_code']!=2 && $game['game_code']!=302 && $game['game_code']!=402 && $game['game_code']!=502 && $game['game_code']!=602 && $game['game_code']!=702)
							<div id="{{$game['game_code']}}" class="tab-pane hidden-xss">
								<div class="row">
									<div class="col-sm-12 col-lg-12 col-md-12 {{$game['game_code']}} game_content">
									</div>
								</div>
							</div>
						@else
							<div id="{{$game['game_code']}}" class="tab-pane">
						<!-- <div class="panel-body"> -->
							<div class="row">
								<!-- <div class="row"> -->
									
								<!-- </div> -->
								<div class="tab-content br-n pn hidden-xss">
									
									<div id="200" class="tab-pane hidden-xss">
								<div class="row">
									<div class="col-sm-12 col-lg-12 col-md-12 200 game_content">
									</div>
								</div>
							</div>
								</div>
							<!-- </div> -->
						</div>

					</div>
							<!--<div id="{{$game['game_code']}}" class="tab-pane hidden-xss">
								<div class="row">
									<div class="col-sm-12 col-lg-12 col-md-12 {{$game['game_code']}} game_content">
									</div>
								</div>
							</div>-->
							
						@endif
					@endif
				@endforeach
					<div class="row" >
						<div class="col-lg-12" style="text-align: center !important;">
							<span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
						</div>
					</div>
			</div>



		</div>
	</div>
	<input type="hidden" id="current_game" value="">
	<input type="hidden" id="exchange_rates_raw" value="">
	<label id="total_money" hidden></label>
	<input type="hidden" id="gamecode" value="">
	<input type="hidden" id="url" value="{{url('/games')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<input type="hidden" id="open" value="">
	<input type="hidden" id="close" value="">
	<input type="hidden" id="url_kqsxmin" value="{{url('/kqsxmin-by-day')}}">
	<input type="hidden" id="url_kqkenomin" value="{{url('/kqkenomin-by-now')}}">

	@if($user->roleid==1 || $user->roleid==2 || $user->roleid==4 || $user->roleid==5)
		<input type="hidden" id="flag-play" value="0">
	@else
		<input type="hidden" id="flag-play" value="1">
	@endif
		<a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
		<!-- <a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã cược thành công')"></a> -->
@endsection
    <script>
        window.addEventListener('load', (event) => {
            //   $('#tab').show();
            $('#info').removeClass('col-lg-4');
            $('#main').removeClass('col-lg-8');
            $("#tabnew").insertBefore("#main");
            $('#tabnew').show();
            $('.tableft').click(function (e) {
                $(this).children('a')[0].click();
            })
            $('.tabnew').click(function (e) {
                $(this).children('a')[0].click();
            })
            

            // $('.bubble').click(function() {
            //   $(this).children('div').trigger('click');
            // });
            
            // try adding to the below event not on the parent.
            console.log($(window).width())
            if ($(window).width() < 960) {
                $('.tableft').click(function(){
                if($('.new').css('max-height') == '41px'){
                    $('.tableft').css('position', 'relative')
                    $('.tableft').css('visibility', 'unset')
                    $('.new').css('max-height', '1000px')
                }else{
                    $('.new').css('max-height', '41px')
                    $('.tableft:not(.active)').css('visibility', 'hidden')
                    $('.tableft:not(.active)').css('position', 'absolute')
            
                }
            });}

			$('#tableft{{$gameTarget->game_code}}').click()
        });
    </script>
    
<script type="text/javascript">

	// $.getJSON("http://freegeoip.net/json/", function (data) {
    //     	console.log(data);
    //     	// alert(data.ip);
	// 		$('#ipaddress').val(data.ip);
    // 	});

	function refreshHistory() {
		$('#history').fadeOut();
		$('#history').load("{{url('/refresh-history')}}", function() {
			$('#history').fadeIn();
		});
	}
	// function ClickTabGame(gamecode)
	// {
	// 	$('#number_select_text').val('');
	// 	$('#number_select_xien').html('');
	// 	if (gamecode == 1)
	// 	{
	// 		$('#gamecode'+15).click();
	// 	}
	// }

	
</script>

@section('js_call')
<script type="text/javascript">

// $.getJSON("http://api.ipstack.com/check?access_key=a2aafda866e56a85d733ba11e1ed9acd", function (data) {
// 	try{
//     	console.log(data);
//     	// alert(data.ip);
// 		$('#ipaddress').val(data.ip);
// 	}catch(err){
// 		console.log(err);
// 	}
// });

{{--const settings = {
	"async": true,
	"crossDomain": true,
	"url": "https://find-any-ip-address-or-domain-location-world-wide.p.rapidapi.com/iplocation?apikey=873dbe322aea47f89dcf729dcc8f60e8",
	"method": "GET",
	"headers": {
		"x-rapidapi-key": "18f280f03dmshc48a2e4f5ccd1d8p118bc6jsna12c878ffe43",
		"x-rapidapi-host": "find-any-ip-address-or-domain-location-world-wide.p.rapidapi.com"
	}
};

$.ajax(settings).done(function (response) {
	try{
    	console.log(response);
    	// alert(data.ip);
		$('#ipaddress').val(response.ip);
	}catch(err){
		console.log(err);
	}

	// console.log(response);
});--}}

</script>
@endsection