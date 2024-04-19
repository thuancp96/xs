@extends('admin.admin-template')
@section('title', 'Thắng thua chi tiết')
@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Bảng cược chưa xử lý
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
				@if($user_current->id != $user->id)
		<!-- <div class="row"> -->
			<div class="col-sm-2">
				<a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light"><i class="md md-keyboard-return"></i>Trở lại</a>
			</div>
		<!-- </div> -->
	@endif
					<div class="col-sm-6 hidden">
						<div class="form-group contact-search m-b-30">
							<input type="text" id="input_search_history" class="form-control" placeholder="Tìm kiếm người đánh, loại đặt cược,số tiền... " style="height: 30px !important;">
							<button type="button" class="btn btn-white"><i class="fa fa-search"></i></button>
						</div> <!-- form-group -->
					</div>
					<div class="col-sm-3 hidden">
							<div class="form-group contact-search m-b-30">
								<input type="text" class="form-control column_filter hidden" value="{{date("d/m/Y")}}" id="datepicker-ngaydatcuoc" style="height: 30px !important;">
								<button type="button" class="btn btn-white"><i class="fa fa-calendar"></i></button>
							</div>
					</div>
					
				</div>
				<div class="row">
					<div class="table-rep-plugin">
						<div class="table-responsive" style="overflow-x:hidden">
							<table id="table_winlose"  class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer">
								<thead>
								<tr>
									<!-- <th>#</th> -->
									<th>Hội viên</th>
									<th>Đài</th>
									<th>Thể loại</th>
									<!--<th>Thời gian</th>-->
									<th>Mã cược</th>
									<th>Điểm</th>
									<th>Thực thu</th>
									<th>Thắng/Thua</th>
									<!-- <th>Số trúng thưởng</th> -->
									<!-- <th>Tổng số tiền thắng</th> -->
								</tr>
								</thead>
								<tbody>
								
								@foreach (GameHelpers::GetAllGame() as $key)
									<?php 
										$haveData = false;
										$total_bet_money = 0;
										$total_win_money = 0;
										$total_point = 0;
										$betnumber = "";
										$game_id = "";
										$countrecord=0;
										$location_name='';
									?>
									@foreach($xosorecords as $xosorecord)
										@if ($xosorecord->game_id == $key->game_code)
										
										<?php
										$countrecord++;
										$haveData = true;
										$total_bet_money += $xosorecord->total_bet_money;
										$total_win_money += $xosorecord->total_win_money;
										$betnumber.= $xosorecord->bet_number.',';
										$location_name = $xosorecord->location;
										if ($xosorecord->exchange_rates != 0)
											$total_point+=$xosorecord->total_bet_money/$xosorecord->exchange_rates;
										?>
										@endif
									@endforeach

									@if($haveData)
										<tr>
										<!-- <td>#</td> -->
										<td><button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal{{$key->game_code}}">{{$user->name}}</button></td>
										<td>{{$location_name}}</td>
										<td>{{$key->name}}</td>
										<!--<td></td>-->
										<td>
											{{$countrecord}}
										<!--{{$betnumber}}-->
										</td>
										<td class="text_right text-bold"
										@if ($total_point<0)
												style=" color:red;"
											@endif>{{number_format($total_point)}}</td>
										<td class="text_right text-bold"
										@if ($total_bet_money<0)
												style=" color:red;"
											@endif>{{number_format($total_bet_money)}}</td>	
										<td class="text_right text-bold"
										@if ($total_win_money<0)
												style=" color:red;"
											@endif>{{number_format($total_win_money)}}</td>
										
									</tr>
									@endif
								@endforeach
								</tbody>
								<tfoot>
                            <tr>
                            <td colspan="4" class="text_right pr10">Tổng cộng</td>
                            <td class="text_right pr10 suminvoice text-bold"
                            @if ($total_point<0)
												style=" color:red;"
											@endif>{{number_format($total_point,0)}}</td>
                            <td class="text_right pr10 suminvoice text-bold"
                            @if ($total_bet_money<0)
												style=" color:red;"
											@endif>{{number_format($total_bet_money,0)}}</td>
                            <td class="text_right pr10 suminvoice text-bold"
                            @if ($total_win_money < 0)
                            	style=" color:red;"
                            @endif
                            >{{number_format($total_win_money,0)}}</td>

                            </tr>
                        </tfoot>
							</table>
						</div>
					</div>
				</div>
				<input type="hidden" id="url" value="{{url('/rp/betcanceldetail')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
				@foreach (GameHelpers::GetAllGame() as $key)
									<?php 
										$haveData = false;
										$total_bet_money = 0;
										$total_win_money = 0;
										$total_point = 0;
										$betnumber = "";
										$location_name='';
									?>
									<div id="full-width-modal{{$key->game_code}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="full-width-modalLabel">Chi tiết cược</h4>
                                        </div>
                                        <div class="modal-body">

                                        	<div class="table-rep-plugin">
											<div class="table-responsive">
												<table id="table_winlose"  class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer">
													<thead>
														<tr>
															<!-- <th>#</th> -->
															<th>Hội viên</th>
															<th>Đài</th>
															<th>Thể loại</th>
															<th>Thời gian</th>
															<th>Số cược</th>
															<th>Điểm</th>
															<th>Thực thu</th>
															<th>Thắng/Thua</th>
															<!-- <th>Số trúng thưởng</th> -->
															<!-- <th>Tổng số tiền thắng</th> -->
														</tr>
													</thead>
													<tbody>
													
		                                            @foreach($xosorecords as $xosorecord)
													@if ($xosorecord->game_id == $key->game_code)
												
														<?php
														$haveData = true;
														$total_bet_money += $xosorecord->total_bet_money;
														$total_win_money += $xosorecord->total_win_money;
														$betnumber.= $xosorecord->bet_number.',';
														$location_name = $xosorecord->location;
														if ($xosorecord->exchange_rates != 0)
															$total_point+=$xosorecord->total_bet_money/$xosorecord->exchange_rates;
														?>

														<tr>
										<!-- <td>#</td> -->
										<td>{{$xosorecord->name}}</td>
										@if ($xosorecord->locationslug>20)
                                            <td>{{GameHelpers::ChuyenDoiDaiByDate($xosorecord->locationslug,strtotime($xosorecord->created_at))}}</td>
                                        @else
                                            <td>{{$xosorecord->location}}</td>
                                        @endif
										<td>{{$xosorecord->game}}</td>
										<td>{{date("d/m/Y H:i:s", strtotime($xosorecord->created_at))}}
										@if ($xosorecord->game_id >= 700 && $xosorecord->game_id < 800 && isset($xosorecord->xien_id)) ( Kỳ {{$xosorecord->xien_id}}) @endif
										</td>
										<td>{{$xosorecord->bet_number}} 
										@if ($xosorecord->game_id == 18 || $xosorecord->game_id == 9 || $xosorecord->game_id == 10 || $xosorecord->game_id == 11 || $xosorecord->game_id == 29)({{27-$xosorecord->xien_id}})@endif  @if ($xosorecord->game_id >= 100 && $xosorecord->game_id <= 200 && isset($xosorecord->xien_id) && $xosorecord->xien_id <=24) ( Kỳ {{$xosorecord->xien_id}}) @endif</td>
										<td class="text_right">
										@if ($xosorecord->exchange_rates != 0)
											{{number_format($xosorecord->total_bet_money/$xosorecord->exchange_rates,0)}}
											<?php
									
									// $total_point += $xosorecord->total_bet_money/$xosorecord->exchange_rates;
								?>
										@else
											0
										@endif
										</td>
										<td class="text_right text-bold">{{number_format($xosorecord->total_bet_money,0)}}</td>
										
											@if($xosorecord->total_win_money>0)
											<td class="text_right text-bold">
												{{number_format($xosorecord->total_win_money,0)}}
											@elseif ($xosorecord->total_win_money<0)
											<td class="text_right text-bold" style=" color:red;">
												{{number_format($xosorecord->total_win_money,0)}}
											@elseif ($xosorecord->total_win_money==0)
											<td class="text_right text-bold">
												Chưa xử lý
											@endif
										</td>
										<!-- <td>{{$xosorecord->total_win_money}}</td> -->
										<!-- <td>{{number_format($xosorecord->total_win_money,0)}}</td> -->
									</tr>
													@endif
													@endforeach
													</tbody>

													<tfoot>
                            							<tr>
							                            <td colspan="5" class="text_right pr10">Tổng cộng</td>
							                            <td class="text_right pr10 suminvoice">{{number_format($total_point,0)}}</td>
							                            <td class="text_right pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
							                            <td class="text_right pr10 suminvoice"
							                            @if ($total_win_money < 0)
							                            	style=" color:red;"
							                            @endif
							                            >{{number_format($total_win_money,0)}}</td>

							                            </tr>
							                        </tfoot>
												</table>
											</div>
										</div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Đóng</button>
                                            <!-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> -->
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
								@endforeach
			</div>
		</div>
	</div>
@endsection
@section('js_call')
	<script src="/assets/admin/js/report.js?v=1.01111"></script>
@endsection