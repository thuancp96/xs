<?php
$now = \Carbon\Carbon::now();
    $yesterday = date('Y-m-d', time()-86400);
    $datepickerXS= date('d-m-Y', time()-86400);
    if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('M') )<30)){
		// Cache::tags('authors')->add('kqxs-'.$yesterday,xoso::getKetQua(1,$yesterday),env('CACHE_TIME', 0));
		$rs = 
		// Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($yesterday) {
			// return 
			xoso::getKetQua(1,$yesterday);
		// });
    }
    else{
		// $rs = xoso::getKetQua(1,date('Y-m-d'));
		$rs = 
		// Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
			// return 
			xoso::getKetQua(1,date('Y-m-d'));
		// });
        $datepickerXS= date('d-m-Y');
    }

$gameList = GameHelpers::GetAllGameByParentID(0,$location->slug);
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

	<!-- <div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h6 class="panel-title">Chọn loại</h6>
		</div>
		<div class="panel-body">
			<div class="tab-content br-n pn">
				

				
					
			</div>
		</div>
	</div> -->

	<div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Thời gian còn lại</h3>
		</div>
		<div class="panel-body hidden" id="open_close_game_timer" >
			@foreach($gameList as $game)
			<div class="row">
				<!-- <div class="col-xs-6"><b>{{$game['name']}}: </b></div> -->
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
				<input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
				<!-- <div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div> -->
			</div>
			@endforeach
		</div>
		<div class="panel-body">
			@foreach($gameList as $game)
			@if ($game['game_code'] == 2 || $game['game_code'] == 17 || $game['game_code'] == 56 || $game['game_code'] == 27 || $game['game_code'] == 28 || $game['game_code'] == 26 || $game['game_code'] == 15 || $game['game_code'] == 3)  
				<div class="row" hidden>
					<div class="col-xs-6"><b>{{$game['name']}}: </b></div>
					<!-- <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> -->
					<!-- <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> -->
					<div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
				</div>
			@else
				<div class="row">
					<div class="col-xs-6"><b>{{$game['game_code'] == 25 ? 'Thần tài' : $game['name']}}: </b></div>
					<!-- <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> -->
					<!-- <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> -->
					<div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
				</div>
			@endif
			
			@endforeach
		</div>
	</div>

@stop
@section("content")
	<div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
		<div class="panel-body">
			<div class="row">
				<form class="form-group" method="POST" action="{{ url('/quickplay/1') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" id="ipaddress" name="ipaddress" value="undetected">
					
					
					

					@if (isset($_iscuoc) && $_iscuoc == '1')
						<div class="form-group">
						
							<label>Cược nhanh</label>
							
							@if (isset($quicktext))
								<textarea class="form-control" name="quicktext" id="quicktext" rows="3" placeholder="Hãy nhập đúng mẫu cược
Thể loại – Số cược – tiền cược
Ví dụ: de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50" readonly>{{$quicktext}}</textarea>
							@else
								<textarea class="form-control" name="quicktext" id="quicktext" rows="3" placeholder="Hãy nhập đúng mẫu cược
