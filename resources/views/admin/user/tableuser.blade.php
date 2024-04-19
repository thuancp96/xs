<div class="">
					<table id="datatable"  class="table table-bordered mails m-0 table-actions-bar table-striped">
						<thead>
							<tr>
								<!-- <th>#</th> -->
								<th>Tài khoản</th>
								<th>Tên</th>
								<th>Nhóm</th>
								
								<th>Tín dụng</th>
								<!-- <th>Đã dùng</th> -->
								<th>Số dư</th>
								<th style="text-align: center">Thao tác</th>
								<th>Trạng thái</th>
								<!-- <th style="text-align: center">Khóa/Kích hoạt</th> -->
								
							</tr>
						</thead>
						
						<tbody>
						@foreach($users as $user)
							<tr>
								<!-- <td></td> -->
								<td>
								@if ($user->roleid == 6)
								{{$user->name}}
								@else
								<a href="{{url('/users/user-child/'.$user->id)}}">{{$user->name}}</a>
								@endif
								</td>
								<td>{{$user->fullname}}</td>
								<td>
									@foreach($roles as $role)
										@if($role->id==$user->roleid)
											{{$role->name}}
										@endif
									@endforeach
								</td>
								
								<td style="text-align: center">{{ number_format($user->credit, 0)}}</td>
								<!-- <td style="text-align: center">{{ number_format($user->consumer, 0)}}</td> -->
								<td style="text-align: center">{{ number_format($user->remain, 0)}}</td>
								
								<td style="text-align: center">
									
									<div class="btn-group dropup">
                                                    <button type="button" class="btn btn-white dropdown-toggle glyphicon glyphicon-wrench" data-toggle="dropdown" aria-expanded="false"></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                    	@if (Session::get('usersecondper') == 11)
                                    					@else
                                                        <li><a href="#" data-toggle="modal" data-target="#change_credit" onclick="showModalCredit_PG('{{$user->id}}','{{$user->name}}','{{ number_format($user->credit, 0)}}')">Tín dụng</a></li>
                                                        @endif
                                                        @if (Session::get('usersecondper') == 11)
                                    					@else
                                                        <li><a href="#" data-toggle="modal" data-target="#edit-modal" onclick="showModal('{{$user->id}}','{{$user->name}}','{{$user->fullname}}','{{$user->lock}}','{{ number_format($user->credit, 0)}}','{{ number_format($user->consumer, 0)}}','{{ number_format($user->remain, 0)}}','{{$user->roleid}}','{{$user->customer_type}}')">Thông tin tài khoản </a></li>
                                                        @endif
                                                        @if($user_current->id == Auth::user()->id)
                                                        <li><a href="#" data-toggle="modal" data-target="#full-width-modal" onclick="showModalUserPercent('{{$user->id}}','{{$user->name}}')" id="userpercent{{$user->id}}">Thông số</a></li>
                                                        @endif

                                                        <li class="divider"></li>
                                                        <!-- <li><a href="#" class="">Đổi mật khẩu</a></li> -->
                                                        @if (Session::get('usersecondper') == 11)
                                    					@else
                                                        <li hidden><a  data-toggle="modal" data-target="#changepassuser-modal" onclick="ShowLoadChangePassUser('{{$user->id}}','{{$user->name}}')"><i class="ti-settings m-r-5"></i>Mật khẩu</a></li>
                                                        @endif
                                                        @if($user_current->id == Auth::user()->id)
                                                        @if(Auth::user()->roleid==1)
                                                        	@if (Session::get('usersecondper') == 11)
                                    						@else
															<li><a href="#" class="btn_delete" onclick="setId('{{$user->id}}')">Xóa tài khoản</a></li>
															@endif
														@endif
														@endif
                                                        
                                                    </ul>
                                                </div>

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
								<td>@if($user->lock == 3)
										<span class="label label-table label-danger">Đóng/Ngừng đặt</span>
									@elseif($user->lock == 2)
										<span class="label label-table label-danger">Đóng</span>
									@elseif($user->lock == 1)
										<span class="label label-table label-danger">Ngừng đặt</span>
									@else
										<span class="label label-table label-success">Mở</span>
									@endif
									@if($user->roleid==6)
									<br/>
									Chuẩn {{$user->customer_type}}</td>
									@endif
							</tr>
						@endforeach
						</tbody>
						
					</table>

					<!-- <span>Tìm thấy <mark>{{count($users)}}</mark> tài khoản. Bạn đang ở trang 1 trên tổng số 1 trang</span> -->
					
					
					</div>
				</div>
<script type="text/javascript">
	// var t = $('#datatable').DataTable( {
	// 	"paging":   false,
	// 	"oLanguage": {"sZeroRecords": "Chưa có dữ liệu", "sEmptyTable": "Chưa có dữ liệu"},
	// 	"bLengthChange": false,
	// 	"ordering": false,
	// 	"info":     false,
	// 	"columnDefs": [ {
	// 		"searchable": false,
	// 		"orderable": false,
	// 		"targets": 0
	// 	} ],
	// 	// "order": [[ 1, 'asc' ]],
	// } );
	// t.on( 'order.dt search.dt', function () {
	// 	t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	// 		// cell.innerHTML = i+1;
	// 	} );
	// } ).draw();
	// function filterGlobal () {
	// 	$('#datatable').DataTable().search(
	// 			$('#input_search').val()
	// 	).draw();
	// }

	$('.dataTables_filter').hide();
	$('.btn_delete').click(function(){
		swal({
			title: "Bạn có chắc chắn xóa?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Xóa",
			cancelButtonText: "Hủy",
			closeOnConfirm: false
		},function(isConfirm){
			if (isConfirm) {
				$_token = $('#token').val();
				$.ajax({
					url: $('#url').val()+"/destroy/"+$('#user-id-delete').val(),
					method: 'POST',
					dataType: 'json',
					data: {
						_token: $_token,
					},
					success: function(data)
					{
						swal("Đã xóa!", "Bạn đã thành công", "success");
						refreshTable();
					},
					error: function (data) {
					}
				});
			} else {
				// swal("Cancelled", "Your imaginary file is safe :)", "error");
			}
		});
	});
</script>