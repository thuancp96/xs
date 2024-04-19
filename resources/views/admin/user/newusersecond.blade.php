<?php
$customertypes = UserHelpers::GetCustomertype();
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

<div id="create-modal-usersecond" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Thêm tài khoản phụ</h4>
			</div>
			<form id="create-user-form" data-parsley-validate novalidate>
			<div class="modal-body new_user">
				<div class="row form_create">
					<div class="row">	
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-4" class="col-sm-3 control-label">Loại Tài Khoản </label>
								<div class="col-sm-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-group"></i></span> -->
									<select name="role" id="role2">
										<option value="1">Full control</option>
										<option value="2">Tài khoản</option>
										<option value="3">Bảng biểu</option>
									</select>
									
								</div>
							</div>
						</div>
						@if($user->roleid == 5)
							<div class="col-md-6 hidden">
								<div class="form-group">
									<label for="field-4" class="col-sm-3 control-label">Loại khách hàng</label>
									<div class="col-sm-8 input-group">
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
								<label for="field-1" class="col-sm-3 control-label">Tài khoản</label>
								<div class="col-sm-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-user"></i></span> -->
									<input type="text" id="username" name="username" class="form-control" placeholder="Hãy nhập tài khoản" required required data-parsley-error-message="Nhập tài khoản độ dài từ 6-15 ký tự" data-parsley-trigger="keyup" minlength="6" data-parsley-minlength="6" maxlength="15" data-parsley-maxlength="15">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-1" class="col-sm-3 control-label">Mật khẩu</label>
								<div class="col-sm-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-key"></i></span> -->
									<input id="password" type="password"  name="password" class="form-control" autocomplete="false" placeholder="Hãy nhập mật khẩu ít nhất 6 ký tự" required data-parsley-error-message="Hãy nhập mật khẩu ít nhất 6 ký tự" minlength="6" data-parsley-minlength="6">
								</div>
							</div>
						</div>
					</div>

					<div class="row ">
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-1" class="col-sm-3 control-label">Họ và Tên</label>
								<div class="col-sm-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-font"></i></span> -->
									<input type="text" name="fullname" class="form-control" id="fullname" placeholder="Hãy nhập tên" parsley-trigger="change" required data-parsley-error-message="Bạn chưa nhập tên">
								</div>
							</div>
						</div>
							
					</div>

					<div class="row hidden">
						<div class="col-md-6">
							<div class="form-group">
								<label for="field-4" class="col-sm-3 control-label">Tín dụng</label>
								<div class="col-sm-8 input-group">
									<!-- <span class="input-group-addon"><i class="fa fa-dollar"></i></span> -->
									<input type="text" name="credit" data-a-sign="" class="form-control autonumber" value="" id="credit" placeholder="Tài khoản ban đầu" data-toggle="tooltip" data-placement="top" title="0">

									<input type="hidden" name="credit" data-a-sign="" class="form-control" value="0" id="credit_hidden" placeholder="Tài khoản" data-parsley-type="number" data-parsley-min="0" required data-parsley-error-message="Bạn chỉ được nhập số >= 0" >
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group textmoney" id="credit_show_div">
							<div id="max_credit_show" class="autonumber">{{$user->remain}}</div>
							<i class="fa fa-spin fa-refresh"></i>
							<!-- <label for="field-5" class="col-sm-3 control-label" style="width: 250px !important;">Còn lại: <i id="max_credit_show">{{$user->remain}}</i></label> -->
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
			<div class="modal-footer" style="text-align: left !important;">
				<!-- <ins data-dismiss="modal">Đóng</ins> -->
				<!-- <button class="ladda-button  btn btn-info" data-style="slide-left" id="btn_Save">
					<span class="ladda-label">Lưu</span>
					<span class="ladda-spinner"></span>
				</button> -->

				<button type="button" id="btn_Save" class="btn btn-default btn-custom waves-effect waves-light">Thêm mới</button>
				<button type="button" id="btn_ClearData" class="btn btn-default btn-custom waves-effect waves-light">Nhập lại</button>
				
				<input type="hidden" id="sa-success">
				<input type="hidden" id="max_credit" value="{{$user->remain}}">
			</div>
			</form>
		</div>
	</div>
</div>

<a href="#" data-toggle="modal" data-target="#full-width-modal" onclick="showModalUserPercent('{{$user->id}}','{{$user->fullname}}')" id="userpercentid"></a>

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

		$('#username').keyup(function(event) {
			const noSpecialChars = $('#username').val().replace(/[^a-zA-Z0-9]/g, '');
			$('#username').val(noSpecialChars)
		});

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
				url: "{{url('/users/store-second')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					username: $('#username').val(),
					password: $('#password').val(),
					fullname: $('#fullname').val(),
					credit: $('#credit_hidden').val(),
					role2: $('#role2').val(),
					customer_type: $('#custype').val(),
					lock: $('#lock').is(":checked"),
					_token: $_token,
				},
				success: function(data)
				{

					if (data != false){
						$('#btn_create_success').click();
						
						$('.close').click();
						
						@if($user->roleid!=1)
						var t = Number($('#max_credit').val().replace(/[^0-9\.]+/g,""));
						var k = Number($('#credit_hidden').val().replace(/[^0-9\.]+/g,""));
						$('#max_credit').val(t-k);
						$('#max_credit_show').html(t-k);
						$('#max_push').val(t-k)
						@endif
						// refreshTable();
						location.reload();
						// showModalUserPercent(data);
					}
					else
						$('#btn_checkuser').click();
					$('#username').val("");
					$('#password').val("");
					$('#username').prop("readonly", true);
					$('#password').prop("readonly", true);
					$('#fullname').val("");
					$('#credit').val("");
					$('#lock').attr("checked",false);
					$('#max_credit_show').html($('#max_credit').val());
					
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
				}
			});
		}else{
			$('#max_credit_show').html($('#max_credit').val());
		}
	}
	function ClearData(){
		$('#username').val("");
		$('#password').val("");
		$('#fullname').val("");
		$('#lock').attr("checked",false);
	}
	$("#btn_Save" ).click(function() {
		Save();
	});
	$("#btn_ClearData" ).click(function() {
		ClearData();
	});
</script>
