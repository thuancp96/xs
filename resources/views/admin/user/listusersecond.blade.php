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

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}
</style>
	@include('admin.user.newusersecond',['roles' => $roles])
	@include('admin.user.changeuserpercent')
	@include('admin.user.changeuser',['roles' => $roles])
	@include('admin.changepass_user')
	@include('admin.user.changecredit')
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Tài khoản phụ {{XoSoRecordHelpers::GetRoleName(Auth::user()->roleid)}}
					</h3>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box">
			
				<div class="row">
				<div class="col-sm-6 col-xs-6">
						<form role="form">
							<div class="form-group contact-search m-b-30">
								<input type="text" id="input_search" class="form-control" placeholder="Tìm kiếm">
								<button type="button" class="btn btn-white"><i class="fa fa-search"></i></button>

								
							</div> <!-- form-group -->
						</form>
					</div>
					@if (Session::get('usersecondper') == 11)
                                    @else
									<div class="col-sm-6 col-xs-6">

						<button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#create-modal-usersecond" style="height: 38px;">
	                       <span class="btn-label">
	                       <i class="md md-add"></i></span>Thêm mới</button>
					</div>
					@endif
					@if($user_current->id != Auth::user()->id)
		<!-- <div class="row"> -->
			<div class="col-sm-2">
				<a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light"><i class="md md-keyboard-return"></i>Trở lại</a>
			</div>
		<!-- </div> -->
	@endif
				</div>
				<div class="table-rep-plugin">
					<div class="">
					<table id="datatable"  class="table table-bordered mails m-0 table-actions-bar table-striped">
						<thead>
							<tr>
								<!-- <th>#</th> -->
								<th>Tài khoản</th>
								<th>Họ tên</th>
								<th>Trạng thái</th>
								<th>Nhóm</th>
								
								<!-- <th>Tín dụng</th> -->
								<!-- <th>Đã dùng</th> -->
								<!-- <th>Số dư</th> -->
								<th style="text-align: center">Thao tác</th>
								<!-- <th>Trạng thái</th> -->
								<!-- <th style="text-align: center">Khóa/Kích hoạt</th> -->
								
							</tr>
						</thead>
						
						<tbody>
						@foreach($users as $user)
							<tr>
								<!-- <td></td> -->
								<td id="username{{$user->id}}">
								{{$user->name}}
								</td>
								<td>{{$user->fullname}}</td>
								
								<td>@if($user->lock == 2)
										<span class="label label-table label-danger dropbtn" onclick="myFunction('{!!$user->name!!}')">Đóng</span>
									@else
										<span class="label label-table label-success dropbtn" onclick="myFunction('{!!$user->name!!}')">Mở</span>
									@endif

									<div id="myDropdown{!!$user->name!!}" class="dropdown-content dropdown"></a>
										@if($user->lock != 2)<a href="#updated" onclick="changeAccountStatus(2,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-danger">Đóng</span></a>@endif
										@if($user->lock != 0)<a href="#updated" onclick="changeAccountStatus(0,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Mở</span></a>@endif
									</div>
								</td>
						
								<td>
									Tài khoản phụ <span class="label label-table label-success dropbtn" onclick="myFunction('{!!$user->name!!}TKP')">{{UserHelpers::GetRole2Name($user->role2)}} </span>
									<div id="myDropdown{!!$user->name!!}TKP" class="dropdown-content dropdown"></a>
										@if($user->role2 != 1)<a href="#updated" onclick="changeAccountStatusTKP(1,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Full control</span></a>@endif
										@if($user->role2 != 2)<a href="#updated" onclick="changeAccountStatusTKP(2,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Tài khoản</span></a>@endif
										@if($user->role2 != 3)<a href="#updated" onclick="changeAccountStatusTKP(3,'{!!$user->name!!}','{!!$user->id!!}')"><span class="label label-table label-success">Bảng biểu</span></a>@endif
									</div>
								</td>
								
								<td style="text-align: center">
									@if (Session::get('usersecondper') == 11)
                                    					@else

									<div class="btn-group dropup">
                                                    <button type="button" class="btn btn-white dropdown-toggle glyphicon glyphicon-wrench" data-toggle="dropdown" aria-expanded="false"></button>
                                                    <ul class="dropdown-menu" role="menu" style="right: 0; left:unset !important;">
                                                    	

                                                        <!-- <li class="divider"></li> -->
                                                        <!-- <li><a href="#" class="">Đổi mật khẩu</a></li> -->
                                                        @if (Session::get('usersecondper') == 11)
                                    					@else
                                                        <li><a  data-toggle="modal" data-target="#changepassuser-modal" onclick="ShowLoadChangePassUser('{{$user->id}}','{{$user->name}}')"><i class="ti-settings m-r-5"></i>Mật khẩu</a></li>
                                                        @endif
														
														@if(isset($user->fullname) && strlen($user->fullname) > 0 )
														<li><a href="#" class="btn_reset_token_telegram" onclick="setId('{{$user->id}}')">Reset Token Telegram</a></li></li>
														@else
														<li><a href="#" class="btn_create_token_telegram" onclick="setId('{{$user->id}}')">Tạo Token Telegram</a></li></li>
														@endif

														@if (Session::get('usersecondper') == 11)
                                    					@else
                                                        <!-- <li><a href="#" class="btn_lock_second" onclick="setId('{{$user->id}}')">Khóa/Mở tài khoản</a></li> -->
                                                        @endif
                                                        @if($user_current->id == Auth::user()->id)
                                                        <!--@if(Auth::user()->roleid==1)-->
                                                        	@if (Session::get('usersecondper') == 11)
                                    						@else
															<li><a href="#" class="btn_delete_second" onclick="setId('{{$user->id}}')">Xóa tài khoản</a></li>
															@endif
														<!--@endif-->
														@endif
                                                        
                                                    </ul>
                                                </div>
                                     @endif
									<!-- <a href="#" class="table-action-btn"  data-toggle="modal" data-target="#change_credit" onclick="showModalCredit_PG('{{$user->id}}','{{ number_format($user->remain, 0)}}')"><i class="md md-local-atm"></i></a>
									<a href="#" class="table-action-btn"  data-toggle="modal" data-target="#edit-modal" onclick="showModal('{{$user->id}}','{{$user->fullname}}','{{$user->lock}}','{{ number_format($user->credit, 0)}}','{{ number_format($user->consumer, 0)}}','{{ number_format($user->remain, 0)}}','{{$user->roleid}}','{{$user->customer_type}}')"><i class="md md-edit"></i></a> -->
									<!-- <a href="#" class="table-action-btn btn_delete" onclick="setId('{{$user->id}}')"><i class="md md-close"></i></a> -->
								</td>
								<!-- <td class="text-center" style="text-align: center">
									@if($user->lock==true)
										<span class="label label-table label-danger">Bị khóa</span>
										
									@else
										<span class="label label-table label-success">Đã kích hoạt</span>
									@endif
								</td> -->
								
							</tr>
						@endforeach
						</tbody>
						
					</table>

					<script>
							/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
							function myFunction(userName) {
								$("div[id*='myDropdown']").each(function (i, el) {
									el.classList.remove("show");
								});
								document.getElementById("myDropdown"+userName).classList.toggle("show");
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
									cancel: "Huỷ",
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

							function changeAccountStatusTKP(lock_status,userName,userId){
								statusName = ""
								switch (lock_status) {
									case 1:
										statusName = "Full control"
										break;
								
									case 2:
										statusName = "Tài khoản"
										break;

									case 3:
										statusName = "Bảng biểu"
										break;

									default:
										break;
								}

								// swal({
								// 	title: "",
								// 	text: 'Bạn có muốn thay đổi quyền "' + statusName + '" của ' +userName+ " không?",
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
								// 				type: "lock2",
								// 				lock2: lock_status,
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

								swal('Bạn có muốn thay đổi quyền "' + statusName + '" của ' +userName+ " không?", {
									buttons: {
									cancel: "Huỷ",
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
												type: "lock2",
												lock2: lock_status,
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
					
					
					</div>
					
				</div>
				<span>Tìm thấy <mark>{{count($users)}}</mark> tài khoản. Bạn đang ở trang 1 trên tổng số 1 trang</span>
				<input type="hidden" id="user-id-delete">
				<input type="hidden" id="url" value="{{url('/users')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			
			</div>
		</div>
	</div>
	

@endsection

@section('js_call')
	<script src="/assets/admin/js/user.js?v=1.122"></script>
@endsection
