<?php
	$enable_cuoc = false;
	$tongtiencuoc=0;
	$count=0;
	$checkCancelBet = false;
	for($i=0;$i< count($requestdata);$i++){
		$req = $requestdata[$i];
		$status = $req['status'];
		if (str_contains($status, 'error021')) {
			$checkCancelBet = true;
			break;
		}
	}
		
?>
    <div class="row">
		<div class="form-group">
			@if ($checkCancelBet == false)
				<label style=" color:black;">Tin gốc: <label >{{$quicktext}}</label></label>
					</br>

				@if (isset($tin_cuoc) && $tin_cuoc != '')
					<label style=" color:green;">Tin cược: <label >{{$tin_cuoc}}</label></label>
					</br>
				@endif

				@if (isset($tin_huy) && $tin_huy != '')
					<label style=" color:red;">Tin hủy: <label >{{$tin_huy}}</label></label>
					</br>
				@endif
				

				@if (isset($quicktextnotmatch) && $quicktextnotmatch!='')
					<label  style=" color:black;">Lưu ý các mã sau có thể gây lỗi: <label style=" color:red;">"{{$quicktextnotmatch}}"</label> . Vui lòng kiểm tra lại.</label>
					</br>
				@endif
					
					<label>Lưu ý giá cược có thể thay đổi. Hãy kiểm tra lại sau khi cược.</label>
					<!-- <a href="{{ url('/quickplayhistory/1') }}" name=""  style="    float: right;color: #71b8ff;text-decoration: underline !important;" target="_blank">Tra cứu cược nhanh tại đây</a> -->
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
						<thead>
						<tr>
							<th>STT</th>
							<!-- <th>Đài</th> -->
							<th>Thể loại</th>
							<th>Số cược</th>
							<th>Giá</th>
							<th>Điểm</th>
							<!-- <th>Thành tiền</th> -->
							<th>Trạng thái</th>
						</tr>
						</thead>
						<tbody>
							@for($i=0;$i< count($requestdata);$i++)
							<?php
								$req = $requestdata[$i];
								$status = str_replace('overloadmoney','Vượt quá tiền hiện có',$req['status']);
								$status = str_replace('maxbet','Mã vượt quá qua giới hạn',$status);
								$status = str_replace('error021','Vượt quá qua giới hạn',$status);
								if ($req['is_actived'] == false)
									$status = 'Hết hạn cược';
							?>
							@if (isset($req['choices']))
							@for($j=0;$j< count($req['choices']);$j++)
							<?php
								$ch = $req['choices'][$j];
								// if (!isset($ch)) continue;
								$tongtiencuoc+=$ch['total'];
								$count++;
							?>
							
							<tr @if ($req['is_actived'] == false || ($status != '' && $status != 'ok')) style=" color:red !important; --bs-table-striped-color:red !important;" @else <?php $enable_cuoc = true;?> @endif>
									<td>{{$count}}</td>
									<!-- <td>Miền Bắc</td> -->
									<td>{{$req['game_name']}}</td>
									<td>{{$ch['name']}}</td>
									<td>{{number_format($ch['exchange'])}}</td>
									<td>{{number_format($ch['point'])}}</td>
									<!-- <td>{{number_format($ch['total'])}}</td> -->
									<td>{{$status}}</td>
							</tr>
							@endfor
							@endif
							@endfor
						</tbody>
						<tfoot>
							<tr>
							<td colspan="6" class="text_right pr10">Tổng cộng: {{number_format($tongtiencuoc,0)}}</td>
							<!-- <td class="text_right pr10 suminvoice">{{number_format($tongtiencuoc,0)}}</td> -->
							</tr>
						</tfoot>
					@else
					<label style=" color:red;">Tin cược vượt quá mức cho phép</label>
					</br>
					<script>
							$('#vaocuoc_modal').hide()
					</script>
					@endif
		</div>

	</div>

			