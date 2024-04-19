<div id="changecontrol-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Chỉnh sửa nhanh</h4>
			</div>
			<form id="change-control-form" data-parsley-validate novalidate>
				<input type="hidden" id="user_edit_id">
				<div class="modal-body">
					<div class="row form_create">
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<label for="field-4" class="control-label">Game</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
										<select class="form-control" name="game_edit" id="game_edit">
											@foreach($gameList as $game)
												<option value="{{$game['game_code']}}">{{$game['name']}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<label for="field-4" class="control-label">Số</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" name="number_edit"  class="form-control autonumber" value="0" id="number_edit">
										<input type="hidden" name="number_edit_hidden" data-a-sign="" class="form-control" value="0" id="number_edit_hidden" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<label for="field-4" class="control-label">Giá mua</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" name="exchange_edit"  class="form-control autonumber" value="0" id="exchange_edit">
										<input type="hidden" name="exchange_edit_hidden" data-a-sign="" class="form-control" value="0" id="exchange_edit_hidden" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
									</div>
								</div>
								<div class="col-md-4">
									<label for="field-4" class="control-label">A</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" name="a_edit" class="form-control autonumber" value="0" id="a_edit" >
										<input type="hidden" name="a_edit_hidden" data-a-sign="" class="form-control" value="0" id="a_edit_hidden"  data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
									</div>
								</div>
								<div class="col-md-4">
									<label for="field-4" class="control-label">X</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" name="x_edit" class="form-control autonumber" value="0" id="x_edit" >
										<input type="hidden" name="x_edit_hidden" data-a-sign="" class="form-control" value="0" id="x_edit_hidden" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<ins data-dismiss="modal">Đóng</ins>
					<button type="button" id="btn_SaveEdit" class="btn btn-info waves-effect waves-light">Lưu</button>
					<input type="hidden" id="sa-success">
				</div>
			</form>
		</div>
	</div>
</div>
<a id="btn_edit_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Cập nhật thành công')"></a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$('#number_edit').blur(function() {
		GetByNumber();
	});
	$('#number_edit').keyup(function() {
		GetByNumber();
	});
	$('#number_edit').keyup(function(event) {
		if($('#number_edit').val().length > 2 &&  $('#game_edit').val()!="8")
		{
			$('#number_edit').val('');
			alert('Chỉ được nhập 2 số');
		}
		if($('#number_edit').val().length > 3 &&  $('#game_edit').val()=="8")
		{
			$('#number_edit').val('');
			alert('Chỉ được nhập 3 số');
		}
		var credit = Number($('#number_edit').val().replace(/[^0-9\.]+/g,""));
		if($('#number_edit').val()!=""  )
		{
			$('#number_edit_hidden').val(credit);
		}
		else
		{
			$('#number_edit_hidden').val("");
		}
	});
	$('#exchange_edit').keyup(function(event) {
		var credit = $('#exchange_edit').val();
		if($('#exchange_edit').val()!=""  )
		{
			$('#exchange_edit_hidden').val(credit);
		}
		else
		{
			$('#exchange_edit_hidden').val("");
		}
	});
	$('#a_edit').keyup(function(event) {
		var credit = $('#a_edit').val();
		if($('#a_edit').val()!=""  )
		{
			$('#a_edit_hidden').val(credit);
		}
		else
		{
			$('#a_edit_hidden').val("");
		}
	});
	$('#x_edit').keyup(function(event) {
		var credit = $('#x_edit').val();
		if($('#x_edit').val()!=""  )
		{
			$('#x_edit_hidden').val(credit);
		}
		else
		{
			$('#x_edit_hidden').val("");
		}
	});
	function GetByNumber() {
		var numb = $('#number_edit').val();
		var game_code = $('#game_edit').val();
		$_token = "{{ csrf_token() }}";

		$.ajax({
			url: "{{url('/control')}}"+"/search-number",
			method: 'POST',
			dataType: 'json',
			data: {
				number:numb,
				game_code:game_code,
				_token: $_token,
			},
			success: function(data)
			{
				$("#a_edit").val(data['a']);
				$("#x_edit").val(data['x']);
				$("#exchange_edit").val(data['exchange_rates']);
				console.log('OK:', data);
			},
			error: function (data) {
				console.log('Error:', data);
			}
		});
	}
	function SaveChangeNumber() {
		var flag = false;
		if (true === $('#exchange_edit_hidden').parsley().validate()) {
			flag = true;
			if (true === $('#a_edit_hidden').parsley().validate()) {
				flag = true;
				if (true === $('#x_edit_hidden').parsley().validate()) {
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
			var numb = $('#number_edit').val();
			var game_code = $('#game_edit').val();
			$_token = $('#token').val();
			$.ajax({
				url: $('#url').val()+"/update",
				method: 'POST',
				dataType: 'json',
				data: {
					number: numb,
					game_code:game_code,
					exchange_rates:$('#exchange_edit').val(),
					a:$('#a_edit').val(),
					x:$('#x_edit').val(),
					type:'All',
					_token: $_token,
				},
				success: function(data)
				{
					$('.nav li a[href="#' + game_code + '"]').tab('show');
					LoadContentNumber(game_code);
				// 	$('.close').click();
				},
				error: function (data) {
				}
			});
		}
	}
	$("#btn_SaveEdit" ).click(function() {
		SaveChangeNumber();
	});
</script>
