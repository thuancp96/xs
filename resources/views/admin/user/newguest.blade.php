<style>
	.new_user .col-sm-3 {
		padding: 0px;
		margin-top: 5px;
		margin-left: 10px;
	}
</style>

<style>
	.modal-content {
		height: auto !important;
		overflow: auto;
	}

	.parsley-custom-error-message{
		color: rgb(218, 50, 50) !important;
	}
</style>

<div id="create-modal-newguest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
				<h4 class="modal-title">Tạo tài khoản</h4>
			</div>
			<form id="create-user-form" data-parsley-validate novalidate>
				<div class="modal-body new_user">
					<div class="row form_create">
						<div class="row">
							<div class="row" style="margin-bottom: 15px;">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-1" class="col-sm-6 col-xs-6 control-label">Tài khoản</label>
										<div class="col-sm-8 input-group">
											<!-- <span class="input-group-addon"><i class="fa fa-user"></i></span> -->
											<input type="text" id="usernameG" name="usernameG" class="form-control" placeholder="Hãy nhập tài khoản" required data-parsley-error-message="Bạn chưa nhập tài khoản" data-parsley-trigger="keyup">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-1" class="col-sm-6 col-xs-6 control-label">Họ và Tên</label>
										<div class="col-sm-8 col-xs-8 input-group">
											<!-- <span class="input-group-addon"><i class="fa fa-font"></i></span> -->
											<input type="text" name="fullnameG" class="form-control" id="fullnameG" placeholder="Hãy nhập tên" parsley-trigger="change" required data-parsley-error-message="Bạn chưa nhập tên">
										</div>
									</div>
								</div>
								
							</div>

							<div class="row" style="margin-bottom: 15px;">
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-1" class="col-sm-6 col-xs-6 control-label">Mật khẩu</label>
										<div class="col-sm-8 input-group">
											<!-- <span class="input-group-addon"><i class="fa fa-key"></i></span> -->
											<input id="passwordG" type="password" name="passwordG" class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu" required data-parsley-error-message="Bạn chưa nhập mật khẩu">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-1" class="col-sm-12 col-xs-12 control-label">Nhập lại mật khẩu</label>
										<div class="col-sm-8 input-group">
											<!-- <span class="input-group-addon"><i class="fa fa-key"></i></span> -->
											<input id="repasswordG" type="password" name="repasswordG" class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu" required data-parsley-error-message="Bạn chưa nhập mật khẩu">
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="margin-bottom: 15px;">
								<div class="col-md-6">
									<div class="form-group">
										
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="field-4" class="col-sm-6 col-xs-6 control-label">Tín dụng</label>
										<div class="col-sm-8 col-xs-8 input-group">
											<!-- <span class="input-group-addon"><i class="fa fa-dollar"></i></span> -->
											<input disabled type="tel" name="credit" data-a-sign="" class="form-control autonumber" value="50,000" id="credit" placeholder="Tài khoản ban đầu" data-toggle="tooltip" data-placement="top" title="0">
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="text-align: center">
								<div class="col-lg-12 col-md-12 col-sm-12" id="buttonwithuserid">
									<div class="portfolioFilter">

									</div>
								</div>
							</div>


							<!-- <div class="row">
						<div class="form-group">
							<div class="col-md-6">
								<label for="field-5" class="control-label">Khóa tài khoản</label>
								<br/>
								<input type="checkbox" name="lock" id="lock" data-plugin="switchery" data-color="#f05050"/>
							</div>

						</div>
					</div> -->
						</div>
					</div>
					<div class="modal-footer" style="text-align: left; !important">
						<!-- <ins data-dismiss="modal">Đóng</ins> -->
						<!-- <button class="ladda-button  btn btn-info" data-style="slide-left" id="btn_Save">
							<span class="ladda-label">Lưu</span>
							<span class="ladda-spinner"></span>
						</button> -->

						<button type="button" id="btn_Save" class="btn btn-default btn-custom waves-effect waves-light">Đăng ký</button>
						<!-- <button type="button" id="btn_ClearData" class="btn btn-default btn-custom waves-effect waves-light">Nhập lại</button>				 -->
						<input type="hidden" id="sa-success">
					</div>
			</form>
		</div>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/assets/js/numtostring.js"></script>
