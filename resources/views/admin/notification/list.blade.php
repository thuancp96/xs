@extends('admin.admin-template')
@section('title', 'Danh sách thông báo')
@section('content')
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/css/select2.min.css">
<style>
	/* Popover */
	.popover {
		/*border: 2px blue;*/
		width: 80px;
	}

	/* Popover Header */
	.popover-title {
		background-color: #73AD21;
		color: #FFFFFF;
		font-size: 12px;
		text-align: center;
		height: 40px;
	}

	/* Popover Body */
	.popover-content {
		/*background-color: coral;*/
		/*color: #FFFFFF;*/
		text-align: center;
		padding: 12px;
		height: 40px;
	}

	/* Popover Arrow */
	.arrow {
		/*border-right-color: red !important;*/
	}

	.select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
		border: 1px solid #d2d6de;
		border-radius: 0;
		padding: 6px 12px;
		height:38px;
		font-size: 1.1em;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow{
		height: 38px;
		position: absolute;
		top: 1px;
		right: 1px;
		width: 20px;
		font-size: 1.1em;
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

	.dropbtn:hover,
	.dropbtn:focus {
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
		box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
		z-index: 1;
	}

	.dropdown-content a {
		color: black;
		padding: 10px 6px;
		text-decoration: none;
		display: block;
	}

	.dropdown a:hover {
		background-color: #ddd;
	}

	.show {
		display: block;
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="portlet"><!-- /primary heading -->
			<div class="portlet-heading">
				<h3 class="portlet-title text-dark text-uppercase">
					Danh sách thông báo
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
				<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
					<input class="form-control column_filter input-daterange-datepicker-self" type="text" name="daterange" value="" readonly="readonly" style="width: 250px; ">
				</div>
				<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
					<select class="js-notification-category-single" name="notification_category" id="select_notification_category">
						<option value="all">Tất cả</option>
						<option value="system">Hệ thống</option>
						<option value="generate">Chung</option>
						<option value="personal">Cá nhân</option>
					</select>
				</div>
				
				<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
					<span class="input-group-btn">
						<a style="margin-right:5px;" href="#" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter_target">Xem</a>
					</span>

				</div>
			</div>
			<br />
			<div class="table-rep-plugin">
				<div id="reloadGetSeflActiveHistory">
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
				</div>
			</div>
			<script>
						/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
						function myFunctionPin(userName) {
							$("div[id*='myDropdown']").each(function(i, el) {
								el.classList.remove("show");
							});
							document.getElementById("myDropdownPin" + userName).classList.toggle("show");
						}

						function changePinStatus(pin_status, userName, userId) {
							pinStatus = ""
							switch (pin_status) {
								case 0:
									pinStatus = "Huỷ ghim"
									break;

								case 1:
									pinStatus = "Ghim"
									break;

								default:
									break;
							}

							swal("Bạn có muốn " + pinStatus + " không?", {
									buttons: {
										cancel: "Bỏ qua",
										defeat: "Đồng ý",
									},
								})
								.then((value) => {
									switch (value) {

										case "defeat":
											$_token = $('#token').val();
											$.ajax({
												url: "{{url('/notification/update')}}",
												method: 'POST',
												dataType: 'json',
												data: {
													type: "pin",
													value: pin_status,
													id: userId,
													_token: $_token,
												},
												success: function(data) {
													$('#btn_edit_success').click();
													location.reload();
												},
												error: function(data) {
													console.log('Error:', data);
												}
											});
											break;

										default:
											break;
									}
								});
						}

						function myFunctionHidden(userName) {
							$("div[id*='myDropdownHidden']").each(function(i, el) {
								el.classList.remove("show");
							});
							document.getElementById("myDropdownHidden" + userName).classList.toggle("show");
						}

						function changeHiddenStatus(pin_status, userName, userId) {
							pinStatus = ""
							switch (pin_status) {
								case 0:
									pinStatus = "Ẩn"
									break;

								case 1:
									pinStatus = "Hiển thị"
									break;

								default:
									break;
							}

							swal("Bạn có muốn " + pinStatus + " không?", {
									buttons: {
										cancel: "Bỏ qua",
										defeat: "Đồng ý",
									},
								})
								.then((value) => {
									switch (value) {

										case "defeat":
											$_token = $('#token').val();
											$.ajax({
												url: "{{url('/notification/update')}}",
												method: 'POST',
												dataType: 'json',
												data: {
													type: "hidden",
													value: pin_status,
													id: userId,
													_token: $_token,
												},
												success: function(data) {
													$('#btn_edit_success').click();
													location.reload();
												},
												error: function(data) {
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
			
			<input type="hidden" id="user-id-delete">
			<input type="hidden" id="url" value="{{url('/users')}}">
			<input type="hidden" id="token" value="{{ csrf_token() }}">

		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		//Initialize Select2 Elements
		$('.js-notification-category-single').select2({
			minimumResultsForSearch: Infinity,
			dropdownCssClass:'notification-category-height',
			width: "100%"
		});

		jQuery('#date-range').datepicker({
			toggleActive: true,
			format: "dd-mm-yyyy",
			todayHighlight: true,
			language: "vi",
		});

		//Date range picker
		$('.input-daterange-datepicker-self').daterangepicker({
			buttonClasses: ['btn', 'btn-sm'],
			applyClass: 'btn-default',
			cancelClass: 'btn-white',
			minDate: moment().subtract(62, 'days'),
			// maxDate: today,
			locale: {
				format: "DD-MM-YYYY",
				language: "vi",
				separator: " / ",
				applyLabel: "Tiếp",
				cancelLabel: "Hủy",
				fromLabel: "From",
				toLabel: "To",
				"customRangeLabel": "Tùy chọn",
			},
			ranges: {
				'Hôm nay': [moment(), moment()],
				'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Tuần này': [moment().startOf('week'), moment().endOf('week')],
				'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
				// 'Cách đây 7 ngày': [moment().subtract(6, 'days'), moment()],
				// 'Cách đây 30 ngày': [moment().subtract(29, 'days'), moment()],
				'Tháng này': [moment().startOf('month'), moment().endOf('month')],
				'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
			// startDate: today,
			// endDate: today
		}, function(start, end, label) {
			//alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			// $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
		});

		$('.input-daterange-datepicker-target').daterangepicker({
			buttonClasses: ['btn', 'btn-sm'],
			applyClass: 'btn-default',
			cancelClass: 'btn-white',
			minDate: moment().subtract(62, 'days'),
			// maxDate: today,
			locale: {
				format: "DD-MM-YYYY",
				language: "vi",
				separator: " / ",
				applyLabel: "Tiếp",
				cancelLabel: "Hủy",
				fromLabel: "From",
				toLabel: "To",
				"customRangeLabel": "Tùy chọn",
			},
			ranges: {
				'Hôm nay': [moment(), moment()],
				'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Tuần này': [moment().startOf('week'), moment().endOf('week')],
				'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
				// 'Cách đây 7 ngày': [moment().subtract(6, 'days'), moment()],
				// 'Cách đây 30 ngày': [moment().subtract(29, 'days'), moment()],
				'Tháng này': [moment().startOf('month'), moment().endOf('month')],
				'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
			// startDate: today,
			// endDate: today
		}, function(start, end, label) {
			//alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			// $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
		});
	});

	$('#select_notification_category').on('select2:select', function (e) {
		var data = e.params.data;
		console.log(data);
	});

	$("#btn_view_by_filter_target").click(function() {
		// $('#mySelect2').trigger('select2:select');
		console.log($("#select_notification_category").val())
		var range = $('.input-daterange-datepicker-self').val().split('/');
		var startdate = range[0];
		var enddate = range[1];
		console.log(startdate + " - " + enddate)
		// window.location.href = "/notification/list?startdate="+startdate+"&enddate="+enddate+"&category="+$("#select_notification_category").val();
		$.ajax({
			url: "/notification/filter",
			method: 'GET',
			dataType: 'json',
			data: {
				category: $("#select_notification_category").val(),
				startdate: startdate,
				enddate: enddate,
				// _token: $_token,
			},
			complete: function(data) {
				console.log(data.responseText)
				// htmlUpdate = "";
				// // $("#reloadGetSeflActiveHistory").html($htmlUpdate);
				// for (let i = 0; i < data.length; i++) {
				// 	const element = data[i];
				// 	htmlItem = '<tr>'

				// 	htmlItem += '<td class="text_center">' + element.created_at + '</td>'
				// 	htmlItem += '<td class="text_center">' + element.content + '</td>'

				// 	htmlItem += '</tr>'

				// 	htmlUpdate += htmlItem
				// }

				// console.log(htmlUpdate)
				$("#reloadGetSeflActiveHistory").html(data.responseText);
			}
		});

	})

</script>
@endsection

@section('js_call')
<script src="/assets/admin/js/user.js?v=1.000111"></script>
@endsection