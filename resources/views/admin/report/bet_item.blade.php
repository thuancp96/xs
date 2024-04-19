<style>
    .checkbox input[type="checkbox"] {
        cursor: pointer;
        opacity: 1;
        z-index: 1;
        outline: none !important;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="portlet">
            <!-- /primary heading -->
            <div class="portlet-heading">
                <h3 class="portlet-title text-dark text-uppercase">
                    @if ($type_page == 'winlose')
                    Hội viên thắng thua
                    @endif

                    @if ($type_page == 'cxl')
                    Bảng cược chưa xử lý
                    @endif
                    @if ($type_page == 'cancel')
                    Bảng huỷ cược
                    @endif

                    <?php
							$roleChild = 0;
							switch ($userTarget->roleid) {
								case 1: $roleChild = 2; break;
								case 2: $roleChild = 4; break;
								case 4: $roleChild = 5; break;
								case 5: $roleChild = 6; break;
								
								default:
									# code...
									break;
							}
						?>
						{{XoSoRecordHelpers::GetRoleName($roleChild)}}
                </h3>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php
$user_current = Auth::user();
?>

@if($user_current->id != $userTarget->id)
	<div class="row">
        <div class="col-sm-12">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading" style="display: flex;
        align-items: flex-start !important;
        flex-direction: column;">
                    <label class="portlet-title text-dark text-uppercase" style="font-size:13px;">
                    <a onclick="window.history.back();" class="">< Quay về</a>
                    </label>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
	</div>
	@endif
<style>

.input-group .form-control:last-child,.input-group-addon:last-child,.input-group-btn:first-child>.btn-group:not(:first-child)>.btn,.input-group-btn:first-child>.btn:not(:first-child),.input-group-btn:last-child>.btn,.input-group-btn:last-child>.btn-group>.btn,.input-group-btn:last-child>.dropdown-toggle {
        width: 150px;
}

    </style>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box" 
        @if($user_current->id != $userTarget->id)
                            hidden
        @endif>
            <div class="row">
                <div class="col-sm-6 col-xs-6" style="min-width: 120px;width: 170px;">
                    <input class="form-control input-startdate-datepicker" type="text" name="daterange" value="{{$stDate}}" readonly="readonly">
                </div>

                <div class="col-sm-6 col-xs-6" style="min-width: 120px;max-width: 170px;">
                    <input class="form-control input-enddate-datepicker" type="text" name="daterange" value="{{$endDate}}" readonly="readonly">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-6 col-xs-6" style="min-width: 120px;width: 170px;">
                    <div class="input-group-btn">
                            <!-- <strong>Lọc theo loại:</strong> -->
                            <select id="multiple-checkboxes-cate" multiple="multiple">
                                <option value="xoso">Xổ số</option>
                                <option value="7zball">7zball</option>
                                <option value="minigame">minigame</option>
                            </select>
                        </div>
                </div>

                <div class="col-sm-6 col-xs-6" style="min-width: 120px;max-width: 170px;">
                
                <span class="input-group-btn">
                        <button type="button" style="height: 38px;width: 32px;width:115px;" class="btn waves-effect waves-light btn-primary" data-toggle="dropdown">
                        <label id="quickDatePicker">Chọn ngày</label>
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:void(0)" id="btn_homnay_sk">Hôm nay</a></li>
                            <li><a href="javascript:void(0)" id="btn_homqua_sk">Hôm qua</a></li>
                            <li><a href="javascript:void(0)" id="btn_tuannay_sk">Tuần này</a></li>
                            <li><a href="javascript:void(0)" id="btn_tuantruoc_sk">Tuần trước</a></li>
                            <li><a href="javascript:void(0)" id="btn_thangnay_sk">Tháng này</a></li>
                            <li><a href="javascript:void(0)" id="btn_thangtruoc_sk">Tháng trước</a></li>
                        </ul>

                        @if ($type_page == 'winlose')
                        <a style="margin-right:5px;height: 38px; width:38px;border-left: 1px solid #eee !important;" href="{{url('/rp/winlose?stdate='.$stDate.'&enddate='.$endDate)}}" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter"><i class="fa fa-search"></i></a>
                        @endif

                        @if ($type_page == 'cxl')
                        <a style="margin-right:5px;height: 38px;width:38px;" href="{{url('/rp/bettoday?stdate='.$stDate.'&enddate='.$endDate)}}" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter">Xem</a>
                        @endif
                        @if ($type_page == 'cancel')
                        <a style="margin-right:5px;height: 38px;width:38px;" href="{{url('/rp/betcancel?stdate='.$stDate.'&enddate='.$endDate)}}" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter">Xem</a>
                        @endif
                    </span>
                </div>

            </div>
                @if($user_current->id != $userTarget->id && false)
                <!-- <div class="row"> -->
                <div class="col-sm-1 col-xs-1">
                    <a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light"><i class="md md-keyboard-return"></i>Trở lại</a>
                </div>
                @endif
                
            </div>
            <!-- <div class="row" style="margin-top: 15px;">
				<div class="col-sm-2 col-xs-2">
					<div class="">
						<select id="multiple-checkboxes-cate" multiple="multiple">
							<option value="xoso">Xổ số</option>
							<option value="bbin">BBIN</option>
						</select>
					</div>
				</div>
			</div> -->
            

            <div class="row hidden">

                <div class="col-sm-10" style=" left: -10px">
                    <div class="col-sm-4">
                        <input class="form-control column_filter input-daterange-datepicker" type="text" name="daterange" value="" readonly="readonly">
                    </div>
                    <div class="col-sm-3 hidden ">
                        <div class="form-group">
                            <label class="col-sm-3 nopadding control-label datelabel" for="field-1">Từ</label>
                            <div class="col-sm-9 nopadding">
                                <input type="text" class="form-control column_filter" value="{{$stDate}}" id="datepicker-ngaybatdaudatcuoc" style="height: 30px !important;">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 hidden">
                        <div class="form-group">
                            <label class="col-sm-3 nopadding control-label datelabel" for="field-1">Đến</label>
                            <div class="col-sm-9 nopadding">
                                <input type="text" class="form-control column_filter" value="{{$endDate}}" id="datepicker-ngayketthucdatcuoc" style="height: 30px !important;">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <!-- <a href="{{url('/rp/betcancel/'.$stDate.'/'.$endDate)}}" class="btn btn-danger" id="btn_view_by_filter">Xem</a> -->
                        <!-- <button class="btn btn-radius btn-xs btn-blue btn_submit">Xác nhận</button> -->
                        <!-- <button class="btn btn-radius btn-xs today btn-white">Hôm nay</button>
							<button class="btn btn-radius btn-xs btn-white yesterday">Hôm qua</button>
							<button class="btn btn-radius btn-xs btn-white thisweek">Tuần này</button>
							<button class="btn btn-radius btn-xs btn-white lastweek">Tuần trước</button>
							<button class="btn btn-radius btn-xs btn-white thismonth">Tháng này</button>
							<button class="btn btn-radius btn-xs lastmonth btn-turquoise">Tháng trước</button> -->
                    </div>
                </div>
                @if($user_current->id != $userTarget->id)
                <!-- <div class="row"> -->
                <div class="col-sm-2">
                    <a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light"><i class="md md-keyboard-return"></i>Trở lại</a>
                </div>
                @else
                <div class="col-sm-2">
                </div>
                @endif
                <!-- </div> -->
                
            </div>
            
        </div>
        
        @if($user_current->id == $userTarget->id)

        <div class="card-box">
            <div class="row">
                <div class="col-sm-6">
                    <div role="form">
                        <div class="contact-search">
                            <input type="text" id="input_search" class="form-control" value="{{isset($input_search)?$input_search:''}}" placeholder="Tìm kiếm">
                            <button type="button" class="btn btn-white" onclick="goSearchUserChild()"><i class="fa fa-search"></i></button>

                            <script>
                                $('#input_search').keyup(function(event) {
                                    if (event.keyCode == 13) {
                                        goSearchUserChild()
                                    }
                                });

                                function goSearchUserChild() {
                                    // $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
                                    searchUrl = $('#btn_view_by_filter').attr("href") + '&searchkey=' + $("#input_search").val()
                                    // console.log(searchUrl)
                                    window.location.href = searchUrl
                                }
                            </script>
                        </div> <!-- form-group -->
                    </div>
                </div>
            </div>

        </div>
        @endif

        <?php

        use App\Helpers\UserHelpers;
        use App\Helpers\XoSoRecordHelpers;
        use Illuminate\Support\Facades\URL;

        $listBreakCrumb = UserHelpers::buildBreadCrumbsUser($userTarget, 1);
        $currentURL = \Request::getRequestUri(); //URL::current();
        // print_r($listBreakCrumb);
        // print_r(\Request::getRequestUri());
        ?>
        @if($userTarget->id != Auth::user()->id)
        <div class="card-box">
            <div class="row">
                <div class="col-sm-6">
                    @for($i=count($listBreakCrumb)-1;$i>=0;$i--)
                    <a style="font-size:14px;" href="{{str_replace($userTarget->id,$listBreakCrumb[$i]['url'],$currentURL)}}">{{$listBreakCrumb[$i]['name']}} </a>
                    @if($i>0) > @endif
                    @endfor
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

<?php
$adminShow = !(isset($users) && count($users) > 0 && $users[0]->roleid == 2) || (isset($input_search));
// print_r($users);
// echo count($users);
// $users=[];
// $adminShow = true;//!(isset($users) && $users[0]->roleid == 2);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">

            <div class="table-rep-plugin" id="div_history">
                <div class="table-responsive">

                    <table id="datatable" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer table-hover">
                        <thead>
                            <tr>
                                <th rowspan="2">Tài khoản</th>
                                <!-- <th rowspan="2">Họ Tên/Role</th> -->
                                <!-- <th>Nhóm</th> -->
                                <!-- <th>Trạng thái</th> -->
                                <th colspan="3">Hội viên</th>
                                @if($adminShow)
                                <th colspan="2">Tài khoản cấp dưới</th>
                                @endif
                                <th>Công ty</th>
                                <!-- <th rowspan="2">Chi tiết</th> -->
                            </tr>
                            <tr>
                                <th>Đơn hàng</th>
                                <th>Tiền cược</th>
                                <th>Thắng/Thua</th>
                                <!-- <th>Cược hợp lệ</th> -->
                                @if($adminShow)
                                <th>Hoa Hồng 1</th>
                                <th>Hoa hồng 2</th>
                                @if($userTarget->roleid == 1)
                                <th>Tổng cộng</th>
                                @endif
                                @endif
                                <th>Thắng/Thua</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            ini_set('memory_limit', '-1');
                            $total1 = 0;
                            $total2 = 0;
                            $total3 = 0;
                            $total4 = 0;
                            $total5 = 0;
                            $total6 = 0;
                            $total7 = 0;
                            $total8 = 0;
                            $total9 = 0;
                            $stt = 0;

                            // echo $cacheTime;

                            $begin = new DateTime($stDate);
                            $end = new DateTime($endDate);
                            if ($end > (new DateTime()))
                                $end = new DateTime();
                            $end->modify('+1 day');

                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($begin, $interval, $end);

                            // foreach ($period as $dt) {
                            // 	echo $dt->format("l Y-m-d H:i:s\n");
                            // }
                            ?>

                            @foreach($users as $user)
                            <?php
                            $userReport = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                            ?>
                            @foreach($period as $dt)
                            <?php
                            $stDateTemp = $dt->format("d-m-Y");
                            $endDateTemp = $dt->format("d-m-Y");
                            if ($dt->format("Y-m-d") > date('Y-m-d')) {
                                // echo 'continue';
                                break;
                            }

                            // echo $endDateTemp.' ';
                            ?>

                            <?php
                            // $userchild = UserHelpers::GetAllUserChild($user);
                            // foreach ($userchild as $userC) {
                            # code...
                            // if ($user->id==1530) continue;
                            // if ($user->roleid == 2)
                            // 	$userReport = XoSoRecordHelpers::ReportSpAg($user, $stDate, $endDate, isset($type) ? $type : "all");
                            // if ($user->roleid == 4)
                            // 	$userReport = XoSoRecordHelpers::ReportAg($user, $stDate, $endDate, isset($type) ? $type : "all");
                            // if ($user->roleid == 5)
                            // 	$userReport = XoSoRecordHelpers::ReportTong($user, $stDate, $endDate, isset($type) ? $type : "all");
                            // if ($user->roleid == 6)
                            // 	$userReport = XoSoRecordHelpers::ReportKhach($user, $stDate, $endDate, isset($type) ? $type : "all");

                            // if ($user->roleid == 2)
                            // $userReport = XoSoRecordHelpers::ReportKhachv2($user, $stDate, $endDate, isset($type) ? $type : "all");
                            // if ($user->roleid == 4)
                            // 	$userReport = XoSoRecordHelpers::ReportAg($user, $stDate, $endDate, isset($type) ? $type : "all");
                            // if ($user->roleid == 5)
                            // 	$userReport = XoSoRecordHelpers::ReportTong($user, $stDate, $endDate, isset($type) ? $type : "all");
                            // if ($user->roleid == 6)
                            // 	$userReport = XoSoRecordHelpers::ReportKhach($user, $stDate, $endDate, isset($type) ? $type : "all");
                            // 	else
                            $cacheTime = env('CACHE_TIME_SHORT_SK', 0);
                            $endTimeStamp = strtotime($endDateTemp);
                            $endDateNewformat = date('Y-m-d', $endTimeStamp);
                            if ($endDateNewformat < date('Y-m-d', strtotime("yesterday")))
                                $cacheTime = 1440 * 30;
                            // echo $cacheTime.' '.$stDateTemp.' '.$endDateTemp.'-';
                            // if ($endDateNewformat == '2023-05-24')
                            // $userReportTemp = Cache::forget('XoSoRecordHelpers-ReportKhachv20230529'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type);
                            $userReportTemp = [];

                            if ($type_page == 'winlose')
                                $userReportTemp =
                                    // XoSoRecordHelpers::ReportKhachv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                                    Cache::remember('XoSoRecordHelpers-ReportKhachv20240325' . $user->id . '-' . $stDateTemp . '-' . $endDateTemp . '-' . $type, $cacheTime, function () use ($user, $stDateTemp, $endDateTemp, $type) {
                                        return  XoSoRecordHelpers::ReportKhachv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                                    });

                            if ($type_page == 'cxl')
                                $userReportTemp = Cache::remember('XoSoRecordHelpers-ReportKhachCXLv20240325' . $user->id . '-' . $stDateTemp . '-' . $endDateTemp . '-' . $type, $cacheTime, function () use ($user, $stDateTemp, $endDateTemp, $type) {
                                    return  XoSoRecordHelpers::ReportKhachCXLv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                                });

                            if ($type_page == 'cancel')
                                $userReportTemp = Cache::remember('XoSoRecordHelpers-ReportKhachCancelv20240325' . $user->id . '-' . $stDateTemp . '-' . $endDateTemp . '-' . $type, $cacheTime, function () use ($user, $stDateTemp, $endDateTemp, $type) {
                                    return  XoSoRecordHelpers::ReportKhachCancelv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                                });
                            // $userReport = XoSoRecordHelpers::ReportKhachv2($user, $stDate, $endDate, isset($type) ? $type : "all");

                            for ($i = 0; $i <= 13; $i++) {
                                $userReport[$i] += $userReportTemp[$i];
                            }

                            // $userReport
                            ?>
                            @endforeach

                            <?php
                            $urlClick = "";
                            if ($type_page == 'winlose')
                                $urlClick = url('/rp/winlose-detail?user=' . $user->id . '&stdate=' . $stDate . '&enddate=' . $endDate . '&type=' . $type);
                            if ($type_page == 'cxl')
                                $urlClick = url('/rp/bettoday-detail?user=' . $user->id . '&stdate=' . $stDate . '&enddate=' . $endDate . '&type=' . $type);

                            if ($type_page == 'cancel')
                                $urlClick = url('/rp/betcancel-detail?user=' . $user->id . '&stdate=' . $stDate . '&enddate=' . $endDate . '&type=' . $type);
                            ?>

                            <?php
                            if ($user->roleid == 2) {
                                $userReport[1] = ($userReport[1]); //-$userReport[5]-$userReport[8]);//-$tongReport[5]
                                $userReport[2] = ($userReport[2] + $userReport[5] + $userReport[8]); //+$tongReport[5]);
                            }
                            if ($user->roleid <= 4) {
                                $userReport[1] = ($userReport[1]); //-$userReport[4]-$userReport[7]);//-$tongReport[5]
                                $userReport[2] = ($userReport[2] + $userReport[4] + $userReport[7]); //+$tongReport[5]);
                            }
                            if ($user->roleid <= 5) {
                                $userReport[1] = ($userReport[1]); //-$userReport[3]-$userReport[6]);//
                                $userReport[2] = ($userReport[2] + $userReport[3] + $userReport[6]); //+$tongReport[5]);
                            }
                            if ($user->roleid <= 6) {
                                $userReport[1] = ($userReport[1]); //-$userReport[12]);//
                                $userReport[2] = ($userReport[2] + $userReport[12]); //+$tongReport[5]);
                            }
                            ?>
                            @if ($userReport[0] !=0)
                            <tr>


                                <?php
                                $stt++;
                                ?>

                                <td><a href="{{$urlClick}}" class="">{{$user->name}}
                                @if(isset($user->fullname) && $user->fullname!= "")
								<br>
								{{"@".$user->fullname}}
								@endif
                                </a></td>

                                <td class="text_right text-bold"> <a href="{{$urlClick}}" class="">{{number_format($userReport[0])}}</a> </td>

                                <td class="text_right text-bold" @if ($userReport[1]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{number_format($userReport[1])}}</a>
                                </td>

                                <td class="text_right text-bold"><a href="{{$urlClick}}" @if ($userReport[2]<0) style=" color:red;" @endif>{{number_format($userReport[2])}}</a></td>

                                <!-- <td class="text_right text-bold"><a href="{{$urlClick}}">{{number_format($userReport[11])}}</a></td> -->



                                @if ($user->roleid == 6)

                                @if ($userTarget->roleid == 4 && $user->user_create == $userTarget->id)
                                <td class="text_right text-bold"><a href="{{$urlClick}}" class="">{{number_format($userReport[7])}}</a></td>

                                <td class="text_right text-bold" @if ($userReport[3]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{number_format($userReport[4])}}</a>
                                </td>

                                <!-- <td class="text_right text-bold" @if ($userReport[3]<0) style=" color:red;" @endif>
									<a href="{{$urlClick}}" class="">{{number_format($userReport[4]+$userReport[7])}}</a>
								</td> -->
                                <td class="text_right text-bold"><a href="{{$urlClick}}" @if (0 - ($userReport[2] + $userReport[3] + $userReport[12] + $userReport[6])<0) style=" color:red;" @endif>{{number_format(0 - ($userReport[2] + $userReport[4] + $userReport[7]))}}</a></td>
                                @else
                                <td class="text_right text-bold"><a href="{{$urlClick}}" class="">{{number_format($userReport[6])}}</a></td>

                                <td class="text_right text-bold" @if ($userReport[3]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{number_format($userReport[3])}}</a>
                                </td>

                                <!-- <td class="text_right text-bold" @if ($userReport[3]<0) style=" color:red;" @endif>
									<a href="{{$urlClick}}" class="">{{number_format($userReport[3]+$userReport[6])}}</a>
								</td> -->
                                <td class="text_right text-bold"><a href="{{$urlClick}}" @if (0 - ($userReport[2] + $userReport[3] + $userReport[12] + $userReport[6])<0) style=" color:red;" @endif>{{number_format(0 - ($userReport[2] + $userReport[3] + $userReport[6]))}}</a></td>
                                @endif


                                @elseif ($user->roleid == 5)
                                <td class="text_right text-bold"><a href="{{$urlClick}}" class="">{{number_format($userReport[7])}}</a></td>

                                <td class="text_right text-bold" @if ($userReport[4]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{number_format($userReport[4])}}</a>
                                </td>

                                <!-- <td class="text_right text-bold" @if ($userReport[4]<0) style=" color:red;" @endif>
									<a href="{{$urlClick}}" class="">{{number_format($userReport[4]+$userReport[7])}}</a>
								</td> -->

                                <td class="text_right text-bold">
                                    <a href="{{$urlClick}}" @if (0 - ($userReport[2] + $userReport[4] + $userReport[7])<0) style=" color:red;" @endif>{{number_format(0 - ($userReport[2] + $userReport[4] + $userReport[7]))}}</a>
                                </td>

                                @elseif ($user->roleid == 4)
                                <td class="text_right text-bold"><a href="{{$urlClick}}" class="">{{number_format($userReport[8])}}</a></td>

                                <td class="text_right text-bold" @if ($userReport[5]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{number_format($userReport[5])}}</a>
                                </td>

                                <!-- <td class="text_right text-bold" @if ($userReport[5]<0) style=" color:red;" @endif>
									<a href="{{$urlClick}}" class="">{{number_format($userReport[5]+$userReport[8])}}</a>
								</td> -->

                                <td class="text_right text-bold">
                                    <a href="{{$urlClick}}" @if (0 - ($userReport[2] + $userReport[5] + $userReport[8])<0) style=" color:red;" @endif>{{number_format(0 - ($userReport[2] + $userReport[5] + $userReport[8]))}}</a>
                                </td>
                                <?php $total9 += (0 - ($userReport[2] + $userReport[5] + $userReport[8]))  / 100 * $user->thau; ?>
                                @elseif ($user->roleid == 2)
                                <?php $total9 += (0 - ($userReport[2] + $userReport[9] + $userReport[10]))  / 100 * $user->thau; ?>
                                @if($adminShow)
                                <!-- //hh -->
                                <td class="text_right text-bold"><a href="{{$urlClick}}" class="">{{number_format($userReport[10])}}</a></td>

                                <td class="text_right text-bold" @if ($userReport[9]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{number_format($userReport[9])}}</a>
                                </td>

                                <td class="text_right text-bold" @if ($userReport[9]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{number_format($userReport[9]+$userReport[10])}}</a>
                                </td>
                                @endif
                                <td class="text_right text-bold">
                                    <a href="{{$urlClick}}" @if (0 - ($userReport[2] + $userReport[9] + $userReport[10])<0) style=" color:red;" @endif>{{number_format(0 - ($userReport[2] + $userReport[9] + $userReport[10]))}}</a>
                                </td>
                                @else
                                <td class="text_right text-bold"><a href="{{$urlClick}}" class="">{{0}}</a></td>

                                <td class="text_right text-bold" @if ($userReport[9]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{0}}</a>
                                </td>

                                <td class="text_right text-bold" @if ($userReport[9]<0) style=" color:red;" @endif>
                                    <a href="{{$urlClick}}" class="">{{0}}</a>
                                </td>

                                <td class="text_right text-bold">
                                    <a href="{{$urlClick}}" @if (0 - ($userReport[2] + $userReport[9] + $userReport[10])<0) style=" color:red;" @endif>{{number_format(0 - $userReport[2])}}</a>
                                </td>
                                @endif
                            </tr>
                            @endif
                            <?php
                            $total1 += $userReport[0];
                            $total2 += $userReport[1];
                            $total3 += $userReport[2];
                            $total8 += $userReport[11];

                            if ($user->roleid == 6) { //tong
                                $total4 += $userReport[6];
                                $total5 += $userReport[3];
                                $total6 += $userReport[3] + $userReport[6];
                                $total7 += (0 - ($userReport[2] + $userReport[3] + $userReport[6]));
                            } else
								if ($user->roleid == 5) { //agent
                                $total4 += $userReport[7];
                                $total5 += $userReport[4];
                                $total6 += $userReport[4] + $userReport[7];
                                $total7 += (0 - ($userReport[2] + $userReport[4] + $userReport[7]));
                            } else
								if ($user->roleid == 4) { //sp agent
                                $total4 += $userReport[8];
                                $total5 += $userReport[5];
                                $total6 += $userReport[5] + $userReport[8];
                                $total7 += (0 - ($userReport[2] + $userReport[5] + $userReport[8]));
                            } else
								if ($user->roleid == 2) {
                                $total4 += $userReport[10];
                                $total5 += $userReport[9];
                                $total6 += $userReport[9] + $userReport[10];
                                $total7 += (0 - ($userReport[2] + $userReport[9] + $userReport[10]));
                            } else {
                                $total4 += 0;
                                $total5 += 0;
                                $total6 += 0;
                                $total7 += (0 - ($userReport[2] + 0));
                            }
                            // $total7 += (0 - ($userReport[2] + $userReport[4]));
                            ?>

                            @endforeach
                        </tbody>
                        <tfoot>
                            @if (isset($input_search))
                            @else
                            <tr>
                                <td colspan="1" class="text_right pr10">Tổng số</td>
                                <td class="text_right pr10 suminvoice text-bold" @if ($total1<0) style=" color:red;" @endif>{{number_format($total1)}}</td>
                                <td class="text_right pr10 suminvoice text-bold" @if ($total2<0) style=" color:red;" @endif>{{number_format($total2)}}</td>
                                <td class="text_right pr10 suminvoice text-bold" @if ($total3<0) style=" color:red;" @endif>{{number_format($total3)}}</td>
                                <!-- <td class="text_right pr10 suminvoice text-bold" @if ($total8<0) style=" color:red;" @endif>{{number_format($total8)}}</td> -->
                                @if($adminShow)
                                <td class="text_right pr10 suminvoice text-bold" @if ($total4<0) style=" color:red;" @endif>{{number_format($total4)}}</td>
                                <td class="text_right pr10 suminvoice text-bold" @if ($total5<0) style=" color:red;" @endif>{{number_format($total5)}}</td>
                                <!-- <td class="text_right pr10 suminvoice text-bold" @if ($total6<0) style=" color:red;" @endif>{{number_format($total6)}}</td> -->
                                @endif
                                <td class="text_right pr10 suminvoice text-bold" @if ($total7<0) style=" color:red;" @endif>{{number_format($total7)}}</td>

                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
            <input type="hidden" id="user-id-delete">
            @if ($type_page == 'winlose')
            <input type="hidden" id="url" value="{{url('/rp/winlose')}}">
            @endif

            @if ($type_page == 'cxl')
            <input type="hidden" id="url" value="{{url('/rp/bettoday')}}">
            @endif

            @if ($type_page == 'cancel')
            <input type="hidden" id="url" value="{{url('/rp/betcancel')}}">
            @endif


            <input type="hidden" id="token" value="{{ csrf_token() }}">
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var selected = "{{$type}}"
        $('#multiple-checkboxes-cate').multiselect({
            includeSelectAllOption: true,
            nSelectedText: 'selected',
            nonSelectedText: 'Tất cả Trò chơi',
            selectAllText: 'Tất cả Trò chơi',
            allSelectedText: 'Tất cả Trò chơi',
            selectAllValue: 'all',

            onChange: function(option, checked, select) {
                //alert("onchange") // Works but not for 'All Selected'
                // console.log(option[0].value)
                // console.log(checked)
                // console.log(select)
                // 
                // console.log(selected)
                if (checked) {
                    if (selected == "all" || selected == "xoso7zballminigame")
                        selected = ""
                    if (!selected.includes(option[0].value))
                        selected += option[0].value;
                }
                if (!checked) {
                    selected = selected.replace(option[0].value, "")
                    // if (option[0].value == 'xoso')
                    // 	selected = "bbin"
                    // if (option[0].value == 'bbin')
                    // 	selected = "xoso"

                }
                // console.log(1 + " " +selected)
                // if (selected == '' ) searchUrl = searchUrl.replace("&type=", "")
                searchUrl = $('#btn_view_by_filter').attr("href"); // + '/' + $("#input_search").val()

                // console.log(2 + " " +searchUrl)

                searchUrl = searchUrl.replace("&type=xoso7zballminigame", "")
                searchUrl = searchUrl.replace("&type=all", "")
                searchUrl = searchUrl.replace("&type=7zball", "")
                searchUrl = searchUrl.replace("&type=xoso", "")
                searchUrl = searchUrl.replace("&type=minigame", "")
                searchUrl = searchUrl.replace("&type=", "")
                searchUrl = searchUrl.replace("xoso", "")
                searchUrl = searchUrl.replace("7zball", "")
                searchUrl = searchUrl.replace("minigame", "")

                searchUrl += '&type=' + selected
                // console.log(3 + " " +searchUrl);
                $('#btn_view_by_filter').attr("href", searchUrl)
            },

            onSelectAll: function() {
                // console.log("call when select all");
                searchUrl = $('#btn_view_by_filter').attr("href"); // + '/' + $("#input_search").val()
                selected = "xoso7zballminigame"
                // console.log("all")
                 searchUrl = searchUrl.replace("&type=xoso7zballminigame", "")
                searchUrl = searchUrl.replace("&type=all", "")
                searchUrl = searchUrl.replace("&type=7zball", "")
                searchUrl = searchUrl.replace("&type=xoso", "")
                searchUrl = searchUrl.replace("&type=minigame", "")
                searchUrl = searchUrl.replace("&type=", "")
                searchUrl = searchUrl.replace("xoso", "")
                searchUrl = searchUrl.replace("7zball", "")
                searchUrl = searchUrl.replace("minigame", "")

                // searchUrl += '&type=' + 'all'
                // console.log(searchUrl);
                $('#btn_view_by_filter').attr("href", searchUrl)
            },
            onDeselectAll: function() {
                console.log("call when deselect all");
            }
        });
        // if ("all" == "{{$type}}"){
        // 	$('#demo').multiselect('selectAll', true);
        // }else
        arrayInitMulti = [];
        if ("{{$type}}".includes("7zball")) arrayInitMulti.push("7zball")
        if ("{{$type}}".includes("xoso")) arrayInitMulti.push("xoso")
        if ("{{$type}}".includes("minigame")) arrayInitMulti.push("minigame")

        $('#multiple-checkboxes-cate').multiselect('select', arrayInitMulti);
    });
</script>