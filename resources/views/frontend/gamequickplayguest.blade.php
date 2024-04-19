<?php
	$gameList = GameHelpers::GetAllGameByParentID(0);
?>
<style>
	/*li.active{
	pointer-events: none;
	cursor: default;
	}*/

	li{
	margin:2px;
	}

	.panel-title{
		font-size: 12px !important;
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

	<div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h6 class="panel-title">Tra kết quả Cược nhanh</h6>
		</div>
		<div class="panel-body">
			<div class="tab-content br-n pn">
				Hỗ trợ tra cứu nhanh với loại cược: Đề, Lô, Xiên, Nhất.
			</div>
		</div>
	</div>

@stop
@section("content")
	<div class="panel panel-color panel-default panel-border panel-shadow">


		<div class="panel-body">
			<div class="row">
				<form class="form-group" method="POST" action="{{ url('/quickplayguest') }}" style=" margin-left: 30px; margin-right: 30px;">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">	
						<label>Tra kết quả Cược nhanh</label>
						@if (isset($quicktext))
							<textarea class="form-control" name="quicktext" rows="3" placeholder="Ví dụ: de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50" >{{$quicktext}}</textarea>
						@else
							<textarea class="form-control" name="quicktext" rows="3" placeholder="Ví dụ: de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50"></textarea>
						@endif
					</div>

					<div class="form-group col-xs-2">
						<button type="submit" name="xemtruoc" class="btn btn-block btn-default btn-sm" style="margin-left: -10px;">So kết quả</button>
					</div>
					<div class="form-group col-xs-2">
					<!-- <input class="form-control input-datepicker" type="text" name="date" value="{{$date}}" readonly style="height:30px !important"> -->

					<input type="text" data-date-format="dd-mm-yyyy" class="form-control" name="date" value="{{$date}}" id="dp1" style="height:30px !important" readonly="true" >

					</div>
				</form>
			</div>
			
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Thống kê theo Thể loại</a></li>
					<li class=""><a href="#tab_2" data-toggle="tab" >Thống kê theo nhóm</a></li>
				</ul>
				<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
				<div class="row">
				<div class="form-group">
				@if (isset($quicktextnotmatch) && $quicktextnotmatch!='')
					<label>Lưu ý các mã sau có thể gây lỗi: <label style=" color:red;">"{{$quicktextnotmatch}}"</label> . Vui lòng kiểm tra lại.</label>
					</br>
				@endif
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
                        <thead>
                        <tr>
                            <!-- <th>Đài</th> -->
                            <th>Thể loại</th>
							<th>Số cược</th>
                            <th>Mã cược</th>
							<th>Điểm</th>
							<th>Kết quả</th>
                        </tr>
                        </thead>
                        <tbody>
						@foreach (GameHelpers::GetAllGame() as $key)
							<?php 
								$haveData = false;
								$betnumber = "";
								$totalbetnumber=0;
								$game_id = "";
								$countrecord=0;
								$totalpoint=0;
								$totalpointkq=0;
								$location_name="";
							?>
							@for($i=0;$i< count($requestdata);$i++)
								<?php
									$req = $requestdata[$i];
									$status = str_replace('overloadmoney','Vượt quá tiền hiện có',$req['status']);
									$status = str_replace('maxbet','Mã vượt quá qua giới hạn',$status);
									if ($req['is_actived'] == false)
										$status = 'Hết hạn cược';
								?>
								@if (isset($req['choices']))
									@for($j=0;$j< count($req['choices']);$j++)
										@if ($req['game_code'] == $key->game_code)
											<?php
												$haveData=true;
												$game_id=$req['game_name'];
												$ch = $req['choices'][$j];
												$betnumber.=$req['choices'][$j]['name'].',';
												$totalbetnumber++;
												if ($totalbetnumber%15==0 && $totalbetnumber>0)
													$betnumber.='</br>';
												$totalpoint+=$ch['point'];
												$totalpointkq+=($req['choices'][$j]['status']*$ch['point']);
												// if (!isset($ch)) continue;
											?>
										@endif
									@endfor
								@endif
							@endfor

							@if($haveData)
								<tr @if ($req['is_actived'] == false) style=" color:red;" @else <?php $enable_cuoc = true;?> @endif>
										<!-- <td>Miền Bắc</td> -->
										<td><button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal{{$key->game_code}}">{{$game_id}}</button></td>
										<td><?php echo $betnumber;?></td>
										<td>{{$totalbetnumber}}</td>
										<td>{{number_format($totalpoint)}}</td>
										<td>{{number_format($totalpointkq)}}</td>
								</tr>
							@endif

						@endforeach
						</tbody>
						</table>
				</div>
			</div>
				</div>
				<div class="tab-pane" id="tab_2">
				<div class="row">
				<div class="form-group">
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
                        <thead>
                        <tr>
                            <!-- <th>Đài</th> -->
                            <th>Thể loại</th>
							<th>Số cược</th>
                            <th>Mã cược</th>
							<th>Điểm</th>
							<th>Kết quả</th>
                        </tr>
                        </thead>
                        <tbody>
						
							
							@for($i=0;$i< count($requestdata);$i++)

								<?php 
									$haveData = false;
									$betnumber = "";
									$totalbetnumber=0;
									$game_id = "";
									$countrecord=0;
									$totalpoint=0;
									$totalpointkq=0;
									$location_name="";
								?>
								<?php
									$req = $requestdata[$i];
									$status = str_replace('overloadmoney','Vượt quá tiền hiện có',$req['status']);
									$status = str_replace('maxbet','Mã vượt quá qua giới hạn',$status);
									if ($req['is_actived'] == false)
										$status = 'Hết hạn cược';
								?>
								@if (isset($req['choices']))
									@for($j=0;$j< count($req['choices']);$j++)
										
											<?php
												$haveData=true;
												$game_id=$req['game_name'];
												$ch = $req['choices'][$j];
												$betnumber.=$req['choices'][$j]['name'].',';
												$totalbetnumber++;
												if ($totalbetnumber%15==0 && $totalbetnumber>0)
													$betnumber.='</br>';
												$totalpoint+=$ch['point'];
												$totalpointkq+=($req['choices'][$j]['status']*$ch['point']);
												// if (!isset($ch)) continue;
											?>
										
									@endfor
								@endif

								@if($haveData)
								<tr @if ($req['is_actived'] == false) style=" color:red;" @else <?php $enable_cuoc = true;?> @endif>
										<!-- <td>Miền Bắc</td> -->
										<td><button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal{{$i}}_group">{{$game_id}}</button></td>
										<td><?php echo $betnumber;?></td>
										<td>{{$totalbetnumber}}</td>
										<td>{{number_format($totalpoint)}}</td>
										<td>{{number_format($totalpointkq)}}</td>
								</tr>
							@endif
							@endfor
						</tbody>
						</table>

						
							

							
														@for($i=0;$i< count($requestdata);$i++)
														<?php $countmacuoc=1?>
														<div id="full-width-modal{{$i}}_group" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="full-width-modalLabel">Chi tiết cược</h4>
                                        </div>
                                        <div class="modal-body">

                                        	<div class="table-rep-plugin">
											<div class="table-responsive">
												<table id="table_winlose"  class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important;">
													<thead>
														<tr>
															<th>STT</th>
															<!-- <th>Đài</th> -->
															<th>Thể loại</th>
															<th>Số cược</th>
															<th>Điểm</th>
															<th>Kết quả</th>
														</tr>
													</thead>
													<tbody>
														<?php 
															$haveData = false;
															$betnumber = "";
															$game_id = "";
															$countrecord=0;
															$totalpoint=0;
															$totalpointkq=0;
															$location_name="";
														?>
															<?php
																$req = $requestdata[$i];
																$status = str_replace('overloadmoney','Vượt quá tiền hiện có',$req['status']);
																$status = str_replace('maxbet','Mã vượt quá qua giới hạn',$status);
																if ($req['is_actived'] == false)
																	$status = 'Hết hạn cược';
															?>
															@if (isset($req['choices']))
																@for($j=0;$j< count($req['choices']);$j++)
																	
																		<?php
																			$haveData=true;
																			$game_id=$req['game_name'];
																			$ch = $req['choices'][$j];
																			$betnumber.=$req['choices'][$j]['name'].',';
																			$totalpoint+=$ch['point'];
																			$totalpointkq+=($req['choices'][$j]['status']*$ch['point']);
																			// if (!isset($ch)) continue;
																		?>
																	<tr>
																	<td>{{$countmacuoc++}}</td>
																	<!-- <td>Miền Bắc</td> -->
																	<td>{{$req['game_name']}}</td>
																	<td>{{$req['choices'][$j]['name']}}</td>
																	<td>{{number_format($ch['point'])}}</td>
																	<td>{{number_format($req['choices'][$j]['status']*$ch['point'])}}</td>
																	</tr>
																@endfor
															@endif
															</tbody>
							
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
														@endfor
													
				</div>
			</div>		
				</div>
				</div>
			</div>
			
						@foreach (GameHelpers::GetAllGame() as $key)
						<?php $countmacuoc=1?>
							<?php 
								$haveData = false;
								$betnumber = "";
								$game_id = "";
								$countrecord=0;
								$totalpoint=0;
								$totalpointkq=0;
								$location_name="";
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
												<table id="table_winlose"  class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important;">
													<thead>
														<tr>
															<th>STT</th>
															<!-- <th>Đài</th> -->
															<th>Thể loại</th>
															<th>Số cược</th>
															<th>Điểm</th>
															<th>Kết quả</th>
														</tr>
													</thead>
													<tbody>
														@for($i=0;$i< count($requestdata);$i++)
															<?php
																$req = $requestdata[$i];
																$status = str_replace('overloadmoney','Vượt quá tiền hiện có',$req['status']);
																$status = str_replace('maxbet','Mã vượt quá qua giới hạn',$status);
																if ($req['is_actived'] == false)
																	$status = 'Hết hạn cược';
															?>
															@if (isset($req['choices']))
																@for($j=0;$j< count($req['choices']);$j++)
																	@if ($req['game_code'] == $key->game_code)
																		<?php
																			$haveData=true;
																			$game_id=$req['game_name'];
																			$ch = $req['choices'][$j];
																			$betnumber.=$req['choices'][$j]['name'].',';
																			$totalpoint+=$ch['point'];
																			$totalpointkq+=($req['choices'][$j]['status']*$ch['point']);
																			// if (!isset($ch)) continue;
																		?>
																	<tr>
																	<td>{{$countmacuoc++}}</td>
																	<!-- <td>Miền Bắc</td> -->
																	<td>{{$req['game_name']}}</td>
																	<td>{{$req['choices'][$j]['name']}}</td>
																	<td>{{number_format($ch['point'])}}</td>
																	<td>{{number_format($req['choices'][$j]['status']*$ch['point'])}}</td>
																	</tr>
																	@endif
																@endfor
															@endif
														@endfor
													</tbody>
							
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
			
			<?php
				$enable_cuoc = false;
			?>
			
			
			</br>

				
		</div>
		
	</div>



	<input type="hidden" id="current_game" value="">
	<input type="hidden" id="gamecode" value="">
	<input type="hidden" id="url" value="{{url('/games')}}">
	<input type="hidden" id="urlH" value="{{url('/quickplayguest')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<input type="hidden" id="open" value="">
	<input type="hidden" id="close" value="">
	<input type="hidden" id="url_kqsxmin" value="{{url('/kqsxmin-by-day')}}">

	
@endsection

<script type="text/javascript">

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
