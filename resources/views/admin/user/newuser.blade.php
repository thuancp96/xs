<?php

use App\Helpers\UserHelpers;

$customertypes = (new UserHelpers())->GetCustomertype();
$user = Auth::user();
?>
<style>
	.new_user .col-sm-3 {
		padding: 0px;
		margin-top: 5px;
		margin-left: 10px;
	}

</style>

<style>
.modal-content
{
  height:auto !important;
  overflow:auto;
}
</style>

<div id="create-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Thêm mới người dùng</h4>
			</div>
			<form id="create-user-form" data-parsley-validate novalidate>
			<div class="modal-body new_user">
				<div class="row form_create">
					<div class="row">	
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-4" class="col-sm-3 col-xs-3 control-label">Loại Tài Khoản </label>
								<div class="col-sm-8 col-xs-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-group"></i></span> -->
									<select name="role" id="role" @if($user_current->roleid == 4) onchange="OnchangeRole(this)" @endif>
										@foreach($roles as $role)
											@if($user_current->roleid == 1)
												@if($role->id == 2)
													<option value="{{$role->id}}">{{$role->name}}</option>
												@endif
											@endif
											@if($user_current->roleid == 2)
												@if($role->id == 4)
													<option value="{{$role->id}}">{{$role->name}}</option>
												@endif
											@endif
											@if($user_current->roleid == 4)
												@if($role->id == 5)
													<option value="{{$role->id}}">{{$role->name}}</option>
												@endif
											@endif
											@if($user_current->roleid == 5)
												@if($role->id == 6)
													<option value="{{$role->id}}">{{$role->name}}</option>
												@endif
											@endif
											
										@endforeach
									</select>
									
								</div>
							</div>
						</div>
						<script>
							function OnchangeRole(e){
								// alert(e.value)
								if (e.value==6) $("#chooseCustomerCategory").removeClass("hidden")
								else $("#chooseCustomerCategory").addClass("hidden")
							}
							</script>
						@if($user_current->roleid == 4)
							<div class="col-md-6 hidden" id="chooseCustomerCategory">
								<div class="form-group">
									<label for="field-4" class="col-sm-3 col-xs-3 control-label">Loại khách hàng</label>
									<div class="col-sm-8 col-xs-8 input-group">
										<!-- <span class="input-group-addon"><i class="fa fa-drupal"></i></span> -->
										<select name="custype" id="custype">
											@foreach($customertypes as $type)
												<option value="{{$type['code']}}">{{$type['name']}}</option>
											@endforeach
										</select>
								</div>
								</div>	
							</div>
						@endif
						@if($user_current->roleid == 5)
							<div class="col-md-6">
								<div class="form-group">
									<label for="field-4" class="col-sm-3 col-xs-3 control-label">Loại khách hàng</label>
									<div class="col-sm-8 col-xs-8 input-group">
										<!-- <span class="input-group-addon"><i class="fa fa-drupal"></i></span> -->
										<select name="custype" id="custype">
											@foreach($customertypes as $type)
												<option value="{{$type['code']}}">{{$type['name']}}</option>
											@endforeach
										</select>
								</div>
								</div>	
							</div>
						@else
							<!-- <div class="col-md-6">
								<div class="form-group">
									<label for="field-4" class="col-sm-3 control-label">Loại khách hàng</label>
									<div class="col-sm-8 input-group">
										<span class="input-group-addon"><i class="fa fa-drupal"></i></span>
										<select name="custype" id="custype">
											@foreach($customertypes as $type)
												<option value="{{$type['code']}}">{{$type['name']}}</option>
											@endforeach
										</select>
								</div>
								</div>	
							</div> -->
						@endif	
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-1" class="col-sm-3 col-xs-3 control-label">Tài khoản</label>
								<div class="col-sm-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-user"></i></span> -->
									<input type="text" id="username" name="username" class="form-control" placeholder="Hãy nhập tài khoản" required data-parsley-error-message="Nhập tài khoản độ dài từ 6-15 ký tự" data-parsley-trigger="keyup" minlength="6" data-parsley-minlength="6" maxlength="15" data-parsley-maxlength="15">
								</div>
							</div>
						</div>
						@if($user_current->roleid == 6)
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-1" class="col-sm-3 col-xs-3 control-label">Mật khẩu</label>
								<div class="col-sm-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-key"></i></span> -->
									<input id="password" type="password"  name="password" class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu ít nhất 6 ký tự" required data-parsley-error-message="Hãy nhập mật khẩu ít nhất 6 ký tự" minlength="6" data-parsley-minlength="6">
								</div>
							</div>
						</div>
						@endif
					</div>
					
					
					<div class="row">
					@if($user_current->roleid == 6)
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-1" class="col-sm-3 col-xs-3 control-label">Họ và Tên</label>
								<div class="col-sm-8 col-xs-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-font"></i></span> -->
									<input type="text" name="fullname" class="form-control" id="fullname" placeholder="Hãy nhập tên" parsley-trigger="change" required data-parsley-error-message="Bạn chưa nhập tên">
								</div>
							</div>
						</div>
							@endif
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-1" class="col-sm-3 col-xs-3 control-label">Copy thông số</label>
								<div class="col-sm-8 col-xs-8 input-group">
									<?php
										$members = UserHelpers::GetAllUserAvailable($user_current);
									?>
										<select name="member" id="member">
										<option value="non"></option>
											@foreach($members as $member)
												<option value="{{$member->id}}">{{$member->name}} @if ($member->roleid == 6)Chuẩn {{$member->customer_type}} @endif</option>
											@endforeach
										</select>
								</div>
							</div>
						</div>

							
					</div>

					@if($user->roleid==2)
					<div class="row">
					<div class="col-md-6">
									<div class="form-group">
									<label for="field-1" class="col-sm-3 col-xs-3 control-label">Thầu</label>
									<div class="col-sm-8 col-xs-8 input-group">
											<input type="number" name="thau_new" class="form-control" id="thau_new" placeholder="Hãy nhập % thầu" parsley-trigger="change" data-parsley-type="number" required data-parsley-error-message="Thầu từ 0%-100%" data-parsley-min="0" data-parsley-max="100">
										</div>
									</div>
								</div>
								</div>
								@endif

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-4" class="col-sm-3 col-xs-3 control-label">Tín dụng</label>
								<div class="col-sm-8 col-xs-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-dollar"></i></span> -->
									<input type="tel" name="credit" data-a-sign="" class="form-control autonumber" value="" id="credit" placeholder="Tài khoản ban đầu" data-toggle="tooltip" data-placement="top" title="0">

									<input type="hidden" name="credit" data-a-sign="" class="form-control" value="0" id="credit_hidden" placeholder="Tài khoản" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label style='margin-left:10px;' ><input type="checkbox" name="rollback_money" id="rollback_money" data-plugin="switchery" data-color="#f05050" data-size="small" checked/> Hồi tiền hàng ngày</label>
							</div>
						</div>

						
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group textmoney" id="credit_show_div">
							<div id="max_credit_show" class="autonumber">{{$user_current->remain}}</div>
							<i class="fa fa-spin fa-refresh"></i>
							<!-- <label for="field-5" class="col-sm-3 control-label" style="width: 250px !important;">Còn lại: <i id="max_credit_show">{{$user_current->remain}}</i></label> -->
							</div>
						</div>
					</div>

					<div class="row" style="text-align: center">
                    <div class="col-lg-12 col-md-12 col-sm-12" id="buttonwithuserid">
                        <div class="portfolioFilter">
                            <!-- @foreach($customertypes as $type)
                                <a onclick="LoadContentGame('{{$type['code']}}','userid')" data-filter=".{{$type['code']}}">{{$type['name']}}</a>
                            @endforeach -->
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-lg-12" style="text-align: center !important;">
                        <span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
                    </div>
                </div>
                <div class="row port" >
                    <div class="portfolioContainer m-b-15">
                        <!-- @foreach($customertypes as $type)
                            <div class="col-sm-12 col-lg-12 col-md-12 {{$type['code']}} type_content" id="{{$type['code']}}">
                            </div>
                        @endforeach -->
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

				<button type="button" id="btn_Save" class="btn btn-default btn-custom waves-effect waves-light">Thêm mới</button>
				<button type="button" id="btn_ClearData" class="btn btn-default btn-custom waves-effect waves-light">Nhập lại</button>				
				<input type="hidden" id="sa-success">
				<input type="hidden" id="max_credit" value="{{$user_current->remain}}">
				<input type="hidden" id="user_current" value="{{$user_current->id}}">
			</div>
			</form>
		</div>
	</div>
