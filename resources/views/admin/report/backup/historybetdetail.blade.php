@extends('admin.admin-template')
@section('title', 'Thắng thua chi tiết')
@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Lịch sử cá cược
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
					<div class="col-sm-6">
						<div class="form-group contact-search m-b-30">
							<input type="text" id="input_search_history" class="form-control" placeholder="Tìm kiếm người đánh, loại đặt cược,số tiền... ">
							<button type="button" class="btn btn-white"><i class="fa fa-search"></i></button>
						</div> <!-- form-group -->
					</div>
					<div class="col-sm-2">
							<div class="form-group contact-search m-b-30">
								<input type="text" class="form-control column_filter" placeholder="Ngày đặt cược" id="datepicker-ngaydatcuoc">
								<button type="button" class="btn btn-white"><i class="fa fa-calendar"></i></button>
							</div>
					</div>
				</div>
				<div class="row">
					<div class="table-rep-plugin">
						<div class="table-responsive" style="overflow-x:hidden">
							<table id="table_winlose"  class="table table-hover mails m-0 table table-actions-bar">
								<thead>
								<tr>
									<th>#</th>
									<th>Người đánh/Tài khoản</th>
									<th>Loại đặt cược</th>
									<th>Ngày đặt cược</th>
									<th>Số đặt cược</th>
									<th>Tiền đặt cược/1 con</th>
									<th>Tổng số tiền đặt cược</th>
								</tr>
								</thead>
								<tbody>
								@foreach($xosorecords as $xosorecord)
									<tr>
										<td>#</td>
										<td>{{$xosorecord->fullname}}/{{$xosorecord->name}}</td>
										<td>{{$xosorecord->game}}</td>
										<td>{{date("d/m/Y", strtotime($xosorecord->date))}}</td>
										<td>{{$xosorecord->bet_number}}</td>
										<td>{{number_format($xosorecord->bet_money_per_number,0)}}</td>
										<td>{{number_format($xosorecord->total_bet_money,0)}}</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			</div>
		</div>
	</div>
@endsection
@section('js_call')
	<script src="/assets/admin/js/?v=1.01111"></script>
@endsection