@extends('admin.admin-template')
@section('title', 'Quản lí người dùng')
@section('content')
	<!-- @include('admin.user.newuser',['roles' => $roles]) -->
	<!-- @include('admin.user.changeuser',['roles' => $roles]) -->
	<!-- @include('admin.user.changecredit') -->
<?php
$customertypes = UserHelpers::GetCustomertype();
$user = Auth::user();
?>

<style>
	.new_user .col-sm-4 {
		padding: 0px;
		margin-top: 5px;
	}

	.new_user .col-md-3{
		width: 300px !important;
	}
</style>
			<form id="create-user-form" data-parsley-validate novalidate>
			<div class="box">
				<div class="box-body table-responsive no-padding">
					<div class="form_group new_user" style="margin: 10px;">

						<div class="row">
							
								<div class="col-md-3">
									<div class="form-group">
										<label for="field-4" class="col-sm-4 control-label">Loại Tài </label>
										<div class="col-sm-8 input-group">
											<!-- <span class="input-group-addon"><i class="fa fa-group"></i></span> -->
											
											<select class="form-control dropdown-toggle waves-effect waves-light" name="role" id="role">
												@foreach($roles as $role)
													@if($user->roleid == 1)
														@if($role->id == 2)
															<option value="{{$role->id}}">{{$role->name}}</option>
														@endif
													@endif
													@if($user->roleid == 2)
														@if($role->id == 4)
															<option value="{{$role->id}}">{{$role->name}}</option>
														@endif
													@endif
													@if($user->roleid == 4)
														@if($role->id == 5)
															<option value="{{$role->id}}">{{$role->name}}</option>
														@endif
													@endif
													@if($user->roleid == 5)
														@if($role->id == 6)
															<option value="{{$role->id}}">{{$role->name}}</option>
														@endif
													@endif
												@endforeach
											</select>
											
										</div>
									</div>
								</div>
								@if($user->roleid == 5)
									<div class="col-md-3">
										<div class="form-group">
											<label for="field-4" class="col-sm-4 control-label">Loại khách hàng</label>
											<div class="col-sm-8 input-group">
												<!-- <span class="input-group-addon"><i class="fa fa-drupal"></i></span> -->
												<div class="btn btn-default dropdown-toggle waves-effect waves-light">
												<select class="form-control" name="custype" id="custype">
													@foreach($customertypes as $type)
														<option value="{{$type['code']}}">{{$type['name']}}</option>
													@endforeach
												</select>
												</div>
											</div>
										</div>
									</div>
								@else
									<div class="col-md-6 hidden">
										<label for="field-4" class="control-label">Loại khách hàng</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-drupal"></i></span>
											<select class="form-control" name="custype" id="custype">
												@foreach($customertypes as $type)
													<option value="{{$type['code']}}">{{$type['name']}}</option>
												@endforeach
											</select>
										</div>
									</div>
								@endif
							
						</div>

						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="field-1" class="col-sm-4 control-label">Tài khoản</label>
									<div class="col-sm-8 input-group">
										<!-- <span class="input-group-addon"><i class="fa fa-user"></i></span> -->
										<input type="text" id="username" name="username" class="form-control" readonly onfocus="this.removeAttribute('readonly');" placeholder="Hãy nhập tài khoản" required data-parsley-error-message="Bạn chưa nhập tài khoản" data-parsley-trigger="keyup">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="field-1" class="col-sm-4 control-label">Mật khẩu</label>
									<div class="col-sm-8 input-group">
										<!-- <span class="input-group-addon"><i class="fa fa-key"></i></span> -->
										<input id="password" type="password"  name="password" readonly onfocus="this.removeAttribute('readonly');" class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu" required data-parsley-error-message="Bạn chưa nhập mật khẩu">
									</div>
								</div>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="field-1" class="col-sm-4 control-label">Họ và Tên</label>
									<div class="col-sm-8 input-group">
										<!-- <span class="input-group-addon"><i class="fa fa-font"></i></span> -->
										<input type="text" name="fullname" class="form-control" id="fullname" placeholder="Hãy nhập tên" parsley-trigger="change" required data-parsley-error-message="Bạn chưa nhập tên">
									</div>
								</div>
							</div>
								
						</div>

						<div class="row">

							<div class="col-md-3">
								<div class="form-group">
									<label for="field-4" class="col-sm-4 control-label">Tín dụng</label>
									<div class="col-sm-8 input-group">
										<!-- <span class="input-group-addon"><i class="fa fa-dollar"></i></span> -->
										<input type="text" name="credit" data-a-sign="" class="form-control autonumber" value="0" id="credit" placeholder="Nhập số tiền trong tài khoản">
										<input type="hidden" name="credit" data-a-sign="" class="form-control" value="0" id="credit_hidden" placeholder="Nhập số tiền trong tài khoản" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
									</div>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
								<label for="field-5" class="col-sm-4 control-label" style="width: 250px !important;">Còn lại: <i id="max_credit_show">{{$user->remain}}</i></label>
								</div>
							</div>
						</div>

						{{--<div class="row">
																	<div class="form-group">
																		<div class="col-md-3">
																			<label for="field-5" class="control-label">Khóa tài khoản</label>
																			<br/>
																			<input type="checkbox" name="lock" id="lock" data-plugin="switchery" data-color="#f05050"/>
																		</div>
											
																	</div>
																</div>--}}
					

					<button type="button" id="btn_Save" class="btn btn-info waves-effect waves-light">Lưu</button>
					<input type="hidden" id="sa-success">
					<input type="hidden" id="max_credit" value="{{$user->remain}}">
					</div>
			</div>
			
				
				
			
			</form>
		