</div>

<a href="#" data-toggle="modal" data-target="#full-width-modal" onclick="showModalUserPercent('{{$user_current->id}}','{{$user_current->fullname}}')" id="userpercentid"></a>

<a id="btn_checkuser" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Đã tồn tại tài khoản trên')"></a>
<a id="btn_create_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đăng kí thành công')"></a>
<a id="btn_credit" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Số tiền đăng ký lớn hơn giới hạn tài khoản')"></a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/assets/js/numtostring.js"></script>
<script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>

<script type="text/javascript">  

    $(document).ready(function () {
    	// LoadContentGame('A');
    	// LoadContentGame('B');
    	// LoadContentGame('C');

        $.extend($.fn.autoNumeric.defaults, {              
        	vMin:0,
            mDec:0
        });      

        // $('selector').autoNumeric('init'); 

        // Calls the selectBoxIt method on your HTML select box
	  	$("select").selectBoxIt({

	    // Uses the jQuery 'fadeIn' effect when opening the drop down
	    showEffect: "fadeIn",

	    // Sets the jQuery 'fadeIn' effect speed to 400 milleseconds
	    showEffectSpeed: 400,

	    // Uses the jQuery 'fadeOut' effect when closing the drop down
	    hideEffect: "fadeOut",

	    // Sets the jQuery 'fadeOut' effect speed to 400 milleseconds
	    hideEffectSpeed: 400

  });
    });

