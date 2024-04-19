<div id="edit-role-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Thêm mới quyền</h4>
            </div>
            <form id="create-role-form" data-parsley-validate novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="field-1" class="control-label">Tên quyền</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" id="rolename_edit" name="rolename_edit" class="form-control" readonly onfocus="this.removeAttribute('readonly');" placeholder="Hãy nhập tên quyền" required data-parsley-error-message="Bạn chưa nhập tên quyền" data-parsley-trigger="keyup">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-box" style="overflow: scroll; overflow-x: hidden!important;height: 400px" >
                                <div class="col-md-6">
                                    <h4>Danh mục chức năng </h4>
                                </div>
                                <div id="tree_function_edit">
                                @foreach ($chucnangs as $chucnang)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="checkbox checkbox-primary">
                                                <input id="{{$chucnang['code']}}" type="checkbox" onchange="ClickEditFunctions(this,'{{$chucnang['code']}}')">
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
                                                            <input id="{{$item['code']}}" type="checkbox" onchange="ClickEditFunctions(this,'{{$item['code']}}')">
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
                <div class="modal-footer">
                    <ins data-dismiss="modal">Đóng</ins>
                    <button type="button" id="btn_Save_Edit_Role" class="btn btn-info waves-effect waves-light">Lưu</button>
                    <input type="hidden" id="sa-success">
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="hd_functions_edit">
<input type="hidden" id="roleid_edit">
<a id="btn_checkrole" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Đã tồn tại tài khoản trên')"></a>
<a id="btn_change_role_success" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Cập nhật thành công')"></a>
<script type="text/javascript">
    function loadEditRole(id,name,functions) {
        $('#rolename_edit').val(name);
        $('#roleid_edit').val(id);
        $('#hd_functions_edit').val(functions);
        $('#tree_function_edit').fadeOut();
        $('#tree_function_edit').load("{{url('/role/load-tree-function')}}"+"/"+id+"/"+"edit", function() {
            $('#tree_function_edit').fadeIn();
        });
    }
    function ClickEditFunctions(cb,code) {
        var t = $('#hd_functions_edit').val();
        if(cb.checked)
        {
            t +=","+code+",";
        }
        else
        {
            var key = ','+code+",";
            var re = new RegExp(key, 'g');
            t = t.replace(re, ',');
        }
        $('#hd_functions_edit').val(t);
    }
    function Save_Edit_Role() {
        var flag = false;
        if (true === $('#rolename_edit').parsley().validate()) {
            flag = true;
        }
        else
        {
            flag = false;
        }

        if(flag)
        {
            var id = $('#roleid_edit').val();
            var key = ',,';
            var re = new RegExp(key, 'g');
            t = $('#hd_functions_edit').val().replace(re, ',');
            $_token = "{{ csrf_token() }}";
            $.ajax({
                url: "{{url('/role/update')}}"+"/"+id,
                method: 'POST',
                dataType: 'html',
                data: {
                    rolename: $('#rolename_edit').val(),
                    function: t,
                    _token: $_token,
                },
                success: function(data)
                {
                    console.log('Data:', data);
                    $('#btn_change_role_success').click();
                    $('.close').click();
                    reloadrole();
                    loadtreefunction(id);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    }
    $("#btn_Save_Edit_Role" ).click(function() {
        Save_Edit_Role();
    });
</script>