<a id="btn_checkuser" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Đã tồn tại tài khoản trên')"></a>
<a id="btn_create_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đăng kí thành công')"></a>
<a id="btn_credit" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Số tiền đăng ký lớn hơn giới hạn tài khoản')"></a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">

	$('#credit').keyup(function(event) {
		var credit = Number($('#credit').val().replace(/[^0-9\.]+/g,""));
		if($('#credit').val()!=""  )
		{
			$('#credit_hidden').val(credit);
		}
		else
		{
			$('#credit_hidden').val("");
		}
	});
	function Save() {
		var flag = false;
		if (true === $('#username').parsley().validate()) {
			flag = true;
			if (true === $('#password').parsley().validate()) {
				flag = true;
				if (true === $('#credit_hidden').parsley().validate()) {
					flag = true;
				}
				else
				{
					flag = false;
				}
			}
			else
			{
				flag = false;
			}
		}
		else
		{
			flag = false;
		}
		@if($user->roleid!=1)
		var t=  Number($('#max_credit').val().replace(/[^0-9\.]+/g,""));
		var m = Number($('#credit_hidden').val().replace(/[^0-9\.]+/g,""));
		if(t < m)
		{
			$('#btn_credit').click();
			flag = false;
		}
		@endif
		if(flag)
		{
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/users/store')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					username: $('#username').val(),
					password: $('#password').val(),
					fullname: $('#fullname').val(),
					credit: $('#credit_hidden').val(),
					role: $('#role').val(),
					customer_type: $('#custype').val(),
					lock: $('#lock').is(":checked"),
					_token: $_token,
				},
				success: function(data)
				{
					$('#btn_create_success').click();
					$('#username').val("");
					$('#password').val("");
					$('#username').prop("readonly", true);
					$('#password').prop("readonly", true);
					$('#fullname').val("");
					$('#credit').val("0");
					$('#lock').attr("checked",false);
					$('.close').click();
					@if($user->roleid!=1)
						var t = Number($('#max_credit').val().replace(/[^0-9\.]+/g,""));
						var k = Number($('#credit_hidden').val().replace(/[^0-9\.]+/g,""));
						$('#max_credit').val(t-k);
						$('#max_credit_show').html(t-k);
					@endif
					refreshTable();
				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		}
	}
	$("#btn_Save" ).click(function() {
		Save();
	});
</script>
@endsection

@section('js_call')
	<!-- notifications  -->
	<script src="/assets/admin/plugins/notifyjs/dist/notify.min.js"></script>
	<script src="/assets/admin/plugins/notifications/notify-metro.js"></script>

	<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
	<script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>	
	<script src="/assets/admin/js/user.js"></script>
@endsection