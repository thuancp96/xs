@extends('admin.adminlte_template')
@section('title', 'Quản lí quyền')
@section('content')
    @include('admin.role.newrole',['chucnangs'=>$chucnangs])
    @include('admin.role.changerole',['chucnangs'=>$chucnangs])
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Quản lí quyền
					</h3>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="table table-bordered table-striped dataTable">
				<div class="row">
					<div class="col-md-3">
                        <div class="row">
                            <div class="col-sm-4">
                                <a class="btn btn-default btn-md waves-effect waves-light m-b-30" data-toggle="modal" data-target="#create-role-modal"><i class="md md-add"></i>Thêm mới quyền</a>
                            </div>
                        </div>
                        <div id="roles">
                        @foreach($roles as $role)
                            <div class="btn-group m-b-20">
                                <button type="button" style="width: 100px" class="btn btn-white waves-effect btn_role" onclick="loadtreefunction('{{$role->id}}')">{{$role->name}}</button>
                                <a href="#" class="btn btn-white waves-effect"  data-toggle="modal" data-target="#edit-role-modal" onclick="loadEditRole('{{$role->id}}','{{$role->name}}','{{$role->functions}}')"><i class="md md-edit"></i></a>
                                <a href="#" class="btn btn-white waves-effect btn_delete_role hidden" onclick="setRoleId('{{$role->id}}')"><i class="md md md-close"></i></a>
                            </div>
                        @endforeach
                        </div>
					</div>
					<div class="col-md-9 ">
                        <div class="card-box">
                            <div class="col-md-6">
                                <h4>Danh mục chức năng </h4>
                            </div>
                            <div id="tree_function">
                            @foreach ($chucnangs as $chucnang)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="checkbox checkbox-primary">
                                            <input id="{{$chucnang['code']}}" type="checkbox" disabled>
                                            <label for="checkbox2">
                                               {{$chucnang['name']}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($chucnang['children'] as $item)
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <div class="form-group">
                                                <div style="padding-left: 20px">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="{{$item['code']}}" type="checkbox" disabled>
                                                        <label for="checkbox2">
                                                            {{$item['name']}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <input type="hidden" id="role-id-delete">
    <input type="hidden" id="url_role" value="{{url('/role')}}">
    <input type="hidden" id="token_role" value="{{ csrf_token() }}">
    <script type="text/javascript">
        function loadtreefunction(id) {
            $('#tree_function').fadeOut();
            $('#tree_function').load("{{url('/role/load-tree-function')}}"+"/"+id+"/"+"load", function() {
                $('#tree_function').fadeIn();
            });
        }
        function reloadrole() {
            $('#roles').fadeOut();
            $('#roles').load("{{url('/role/reload-role')}}", function() {
                $('#roles').fadeIn();
            });
        }
    </script>
@endsection
@section('js_call')
    <script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
    <script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script> 
    <!-- <script src="/assets/admin/js/user.js"></script> -->
    <script src="/assets/admin/js/role.js"></script>
@endsection

