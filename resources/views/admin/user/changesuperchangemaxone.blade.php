<?php
$customertypes = UserHelpers::GetCustomertype();
$user = Auth::user();
?>

<div id="full-width-modal-maxone" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <?php
							$roleChild = 0;
							switch ($user_current->roleid) {
								case 1: $roleChild = 2; break;
								case 2: $roleChild = 4; break;
								case 4: $roleChild = 5; break;
								case 5: $roleChild = 6; break;
								
								default:
									# code...
									break;
							}
						?>
                <h4 class="modal-title" id="full-width-modalLabel">Cài đặt giới hạn cược {{XoSoRecordHelpers::GetRoleName($roleChild)}} <span class="" id="currentusername_maxone">Sjk3101</span></h4>
            </div>
            <div class="modal-body">
                <div class="row" >
                    <div class="col-lg-12" style="text-align: center !important;">
                        <span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
                    </div>
                </div>
                <div class="row port" >
                    <div class="portfolioContainer m-b-15">
                            <div class="col-sm-12 col-lg-12 col-md-12 type_content" id="supermaxone">
                            </div>
                    </div>
                </div>
                @if (Session::get('usersecondper') == 11)
                @else
                <!-- <div class="row" >
                    <div class="col-lg-12">
                        <button type="button" id="btn_OK" onclick="SaveChangeAllTypeByUser()" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button> 
                    </div>
                </div> -->
                @endif
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> -->
                <!-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</
                button> -->
                <input type="hidden" id="urlUserpercent" value="{{url('/customer-type')}}">
                <input type="hidden" id="currentuserid" value="">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Chỉnh sửa thành công')"></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- <script src="/assets/admin/js/controlmax.js?v=1.011111"></script> -->
    <script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>

<script type="text/javascript">

    $( document ).ready(function() {
        // LoadContentGame('A');
        // LoadContentGame('B');
        // LoadContentGame('C');
    });


    function LoadContentGameMaxone(id) {
        $('.refresh').show();
        changes = [];
        // $('.type_content').fadeOut();
        $('#supermaxone').fadeOut();
        $('#supermaxone').load("/control-max/super-change-maxone/"+id, function() {
            $('#supermaxone').fadeIn();
            $('.refresh').hide();
        });
    }

    function showModalUserPercentMaxone(id,name) {
        var content = $('#buttonwithuserid').html().replace('userid',id).replace('userid',id).replace('userid',id);
        // $('full-width-modal').focus();
        // alert(document.getElementById("currentuserid").value);
        document.getElementById("currentusername_maxone").textContent = name;
        $('#currentuserid').val(id);
        LoadContentGameMaxone(id);
        // document.getElementById("currentuserid").value = id;
        // LoadContentGameByUser('A',id);
        // LoadContentGameByUser('B',id);
        // LoadContentGameByUser('C',id);
        // LoadContentGameByUser('D',id);
        
        // $('#buttonwithuserid').html(id);
        // $('.autonumber').autoNumeric('init');

        // $('#user_edit_id').val(id);


        // $('#fullname_edit').val(fullname);
        // var value = credit;

        // var num = Number(credit.replace(/[^0-9\.]+/g,""));
        // $('#credit_edit_hidden').val(num);
        // $('#credit_edit').val(credit);
        // var num = Number(consumer.replace(/[^0-9\.]+/g,""));
        // $('#consumer_edit_hidden').val(num);
        // $('#consumer_edit').val(consumer);
        // var num = Number(remain.replace(/[^0-9\.]+/g,""));
        // $('#remain_edit_hidden').val(num);
        // $('#remain_edit').val(remain);
        // $('#role_edit').val(roleid).change();
        // $('#customer_type').val(customer_type).change();
        // if(lock==="1")
        // {
        //     if(!$('#lock_edit').is(":checked"))
        //         $('.switchery').click();
        // }
    }
</script>