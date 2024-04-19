
<div id="changepassuser-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Thay đổi mật khẩu tài khoản <span class="badge badge-blue" id="usernamecpw"></span></h4>
			</div>
			<form id="change-pass-user-form" class="form-horizontal" data-parsley-validate novalidate>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Mật khẩu mới</label>
						<div class="col-md-9">
							<input id="newpassuser" type="password"  name="newpassuser" class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu mới" required data-parsley-error-message="Bạn chưa nhập mật khẩu mới">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Xác nhận lại mật khẩu</label>
						<div class="col-md-9">
							<input data-parsley-equalto="#newpassuser" id="confirmpassuser" type="password"  name="confirmpassuser" class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu mới" required data-parsley-error-message="Nhập lại trùng với mật khẩu mới">
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align: left !important;">
					<!-- <ins data-dismiss="modal">Đóng</ins> -->
					<button type="button" id="btn_Save_Change_User" class="btn btn-info waves-effect waves-light">Lưu</button>
					<input type="hidden" id="sa-success">
					<input type="hidden" id="user_edit_id_cpw">
				</div>
			</form>
		</div>
	</div>
</div>
<a id="btn_checkpassuser" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Mật khẩu không đúng')"></a>
<a id="btn_changepass_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Thay đổi mật khẩu thành công')"></a>
<script type="text/javascript">
	
	function checkvalidatepassuser() {
		if($('#confirmpassuser').val()!=$('#newpassuser').val())
		{
			$('#confirmpassuser').val('');
			$('#btn_checkpassuser').click();
		}
	}
	function ShowLoadChangePassUser(userid,name) {

		// $('#oldpass').val("");
		$('#newpassuser').val("");
		$('#confirmpassuser').val("");
		$('#user_edit_id_cpw').val(userid);
		$('#usernamecpw').html(name);
		// $('#oldpass').attr('readonly', true);
		// $('#newpassuser').attr('readonly', true);
		// $('#confirmpassuser').attr('readonly', true);
	}
	// $('#confirmpassuser').blur(function(event) {
	// 	checkvalidatepassuser();
	// });
	function Save_Change_Pass_User() {
		var flag = false;
		// if (true === $('#oldpass').parsley().validate()) {
			// flag = true;
			if (true === $('#newpassuser').parsley().validate()) {
				flag = true;
				if (true === $('#confirmpassuser').parsley().validate()) {
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
		// }
		// else
		// {
		// 	flag = false;
		// }

		if(flag)
		{
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/admin/change-pass')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					userid: $('#user_edit_id_cpw').val(),
					newpass: $('#newpassuser').val(),
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
	$("#btn_Save_Change_User" ).click(function() {
		Save_Change_Pass_User();
	});
</script>