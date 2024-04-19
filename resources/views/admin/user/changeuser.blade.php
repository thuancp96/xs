<?php
$customertypesedit = UserHelpers::GetCustomertype();
$user = Auth::user();
?>
<style>
.modal-content
{
  height:600px;
  overflow:auto;
}
#role_editSelectBoxIt{
	width:110px !important;
}
</style>
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close btn-lg" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Thông tin 
				<span class="" id="usernameedit">{{$user->name}}</span>
				</h4>
			</div>
			<form id="edit-user-form" data-parsley-validate novalidate>
				<input type="hidden" id="user_edit_id">
				<div class="modal-body">
					<div class="row form_create">
						<div class="row">
							
								<!-- <div class="col-md-12 col-xs-12">
									<div class="form-group">
										<label for="field-1" class="control-label">Tên</label>
										<div class="input-group">
											<input type="text" name="fullname_edit" class="form-control" id="fullname_edit" placeholder="Hãy nhập tên" parsley-trigger="change" required data-parsley-error-message="Bạn chưa nhập tên" value="0">
										</div>
									</div>
								</div> -->
								<div class="col-md-12 col-xs-12">
									<label for="field-4" class="control-label">Quyền</label>
									<div class="col-sm-12 input-group">
										
										<select name="role_edit" id="role_edit" disabled>
											@foreach($roles as $role)
												<option value="{{$role->id}}">{{$role->name}}</option>
												
											@endforeach
										</select>
									</div>
								</div>
								@if($user->roleid == 5)
									<div class="col-md-4">
										<label for="field-4" class="control-label">Loại khách hàng</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-drupal"></i></span>
											<select class="form-control" name="customer_type" id="customer_type" disabled>
												@foreach($customertypesedit as $type)
													<option value="{{$type['code']}}">{{$type['name']}}</option>
												@endforeach
											</select>
										</div>
									</div>
								@else
									<div class="col-md-4 hidden">
										<label for="field-4" class="control-label">Loại khách hàng</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-drupal"></i></span>
											<select class="form-control" name="customer_type" id="customer_type">
												@foreach($customertypesedit as $type)
													<option value="{{$type['code']}}">{{$type['name']}}</option>
												@endforeach
											</select>
										</div>
									</div>
								@endif

								@if($user_current->roleid == 2 || true)
								<div class="col-md-12 col-xs-12" id="thau_edit_div">
									<div class="form-group">
										<label for="field-1" class="control-label">Thầu</label>
										<div class="input-group">
										<input type="number" name="thau_edit" class="form-control" id="thau_edit" placeholder="Hãy nhập % thầu" parsley-trigger="change" data-parsley-type="number" required data-parsley-error-message="Thầu từ 0%-100%" data-parsley-min="0" data-parsley-max="100">
										</div>
									</div>
								</div>
								@endif
							</div>

						<div class="row">
							<div class="form-group">
								<div class="col-md-12 col-xs-12">
									<label for="field-4" class="control-label">Tín dụng hạn mức</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" disabled name="credit_edit"  class="form-control autonumber" value="0" id="credit_edit"  placeholder="Nhập số tiền trong tài khoản">
										<input type="hidden" name="credit_edit_hidden"  class="form-control" value="0" id="credit_edit_hidden" placeholder="Nhập số tiền trong tài khoản" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0">
									</div>
								</div>
								<div class="col-md-12 col-xs-12 " id="tindungdadung">
									<label for="field-4" class="control-label">Tín dụng đã dùng</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" disabled name="consumer_edit" class="form-control autonumber" value="0" id="consumer_edit" placeholder="Nhập số tiền đã tiêu">
										<input type="hidden" name="consumer_edit_hidden" class="form-control" value="0" id="consumer_edit_hidden" placeholder="Nhập số tiền trong tài khoản" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
									</div>
								</div>
								<div class="col-md-12 col-xs-12">
									<label for="field-4" class="control-label">Tín dụng còn lại </label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
										<input type="text" name="remain_edit" disabled class="form-control autonumber" value="0" id="remain_edit" >
										<input type="hidden" name="remain_edit_hidden"  class="form-control autonumber" value="0" id="remain_edit_hidden" placeholder="Nhập số tiền trong tài khoản" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
									</div>
								</div>
							</div>
						</div>
						<br/>
						@if($user->roleid == 1 && $user_current->roleid == 1)
						<div class="row ">
							<div class="form-group">
						<div class="col-md-12 col-xs-12">
							<label for="field-6" class="control-label"> Thao tác giá mua</label>
							<div class="col-sm-5 col-md-5 col-xs-5 input-group">
								
								<select name="lock_edit3" id="lock_edit3">
									<option value=1>Ngừng lên giá</option>
									<option value=0>Giới hạn giá mua</option>
									<option value=2>Giá mua 789</option>
								</select>
							</div>
						</div>
						</div>
						</div>
						<br/>
						<!-- <label><input type="checkbox" name="lock_edit3" id="lock_edit3" data-plugin="switchery" data-color="#f05050" data-size="small"/> Ngừng lên giá</label> -->
						@endif
						<div class="row ">
							<div class="form-group">
								<div class="col-md-6">
									<label for="field-5" class="control-label">Trạng thái</label>
									<br/>
									<label><input type="checkbox" name="lock_edit1" id="lock_edit1" data-plugin="switchery" data-color="#f05050" data-size="small"/> Khóa tài khoản</label>
									<br/>
									<label><input type="checkbox" name="lock_edit2" id="lock_edit2" data-plugin="switchery" data-color="#f05050" data-size="small"/> Ngừng đặt</label>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align: left !important">
					<!-- <ins data-dismiss="modal">Đóng</ins> -->
					<button type="button" id="btn_SaveEdit" class="btn btn-info waves-effect waves-light">Lưu</button>
					<input type="hidden" id="sa-success">
				</div>
			</form>
		</div>
	</div>