</script>

<script type="text/javascript">

	$('#username').keyup(function(event) {
		const noSpecialChars = $('#username').val().replace(/[^a-zA-Z0-9]/g, '');
		$('#username').val(noSpecialChars)
	});
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

		var title = $('#credit').attr("title");
		var t = Number($('#max_credit').val().replace(/[^0-9\.]+/g,""));
						var k = Number($('#credit_hidden').val().replace(/[^0-9\.]+/g,""));
		var remainmoney = t-k;
		$('#max_credit_show').html(remainmoney.toLocaleString());
		// $('#credit').attr("data-original-title",credit);
		// $('#credit').tooltip({title: credit});

		if(remainmoney < 0)
		$('#credit_show_div').addClass("negative_number");
	else
		$('#credit_show_div').removeClass("negative_number");
		$('#credit').tooltip('show')
          .attr('data-original-title', docso(credit)+ ' chip')
          // .tooltip('fixTitle')
          .tooltip('show');

		
	});
	function Save() {
		var flag = false;
		if (true === $('#username').parsley().validate()) {
			flag = true;
			@if($user_current->roleid == 6)
			if (true === $('#password').parsley().validate()) {
				flag = true;
				@endif
				if (true === $('#credit_hidden').parsley().validate()) {
					flag = true;
					if ($('#thau_new').length > 0)
					if (true === $('#thau_new').parsley().validate() ) {
						if ( $('#thau_new').val() >=0 && $('#thau_new').val() <=100)
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
				@if($user_current->roleid == 6)
			}
			else
			{
				flag = false;
			}
			@endif
		}
		else
		{
			flag = false;
		}
		@if($user_current->roleid!=1)
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
			var rbm = $('#rollback_money').is(":checked");
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/users/store')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					username: $('#username').val(),
					user_current: $('#user_current').val(),
					password: $('#password').val(),
					fullname: $('#fullname').val(),
					credit: $('#credit_hidden').val(),
					thau: $('#thau_new').val(),
					role: $('#role').val(),
					customer_type: $('#custype').val(),
					lock: $('#lock').is(":checked"),
					rollback_money:$('#rollback_money').is(":checked") == true ? 1 : 0,
					copy_data:$('#member').val(),
					_token: $_token,
				},
				success: function(data)
				{
					// console.log(data)
                    $("#btn_Save" ).html(`Thêm mới` );
					if (data != false){
						$('#btn_create_success').click();
						
						$('.close').click();
						
						@if($user_current->roleid!=1)
						var t = Number($('#max_credit').val().replace(/[^0-9\.]+/g,""));
						var k = Number($('#credit_hidden').val().replace(/[^0-9\.]+/g,""));
						$('#max_credit').val(t-k);
						$('#max_credit_show').html(t-k);
						$('#max_push').val(t-k)
						@endif
						// refreshTable();
						dataJson = data
						message = "Thêm mới tài khoản thành công \n Token telegram kích hoạt : " + dataJson.token
						swal({
							title: "Thông báo",
							text: message,
							icon: "success",
							timer: 10000,
							buttons: {
							cancel: "Tiếp tục",
							},
						})
						.then((value) => {
							location.reload();
						});
						
						// showModalUserPercent(data);
					}
					else
						$('#btn_checkuser').click();
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
				error: function (data) {
					$('#max_credit_show').html($('#max_credit').val());
					console.log('Error:', data);
					$("#btn_Save" ).html(`Thêm mới` );
				}
			});
		}else{
			$('#max_credit_show').html($('#max_credit').val());
			$("#btn_Save" ).html(`Thêm mới` );
		}
	}

	function ClearData(){
		$('#username').val("");
		$('#password').val("");
		// $('#username').prop("readonly", true);
		// $('#password').prop("readonly", true);
		$('#fullname').val("");
		$('#thau_new').val("");
		$('#credit').val("");
		$('#lock').attr("checked",false);
		//$('#max_credit_show').html($('#max_credit').val().toLocateString());
		
		var credit = Number($('#credit').val().replace(/[^0-9\.]+/g,""));

		if($('#credit').val()!=""  )
		{
			$('#credit_hidden').val(credit);
		}
		else
		{
			$('#credit_hidden').val("");
		}

		var title = $('#credit').attr("title");
		var t = Number($('#max_credit').val().replace(/[^0-9\.]+/g,""));
						var k = Number($('#credit_hidden').val().replace(/[^0-9\.]+/g,""));
		var remainmoney = t-k;
		$('#max_credit_show').html(remainmoney.toLocaleString());
		// $('#credit').attr("data-original-title",credit);
		// $('#credit').tooltip({title: credit});

		if(remainmoney < 0)
		$('#credit_show_div').addClass("negative_number");
	}
	$("#btn_Save" ).click(function() {
	    $(this).html(
            `<i class="fa fa-spinner fa-spin"></i>Đang tạo...`
         );
		Save();
	});

	$("#btn_ClearData" ).click(function() {
		ClearData();
	});
</script>
