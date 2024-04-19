@foreach($roles as $role)
	<div class="btn-group m-b-20">
		<button type="button" style="width: 100px" class="btn btn-white waves-effect btn_role" onclick="loadtreefunction('{{$role->id}}')">{{$role->name}}</button>
		<a href="#" class="btn btn-white waves-effect"  data-toggle="modal" data-target="#edit-role-modal" onclick="loadEditRole('{{$role->id}}','{{$role->name}}','{{$role->functions}}')"><i class="md md-edit"></i></a>
		<a href="#" class="btn btn-white waves-effect btn_delete_role hidden" onclick="setRoleId('{{$role->id}}')"><i class="md md md-close"></i></a>
	</div>
@endforeach

<script type="text/javascript">
	$('.btn_delete_role').click(function(){
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
				$_token = $('#token_role').val();
				$.ajax({
					url: $('#url_role').val()+"/destroy/"+$('#role-id-delete').val(),
					method: 'POST',
					dataType: 'json',
					data: {
						_token: $_token,
					},
					success: function(data)
					{
						if(data==true)
						{
							swal("Đã xóa!", "Bạn đã thành công", "success");
							reloadrole();
						}
						else
						{
							swal("Không thành công", "Có thành viên đang được phân quyền này nên không thể xóa", "error");
						}
					},
					error: function (data) {
					}
				});
			} else {

			}
		});
	});
</script>