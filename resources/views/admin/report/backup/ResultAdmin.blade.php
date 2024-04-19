@extends('admin.adminlte_template')
@section('title', 'Hội viên thắng thua')
@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Thắng thua chi tiết
					</h3>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<?php
			$user_current = Auth::user();
	?>
	@if($user_current->id != $user->id)
	<div class="row">
		<div class="col-sm-12">
			<a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light m-b-30"><i class="md md-keyboard-return"></i>Trở lại</a>
		</div>
	</div>
	@endif
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box">
				<div class="row">
					<div class="col-sm-8">
						<form role="form">
							<div class="form-group contact-search m-b-30">
								<input type="text" id="input_search" class="form-control" placeholder="Tìm kiếm tài khoản, tên, quyền,...">
								<button type="button" class="btn btn-white"><i class="fa fa-search"></i></button>
							</div> <!-- form-group -->
						</form>
					</div>
				</div>
				<div class="table-rep-plugin">
					<div class="table-responsive" style="overflow-x:hidden">
						<table id="datatable"  class="table table-hover mails m-0 table table-actions-bar">
							<thead>
							<tr>
								<th>#</th>
								<th>Tài khoản</th>
								<th>Tên</th>
								<th>Nhóm</th>
								<th>Trạng thái</th>
								<th>Tín dụng đã nạp</th>
								<th>Tín dụng đã chi</th>
								<th>Tín dụng còn lại</th>
								<th style="text-align: center">Khóa/Kích hoạt</th>
								<th style="text-align: center">Chi tiết</th>
							</tr>
							</thead>
							<tbody>
							@foreach($users as $userchild)
								<tr>
									<td></td>
									<td>{{$userchild->name}}</td>
									<td>{{$userchild->fullname}}</td>
									<td>
										@foreach($roles as $role)
											@if($role->id==$userchild->roleid)
												{{$role->name}}
											@endif
										@endforeach
									</td>
									<td>
									@if($userchild->roleid == 6)
										Chuẩn {{$userchild->customer_type}}
									@endif
									</td>
									<td style="text-align: center">{{ number_format($userchild->credit, 0)}}</td>
									<td style="text-align: center">{{ number_format($userchild->consumer, 0)}}</td>
									<td style="text-align: center">{{ number_format($userchild->remain, 0)}}</td>
									<td style="text-align: center">
										@if($userchild->lock==true)
											Bị khóa
										@else
											Đã kích hoạt
										@endif
									</td>
									<td style="text-align: center">
										<a href="{{url('/rp/winlose-detail/'.$userchild->id)}}" class="table-action-btn"><i class="md md-assignment"></i></a>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<input type="hidden" id="user-id-delete">
				<input type="hidden" id="url" value="{{url('/rp')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			</div>
		</div>
	</div>
@endsection

@section('js_call')
<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
	<script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>	
	<script src="/assets/admin/js/user.js"></script>
@endsection
