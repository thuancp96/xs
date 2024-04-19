@extends('admin.admin-template')
@section('title', 'Hội viên thắng thua')
@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Bảng cược đã huỷ
					</h3>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<?php
			$user_current = Auth::user();
	?>

	<div class="row">
		<div class="col-sm-12">
			<div class="card-box">

				<div class="row">
					<div class="col-sm-2 col-xs-2">
						<input class="form-control input-startdate-datepicker" type="text" name="daterange" value="{{$stDate}}" readonly="readonly" >
					</div>

					<div class="col-sm-2 col-xs-2">
						<input class="form-control input-enddate-datepicker" type="text" name="daterange" value="{{$endDate}}" readonly="readonly" >
					</div>

					<div class="col-sm-2 col-xs-2">
						<span class="input-group-btn">
							<a style="margin-right:5px;" href="{{url('/rp/betcancel/'.$stDate.'/'.$endDate)}}" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter">Xem</a>
							<button type="button" style="height: 38px;margin-left: -5px; !important;" class="btn waves-effect waves-light btn-primary" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="javascript:void(0)" id="btn_homnay_sk">Hôm nay</a></li>
								<li><a href="javascript:void(0)" id="btn_homqua_sk">Hôm qua</a></li>
								<li><a href="javascript:void(0)" id="btn_tuannay_sk">Tuần này</a></li>
								<li><a href="javascript:void(0)" id="btn_tuantruoc_sk">Tuần trước</a></li>
								<li><a href="javascript:void(0)" id="btn_thangnay_sk">Tháng này</a></li>
								<li><a href="javascript:void(0)" id="btn_thangtruoc_sk">Tháng trước</a></li>
							</ul>
						</span>
					</div>
					@if($user_current->id != $user->id)
				<!-- <div class="row"> -->
					<div class="col-sm-2 col-xs-2">
						<a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light"><i class="md md-keyboard-return"></i>Trở lại</a>
					</div>
					@else
					<div class="col-sm-2">
					</div>
					@endif
				</div>
				<div class="row hidden">
					
					<div class="col-sm-10" style=" left: -10px">
						<div class="col-sm-4">
	                    	<input class="form-control column_filter input-daterange-datepicker" type="text" name="daterange" value="" readonly="readonly">
	                	</div>
						<div class="col-sm-3 hidden ">
							<div class="form-group">
								<label class="col-sm-3 nopadding control-label datelabel" for="field-1">Từ</label>
								<div class="col-sm-9 nopadding">
									<input type="text" class="form-control column_filter" value="{{$stDate}}" id="datepicker-ngaybatdaudatcuoc" style="height: 30px !important;">
								</div>
							</div>
						</div>
						<div class="col-sm-3 hidden">
							<div class="form-group">
								<label class="col-sm-3 nopadding control-label datelabel" for="field-1">Đến</label>
								<div class="col-sm-9 nopadding">
									<input type="text" class="form-control column_filter" value="{{$endDate}}" id="datepicker-ngayketthucdatcuoc" style="height: 30px !important;">         
								</div>
							</div>
						</div>
						<div class="col-sm-4">
						<!-- <a href="{{url('/rp/betcancel/'.$stDate.'/'.$endDate)}}" class="btn btn-danger" id="btn_view_by_filter">Xem</a> -->
							<!-- <button class="btn btn-radius btn-xs btn-blue btn_submit">Xác nhận</button> -->
							<!-- <button class="btn btn-radius btn-xs today btn-white">Hôm nay</button>
							<button class="btn btn-radius btn-xs btn-white yesterday">Hôm qua</button>
							<button class="btn btn-radius btn-xs btn-white thisweek">Tuần này</button>
							<button class="btn btn-radius btn-xs btn-white lastweek">Tuần trước</button>
							<button class="btn btn-radius btn-xs btn-white thismonth">Tháng này</button>
							<button class="btn btn-radius btn-xs lastmonth btn-turquoise">Tháng trước</button> -->
						</div>
					</div>	
				@if($user_current->id != $user->id)
				<!-- <div class="row"> -->
					<div class="col-sm-2">
						<a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light"><i class="md md-keyboard-return"></i>Trở lại</a>
					</div>
					@else
					<div class="col-sm-2">
					</div>
					@endif
				<!-- </div> -->
	
				</div>
			</div>
			<?php

		use App\Helpers\UserHelpers;
		use Illuminate\Support\Facades\URL;

		$listBreakCrumb = UserHelpers::buildBreadCrumbsUser($user, 1);
		$currentURL = \Request::getRequestUri();//URL::current();
		// print_r($listBreakCrumb);
		// print_r(\Request::getRequestUri());
		?>
		@if($user->id != Auth::user()->id)
		<div class="card-box">
			<div class="row">
				<div class="col-sm-6">
					@for($i=count($listBreakCrumb)-1;$i>=0;$i--)
					<a style="font-size:14px;" href="{{str_replace($user->id,$listBreakCrumb[$i]['url'],$currentURL)}}">{{$listBreakCrumb[$i]['name']}} </a>
					@if($i>0) > @endif
					@endfor
				</div>
			</div>
		</div>
		@endif
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box">
				
				<div class="table-rep-plugin" id="div_history">
					<div class="table-responsive" style="overflow-x:hidden">
						<table id="datatable"  class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer table-hover">
							<thead>
							<tr>
								<!-- <th>#</th> -->
								<th rowspan="2">Tài khoản</th>
								<th rowspan="2">Họ Tên</th>
								<!-- <th>Nhóm</th> -->
								<!-- <th>Trạng thái</th> -->
								<th colspan="2">Hội viên</th>
								<th colspan="3">Tài khoản cấp dưới</th>
								<th>Công ty</th>
								<!-- <th rowspan="2">Chi tiết</th> -->
							</tr>
							<tr>
								<th>Đơn hàng</th>
								<th>Tiền cược</th>
								<!-- <th>Thắng/Thua</th> -->
								<th>Hoa hồng1</th>
								<th>Hoa hồng2</th>
								<th>Tổng cộng</th>
								<th>Thắng/Thua</th>

							</tr>
							</thead>
							<tbody>
								<?php
									$total1 = 0;
									$total2 = 0;
									$total3 = 0;
									$total4 = 0;
									$total5 = 0;
									$total6 = 0;
									$total7 = 0;
									$begin = new DateTime($stDate);
									$end = new DateTime($endDate);
									$end->modify('+1 day');
									$interval = DateInterval::createFromDateString('1 day');
									$period = new DatePeriod($begin, $interval, $end);
								?>
								@foreach($users as $user)
									<?php
										$userReport = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
									?>
										@foreach($period as $dt) 
							<?php
								$stDateTemp = $dt->format("d-m-Y");
								$endDateTemp = $dt->format("d-m-Y");
								if ($dt->format("Y-m-d") > date('Y-m-d')){
									// echo 'continue';
									break;
								}
									
								// echo $endDateTemp.' ';
							?>	
										<?php
											// $userchild = UserHelpers::GetAllUserChild($user);
											// foreach ($userchild as $userC) {
												# code...

										// if ($user->roleid == 2)
										// 		$userReport = XoSoRecordHelpers::ReportSpAgCancel($user,$stDate,$endDate);
										// if ($user->roleid == 4)
										// 		$userReport = XoSoRecordHelpers::ReportAgCancel($user,$stDate,$endDate);
										// if ($user->roleid == 5)
										// 		$userReport = XoSoRecordHelpers::ReportTongCancel($user,$stDate,$endDate);
										// if ($user->roleid == 6)
										// 		$userReport = XoSoRecordHelpers::ReportKhachCancel($user,$stDate,$endDate);

										$type="";
										$cacheTime = env('CACHE_TIME_SHORT', 0);
										$endTimeStamp = strtotime($endDateTemp);
										$endDateNewformat = date('Y-m-d',$endTimeStamp);
										if ($endDateNewformat < date('Y-m-d'))
											$cacheTime = 1440*30;

										$userReportTemp = Cache::remember('XoSoRecordHelpers-ReportKhachCancelv220230115'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type, $cacheTime, function () use ($user,$stDateTemp,$endDateTemp,$type) {
											return  XoSoRecordHelpers::ReportKhachCancelv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
										});

										// $userReport = XoSoRecordHelpers::ReportKhachCancelv2($user, $stDate, $endDate, isset($type) ? $type : "all");
										// Cache::remember('1XoSoRecordHelpers-ReportKhachCXLv2'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate,$type) {
										// 	return  XoSoRecordHelpers::ReportKhachCXLv2($user, $stDate, $endDate, isset($type) ? $type : "all");
										// });
										// $userReport = XoSoRecordHelpers::ReportKhachv2($user, $stDate, $endDate, isset($type) ? $type : "all");
										
										// if ($user->roleid == 2){
										// 	$userReport[1]=($userReport[1]-$userReport[5]-$userReport[8]);//-$tongReport[5]
										// 	$userReport[2]=($userReport[2]+$userReport[5]+$userReport[8]);//+$tongReport[5]);
										// }
										// if ($user->roleid <= 4){
										// 	$userReport[1]=($userReport[1]-$userReport[4]-$userReport[7]);//-$tongReport[5]
										// 	$userReport[2]=($userReport[2]+$userReport[4]+$userReport[7]);//+$tongReport[5]);
										// }
										// if ($user->roleid <= 5){
										// 	$userReport[1]=($userReport[1]-$userReport[3]-$userReport[6]);//
										// 	$userReport[2]=($userReport[2]+$userReport[3]+$userReport[6]);//+$tongReport[5]);
										// }
										for($i=0;$i<=8;$i++){
											if (isset($userReportTemp[$i]))
												$userReport[$i] += $userReportTemp[$i];
										}			
										?>
										@endforeach
										<?php
										if ($user->roleid == 2){
											$userReport[1]=($userReport[1]-$userReport[5]-$userReport[8]);//-$tongReport[5]
											$userReport[2]=($userReport[2]+$userReport[5]+$userReport[8]);//+$tongReport[5]);
										}
										if ($user->roleid <= 4){
											$userReport[1]=($userReport[1]-$userReport[4]-$userReport[7]);//-$tongReport[5]
											$userReport[2]=($userReport[2]+$userReport[4]+$userReport[7]);//+$tongReport[5]);
										}
										if ($user->roleid <= 5){
											$userReport[1]=($userReport[1]-$userReport[3]-$userReport[6]);//
											$userReport[2]=($userReport[2]+$userReport[3]+$userReport[6]);//+$tongReport[5]);
										}
										?>
										@if ($userReport[0] !=0) 
										<tr>
										
										@if($user_current->roleid == 7 && $user_current->id != $user->id)
											<td>{{$user->name}}
											</td>
											<td>{{$user->fullname}}</td>
											<td class="text_right">
											{{number_format($userReport[0])}}
											</td>
											<td class="text_right">
												{{number_format($userReport[1])}}</td>
											<td class="text_right text-bold"
											@if ($userReport[2]<0)
												style=" color:red;"
											@endif
											>{{number_format($userReport[2])}}</td>

											<td class="text_right text-bold"
											@if (0<0)
												style=" color:red;"
											@endif
											>{{number_format(0)}}</td>
											@if ($user->roleid == 5)
												<td class="text_right text-bold"
											@if ($userReport[4]<0)
												style=" color:red;"
											@endif>{{number_format($userReport[4])}}</td>
												<td class="text_right  text-bold" 
												@if ($userReport[4]<0)
												style=" color:red;"
											@endif>{{number_format($userReport[4]+0)}}</td>
												<td class="text_right  text-bold"
												@if (0 - ($userReport[2] + $userReport[4])<0)
												style=" color:red;"
											@endif>{{number_format(0 - ($userReport[2] + $userReport[4]))}}</td>
											@else
												<td class="text_right  text-bold"
												@if ($userReport[3]<0)
												style=" color:red;"
											@endif>{{number_format($userReport[3])}}</td>
												<td class="text_right"
												@if ($userReport[3]<0)
												style=" color:red;"
											@endif>{{number_format($userReport[3]+0)}}</td>
												<td class="text_right text-bold"
												@if (0 - ($userReport[2] + $userReport[3])<0)
												style=" color:red;"
											@endif>{{number_format(0 - ($userReport[2] + $userReport[3]))}}</td>
											@endif
										@else
											<td><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{$user->name}}</a>
											</td>
											<td><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{$user->fullname}}</a></td>
											<td class="text_right text-bold">
												<a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[0])}}</a>
											</td>
											<td class="text_right text-bold"
											@if ($userReport[1]<0)
												style=" color:red;"
											@endif
											>
												<a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[1])}}</a></td>
											

											<td class="text_right text-bold"><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format(0)}}</a></td>
											@if ($user->roleid == 5)
												<td class="text_right text-bold"
												@if ($userReport[4]<0)
												style=" color:red;"
											@endif
												><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[4])}}</a></td>
												<td class="text_right text-bold"
												@if ($userReport[4]<0)
												style=" color:red;"
											@endif
											><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[4]+0)}}</a></td>
												<td class="text_right text-bold"
												
											><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" @if (0 - ($userReport[2] + $userReport[4])<0)
												style=" color:red;"
											@endif>{{number_format(0 - ($userReport[2] + $userReport[4]))}}</a></td>
											@elseif ($user->roleid == 4 || $user->roleid == 2)
												<td class="text_right text-bold"
												@if ($userReport[4]<0)
												style=" color:red;"
											@endif
												><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[4])}}</a></td>
												<td class="text_right text-bold"
												@if ($userReport[4]<0)
												style=" color:red;"
											@endif
											><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[4]+0)}}</a></td>
												<td class="text_right text-bold"
												
											><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" @if (0 - ($userReport[2] + $userReport[4])<0)
												style=" color:red;"
											@endif>{{number_format(0 - ($userReport[2] + $userReport[4]))}}</a></td>
											@else
												<td class="text_right text-bold"
												@if ($userReport[3]<0)
												style=" color:red;"
											@endif
											><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[3])}}</a></td>
												<td class="text_right text-bold"
												@if ($userReport[3]<0)
												style=" color:red;"
											@endif><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="">{{number_format($userReport[3]+0)}}</a></td>
												<td class="text_right text-bold"
												><a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" @if (0 - ($userReport[2] + $userReport[3])<0)
												style=" color:red;"
											@endif>{{number_format(0 - ($userReport[2] + $userReport[3]))}}</a></td>
											@endif
										@endif
										<!-- <td style="text-align: center">
										<a href="{{url('/rp/betcancel-detail/'.$user->id.'/'.$stDate.'/'.$endDate)}}" class="table-action-btn"><i class="md md-assignment"></i></a>
									</td> -->
									</tr>
									@endif
									<?php
									$total1 += $userReport[0];
									$total2 += $userReport[1];
									$total3 += $userReport[2];
									$total4 += 0;
									if ($user->roleid == 5){
										$total5 += $userReport[4];
										$total6 += $userReport[4];
										$total7 += (0 - ($userReport[2] + $userReport[4]));
									}
									else
									if ($user->roleid == 4 || $user->roleid == 2){
										$total5 += $userReport[4];
										$total6 += $userReport[4];
										$total7 += (0 - ($userReport[2] + $userReport[4]));
									}
									else{
										$total5 += $userReport[3];
										$total6 += $userReport[3];
										$total7 += (0 - ($userReport[2] + $userReport[3]));
									}
									
									// $total7 += (0 - ($userReport[2] + $userReport[4]));
								?>
								@endforeach
							</tbody>
							<tfoot>
                            <tr>
                            	<td colspan="2" class="text_right pr10">Tổng số</td>
                            	<td class="text_right pr10 suminvoice text-bold"
                            	@if ($total1<0)
												style=" color:red;"
											@endif
											>{{number_format($total1)}}</td>
                            	<td class="text_right pr10 suminvoice text-bold"
                            	@if ($total2<0)
												style=" color:red;"
											@endif
											>{{number_format($total2)}}</td>
                            	
                            	<td class="text_right pr10 suminvoice text-bold"
                            	@if ($total4<0)
												style=" color:red;"
											@endif>{{number_format($total4)}}</td>
                            	<td class="text_right pr10 suminvoice text-bold"
                            	@if ($total5<0)
												style=" color:red;"
											@endif>{{number_format($total5)}}</td>
                            	<td class="text_right pr10 suminvoice text-bold"
                            	@if ($total6<0)
												style=" color:red;"
											@endif>{{number_format($total6)}}</td>
                            	<td class="text_right pr10 suminvoice text-bold"
                            	@if ($total7<0)
												style=" color:red;"
											@endif>{{number_format($total7)}}</td>
                            </tr>
                        </tfoot>
						</table>
					</div>
				</div>
				<input type="hidden" id="user-id-delete">
				<input type="hidden" id="url" value="{{url('/rp/betcancel')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			</div>
		</div>
	</div>
@endsection

@section('js_call')
	<script src="/assets/admin/js/user.js"></script>
	<script src="/assets/admin/js/report.js?v=1.01111"></script>
@endsection
