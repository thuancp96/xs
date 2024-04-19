@extends('admin.admin-template')
@section('title', 'Quản lí người dùng')
@section('content')
<style>
  /* Popover */
  .popover {
      /*border: 2px blue;*/
      width:80px;
  }
  /* Popover Header */
  .popover-title {
      background-color: #73AD21;
      color: #FFFFFF;
      font-size: 12px;
      text-align:center;
      height:40px;
  }
  /* Popover Body */
  .popover-content {
      /*background-color: coral;*/
      /*color: #FFFFFF;*/
      text-align:center;
      padding: 12px;
      height:40px;
  }
  /* Popover Arrow */
  .arrow {
      /*border-right-color: red !important;*/
  }
  .sweet-alert { 
       margin-top:-400px !important;
}
  </style>

<style>
.dropbtn {
  /* background-color: #3498DB; */
  /* color: white; */
  /* padding: 16px; */
  /* font-size: 16px; */
  border: none;
  cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
  background-color: #2980B9;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 100px;
  overflow: auto;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 10px 6px;
  text-decoration: none;
  display: block;
}

.dropdown-menu{
	position:absolute;
	right:0 !important;
	left:unset !important;
}
.dropdown a:hover {background-color: #ddd;}

.show {display: block;}

</style>

	@include('admin.user.newuser',['roles' => $roles])
	@include('admin.user.changeuserpercent_tab')
	@if(Auth::user()->roleid != 11)
		@include('admin.user.changesuperchangemaxone')
	@endif
	@if(Auth::user()->roleid == 1)
		@include('admin.user.changesupermaxex')
	@endif
	@include('admin.user.changeuser',['roles' => $roles])
	@include('admin.changepass_user')
	@include('admin.user.changecredit')
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						<?php
							$roleChild = 0;
							switch ($user_current->roleid) {
								case 1: $roleChild = 2; break;
								case 2: $roleChild = 4; break;
								case 4: $roleChild = 5; break;
								case 5: $roleChild = 6; break;
								
								default:
									# code...
									break;
							}
						?>
						Tài khoản  {{XoSoRecordHelpers::GetRoleName($roleChild)}}
						
					</h3>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>

	@if($user_current->id != Auth::user()->id)
	<div class="row">
	<div class="col-sm-12">
		<div class="portlet"><!-- /primary heading -->
			<div class="portlet-heading" style="display: flex;
    align-items: flex-start !important;
    flex-direction: column;">
				<label class="portlet-title text-dark text-uppercase" style="font-size:13px;">
				<a onclick="window.history.back();" class="">< Quay về</a>
				</label>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	</div>
	@endif

	<div class="row">
		<div class="col-sm-12">
			<div class="card-box">
				<div class="row">
					@if($user_current->id == Auth::user()->id)
					<div class="col-sm-6 col-xs-6">
						<div role="form">
							<div class="form-group contact-search m-b-30">
							    <input type="text" id="input_search" class="form-control" placeholder="Tìm kiếm" @if (isset($search)) value="{{$search}}" @endif>
								<button type="button" class="btn btn-white" onclick="goSearchUserChild()"><i class="fa fa-search"></i></button>

								<script>

									$('#input_search').keyup(function(event) {
											if (event.keyCode == 13) {
												goSearchUserChild()
											}
										});
										
									function goSearchUserChild(){
										window.location.href = "{{url('/users/user-child/'.$user_current->id)}}/" + $("#input_search").val();   
									}
									</script>
							</div> <!-- form-group -->
						</div>
					</div>
					@endif
					@if (Session::get('usersecondper') == 11 && Session::get('username') != 'dta')
                    @else
						@if(Auth::user()->roleid==11 || $user_current->id == Auth::user()->id)
							<div class="col-sm-6 col-xs-6">
								<button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#create-modal" style="height: 38px;">
								<span class="btn-label">
								<i class="md md-add"></i></span>Thêm mới</button>
							</div>
						@endif
					@endif
				</div>
				<?php
						use App\Helpers\UserHelpers;
						$listBreakCrumb = [];
						if (!isset($search) || $search == "")
							$listBreakCrumb = UserHelpers::buildBreadCrumbsUser($user_current,0);
						// print_r($listBreakCrumb);
					?>
					
				@if($user_current->id != Auth::user()->id)
					@for($i=count($listBreakCrumb)-1;$i>=0;$i--)
						<a style="font-size:14px;" href="{{$listBreakCrumb[$i]['url']}}">{{$listBreakCrumb[$i]['name']}} </a> 
						@if($i>0) > @endif
					@endfor
					<br>
				@endif
				<br>
				<div class="table-rep-plugin">
				<div class="table-responsive1">
					<table id="datatable"  class="table table-bordered mails m-0 table-actions-bar table-striped">
						<thead>
							<tr>
								<th >Tài khoản</th>
								<th>Tín dụng<br>Số dư</th>
								<th style="text-align: center">Thao tác</th>
								<!-- @if(isset($users[0]) ? $users[0]->roleid == 2 : false)
								<th>Thầu</th> -->
								<!-- @endif -->
								<th>Đăng nhập</th>
								<!-- <th>Ghi chú</th> -->
								<!-- <th style="text-align: center">Khóa/Kích hoạt</th> -->
								
							</tr>
						</thead>
						
						<style>
							.box {
								inline-size: 150px;
								overflow-wrap: break-word;
								}
						</style>
						
						<tbody>
						@foreach($users as $stt=>$user)
							<tr>
								<td style="max-width:100px;" class="box">
								@if ($user->roleid == 6)
									<?php 
									
										$listBreakCrumbMember = [];//UserHelpers::buildBreadCrumbsUser($user,0);
										if ((isset($search) && $search != "") && count($users) < 500)
											$listBreakCrumbMember = UserHelpers::buildBreadCrumbsUser($user,0);
									?>
									
									<label data-toggle="collapse" data-target="#{{$user->name}}">{{$user->name}}</label>
										<div id="{{$user->name}}" class="collapse">
										@for($i=count($listBreakCrumbMember)-1;$i>=1;$i--)
										<a style="font-size:14px; color:red" href="{{$listBreakCrumbMember[$i]['url']}}"> {{$listBreakCrumbMember[$i]['name']}} </a> 
										<!-- @if($i>0) > @endif -->
										@endfor
										</div>
									
								@else
								<a href="{{url('/users/user-child/'.$user->id)}}" id="username{{$user->id}}">{{$user->name}}</a>
								@endif
								@if(isset($user->fullname) && $user->fullname!= "")
								<br>
								{{"@".$user->fullname}}
								@endif
								<br>
									@if(isset($users[0]) ? $users[0]->roleid == 4 : false)
								    {{ number_format($user->thau, 0)}}%
								@endif

								</td>
								
								<td style="text-align: right">
								@if (Session::get('usersecondper') == 11 && Session::get('username') != 'dta')
									{{ number_format($user->credit, 0)}}
								@else
								<a style="color: red;" href="#" data-toggle="modal" data-target="#change_credit" onclick="showModalCredit_PG('{{$user->id}}','{{$user->name}}','{{ number_format($user->credit, 0)}}','{{ number_format($user->remain, 0)}}')">{{ number_format($user->credit, 0)}}</a>
								@endif
								<br>
								{{ number_format($user->remain, 0)}}
								</td>

								<!-- @if(isset($users[0]) ? $users[0]->roleid == 2 : false)
								    <td style="text-align: center">{{ number_format($user->thau, 0)}}%</td>
								@endif -->
								<td style="text-align: center">
									
									<div class="btn-group dropup">
                                                    <button type="button" class="btn btn-white dropdown-toggle glyphicon glyphicon-wrench" data-toggle="dropdown" aria-expanded="false"></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                    	
                                                        @if (Session::get('usersecondper') == 11 && Session::get('username') != 'dta')
                                    					@else
                                                        <li><a href="#" data-toggle="modal" data-target="#edit-modal" onclick="showModal('{{$user->id}}','{{$user->name}}','{{$user->fullname}}','{{$user->lock}}','{{$user->lock_price}}','{{ number_format($user->credit, 0)}}','{{ number_format($user->consumer, 0)}}','{{ number_format($user->remain, 0)}}','{{$user->roleid}}','{{$user->customer_type}}','{{$user->thau}}')">Thông tin tài khoản </a></li> 
                                                        @endif
                                                        
                                                        <li><a href="#" data-toggle="modal" data-target="#full-width-modal" onclick="showModalUserPercent('{{$user->id}}','{{$user->name}}',{{$user->roleid}},'{{$user->customer_type}}')" id="userpercent{{$user->id}}">Giá mua/ Trả thưởng</a></li>
														@if($user->roleid!=12)
                                                        <li><a href="#" data-toggle="modal" data-target="#full-width-modal-maxone" onclick="showModalUserPercentMaxone('{{$user->id}}','{{$user->name}}')" id="userpercent{{$user->id}}">Giới hạn cược</a></li>
                                                        @endif

														@if($user->roleid == 2)
														<li><a href="#" data-toggle="modal" data-target="#full-width-modal-maxex" onclick="showModalUserPercentMaxex('{{$user->id}}','{{$user->name}}')" id="userpercent{{$user->id}}">Giới hạn lên giá</a></li>
														@endif
														<li class="divider"></li>
                                                        <!-- <li><a href="#" class="">Đổi mật khẩu</a></li> -->
                                                        @if (Session::get('usersecondper') == 11 && Session::get('username') != 'dta')
                                    					@else
                                                        <li ><a  data-toggle="modal" data-target="#changepassuser-modal" onclick="ShowLoadChangePassUser('{{$user->id}}','{{$user->name}}')"><i class="ti-settings m-r-5"></i>Mật khẩu</a></li>
														@if(isset($user->fullname) && strlen($user->fullname) > 0 )
														<li><a href="#" class="btn_reset_token_telegram" onclick="setId('{{$user->id}}')">Reset Token Telegram</a></li></li>
														@else
														<li><a href="#" class="btn_create_token_telegram" onclick="setId('{{$user->id}}')">Tạo Token Telegram</a></li></li>
														@endif
														<li><a href="#" class="btn_reset_otp" onclick="setId('{{$user->id}}')">Reset OTP</a></li></li>
                                                        @endif

														@if(Auth::user()->roleid==1)
															@if (Session::get('usersecondper') != 11)
																<li><a href="#" class="btn_delete" onclick="setId('{{$user->id}}')">Xóa tài khoản</a></li>
															@endif
														@else
															@if($user_current->id == Auth::user()->id)
															@if(Auth::user()->roleid==1)
																@if (Session::get('usersecondper') == 11)
																@else
																<li><a href="#" class="btn_delete" onclick="setId('{{$user->id}}')">Xóa tài khoản</a></li>
																@endif
															@endif
															@endif
														@endif
                                                        
                                                    </ul>
                                                </div>

									<!-- <a href="#" class="table-action-btn"  data-toggle="modal" data-target="#change_credit" onclick="showModalCredit_PG('{{$user->id}}','{{ number_format($user->remain, 0)}}')"><i class="md md-local-atm"></i></a>
									<a href="#" class="table-action-btn"  data-toggle="modal" data-target="#edit-modal" onclick="showModal('{{$user->id}}','{{$user->fullname}}','{{$user->lock}}','{{ number_format($user->credit, 0)}}','{{ number_format($user->consumer, 0)}}','{{ number_format($user->remain, 0)}}','{{$user->roleid}}','{{$user->customer_type}}')"><i class="md md-edit"></i></a> -->
									<!-- <a href="#" class="table-action-btn btn_delete" onclick="setId('{{$user->id}}')"><i class="md md-close"></i></a> -->
									<br><br>
									@if($user->lock == 3)
										<span class="label label-table label-danger dropbtn" onclick="myFunction('{!!$user->name!!}')">Đóng/Ngừng đặt</span>
									@elseif($user->lock == 2)
										<span class="label label-table label-danger dropbtn" onclick="myFunction('{!!$user->name!!}')">Đóng</span>
									@elseif($user->lock == 1)
										<span class="label label-table label-danger dropbtn" onclick="myFunction('{!!$user->name!!}')">Ngừng đặt</span>
									@else
										<span class="label label-table label-success dropbtn" onclick="myFunction('{!!$user->name!!}')">Mở</span>
									@endif

									<div id="myDropdown{!!$user->name!!}" class="dropdown-content dropdown" style="position:absolute;"></a>
										<!-- @if($user->lock != 3)<a href="#updated" onclick="changeAccountStatus(3,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-danger">Đóng/Ngừng đặt</span></a>@endif -->
										@if($user->lock != 2)<a href="#updated" onclick="changeAccountStatus(2,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-danger">Đóng</span></a>@endif
										@if($user->lock != 1)<a href="#updated" onclick="changeAccountStatus(1,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-danger">Ngừng đặt</span></a>@endif
										@if($user->lock != 0)<a href="#updated" onclick="changeAccountStatus(0,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Mở</span></a>@endif
									</div>
									
									
									@if($user->roleid==6 )
									<br><br>
											@if(Auth::user()->roleid>=4)
												<span class="label label-table label-danger dropbtn" onclick="myFunctionCustomerType('{!!$user->name!!}')">Chuẩn {{$user->customer_type}}</span>
										
												<div id="myDropdownCustomerType{!!$user->name!!}" class="dropdown-content dropdown"></a>
													<!-- @if($user->lock != 3)<a href="#updated" onclick="changeAccountStatus(3,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-danger">Đóng/Ngừng đặt</span></a>@endif -->
													@if($user->customer_type != 'A')<a href="#updated" onclick="changeCustomerType('A','{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Chuẩn A</span></a>@endif
													@if($user->customer_type != 'B')<a href="#updated" onclick="changeCustomerType('B','{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Chuẩn B</span></a>@endif
													@if($user->customer_type != 'C')<a href="#updated" onclick="changeCustomerType('C','{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Chuẩn C</span></a>@endif
													@if($user->customer_type != 'D')<a href="#updated" onclick="changeCustomerType('D','{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Chuẩn D</span></a>@endif
												</div>
											@else
												<span class="label label-table label-danger">Chuẩn {{$user->customer_type}}</span>
											@endif
									@else
									@endif

								</td>

								<!-- <td class="text-center" style="text-align: center">
									@if($user->lock==true)
										<span class="label label-table label-danger">Bị khóa</span>
										
									@else
										<span class="label label-table label-success">Đã kích hoạt</span>
									@endif
								</td> -->
								<td>
								<?php 
									$timeLogin = explode(" ",$user->latestlogin);
									$timeDate = strtotime($user->latestlogin);
								?>	
								{{date("m-d",$timeDate)}}<br>{{date("H:i:s",$timeDate)}}</td>
							</tr>
						@endforeach

						<script>
							/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
							function myFunction(userName) {
								$("div[id*='myDropdown']").each(function (i, el) {
									el.classList.remove("show");
								});
								document.getElementById("myDropdown"+userName).classList.toggle("show");
							}

							function myFunctionCustomerType(userName) {
								$("div[id*='myFunctionCustomerType']").each(function (i, el) {
									el.classList.remove("show");
								});
								document.getElementById("myDropdownCustomerType"+userName).classList.toggle("show");
							}

							function changeAccountStatus(lock_status,userName,userId){
								statusName = ""
								switch (lock_status) {
									case 0:
										statusName = "Mở"
										break;
								
									case 1:
										statusName = "Ngừng đặt"
										break;

									case 2:
										statusName = "Đóng"
										break;
									
									case 3:
										statusName = "Đóng/Ngừng đặt"
										break;

									default:
										break;
								}
								// swal({
								// 	title: "",
								// 	text: "Bạn có muốn " + statusName + " " +userName+ " không?",
								// 	type: "warning",
								// 	showCancelButton: true,
								// 	confirmButtonColor: "#DD6B55",
								// 	confirmButtonText: "Đồng ý",
								// 	cancelButtonText: "Hủy",
								// 	closeOnConfirm: false
								// }, function(isConfirm) {
								// 	if (isConfirm) {
								// 		$_token = $('#token').val();
								// 		$.ajax({
								// 			url: "{{url('/users/update')}}"+"/"+userId,
								// 			method: 'POST',
								// 			dataType: 'json',
								// 			data: {
								// 				type: "lock",
								// 				lock: lock_status,
								// 				_token: $_token,
								// 			},
								// 			success: function(data)
								// 			{
								// 				$('#btn_edit_success').click();
								// 				location.reload();
								// 			},
								// 			error: function (data) {
								// 				console.log('Error:', data);
								// 			}
								// 		});
								// 	} else {
								// 	}
								// });

								swal("Bạn có muốn " + statusName + " " +userName+ " không?", {
									buttons: {
									cancel: "Xóa",
									defeat: "Đồng ý",
									},
								})
								.then((value) => {
									switch (value) {
								
									case "defeat":
										$_token = $('#token').val();
										$.ajax({
											url: "{{url('/users/update')}}"+"/"+userId,
											method: 'POST',
											dataType: 'json',
											data: {
												type: "lock",
												lock: lock_status,
												_token: $_token,
											},
											success: function(data)
											{
												$('#btn_edit_success').click();
												location.reload();
											},
											error: function (data) {
												console.log('Error:', data);
											}
										});
										break;
								
									default:
										break;
									}
								});

							}

							function changeCustomerType(customertype_status,userName,userId){
								statusName = ""

								swal("Bạn có muốn thay đổi "+userName+" sang Chuẩn " + customertype_status + " không?", {
									buttons: {
									cancel: "Xóa",
									defeat: "Đồng ý",
									},
								})
								.then((value) => {
									switch (value) {
								
									case "defeat":
										$_token = $('#token').val();
										$.ajax({
											url: "{{url('/users/update-customertype')}}"+"/"+userId,
											method: 'POST',
											dataType: 'json',
											data: {
												customertype: customertype_status,
												_token: $_token,
											},
											success: function(data)
											{
												// if (data=='true')
												swal({
													title: "Thông báo",
													text: "Chỉnh sửa thành công",
													icon: "success",
													timer: 10000,
													buttons: {
													defeat: "Thoát",
													},
												})
												.then((value) => {
													location.reload();
												});
											},
											error: function (data) {
												console.log('Error:', data);
											}
										});
										break;
								
									default:
										break;
									}
								});

							}

							// Close the dropdown if the user clicks outside of it
							window.onclick = function(event) {
								if (!event.target.matches('.dropbtn')) {
									var dropdowns = document.getElementsByClassName("dropdown-content");
									var i;
									for (i = 0; i < dropdowns.length; i++) {
										var openDropdown = dropdowns[i];
										if (openDropdown.classList.contains('show')) {
											openDropdown.classList.remove('show');
										}
									}
								}
							}
						</script>

						</tbody>
						
					</table>
					</div>
					
				</div>
				<span>Tìm thấy <mark>{{count($users)}}</mark> tài khoản. Bạn đang ở trang 1 trên tổng số 1 trang</span>
				<input type="hidden" id="user-id-delete">
				<input type="hidden" id="url" value="{{url('/users')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			
			</div>
		</div>
	</div>
	
	<script>
$(document).ready(function(){
	$("[data-toggle=popover]").popover();
});
</script>

@endsection

@section('js_call')
	<script src="/assets/admin/js/user.js?v=1.163"></script>
@endsection
