<table id="datatable" class="table table-bordered mails m-0 table-actions-bar table-striped">
						<thead>
							<tr>
								<!-- <th>#</th> -->
								<th>Nội dung</th>
								<th>Loại</th>
								<th>Cá nhân</th>
								<th>Ghim</th>
								<th>Ẩn</th>
								<th>Ngày tạo</th>
								<th>Ngày cập nhật</th>

								<!-- <th>Tín dụng</th> -->
								<!-- <th>Đã dùng</th> -->
								<!-- <th>Số dư</th> -->
								<!-- <th style="text-align: center">Thao tác</th> -->
								<!-- <th>Trạng thái</th> -->
								<!-- <th style="text-align: center">Khóa/Kích hoạt</th> -->

							</tr>
						</thead>

						<tbody>
							@foreach($notifications as $item)
							<tr>
								<td>{{$item->message}}</td>
								<td>{{$item->type}}</td>
								<td>{{$item->target}}</td>
								<td>@if($item->pin == 0)
									<span class="label label-table label-danger dropbtn" onclick="myFunctionPin('{!!$item->id!!}')">Không ghim</span>
									@else
									<span class="label label-table label-success dropbtn" onclick="myFunctionPin('{!!$item->id!!}')">Ghim</span>
									@endif

									<div id="myDropdownPin{!!$item->id!!}" class="dropdown-content dropdown"></a>
										@if($item->pin != 0)<a href="#updated" onclick="changePinStatus(0,'{!!$item->id!!}','{!!$item->id!!}')"><span class="label label-table label-danger">Huỷ ghim</span></a>@endif
										@if($item->pin != 1)<a href="#updated" onclick="changePinStatus(1,'{!!$item->id!!}','{!!$item->id!!}')"><span class="label label-table label-success">Ghim</span></a>@endif
									</div>
								</td>

								<td>@if($item->hidden == 0)
									<span class="label label-table label-danger dropbtn" onclick="myFunctionHidden('{!!$item->id!!}')">Ẩn</span>
									@else
									<span class="label label-table label-success dropbtn" onclick="myFunctionHidden('{!!$item->id!!}')">Hiển thị</span>
									@endif

									<div id="myDropdownHidden{!!$item->id!!}" class="dropdown-content dropdown"></a>
										@if($item->hidden != 1)<a href="#updated" onclick="changeHiddenStatus(1,'{!!$item->id!!}','{!!$item->id!!}')"><span class="label label-table label-success">Hiển thị</span></a>@endif
										@if($item->hidden != 0)<a href="#updated" onclick="changeHiddenStatus(0,'{!!$item->id!!}','{!!$item->id!!}')"><span class="label label-table label-danger">Ẩn</span></a>@endif
									</div>
								</td>

								<td>{{$item->created_at}}</td>
								<td>{{$item->updated_at}}</td>
							</tr>
							@endforeach
						</tbody>

					</table>
                    <span>Tìm thấy <mark>{{count($notifications)}}</mark> thông báo. Bạn đang ở trang 1 trên tổng số 1 trang</span>