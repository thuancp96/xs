<?php
$user = Auth::user();
?>
<div id="change_credit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Tài khoản 
				<span class="badge badge-blue" id="usernamepush">{{$user->name}}</span>
				Tín dụng hạn mức</h4>

			</div>
			<form id="edit-user-form" data-parsley-validate novalidate>
				<input type="hidden" id="user_edit_id">
				<div class="modal-body">
					<div class="row form_create">
						<div class="row">
							<div class="form-group">
								<div class="col-md-6">
									<label for="field-4" class="control-label">Tín dụng <span class="badge badge-blue" id="usernamepush1">{{$user->name}}</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="tel" id="credit_data" name="credit_data" class="form-control autonumber">
										<input type="hidden" name="credit_data"  class="form-control" value="0" id="credit_data_hidden" placeholder="Nhập số tiền trong tài khoản" >
										<input type="hidden" name="remain_data"  class="form-control" value="0" id="remain_data_hidden">
									</div>
								</div>
								<div class="col-md-6 @if(isset($search) && $search != "") hidden @endif">
									<label for="field-4" class="control-label">Tín dụng <span class="badge badge-blue" id="usernamepush2">{{$user_current->name}}</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" disabled name="credit_pg"  class="form-control autonumber" value="{{$user_current->remain}}" id="credit_pg" placeholder="Nhập số tiền cần rút/nạp" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
										<input type="hidden" name="credit_pg"  class="form-control" value="0" id="credit_pg_hidden" placeholder="Nhập số tiền trong tài khoản" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0">
									</div>
									<!-- <label for="field-4" class="control-label">Giới hạn nạp: <i class="autonumber" id="max_push_show">{{$user->remain}}</i></label> -->
									
									<!-- <label type="hidden" for="field-4" class="control-label">Giới hạn nạp: <div id="max_push_show" class="autonumber">{{$user->remain}}</div></label> -->

									<input type="hidden" id="max_push" value="{{$user_current->remain}}">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<!-- <ins data-dismiss="modal">Đóng</ins> -->
					<!-- <button type="button" id="btn_SaveGet" class="btn btn-info waves-effect waves-light">Rút tiền</button>
					<button type="button" id="btn_SavePut" class="btn btn-info waves-effect waves-light">Nạp tiền</button> -->

					<button type="button" id="btn_SaveCredit" class="btn btn-info waves-effect waves-light">Thanh toán</button>
				</div>
			</form>
		</div>
	</div>