Thể loại – Số cược – tiền cược
Ví dụ: de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50" readonly></textarea>
							@endif
						</div>

						<div class="form-group col-xs-2">
							<a href="{{ url('/quickplay/1') }}" name="" class="btn btn-block btn-default btn-sm" style="margin-left: -10px;">Cược tiếp</a>
						</div>
						<label>Vui lòng xem bảng dưới và Kiểm tra mã ở <a href="/history/1" target="_blank">Bảng Cược</a></label>
					@else
						
							<div class="form-group">
							
								<label>Cược nhanh.</label> <a class="hdcuoc" rel="noopener noreferrer" href="/huongdannhaptinnhanh" >Hướng dẫn cược nhanh tại đây</a>
								
								@if (isset($quicktext))
									<textarea class="form-control" name="quicktext" id="quicktext" rows="3" placeholder="Hãy nhập đúng mẫu cược. &#10;Thể loại – Số cược – tiền cược.">{{$quicktext}}</textarea>
									<!-- Ví dụ: de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50"  -->
								@else
									<textarea class="form-control" name="quicktext" id="quicktext" rows="3" placeholder="Hãy nhập đúng mẫu cược.&#10;Thể loại – Số cược – tiền cược."></textarea>

								@endif
								</br>
									<label>Chọn <input style="margin-top: -2px;" type="checkbox" name="checkbox_lowp" id="checkbox_lowp" {{(isset($checkbox_lowp) && $checkbox_lowp==true) ? 'checked' : "" }} /> nếu muốn giới hạn cược theo giá thấp.</label>
									<a class="hdcuoc" rel="noopener noreferrer" href="/thongsogiathap" >Sửa Thông số giá thấp tại đây</a>
									</br>
							</div>

							<div class="form-group col-xs-2">
							
							</div>

							<!-- <div class="form-group col-xs-2">
								<button type="button" data-toggle="modal" data-target="#myModal" name="xemtruoc" class="btn btn-block btn-default btn-sm" style="margin-left: -10px;">Xem trước</button>
							</div> -->

							<div class="form-group col-xs-2">
							    <button type="button" data-loading-text="Đang tải ..." onclick="clickXemTruoc('0')"  name="xemtruoc" id="xemtruoc" 
								data-whatever="Xem trước mã cược" class="btn btn-block btn-default btn-sm" style="margin-left: -10px;">Xem trước</button>
								<button type="button" data-loading-text="Đang vào cược ..." onclick="clickXemTruoc('1',1)" name="vaocuoc" id="vaocuoc" 
								data-whatever="Vào cược thành công!" class="btn btn-block btn-danger btn-sm" style="margin-left: 0px;">Vào cược</button>
							</div>

							<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
								<div class="modal-dialog modal-dialog-centered" style="    max-width: 1000px;" role="document">
									<div class="modal-content">
										<div class="modal-header">
											
											<h6 class="modal-title" style="color:white" id="exampleModalLabel">Xem trước</h6>
											<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body" id="previewModalContent">
										</div>

										<div class="modal-footer">
											<div class="row">

												<!-- <div class="form-group col-xs-2">
													<button type="button" data-toggle="modal" data-target="#myModal" name="xemtruoc" class="btn btn-block btn-default btn-sm" style="margin-left: -10px;">Xem trước</button>
												</div> -->

												<div class="col-xs-2">
												    <button type="button" class="btn btn-block btn-danger btn-sm" data-bs-dismiss="modal">Quay lại</button>

													<button type="button" data-loading-text="Đang vào cược ..." onclick="clickXemTruoc('1',0)" name="vaocuoc" id="vaocuoc_modal" 
													data-whatever="Vào cược thành công!" class="btn btn-block btn-success btn-sm" style="margin-left:;">Vào cược</button>
													
												</div>
											</div>
											
										</div>

									</div>
								</div>
							</div>
							@if (isset($quicktext))
								<input type="hidden" >
								<div class="form-group col-xs-2">
									<button type="submit" name="vaocuoc" class="btn btn-block btn-danger btn-sm" style="margin-left: -10px;">Vào cược</button>
								</div>
							@endif

					@endif
				</form>
				
			</div>

			<?php
				$enable_cuoc = false;
				$tongtiencuoc=0;
				$count=0;
			?>
            <div class="row">
				<div class="form-group">

					@if (isset($tin_cuoc) && $tin_cuoc != '')
						<label>Tin cược: <label style=" color:green;">{{$tin_cuoc}}</label></label>
						</br>
						</br>
					@endif

					@if (isset($tin_huy) && $tin_huy != '')
						<label>Tin hủy: <label style=" color:red;">{{$tin_huy}}</label></label>
						</br>
					@endif
					

					@if (isset($quicktextnotmatch) && $quicktextnotmatch!='')
						<label>Lưu ý các mã sau có thể gây lỗi: <label style=" color:red;">"{{$quicktextnotmatch}}"</label> . Vui lòng kiểm tra lại.</label>
						</br>
					@endif
						@if (isset($_iscuoc) && $_iscuoc == '1')
							<label>Lưu ý giá cược có thể thay đổi. Hãy kiểm tra lại sau khi cược.</label>
							<a href="{{ url('/quickplayhistory/1') }}" name=""  style="margin-left: 0px;" target="_blank">Tra cứu cược nhanh tại đây</a>
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
									
									<tr @if ($req['is_actived'] == false || ($status != '' && $status != 'ok')) style="--bs-table-striped-color:red !important; color:red !important;" @else <?php $enable_cuoc = true;?> @endif>
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
									<td colspan="6" class="text_right pr10">Tổng cộng</td>
									<td class="text_right pr10 suminvoice">{{number_format($tongtiencuoc,0)}}</td>
									</tr>
								</tfoot>
					</div>
					@endif
			</div>

			@if (!isset($_iscuoc) || $_iscuoc != '1')
			</br>
			<div class="row">
				<div class="form-group" id="loadHistoryQuickPlay">
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
                        <thead>
                        <tr>
							<th style="width:5%">STT</th>
                            <th style="width:15%">Thời gian</th>
                            <th style="width:40%">Nội dung cược nhanh</th>
							<th style="width:40%">Nội dung cược hủy</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
							$count=count($quickplayhistory);
						?>
							@for($i=0;$i< count($quickplayhistory);$i++)
								<tr>
									<td width='50px'>{{$count--}}</td>
									<td width='150px'>{{explode(" ",$quickplayhistory[$i]->created_at)[1] }}</td>
									<td id="quickbeth{{$i}}" style="word-break: break-all; user-select: text;" onclick="selectTextOnly('quickbeth{{$i}}')">{{$quickplayhistory[$i]->content}}</td>
									<td id="quickbeth{{$i}}-2" style='color: red;user-select: text;' onclick="selectTextOnly('quickbeth{{$i}}-2')">{{$quickplayhistory[$i]->cancel}}</td>
								</tr>
							@endfor
						</tbody>
						<tfoot>
							<tr></tr>
						</tfoot>
					</table>
				</div>
			</div>
			@endif
			</br>
			
		</div>
		
	</div>
	<input type="hidden" id="current_game" value="">
	<input type="hidden" id="gamecode" value="">
	<input type="hidden" id="url" value="{{url('/games')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<input type="hidden" id="open" value="">
	<input type="hidden" id="close" value="">
	<input type="hidden" id="url_kqsxmin" value="{{url('/kqsxmin-by-day')}}">

	@if($user->roleid==1 || $user->roleid==2 ||$user->roleid==4 || $user->roleid==5)
		<input type="hidden" id="flag-play" value="0">
	@else
		<input type="hidden" id="flag-play" value="1">
	@endif
		<a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
		
		
	<script type="text/javascript">

	function refreshHistory() {
		$('#history').fadeOut();
		$('#history').load("{{url('/refresh-history')}}", function() {
			$('#history').fadeIn();
		});
	}

	function checkDuplicate(quicktext){
		try{
			for (let index = 0; index < 30; index++) {
				const quickbeth = $('#quickbeth'+index).html()
				const quickbeth2 = $('#quickbeth'+index+'-2').html()

				if (quickbeth == "") return false;

				if (quickbeth.includes(quicktext)
				|| quicktext.includes(quickbeth)){
					return true;
				}
			}
		}catch(err){

		}
		return false
	}

	function clickXemTruoc(inputC,isShowConfirm=1){

		// $('#previewModalContent').load("{{url('/load-preview-modal')}}", function() {
		// 	$('#myModal').modal('show')
		// });
		//preview
		var quicktext = $('textarea#quicktext').val() == "" ? "de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50" : $('textarea#quicktext').val()
		
		// alert(checkDuplicate(quicktext))

		if (checkDuplicate(quicktext)){
			Swal.fire({
				title: "Phát hiện trùng với lệnh lần cược trước. Bạn có muốn tiếp tục ?",
				text: quicktext,
				type: "info",
				timer: 10000,
				showCancelButton: true,
				confirmButtonColor: "green",
				cancelButtonColor: "brown",
				confirmButtonText: "Vào tiếp",
				cancelButtonText: "Huỷ cược",
				closeOnConfirm: true,
				reverseButtons: true,
				allowOutsideClick: false,
				allowEscapeKey: false,
			}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				openBet(inputC,quicktext,isShowConfirm)
			}
			})
		}else{
			openBet(inputC,quicktext,isShowConfirm)
		}
			
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
	
	function openBet(inputC,quicktext,isShowConfirm=1){

		var text = ""
		if(inputC == "0"){
		    text = "xem trước"
			$($('button')[1]).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...');
		}else{
		    text = "đặt cược"
// 			$($('button')[2]).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
		}

		if(inputC == "0"){
			actionC(inputC)
		}
		else{
			var modal = $('#myModal')
			modal.modal('hide')
			if (isShowConfirm == 0)
				actionC(inputC)
			else
			Swal.fire({
				title: "Bạn có muốn " + text + " ?",
				text: quicktext,
				type: "info",
				timer: 10000,
				showDenyButton: true,
				showCancelButton: true,
				showCancelButton: true,
				confirmButtonColor: "green",
				cancelButtonColor: "brown",
				denyButtonColor: "gray",
				confirmButtonText: "Vào cược",
				cancelButtonText: "Hủy cược",
				denyButtonText: "Xem trước",
				closeOnConfirm: true,
				reverseButtons: true,
				allowOutsideClick: false,
				allowEscapeKey: false,
			}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				// Swal.fire('Saved!', '', 'success')
				actionC(inputC)
			} else if (result.isDenied) {
				// Swal.fire('Changes are not saved', '', 'info')
				clickXemTruoc('0')
			}
			})
		}	
	}

	function actionC(inputC){
		$("#previewModalContent").empty();
			// var $btn = $(this).button('loading')
			if(inputC == "0"){
				$('#xemtruoc').button('loading')
				$('#vaocuoc_modal').show()
			}
			else{
				$('#vaocuoc').button('loading')
				$('#vaocuoc_modal').hide()
			}
				
			// business logic...
			let timerInterval
                Swal.fire({
                title: 'Đang xử lý',
                html: 'Vui lòng chờ trong giây lát.',
                timer: 20000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
                })

			$.ajax({
					type: "GET",
					url: "/load-preview-modal",
					dataType: "html",
					data: {
						quicktext:$('textarea#quicktext').val(),
						ipaddress:$('#ipaddress').val(),
						slug:'1',
						inputC:inputC,
						checkbox_lowp: $("input[name='checkbox_lowp']:checked").val()
						// $('#checkbox_lowp').val()
					},
					success : function(data) { 
					    Swal.close()
					    if(inputC == "0"){
                		    text = "xem trước"
                			$($('button')[1]).html(text);
                		}else{
                		    text = "đặt cược"
                			$($('button')[2]).html(text);
                		}
						$('#previewModalContent').html(data);
						var modal = $('#myModal');
						var button = null;
						modal.modal('show');

						if(inputC == "0"){
							button = $('#xemtruoc')
						}
						else{
							button = $('#vaocuoc')
							$('textarea#quicktext').val("");
							var millisecondsToWait = 2000;
							setTimeout(function() {
								// Whatever you want to do after the wait
								$('#loadHistoryQuickPlay').fadeOut();
								$('#loadHistoryQuickPlay').load("{{url('/reload-quickplayhistory')}}", function() {
								$('#loadHistoryQuickPlay').fadeIn();
							});
							}, millisecondsToWait);
							
						}
						button.button('reset')
						var recipient = button.data('whatever')
						modal.find('.modal-title').text(recipient)
					},
					error : function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
						Swal.close()

						Swal.fire({
							title: "Lỗi",
							// html: text + '</br>' + text2 + '</br>' + text3,
							html: 'Xảy ra lỗi. Vui lòng kiểm tra lại nội dung cược nhanh',
							type: "info",
							timer: 5000,
							showCancelButton: false,
							// confirmButtonColor: "#DD6B55",
							confirmButtonText: "Đã hiểu",
							// cancelButtonText: "Hủy",
							closeOnConfirm: true,
							allowOutsideClick: false,
							allowEscapeKey: false,
						});
					}
			});
	}
	
</script>
		<!-- <a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã cược thành công')"></a> -->
@endsection

<!-- @if (isset($quicktext)) -->

@section('js_call')
<!-- <script type="text/javascript">

$.getJSON("http://api.ipstack.com/check?access_key=a2aafda866e56a85d733ba11e1ed9acd", function (data) {
	try{
    	console.log(data);
    	// alert(data.ip);
		$('#ipaddress').val(data.ip);
	}catch(err){
		console.log(err);
	}
});

</script> -->
@endsection

<!-- @endif -->