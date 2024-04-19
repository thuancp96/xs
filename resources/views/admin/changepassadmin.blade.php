<style>
	.form-horizontal .form-group {
    margin-left: -10px !important;
    margin-right: -10px !important;
}
	</style>
<div id="changepass-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Thay đổi mật khẩu (Yêu cầu thay đổi hàng tháng)</h4>
			</div>
			<div id="change-pass-form" class="form-horizontal" data-parsley-validate novalidate>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Mật khẩu cũ</label>
						<div class="col-md-9">
							<input type="password" id="oldpass" name="oldpass" class="form-control"  placeholder="Hãy nhập mật khẩu cũ " required data-parsley-error-message="Bạn chưa nhập mật khẩu cũ">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Mật khẩu mới</label>
						<div class="col-md-9">
							<input id="newpass" type="password"  name="newpass"  class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu mới" required data-parsley-error-message="Nhập mật khẩu tối thiểu 6 ký tự" data-parsley-minlength="6">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Xác nhận lại mật khẩu</label>
						<div class="col-md-9">
							<input id="confirmpass" type="password"  name="confirmpass"  class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu mới" required data-parsley-error-message="Nhập mật khẩu tối thiểu 6 ký tự" data-parsley-minlength="6">
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align: left !important;">
					<!-- <ins data-dismiss="modal">Đóng</ins> -->
					<button type="button" id="btn_Save_Change" class="btn btn-info waves-effect waves-light">Thay đổi</button>
					<input type="hidden" id="sa-success">
					<input type="hidden" id="user_edit_id_cpw">
				</div>
</div>
		</div>
	</div>
</div>
<a id="btn_checknewpass" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Mật khẩu mới không trùng nhau hoặc không hợp lệ')"></a>
<a id="btn_checknewpass1" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Mật khẩu mới trùng Mật khẩu cũ')"></a>
<a id="btn_checkpass" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Mật khẩu cũ không đúng')"></a>
<a id="btn_changepass_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Thay đổi mật khẩu thành công')"></a>
<script type="text/javascript">
	// $('#oldpass').blur(function(event) {
	// 	if($('#oldpass').val().trim()!="")
	// 	{
	// 		$_token = "{{ csrf_token() }}";
	// 		$.ajax({
	// 			url: "{{url('/admin/check-pass')}}",
	// 			method: 'POST',
	// 			dataType: 'json',
	// 			data: {
	// 				oldpass: $('#oldpass').val(),
	// 				_token: $_token,
	// 			},
	// 			success: function(data)
	// 			{
	// 				if(data == true)
	// 				{

	// 				}
	// 				else
	// 				{
	// 					$('#btn_checkpass').click();
	// 					$('#oldpass').val('');
	// 				}
	// 				//console.log('OK:', data);
	// 			},
	// 			error: function (data) {
	// 				console.log('Error:', data);
	// 			}
	// 		});
	// 	}
	// });
	function checkvalidatepass() {
		if($('#confirmpass').val()!=$('#newpass').val())
		{
			$('#confirmpass').val('');
			$('#newpass').val('');
			$('#btn_checkpass').click();
		}
	}
	function ShowLoadChangePass(userid) {
		$('#oldpass').val("");
		$('#newpass').val("");
		$('#confirmpass').val("");
		$('#user_edit_id_cpw').val(userid);
		// $('#oldpass').attr('readonly', true);
		// $('#newpass').attr('readonly', true);
		// $('#confirmpass').attr('readonly', true);
	}
	// $('#confirmpass').blur(function(event) {
	// 	checkvalidatepass();
	// });
	function Save_Change_Pass() {

		if($('#confirmpass').val()!=$('#newpass').val())
		{
			$('#confirmpass').val('');
			$('#newpass').val('');
			$('#btn_checknewpass').click();
			return;
		}

		if($('#oldpass').val()==$('#newpass').val())
		{
			// $('#confirmpass').val('');
			// $('#newpass').val('');
			$('#btn_checknewpass1').click();
			return;
		}

		if($('#oldpass').val().trim()!="")
		{
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/admin/check-pass')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					oldpass: $('#oldpass').val(),
					_token: $_token,
				},
				success: function(data)
				{
					if(data == true)
					{
						var flag = false;
						if (true === $('#oldpass').parsley().validate()) {
							flag = true;
							if (true === $('#newpass').parsley().validate()) {
								flag = true;
								if (true === $('#confirmpass').parsley().validate()) {
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

						if(flag)
						{
							$_token = "{{ csrf_token() }}";
							$.ajax({
								url: "{{url('/admin/change-pass')}}",
								method: 'POST',
								dataType: 'json',
								data: {
									userid: $('#user_edit_id_cpw').val(),
									newpass: $('#newpass').val(),
									_token: $_token,
								},
								success: function(data)
								{
									$('#btn_changepass_success').click();
									$('.close').click();
								},
								error: function (data) {
									console.log('Error:', data);
								}
							});
						}
					}
					else
					{
						$('#btn_checkpass').click();
						$('#oldpass').val('');
						return;
					}
					//console.log('OK:', data);
				},
				error: function (data) {
					console.log('Error:', data);
					return;
				}
			});
		}

		
	}
	$("#btn_Save_Change" ).click(function() {
		Save_Change_Pass();
	});
</script>

<script type="text/javascript">
        jQuery(document).ready(function($)
		{
			<?php

use App\User;

				$user = Auth::user();
				if (Session::get('usersecondper') == 1){
					$user = User::where('id',Session::get('usersecondid'))->first();
				}
				if (isset($user)){
					$diff = abs(time() - strtotime($user->lastcpw));
					$years = floor($diff / (365*60*60*24));
					$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
					$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
					if ($user->google2fa_secret){
						$months = 0;
					}
				}else
					$months=0;
			?>
            @if ($months >= 10)
			var delayInMilliseconds = 1000*60; //1 second

			setTimeout(function() {
			//your code to be executed after 1 second
				var aitem = $('#changepassword');
				// $('#changepassword').click();
				document.getElementById('changepassword').click();
			}, delayInMilliseconds);
			@endif
    });

</script>