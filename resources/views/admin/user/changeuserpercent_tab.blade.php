<?php
$customertypes = UserHelpers::GetCustomertype();
$user = Auth::user();
?>

<div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
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
                <h4 class="modal-title" id="full-width-modalLabel">Cài đặt giá mua cho {{XoSoRecordHelpers::GetRoleName($roleChild)}} <span class="" id="currentusername">Sjk3101</span></h4>
            </div>
            <div class="modal-body">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php $first = true; ?>
                    @foreach($customertypes as $type)
                        <li id="li_tab_{{$type['code']}}" class="@if($first) active @endif" @if(isset($user_current->customer_type) && $user_current->roleid == 5 && $user_current->customer_type != $type) hidden @endif ><a href="#tab_{{$type['code']}}" data-toggle="tab" aria-expanded="@if($first) true @endif">Chuẩn {{$type['code']}}</a></li>
                        <?php if ($user_current->roleid != 5)$first = false; ?>
                    @endforeach
                </ul>
                <div class="tab-content" style="padding:unset;">
                <?php $first = true; ?>
                    @foreach($customertypes as $type)
                    <div class="tab-pane @if($first) active @endif" id="tab_{{$type['code']}}">
                            <div class="row">
                                <div class="col-sm-12 col-lg-12 col-md-12 {{$type['code']}} type_content" id="{{$type['code']}}">
                                </div>
                            </div>
                    </div>
                    <?php if ($user_current->roleid != 5)$first = false; ?>
                    @endforeach
                </div>
            </div>

                <div class="row" style="text-align: center">
                    <div class="col-lg-12 col-md-12 col-sm-12" id="buttonwithuserid">
                        <div class="portfolioFilter">
                            <!-- @foreach($customertypes as $type)
                                <a onclick="LoadContentGame('{{$type['code']}}','userid')" data-filter=".{{$type['code']}}">{{$type['name']}}</a>
                            @endforeach -->
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-lg-12" style="text-align: center !important;">
                        <span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
                    </div>
                </div>
                <div class="row port" >
                    <div class="portfolioContainer m-b-15">
                        @foreach($customertypes as $type)
                            
                        @endforeach
                    </div>
                </div>
                @if (Session::get('usersecondper') == 11)
                @else
                <div class="row" >
                    <div class="col-lg-12">
                        <button type="button" id="btn_OK" onclick="SaveChangeAllTypeByUser()" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button> 
                    </div>
                </div>
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


<!-- customertype.js -->
<script src="/assets/admin/js/customertype.js?v=0.1"></script>


    <script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>

<script type="text/javascript">

    $( document ).ready(function() {
        // LoadContentGame('A');
        // LoadContentGame('B');
        // LoadContentGame('C');
    });


    function showModalUserPercent(id,name,roleid,customer_type) {
        var content = $('#buttonwithuserid').html().replace('userid',id).replace('userid',id).replace('userid',id);
        // $('full-width-modal').focus();
        // alert(document.getElementById("currentuserid").value);
        document.getElementById("currentusername").textContent = name;
        $('#currentuserid').val(id);
        // document.getElementById("currentuserid").value = id;
        LoadContentGameByUser('A',id);
        LoadContentGameByUser('B',id);
        LoadContentGameByUser('C',id);
        LoadContentGameByUser('D',id);

        if (roleid == 6){
            $('#li_tab_A').hide();
            $('#li_tab_B').hide();
            $('#li_tab_C').hide();
            $('#li_tab_D').hide();
        }else{
            $('#li_tab_A').show();
            $('#li_tab_B').show();
            $('#li_tab_C').show();
            $('#li_tab_D').show();
        }
        
        
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