</div>
<a id="btn_edit_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Cập nhật thành công')"></a>
<script type="text/javascript">

	// $(document).ready(function () {          
 //        // $.extend($.fn.autoNumeric.defaults, {              
 //        //     mDec:0
 //        // });      

 //        // $('selector').autoNumeric('init'); 

 //        // Calls the selectBoxIt method on your HTML select box
	//   	$("select").selectBoxIt({

	//     // Uses the jQuery 'fadeIn' effect when opening the drop down
	//     showEffect: "fadeIn",

	//     // Sets the jQuery 'fadeIn' effect speed to 400 milleseconds
	//     showEffectSpeed: 400,

	//     // Uses the jQuery 'fadeOut' effect when closing the drop down
	//     hideEffect: "fadeOut",

	//     // Sets the jQuery 'fadeOut' effect speed to 400 milleseconds
	//     hideEffectSpeed: 400

 //  });
 //    });

	$('#credit_edit').keyup(function(event) {
		var credit_edit = Number($('#credit_edit').val().replace(/[^0-9\.]+/g,""));
		var consumer_edit =  Number($('#consumer_edit').val().replace(/[^0-9\.]+/g,""));
		if($('#credit_edit').val()!=""  )
		{
			$('#credit_edit_hidden').val(credit_edit);
		}
		else
		{
			$('#credit_edit_hidden').val("");
		}
		if($('#consumer_edit').val()!="")
		{
			$('#consumer_edit_hidden').val(consumer_edit);
		}
		else
		{
			$('#consumer_edit_hidden').val("");
		}
		var remain = credit_edit-consumer_edit;
		$('#remain_edit_hidden').val(remain);
		$('#remain_edit').val(remain);
	});
	$('#consumer_edit').keyup(function(event) {
		var credit_edit = Number($('#credit_edit').val().replace(/[^0-9\.]+/g,""));
		var consumer_edit =  Number($('#consumer_edit').val().replace(/[^0-9\.]+/g,""));
		if($('#credit_edit').val()!=""  )
		{
			$('#credit_edit_hidden').val(credit_edit);
		}
		else
		{
			$('#credit_edit_hidden').val("");
		}
		if($('#consumer_edit').val()!="")
		{
			$('#consumer_edit_hidden').val(consumer_edit);
		}
		else
		{
			$('#consumer_edit_hidden').val("");
		}
		var remain = credit_edit-consumer_edit;
		$('#remain_edit_hidden').val(remain);
		$('#remain_edit').val(remain);
	});
	function showModal(id,name,fullname,lock,lock_price,credit,consumer,remain,roleid,customer_type,thau) {
		$('.autonumber').autoNumeric('init');

		$('#user_edit_id').val(id);

		$('#fullname_edit').val(fullname);
		var value = credit;
		$('#usernameedit').html(name);

		var num = Number(credit.replace(/[^0-9\.]+/g,""));
		$('#credit_edit_hidden').val(num);
		$('#credit_edit').val(credit);
		var num = Number(consumer.replace(/[^0-9\.]+/g,""));
		$('#consumer_edit_hidden').val(num);
		$('#consumer_edit').val(consumer);
		var num = Number(remain.replace(/[^0-9\.]+/g,""));
		$('#remain_edit_hidden').val(num);
		$('#remain_edit').val(remain);
		$('#role_edit').val(roleid).change();
		$('#customer_type').val(customer_type).change();
		$('#thau_edit').val(thau);
		if (roleid != 4)
			$('#thau_edit_div').css('display','none')
		else
			$('#thau_edit_div').css('display','block')

		if (roleid != 6)
			$('#tindungdadung').addClass('hidden')
		else
			$('#tindungdadung').addClass('hidden')

		if(lock==="0")
		{
			if($('#lock_edit1').is(":checked"))
				$('#lock_edit1').click();
			if($('#lock_edit2').is(":checked"))
				$('#lock_edit2').click();
		}
		if(lock==="1")
		{
			if(!$('#lock_edit2').is(":checked"))
				$('#lock_edit2').click();
		}
		if(lock==="2")
		{
			if(!$('#lock_edit1').is(":checked"))
				$('#lock_edit1').click();
		}
		if(lock==="3")
		{
			if(!$('#lock_edit1').is(":checked"))
				$('#lock_edit1').click();
			if(!$('#lock_edit2').is(":checked"))
				$('#lock_edit2').click();
		}
		$('#lock_edit3').val(lock_price).change();
		// if(lock_price==="1")
		// {
		// 	if(!$('#lock_edit3').is(":checked"))
		// 		$('#lock_edit3').click();
		// }else{
		// 	if($('#lock_edit3').is(":checked"))
		// 		$('#lock_edit3').click();
		// }
	}
	function SaveEdit() {
		var flag = false;
		if (true === $('#credit_edit_hidden').parsley().validate()) {
			flag = true;
			if (true === $('#consumer_edit_hidden').parsley().validate()) {
				flag = true;
				if (true === $('#remain_edit_hidden').parsley().validate()) {
					flag = true;
					if ($('#thau_edit').length > 0)
						if (true === $('#thau_edit').parsley().validate() ) {
							if ( $('#thau_edit').val() >=0 && $('#thau_edit').val() <=100)
								flag = true;
							else{
								flag = false;
								// alert("0% < Thầu < 100% ")
							}
						}else flag = false;
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
			var lock_status = 0;
			if ($('#lock_edit1').is(":checked") && $('#lock_edit2').is(":checked"))
			{
				lock_status = 3;
			}else
				if ($('#lock_edit1').is(":checked") && false == $('#lock_edit2').is(":checked"))
			{
				lock_status = 2;
			}else
				if (false == $('#lock_edit1').is(":checked") && $('#lock_edit2').is(":checked"))
			{
				lock_status = 1;
			}else
				lock_status = 0;

			var lock_price_status = 0;
			// if (false == $('#lock_edit3').is(":checked"))
			// {
			// 	lock_price_status = 0;
			// }else
			// 	lock_price_status = 1;
			lock_price_status = $('#lock_edit3').val();
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/users/update')}}"+"/"+$('#user_edit_id').val(),
				method: 'POST',
				dataType: 'json',
				data: {
                    type: "",
					fullname: $('#fullname_edit').val(),
					credit: $('#credit_edit').val(),
					consumer: $('#consumer_edit').val(),
					remain: $('#remain_edit').val(),
					customer_type: $('#customer_type').val(),
					role: $('#role_edit').val(),
					thau:$('#thau_edit').val(),
					lock: lock_status,
					lock_price: lock_price_status,
					_token: $_token,
				},
				success: function(data)
				{
					$('#btn_edit_success').click();
				// 	$('.close').click();
				// 	refreshTable();
					location.reload();
				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		}
	}
	$("#btn_SaveEdit" ).click(function() {
		SaveEdit();
	});
</script>
