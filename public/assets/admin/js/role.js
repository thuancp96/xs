/**
 * Created by AnNH8 on 9/23/2016.
 */
/**
 * Create User Load
 */

$(document).ready(function() {
    //$('.btn_role')[0].click();
});
function setRoleId(id) {
    $('#role-id-delete').val(id);
}
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