</div>
<a id="btn_checkcreditpg" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Số tiền rút ra quá lớn')"></a>
<script type="text/javascript">

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

	function showModalCredit_PG(id,name,remain,remain2=0) {
		$('.autonumber').autoNumeric('init');
		var num = Number(remain.replace(/[^0-9\.]+/g,""));
		$('#usernamepush').html(name);
		$('#usernamepush1').html(name);
		$('#credit_data').val(remain);
		$('#credit_data_hidden').val(remain);
		$('#remain_data_hidden').val(remain2);
		$('#user_edit_id').val(id);
		// alert($('#max_push').val());
		$('#credit_pg').val(addCommas(Number($('#max_push').val().replace(/[^0-9\.]+/g,""))));
	}

	$('#credit_data').keyup(function(event) {
		var creditTaget = Number($('#credit_data').val().replace(/[^0-9\.]+/g,""));
		var creditDestination = Number($('#credit_pg').val().replace(/[^0-9\.]+/g,""));
		var creditTagetOrg = Number($('#credit_data_hidden').val().replace(/[^0-9\.]+/g,""));
		if ($('#credit_pg').val()[0] == '-')
			 creditDestination = 0-creditDestination;
		creditDestination = creditDestination - (creditTaget - creditTagetOrg);

		$('#credit_pg').val(addCommas(creditDestination));
		$('#credit_data_hidden').val(addCommas(creditTaget));

		$('#credit_data').tooltip('show')
          .attr('data-original-title', docso(creditTaget)+ ' chip')
          // .tooltip('fixTitle')
          .tooltip('show');

		
	});

	$('#credit_pg').keyup(function(event) {
		var credit = Number($('#credit_pg').val().replace(/[^0-9\.]+/g,""));

		if($('#credit_pg').val()!=""  )
		{
			$('#credit_pg_hidden').val(credit);
		}
		else
		{
			$('#credit_pg_hidden').val("");
		}

		var title = $('#credit_pg').attr("title");
		var t = Number($('#max_credit').val().replace(/[^0-9\.]+/g,""));
						var k = Number($('#credit_pg_hidden').val().replace(/[^0-9\.]+/g,""));
		var remainmoney = t-k;
		$('#max_push_show').html(addCommas(remainmoney));
		// $('#credit').attr("data-original-title",credit);
		// $('#credit').tooltip({title: credit});

		// if(remainmoney < 0)
		// $('#credit_show_div').addClass("negative_number");
		// else
		// $('#credit_show_div').removeClass("negative_number");
		$('#credit_pg').tooltip('show')
          .attr('data-original-title', docso(credit*1000)+ ' chip')
          // .tooltip('fixTitle')
          .tooltip('show');

	});
	// });
	function SaveCredit() {

		var creditTaget = Number($('#credit_data').val().replace(/[^0-9\.]+/g,""));
		var creditDestination = Number($('#credit_pg').val().replace(/[^0-9\.]+/g,""));
		var creditTagetOrg = Number($('#credit_data_hidden').val().replace(/[^0-9\.]+/g,""));
		if ($('#credit_pg').val()[0] == '-')
			 creditDestination = 0-creditDestination;
		var flag = true;
		if (creditDestination < 0)
		{
			flag = false;
			$.Notification.notify('error','right top', 'Thông báo', 'Tài khoản không đủ tiền.');
		}
		if(flag)
		{
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/users/update')}}"+"/"+$('#user_edit_id').val(),
				method: 'POST',
				dataType: 'json',
				data: {
					type: 'credit',
					credit: creditTaget,
					_token: $_token,
				},
				success: function(data)
				{
					if (data == false){
						$.Notification.notify('error','right top', 'Thông báo', 'Số dư âm, Không hợp lệ. Bạn được rút tối đa là ' + $('#remain_data_hidden').val());
						return;
					}
					$('#btn_edit_success').click();
					$('.close').click();
					$('#max_push').val(addCommas(creditDestination));
					$('#max_credit').val(addCommas(creditDestination));
						$('#max_credit_show').html(addCommas(creditDestination));
						
					// @if($user->roleid!=1)
					// if(type==='put') {
					// 	var t = Number($('#max_push').val().replace(/[^0-9\.]+/g, ""));
					// 	var m = Number($('#credit_pg_hidden').val().replace(/[^0-9\.]+/g, ""));
					// 	$('#max_push').val(t - m);
					// 	$('#max_push_show').val(t - m);
					// }

					// @endif
					// location = "{{url('/users')}}";
					// location.reload();
					window.location.href = "{{url('/users')}}";
			// 		$('div.table-rep-plugin').fadeOut();
			// $('div.table-rep-plugin').load("{{url('/users/refresh-data/'.$user_current->id)}}", function() {
			// 	$('div.table-rep-plugin').fadeIn();
			// });
				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		}
	}

	function SaveGetPut(type) {
		var flag = false;

		if (true === $('#credit_pg_hidden').parsley().validate()) {
			flag = true;
		}
		else
		{
			flag = false;
		}

		if(type=='get')
		{
			var credit= $('#credit_pg').val();
			var credit_data = $('#credit_data').val();
			var num1 = Number(credit.replace(/[^0-9\.]+/g,""));
			var num2 = Number(credit_data.replace(/[^0-9\.]+/g,""));
			if(num2 <= 0 || num1>num2)
			{
				flag = false;
				$('#btn_checkcreditpg').click();
				return;
			}
		}
		if (true === $('#credit_pg_hidden').parsley().validate()) {
			flag = true;
		}
		@if($user->roleid!=1)
		if(type==='put') {
			var t=  Number($('#max_push').val().replace(/[^0-9\.]+/g,""));
			var m = Number($('#credit_pg_hidden').val().replace(/[^0-9\.]+/g,""));
			if(t < m)
			{
				flag = false;
				$('#btn_credit').click();
			}
		}
		@endif
		if(flag)
		{
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/users/update')}}"+"/"+$('#user_edit_id').val(),
				method: 'POST',
				dataType: 'json',
				data: {
					type: type,
					credit: $('#credit_pg_hidden').val(),
					_token: $_token,
				},
				success: function(data)
				{
					$('#btn_edit_success').click();
					$('.close').click();
					@if($user->roleid!=1)
					if(type==='put') {
						var t = Number($('#max_push').val().replace(/[^0-9\.]+/g, ""));
						var m = Number($('#credit_pg_hidden').val().replace(/[^0-9\.]+/g, ""));
						$('#max_push').val(t - m);
						$('#max_push_show').val(t - m);
					}

					@endif
					// refreshTable();
					// windows.reload("{{url('/users')}}");
					window.location.reload("{{url('/users')}}");
				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		}
	}
	$("#btn_SaveGet" ).click(function() {
		SaveGetPut('get');
	});
	$("#btn_SavePut" ).click(function() {
		SaveGetPut('put');
	});

	$("#btn_SaveCredit" ).click(function() {
		SaveCredit();
	});
</script>
