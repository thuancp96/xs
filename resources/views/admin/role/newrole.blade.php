<div id="create-role-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Thêm mới quyền</h4>
			</div>
			<form id="create-role-form" data-parsley-validate novalidate>
			<div class="modal-body">
				<div class="row">
					<div class="form-group">
						<div class="col-md-6">
							<label for="field-1" class="control-label">Tên quyền</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" id="rolename" name="rolename" class="form-control" readonly onfocus="this.removeAttribute('readonly');" placeholder="Hãy nhập tên quyền" required data-parsley-error-message="Bạn chưa nhập tên quyền" data-parsley-trigger="keyup">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12" >
						<div class="card-box" style="overflow: scroll; overflow-x: hidden!important;height: 300px" >
							<div class="col-md-6">
								<h4>Danh mục chức năng </h4>
							</div>
							@foreach ($chucnangs as $chucnang)
								<div class="row">
									<div class="col-md-12">
										<div class="checkbox checkbox-primary">
											<input id="{{$chucnang['code']}}" type="checkbox" onchange="ClickFunctions(this,'{{$chucnang['code']}}')">
											<label for="checkbox2">
												{{$chucnang['name']}}
											</label>
										</div>
									</div>
								</div>
								@foreach ($chucnang['children'] as $item)
									<div class="row">
										<div class="col-md-12" >
											<div class="form-group">
												<div style="padding-left: 20px">
													<div class="checkbox checkbox-primary">
														<input id="{{$item['code']}}" type="checkbox" onchange="ClickFunctions(this,'{{$item['code']}}')">
														<label for="checkbox2">
															{{$item['name']}}
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							@endforeach
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<ins data-dismiss="modal">Đóng</ins>
				<button type="button" id="btn_Save_Role" class="btn btn-info waves-effect waves-light">Lưu</button>
				<input type="hidden" id="sa-success">
			</div>
			</form>
		</div>
	</div>
</div>
<input type="hidden" id="hd_functions">
<a id="btn_checkrole" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Đã tồn tại tài khoản trên')"></a>
<a id="btn_create_role_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đăng kí thành công')"></a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	function ClickFunctions(cb,code) {
		var t = $('#hd_functions').val();
		if(cb.checked)
		{
			t +=","+code+",";
		}
		else
		{
			var key = ','+code+",";
			var re = new RegExp(key, 'g');
			t = t.replace(re,',');
		}
		$('#hd_functions').val(t);
	}
	function Save_New_Role() {
		var flag = false;
		if (true === $('#rolename').parsley().validate()) {
			flag = true;
		}
		else
		{
			flag = false;
		}

		if(flag)
		{
			var key = ',,';
			var re = new RegExp(key, 'g');
			t = $('#hd_functions').val().replace(re, ',');
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/role/store')}}",
				method: 'POST',
				dataType: 'html',
				data: {
					rolename: $('#rolename').val(),
					function: t,
					_token: $_token,
				},
				success: function(data)
				{
					console.log('Data:', data);
					$('#btn_create_role_success').click();
					$('.close').click();
					reloadrole();

				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		}
	}
	$("#btn_Save_Role" ).click(function() {
		Save_New_Role();
	});
</script>
