@extends('admin.admin-template')

@section('content')

<style>
						.line-break {
							padding-top: 10px;
							padding-bottom: 5px;
							/* border-bottom: 1px solid rgba(0, 0, 0, 0.05);
							*/
							border: 1px solid rgba(0, 0, 0, 0.1);
						}
						.title-sub-card{
							font-size: 13px;
							font-weight: 600;
							text-decoration: solid underline purple 1px;
						}

						.card-text-line-break{
							padding-left: 10px;
							padding-top: 5px;
							word-wrap: break-word; white-space: -moz-pre-wrap; white-space: pre-wrap;
						}
					</style>

<div class="row">
	<div class="col-sm-12">
		<div class="portlet"><!-- /primary heading -->
			<div class="portlet-heading">
				<h3 class="portlet-title text-dark text-uppercase">

					Hoạt động liên quan cá nhân

				</h3>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<div class="card-box">
	<div class="row">
		<div class="col-xs-12">
			<?php

			use App\Helpers\HistoryHelpers;
			use Illuminate\Support\Facades\Auth;

			$seflactiveall = HistoryHelpers::GetAllActiveHistoryByUser(Auth::user(), date('Y-m-d'), date('Y-m-d'));
			?>
			<!-- <div class="row" hidden bis_skin_checked="1">
				<div class="col-sm-5 col-xs-5" style="min-width: 120px;" bis_skin_checked="1">
					<input class="form-control input-startdate-datepicker" type="text" name="daterange" value="06-01-2024" readonly="readonly" control-id="ControlID-3">
				</div>

				<div class="col-sm-5 col-xs-5" style="min-width: 120px;" bis_skin_checked="1">
					<input class="form-control input-enddate-datepicker" type="text" name="daterange" value="06-01-2024" readonly="readonly" control-id="ControlID-4">
				</div>
				<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
					<span class="input-group-btn">
						<a style="margin-right:5px;" href="#" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter_self">Xem</a>
					</span>

				</div>
			</div> -->
			<div class="row">
				<div class="col-sm-8 col-xs-8" bis_skin_checked="1">
					<input class="form-control column_filter input-daterange-datepicker-self" type="text" name="daterange" value="" readonly="readonly">
				</div>

				<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
					<span class="input-group-btn">
						<a style="margin-right:5px;" href="#" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter_self">Xem</a>
					</span>

				</div>
			</div>
			<br />

			<div id="reloadGetSeflActiveHistory" bis_skin_checked="1">
				@foreach($seflactiveall as $active)

				<div class="col-lg-12 line-break" style="text-align: left !important;background: #3f86c3; color:white;" bis_skin_checked="1">
					<label style="display: flex; align-items: center;">{{$active->created_at}}
						<div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">
							<em style="font-weight:500; font-size:12px;"></em>
						</div>
					</label>
				</div>
				<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
					<div style="text-align: left;" bis_skin_checked="1">{{$active->content}}
					</div>

				</div>
				<br><br>
				@endforeach
				<span>Tìm thấy <mark>{{count($seflactiveall)}}</mark> hoạt động. Bạn đang ở trang 1 trên tổng số 1 trang</span>
			</div>

		</div>

	</div>
</div>

<script>
	$(document).ready(function() {
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

	$("#btn_view_by_filter_self").click(function() {
		var range = $('.input-daterange-datepicker-self').val().split('/');
		var startdate = range[0];
		var enddate = range[1];
		console.log(startdate + " - " + enddate)
		$.ajax({
			url: "/admin/hoatdongcanhan",
			method: 'GET',
			dataType: 'json',
			data: {
				// user_create: gamecode,
				startdate: startdate,
				enddate: enddate,
				// _token: $_token,
			},
			success: function(data) {
				htmlUpdate = "";
				// $("#reloadGetSeflActiveHistory").html($htmlUpdate);
				for (let i = 0; i < data.length; i++) {
					const element = data[i];

					htmlItem = '<div class="col-lg-12 line-break" style="text-align: left !important;background: #3f86c3; color:white;" bis_skin_checked="1">'
					htmlItem += '<label style="display: flex; align-items: center;">'+element.created_at
					htmlItem += '<div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">'
					htmlItem += '<em style="font-weight:500; font-size:12px;">'+''+'</em>'
					htmlItem += '</div>'
					htmlItem += '</label>'
					htmlItem += '</div>'
					htmlItem += '<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">'
					htmlItem += '<div style="text-align: left;" bis_skin_checked="1">'+element.content
					htmlItem += '</div>'
					htmlItem += '</div>'
					htmlItem += '<br><br>'

					htmlUpdate += htmlItem
				}

				// console.log(htmlUpdate)
				$("#reloadGetSeflActiveHistory").html(htmlUpdate);
			}
		});

	})

	$("#btn_view_by_filter_target").click(function() {
		var range = $('.input-daterange-datepicker-target').val().split('/');
		var startdate = range[0];
		var enddate = range[1];
		console.log(startdate + " - " + enddate)
		$.ajax({
			url: "/admin/hoatdongqly",
			method: 'GET',
			dataType: 'json',
			data: {
				// user_create: gamecode,
				startdate: startdate,
				enddate: enddate,
				// _token: $_token,
			},
			success: function(data) {
				htmlUpdate = "";
				// $("#reloadGetSeflActiveHistory").html($htmlUpdate);
				for (let i = 0; i < data.length; i++) {
					const element = data[i];
					htmlItem = '<tr>'

					htmlItem += '<td class="text_center">' + element.created_at + '</td>'
					htmlItem += '<td class="text_center">' + element.content + '</td>'

					htmlItem += '</tr>'

					htmlUpdate += htmlItem
				}

				// console.log(htmlUpdate)
				$("#reloadGetTargetActiveHistory").html(htmlUpdate);
			}
		});

	})
</script>

@endsection