<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.css" integrity="sha512-SZgE3m1he0aEF3tIxxnz/3mXu/u/wlMNxQSnE0Cni9j/O8Gs+TjM9tm1NX34nRQ7GiLwUEzwuE3Wv2FLz2667w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css" integrity="sha512-3q8fi8M0VS+X/3n64Ndpp6Bit7oXSiyCnzmlx6IDBLGlY5euFySyJ46RUlqIVs0DPCGOypqP8IRk/EyPvU28mQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
	#ex1Slider .slider-selection {
		background: #BABABA;
	}

	.slider-handle.round {
		position: absolute;
		width: 20px;
		height: 20px;
		border: 1px solid #EFF2F7;
		background: #5b9422 !important;
		cursor: pointer;
	}

	.card {
		border: 0 !important;
	}

	.card-body {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.c-saba-popup-header-balance {
		display: flex;
		flex-direction: column;
	}

	.mainbalancesaba,
	.gamebalancesaba,
	.maxtransfersaba {
		font-weight: bold;
	}

	hr {
		margin: 0;
		margin-left: 10px !important;
		margin-right: 10px !important;
		color: #c5c5c5 !important;
	}

	.c-saba-popup-main-entry {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.modal .modal-dialog .modal-content .modal-body,
	#thuhoi {
		font-size: 13;
	}

	.modal-footer {
		padding: 0 !important;
	}
</style>

<div id="transfer-modal-minigame" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" style="color:white" id="sabaModalLabel">CHUYỂN TIỀN minigame</h6>
			</div>
			<form id="change-pass-form" class="form-horizontal" data-parsley-validate novalidate>
				<div class="modal-body">
					<div class="mb-3">
						<div class="card">
							<div class="card-body">
								<div class="c-saba-popup-header-balance">
									<span class="c-text" title="Số Dư Khả Dụng">Số Dư Khả Dụng</span>
									<div clas="row"><span class="card-text mainbalancesaba"></span> CHIP</div>
								</div>
								<a onclick="thuhoi()" id="thuhoi" class="btn btn-primary" style="display: block !important">Thu hồi tất cả</a>

							</div>
						</div>
					</div>
					<div class="mb-3">
						<div class="card">
							<div class="card-body">
								<div class="c-saba-popup-header-balance">
									<span class="c-text" title="Số Dư Khả Dụng">Số Dư CHIP</span>
									<span class="card-text gamebalancesaba"></span>
								</div>

								<div class="c-saba-popup-header-balance">
									<span class="c-text" title="Số Dư Khả Dụng">Tối đa mua vào</span>
									<span class="card-text maxtransfersaba"></span>
									<span class="card-text maxbetsaba hidden"></span>
								</div>

							</div>
							<hr style="margin: 0">
							<div class="card-body">
								<div class="c-saba-popup-header-balance">
									<span class="c-text" id="titlemoneyTranssaba" title="Số Dư Khả Dụng" style="margin-top: -15;">Số CHIP chuyển</span>
								</div>


								<div class="form-group" bis_skin_checked="1">
									<!-- <label for="exampleInputEmail1">Email address</label> -->
									<input type="text" class="form-control" id="moneyTranssaba" onkeypress='validate(event)' name="confirmpass" style="text-align: center; font-weight: bold; width: 100%" autocomplete="false" placeholder="" value="" data-parsley-minlength="6">
									~ <label id="moneyConvertToUser" class="form-text text-muted"></label>
								</div>

							</div>



						</div>
					</div>

					<!--<div class="mb-3">-->
					<!--                <input type="password" class="form-control" id="confirmpass" name="confirmpass" autocomplete="false" placeholder="Hãy nhập mật khẩu mới" required data-parsley-error-message="Nhập mật khẩu tối thiểu 6 ký tự" data-parsley-minlength="6">-->
					<!--            </div>-->
					<div class="modal-footer d-block  text-center">
						<button onclick='Chuyen("IN") ' type="button" id="chuyentien" class="btn btn-primary" style="border: solid 1px #6b8c14 !important; background: linear-gradient(109deg,#4C9EEA,#365294)!important;">Chuyển tiền và chơi</button>
						<button onclick='Chuyen("OUT") ' type="button" id="ruttien" class="btn btn-primary" style="border: solid 1px #6b8c14 !important; background: linear-gradient(109deg,#4C9EEA,#365294)!important;display:none">Rút tiền và chơi</button>
						<a id="btnClickSaba" onclick='window.open("http://99lucky.com/minigame", "_blank");' style='display: none'></a>
					</div>

				</div>



				<div class="modal-footer hidden" style="text-align: center !important;">
					<!-- <ins data-dismiss="modal">Đóng</ins> -->
					<input type="hidden" id="sa-success">
					<input type="hidden" id="user_edit_id_cpw">
				</div>
			</form>
		</div>
	</div>
</div>

<input type="hidden" id="urlUserpercent" value="{{url('/customer-type')}}">
<input type="hidden" id="url" value="{{url('/')}}">
<input type="hidden" id="token" value="{{ csrf_token() }}">
<a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Chỉnh sửa thành công')"></a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.js"></script>
<script>
	isloaded = false;

	function thuhoi() {
		$($('#thuhoi')).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang xử lý');
		$.get("/api/minigame/recall", function(data, status) {
			$($('#thuhoi')).html('Thu hồi tất cả');
			if (data.message != true) {
				isloaded = false;
				if (window.location.href.indexOf("thongtintk") == -1) {
					// isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
					$('.container.white-bg').hide();
					$('.loadere').show();
				} else {
					$('.container.white-bg').show();
					$('.loadere').hide();
				}
				$("#transfer-modal-minigame").modal("hide");
				Swal.fire("", data.message, "error").then((value) => {
					if (!isloaded) {
						$('.container.white-bg').hide();
						$('.loadere').show();
						if (isMobile.any) {
							$('#btnClickSaba').trigger('click');
							$('.container.white-bg').show();
							$('.loadere').hide();
							$.get("/api/minigame/info", function(data, status) {
								$(".card-text.mainbalancesaba").html(data.mainbalance.toLocaleString('en-US'));
								$(".card-text.gamebalancesaba").html(data.SABAbalance.toLocaleString('en-US'));
							});
							//   $("#transfer-modal-minigame").modal("show");
						}
					}
				});;
			} else {
				if (window.location.href.indexOf("thongtintk") == -1) {
					isMobile.any ? true : document.getElementById('MainSabaGame').src = "/api/minigame";
					$('.container.white-bg').hide();
					$('.loadere').show();
				} else {
					$('.container.white-bg').show();
					$('.loadere').hide();
				}
				$("#transfer-modal-minigame").modal("hide");
				$.get("/api/minigame/info", function(data, status) {
					$(".card-text.mainbalancesaba").html(data.mainbalance.toLocaleString('en-US'));
					$(".card-text.gamebalancesaba").html(data.SABAbalance.toLocaleString('en-US'));
				});
				Swal.fire("", "Thu hồi thành công", "success").then((value) => {
					;
					if (window.location.href.indexOf("thongtintk") != -1) {
						window.location.href = "/thongtintk";
					}
				})
			}
		});
	}

	function Chuyen(mode = "IN") {
		if (Number($('#moneyTranssaba').val().replaceAll(',','') == '0' || Number($('#moneyTranssaba').val().replaceAll(',','') == ''))){
		    $("#transfer-modal-minigame").modal("hide");
		    // if (isMobile.any)
		        // $('#btnClickSaba').trigger('click');
				if (window.location.href.indexOf("thongtintk") == -1) {
					document.getElementById('MainSabaGame').src = "/api/minigame";
				}
		        return;
		}
		isMobile.any = true;
		$('.loadere').show();
		$($('#chuyentien')).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang xử lý');
		$($('#ruttien')).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang xử lý');
		$.get("/api/minigame/transfer?mode=" + mode + "&money=" + Number($('#moneyTranssaba').val().replaceAll(',', '')), function(data, status) {
			$($('#chuyentien')).html('Chuyển tiền');
			$($('#ruttien')).html('Rút tiền');

			if (data.message != true) {
				isloaded = false;
				if (window.location.href.indexOf("thongtintk") == -1) {
					// isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
					$('.container.white-bg').hide();
					$('.loadere').show();
				} else {
					$('.container.white-bg').show();
					$('.loadere').hide();
				}

				$("#transfer-modal-minigame").modal("hide");
				Swal.fire("", data.message, "error").then((value) => {
					if (!isloaded) {
						$('.container.white-bg').hide();
						$('.loadere').show();
					}
					// if (isMobile.any) 
					{
						// $('#btnClickSaba').trigger('click');
						document.getElementById('MainSabaGame').src = "/api/minigame";
						$('.container.white-bg').show();
						$('.loadere').hide();
						$("#transfer-modal-minigame").modal("show");
						$.get("/api/minigame/info", function(data, status) {
							// console.log(data)
							$(".card-text.mainbalancesaba").html(data.mainbalance.toLocaleString('en-US'));
							$(".card-text.gamebalancesaba").html(data.SABAbalance.toLocaleString('en-US'));
							$(".card-text.maxtransfersaba").html(data.MaxTransfer.toLocaleString('en-US'));
							$(".card-text.maxbetsaba").html(data.MaxBet.toLocaleString('en-US'));
							$("#total_money_saba").html(data.SABAbalance.toLocaleString('en-US'));
							$("#total_money_saba_menubar").html(data.SABAbalance.toLocaleString('en-US')+ ' saba');
						});
					}

					if (window.location.href.indexOf("thongtintk") != -1) {
						window.location.href = "/thongtintk";
					}
				});
			} else {

				isloaded = false;
				if (window.location.href.indexOf("thongtintk") == -1) {
					// isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
					$('.container.white-bg').hide();
					$('.loadere').show();
				} else {
					$('.container.white-bg').show();
					$('.loadere').hide();
				}

				$("#transfer-modal-minigame").modal("hide");
				$('.loadere').hide();
				Swal.fire("", "Chuyển tiền thành công", "success").then((value) => {
					if (!isloaded) {
						$('.container.white-bg').hide();
						$('.loadere').show();
					}
					// if (isMobile.any) 
					{
						// $('#btnClickSaba').trigger('click');
						if (window.location.href.indexOf("thongtintk") == -1) {
							document.getElementById('MainSabaGame').src = "/api/minigame";
						}
						$('.container.white-bg').show();
						$('.loadere').hide();
						// $("#transfer-modal-minigame").modal("show");
						$.get("/api/minigame/info", function(data, status) {
							console.log(data)
							$(".card-text.mainbalancesaba").html(data.mainbalance.toLocaleString('en-US'));
							$(".card-text.gamebalancesaba").html(data.SABAbalance.toLocaleString('en-US'));
							$(".card-text.maxtransfersaba").html(data.MaxTransfer.toLocaleString('en-US'));
							$(".card-text.maxbetsaba").html(data.MaxBet.toLocaleString('en-US'));
							$("#total_money_saba").html(data.SABAbalance.toLocaleString('en-US'));
							$("#total_money_saba_menubar").html(data.SABAbalance.toLocaleString('en-US')+ ' saba');
						});
					}
					if (window.location.href.indexOf("thongtintk") != -1) {
						window.location.href = "/thongtintk";
					}
					
				});
			}
		});
	}

	// document.addEventListener('DOMContentLoaded', () => {
	//     isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";


	// });
	document.addEventListener('DOMContentLoaded', () => {
		// alert('load saba')
		try {
			if (window.location.href.indexOf("thongtintk") != -1) {
				$.get("/api/minigame/info", function(data, status) {
					// $("#transfer-modal-minigame").modal("show");
					$("#total_money_minigame").html(data.SABAbalance.toLocaleString('en-US'));
					$(".card-text.mainbalancesaba").html(data.mainbalance.toLocaleString('en-US'));
					$(".card-text.gamebalancesaba").html(data.SABAbalance.toLocaleString('en-US'));
					$(".card-text.maxtransfersaba").html(data.MaxTransfer.toLocaleString('en-US'));
					$(".card-text.maxbet").html(data.MaxBet.toLocaleString('en-US'));
					$("#total_money_saba").html(data.SABAbalance.toLocaleString('en-US'));
					$("#total_money_saba_menubar").html(data.SABAbalance.toLocaleString('en-US') + ' saba');
					// 
					slider = new Slider('#ex1', {
						max: data.mainbalance
					});
					slider.on("slide", function(sliderValue) {
						$("#moneyTranssaba").val(sliderValue);
						$('#moneyTranssaba').trigger("input");
					});
					slider.on("change", function(sliderValue) {
						seft = this;
						$("#moneyTranssaba").val($("#ex1")[0].defaultValue);
						$('#moneyTranssaba').trigger("input");
					});

					// $("#transfer-modal-minigame").modal("show");
				});
			}

			$('#moneyTranssaba').on('input', function(e) {
				$this = $(this);
				moneyTarget = Number($this.val().replaceAll(',', '').replaceAll('.', ''))
				moneyMax = Number($(".card-text.mainbalancesaba").html().replaceAll(',', '').replaceAll('.', ''))
				moneyInGame = Number($(".card-text.gamebalancesaba").html().replaceAll(',', '').replaceAll('.', ''))
				moneyMaxTransfer = Number($(".card-text.maxtransfersaba").html().replaceAll(',', '').replaceAll('.', ''))
				moneyMaxBet = Number($(".card-text.maxbetsaba").html().replaceAll(',', '').replaceAll('.', ''))
				// if ($("#sabaModalLabel").html() == "CHUYỂN TIỀN SANG SABA" || $("#sabaModalLabel").html() == "CHUYỂN TIỀN"){
				// 	maxxx = moneyMax > moneyMaxTransfer ? moneyMaxTransfer : moneyMax
				// 	if (maxxx > moneyInGame)
				// 		maxxx = maxxx - moneyInGame
				// 	else moneyTarget = 0
				// 	if (maxxx < moneyTarget)
				// 		moneyTarget = maxxx

				// }else{
				// 	if (moneyInGame < moneyTarget)
				// 		moneyTarget = moneyInGame
				// 	else if (moneyMaxBet < moneyInGame ){
				// 		minTranfer = moneyInGame - moneyMaxTransfer
				// 		if (moneyTarget < minTranfer)
				// 			moneyTarget = minTranfer
				// 	}

				// }
				$this.val(moneyTarget.toLocaleString('en-US'));
				$this.val(moneyTarget.toLocaleString('en-US'));
				$('#moneyConvertToUser').html((moneyTarget).toLocaleString('en-US'));
			});

			$('#moneyTranssaba').focusout('input', function(e) {
				$this = $(this);
				moneyTarget = Number($this.val().replaceAll(',', '').replaceAll('.', ''))
				moneyMax = Number($(".card-text.mainbalancesaba").html().replaceAll(',', '').replaceAll('.', ''))
				moneyInGame = Number($(".card-text.gamebalancesaba").html().replaceAll(',', '').replaceAll('.', ''))
				moneyMaxTransfer = Number($(".card-text.maxtransfersaba").html().replaceAll(',', '').replaceAll('.', ''))
				moneyMaxBet = Number($(".card-text.maxbetsaba").html().replaceAll(',', '').replaceAll('.', ''))
				if ($("#sabaModalLabel").html() == "CHUYỂN TIỀN minigame" || $("#sabaModalLabel").html() == "CHUYỂN TIỀN") {
					maxxx = moneyMax > moneyMaxTransfer ? moneyMaxTransfer : moneyMax
					if (maxxx > moneyInGame)
						maxxx = maxxx - moneyInGame
					else moneyTarget = 0
					if (maxxx < moneyTarget)
						moneyTarget = maxxx

				} else {
					if (moneyInGame < moneyTarget)
						moneyTarget = moneyInGame
					else if (moneyMaxBet < moneyInGame) {
						minTranfer = moneyInGame - moneyMaxTransfer
						if (moneyTarget < minTranfer)
							moneyTarget = minTranfer
					}

				}
				$this.val(moneyTarget.toLocaleString('en-US'));
				$('#moneyConvertToUser').html((moneyTarget).toLocaleString('en-US'));
			});


		} catch (err) {}

	});



	document.querySelector("iframe").addEventListener("load", function(e) {
		$('.container.white-bg').show();
		$('.loadere').hide();
		isloaded = true;
	});

	function chuyentienClick() {
		$($('#chuyentien')).html('Chuyển tiền');
		$($('#ruttien')).html('Rút tiền');
		$("#transfer-modal-minigame").modal("show");
		$("#sabaModalLabel").html("CHUYỂN TIỀN minigame");
		$("#titlemoneyTranssaba").html("Số tiền chuyển");
		$("#chuyentientab").addClass("active");
		$("#ruttientab").removeClass("active");
		$("#chuyentien").css("display", "-webkit-inline-flex");
		$("#ruttien").css("display", "none");
		// $("#thuhoi").css("display", "none");
		$("#chuyentienA").css("cssText", "line-height: 24px !important; width: 150px; color: white !important;");
		$("#ruttienA").css("cssText", "line-height: 24px !important; width: 150px; color: black !important;");
		$("#moneyTranssaba").attr("placeholder", "Nhập tiền cần chuyển");
	}

	function ruttienClick() {
		$($('#chuyentien')).html('Chuyển tiền');
		$($('#ruttien')).html('Rút tiền');
		$("#transfer-modal-minigame").modal("show");
		$("#sabaModalLabel").html("RÚT TIỀN TỪ minigame");
		$("#titlemoneyTranssaba").html("Số tiền rút");
		$("#chuyentientab").removeClass("active");
		$("#ruttientab").addClass("active");
		$("#chuyentien").css("display", "none");
		$("#ruttien").css("display", "-webkit-inline-flex");
		// $("#thuhoi").css("display","-webkit-inline-flex");
		$("#ruttienA").css("cssText", "line-height: 24px !important; width: 150px; color: white !important;");
		$("#chuyentienA").css("cssText", "line-height: 24px !important; width: 150px; color: black !important;");
		$("#moneyTranssaba").attr("placeholder", "Nhập tiền cần rút");
	}

	// function chuyentienClick() {
	// 	$("#transfer-modal-minigame").modal("show");
	// 	$("#sabaModalLabel").html("CHUYỂN TIỀN SANG SABA");
	// 	$("#chuyentien").css("display","-webkit-inline-flex");
	// 	$("#thuhoi").css("display", "none");
	// }

	// function ruttienClick() {
	// 	$("#transfer-modal-minigame").modal("show");
	// 	$("#sabaModalLabel").html("RÚT TIỀN TỪ SABA");
	// 	$("#chuyentien").css("display", "none");
	// 	$("#thuhoi").css("display","-webkit-inline-flex");
	// }





	// document.querySelector("iframe").addEventListener("load", function(e) {
	// 	// $('.container.white-bg').show();
	// 	// $('.loadere').hide();
	// 	isloaded = true;
	// });

	function validate(evt) {
		var theEvent = evt || window.event;

		// Handle paste
		if (theEvent.type === 'paste') {
			key = event.clipboardData.getData('text/plain');
		} else {
			// Handle key press
			var key = theEvent.keyCode || theEvent.which;
			key = String.fromCharCode(key);
		}
		var regex = /[0-9]|\./;
		if (!regex.test(key)) {
			theEvent.returnValue = false;
			if (theEvent.preventDefault) theEvent.preventDefault();
		}
	}
</script>