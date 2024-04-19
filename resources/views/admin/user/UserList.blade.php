@extends('admin.adminlte_template')
@section('title', 'Quản lí người dùng')
@section('content')
	@include('admin.user.newuser',['roles' => $roles])
	@include('admin.user.changeuser',['roles' => $roles])
	@include('admin.user.changecredit')

	<div class="box">
		<div class="box-header">
		  <h3 class="box-title">
		  	<a class="btn btn-default" data-toggle="modal" data-target="#create-modal"><i class="md md-add"></i>Thêm mới</a>
		  </h3>

	      <div class="box-tools">
	        <div class="input-group input-group-sm" style="width: 150px;">
	          <input type="text" id="input_search" class="form-control pull-right" placeholder="Tìm kiếm tài khoản, tên, quyền,...">
	          <div class="input-group-btn">
	            <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
	          </div>
	        </div>
	      </div>
	    </div>

	    <div class="box-body table-responsive no-padding">
	    <div class="row">
					<div class="col-sm-12">
					<table id="datatable"  class="table table-bordered table-striped dataTable">
						<thead>
							<tr>
								{{--<th>#</th>--}}
								<th>Tài khoản</th>
								<th>Nhóm</th>
								<th>Tên</th>
								<th>Tín dụng</th>
								<th>Tín dụng đã dùng</th>
								<th>Số dư</th>
								<th>Trạng thái</th>
								<th style="text-align: center">Khóa/Kích hoạt</th>
								<th style="text-align: center">Chỉnh sửa</th>
							</tr>
						</thead>
						<tbody>
						@foreach($users as $user)
							<tr>
								{{--<td></td>--}}
								<td>{{$user->name}}</td>
								<td>
									@foreach($roles as $role)
										@if($role->id==$user->roleid)
											{{$role->name}}
										@endif
									@endforeach
								</td>
								<td>{{$user->fullname}}</td>
								<td style="text-align: center">{{ number_format($user->credit, 0)}}</td>
								<td style="text-align: center">{{ number_format($user->consumer, 0)}}</td>
								<td style="text-align: center">{{ number_format($user->remain, 0)}}</td>
								<td>Chuẩn {{$user->customer_type}}</td>
								<td style="text-align: center">
									@if($user->lock==true)
										Bị khóa
									@else
										Đã kích hoạt
									@endif
								</td>
								<td style="text-align: center">
									<a href="#" class="table-action-btn"  data-toggle="modal" data-target="#change_credit" onclick="showModalCredit_PG('{{$user->id}}','{{ number_format($user->remain, 0)}}')"><i class="md md-local-atm"></i></a>
									<a href="#" class="table-action-btn"  data-toggle="modal" data-target="#edit-modal" onclick="showModal('{{$user->id}}','{{$user->fullname}}','{{$user->lock}}','{{ number_format($user->credit, 0)}}','{{ number_format($user->consumer, 0)}}','{{ number_format($user->remain, 0)}}','{{$user->roleid}}','{{$user->customer_type}}')"><i class="md md-edit"></i></a>
									<a href="#" class="table-action-btn btn_delete" onclick="setId('{{$user->id}}')"><i class="md md-close"></i></a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					</div>
				</div>
				<input type="hidden" id="user-id-delete">
				<input type="hidden" id="url" value="{{url('/users')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			</div>
	</div>

	{{--<div class="row">
				<div class="col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-sm-8">
								<form role="form">
									<div class="form-group contact-search m-b-30">
										<input type="text" id="input_search" class="form-control pull-right" placeholder="Tìm kiếm tài khoản, tên, quyền,...">
										<button type="button" class="btn btn-white"><i class="fa fa-search"></i></button>
									</div> <!-- form-group -->
								</form>
							</div>
							<div class="col-sm-4">
								<a class="btn btn-default btn-md waves-effect waves-light m-b-30" data-toggle="modal" data-target="#create-modal"><i class="md md-add"></i>Thêm mới</a>
							</div>
						</div>
						
					</div>
				</div>
			</div>--}}
@endsection

@section('js_call')
	<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
	<script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>	
	<script src="/assets/admin/js/user.js"></script>
@endsection