<!-- <script src="/assets/admin/js/customertype.js?v=1.01"></script> -->
<script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>

<script type="text/javascript">
	function Save() {
		var flag = false;
		if (true === $('#usernameG').parsley().validate()) {
			flag = true;
			if (true === $('#passwordG').parsley().validate()) {
				flag = true;
			} else {
				flag = false;
			}
		} else {
			flag = false;
		}

		if (flag) {
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/users/storenewguest')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					username: $('#usernameG').val(),
					password: $('#passwordG').val(),
					fullname: $('#fullnameG').val(),
					_token: $_token,
				},
				success: function(data) {
					$("#btn_Save").html(`Thêm mới`);
					if (data != false) {
						// $('#btn_create_success').click();

						// $('.close').click();
						$('#create-modal-newguest').modal('toggle');
						Swal.fire({
						position: 'center',
						icon: 'success',
						title: 'Tạo tài khoản thành công',
						showConfirmButton: false,
						timer: 2500
						}).then((result) => {
							$('#portfolioModal1').modal('toggle');
						})
						
						// refreshTable();
						// location.reload();
						// showModalUserPercent(data);
					} else
						// $('#create-modal-newguest').modal('toggle');
						Swal.fire({
						position: 'center',
						icon: 'warning',
						title: 'Tạo tài khoản không thành công (Trùng tên tài khoản)',
						showConfirmButton: false,
						timer: 2500
						}).then((result) => {
							// $('#portfolioModal1').modal('toggle');
						})
						// $('#btn_checkuser').click();
					// 	$('#username').val("");
					// 	$('#password').val("");
					// 	$('#username').prop("readonly", true);
					// 	$('#password').prop("readonly", true);
					// 	$('#fullname').val("");
					// 	$('#credit').val("");
					// 	$('#lock').attr("checked",false);
					// 	$('#max_credit_show').html($('#max_credit').val());

					// setTimeout(function(){
					// 				//do what you need here
					// }, 2000);
					// alert('#userpercent'+(data-0));


					// $( '#userpercent'+data ).ready(function() {
					//    				$('#userpercent'+data).click();
					// 			});
					// $('#userpercentid').html().replace('userid',id).replace('userid',id).replace('userid',id);


					// var placementRight = 'right';
					// var placementLeft = 'left';

					// // Define the tour!
					// var tour = {
					//     id: "my-intro",
					//     steps: [
					//         {
					//             target: "userpercent18",
					//             title: "Logo Here",
					//             content: "You can find here status of user who's currently online.",
					//             placement: placementRight,
					//             yOffset: 10
					//         }
					//     ],
					//     showPrevButton: true
					// };

					// // Start the tour!
					// hopscotch.startTour(tour);



				},
				error: function(data) {
					// $('#max_credit_show').html($('#max_credit').val());
					console.log('Error:', data);
					// $("#btn_Save").html(`Thêm mới`);
				}
			});
		} else {
			// $('#max_credit_show').html($('#max_credit').val());
			// $("#btn_Save").html(`Thêm mới`);
		}
	}

	function ClearData() {
		$('#username').val("");
		$('#password').val("");
		// $('#username').prop("readonly", true);
		// $('#password').prop("readonly", true);
		$('#fullname').val("");
		$('#credit').val("");
	}
	$("#btn_Save").click(function() {
		// $(this).html(
		// 	`<i class="fa fa-spinner fa-spin"></i>Đang tạo...`
		// );
		Save();
	});

	$("#btn_ClearData").click(function() {
		ClearData();
	});
</script>