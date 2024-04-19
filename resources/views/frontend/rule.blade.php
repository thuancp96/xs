<?php
// $now = \Carbon\Carbon::now();
//     $yesterday = date('Y-m-d', time()-86400);
//     $datepickerXS= date('d-m-Y', time()-86400);
//     if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('M') )<30)){
//         $rs = xoso::getKetQua(1,$yesterday);
//     }
//     else{
//         $rs = xoso::getKetQua(1,date('Y-m-d'));
//         $datepickerXS= date('d-m-Y');
//     }
// $gameList = GameHelpers::GetAllGameByParentID(0);
?>
@extends("frontend.frontend-template")
@section('sidebar-menu')
	@parent
	
@stop
@section("content")
<style>
	li, ul {
    color: black;
}
.nav.nav-tabs + .tab-content {
  padding: 1px;
}
.panel-default > .panel-heading {
    color: white;
}
	</style>
<div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
		<div class="panel-body body-headcontent"> <div class="row scrollable ps-container ps-active-y" style="">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
						<li class="active">
							<a data-target="#rules_content" data-toggle="tab" aria-expanded="true"><span>Nội duy diễn giải</span></a>
						</li>
						<li class="">
							<a data-target="#rules_north_1" data-toggle="tab" aria-expanded="false"><span>Thể loại cược</span></a>
						</li>
						<li class="">
							<a data-target="#rules_keno" data-toggle="tab" aria-expanded="false"><span>CÁCH CHƠI KENO Vietlott </span></a>
						</li>
					</ul>
					<div class="tab-content">

					<div class="tab-pane" id="rules_keno">
					<!-- <link rel="stylesheet" type="text/css" href="https://keno.gamingon.net/public/css/rules.css"> -->
<div class="rulesWrap">
    
    <div class="rulesContent">
        <h6>Các dạng cược keno:</h6>
        <ol class="listSummary">
            <li>Tài/ Xỉu</li>
            <li>Lẻ/ Chẵn</li>
            <li>Rồng/ Hòa/ Hổ</li>
            <li>Bé/ Giữa/ Lớn</li>
            <li>Tài Lẻ/ Xỉu Lẻ/ Tài Chẵn/ Xỉu Chẵn</li>
            <li>Kim/ Mộc/ Thủy/ Hỏa/ Thổ</li>
        </ol>

        <h6>Cách tính</h6>

        <p>Kết quả Keno được tính dựa trên 20 quả bóng được rút ra ngẫu nhiên từ 80 quả bóng với các con số tương ứng.</p>
        <ol class="listDetail">
            <li><h6>Tài/ Xỉu</h6>
                <ul>
                    <li><strong>Tài:</strong> Tổng số của 20 quả bóng được rút ra >= 811.</li>
                    <li><strong>Xỉu:</strong> Tổng số của 20 quả bóng được rút ra =< 810.</li>
                </ul>
                <p>Ví dụ: 20 quả bóng được rút ra là: 10, 5, 2, 14, 13, 16, 20, 21, 35, 50, 52, 39, 29, 70, 61, 11, 71, 8, 33, 73. Tổng 20 số này là 633. Chọn Xỉu sẽ thắng.</p>
            </li>
            <li>
                <h6>Lẻ/ Chẵn</h6>
                <ul>
                    <li><strong>Lẻ:</strong> Tổng số của 20 quả bóng được rút ra là số lẻ.</li>
                    <li><strong>Chẵn:</strong> Tổng số của 20 quả bóng được rút ra là số chẵn.</li>
                </ul>
                <p>Ví dụ: 20 quả bóng được rút ra là: 10, 5, 2, 14, 13, 16, 20, 21, 35, 50, 52, 39, 29, 70, 61, 11, 71, 8, 33, 73. Tổng 20 số này là 633. Chọn Lẻ sẽ thắng.</p>
            </li>
            <li>
                <h6>Rồng/ Hòa/ Hổ</h6>
                <ul>   
                    <li><strong>Rồng:</strong> Là chữ số hàng chục của kết quả của 20 quả bóng được rút ra.</li>
                    <li><strong>Hổ:</strong> Là chữ số hàng đơn vị của kết quả của 20 quả bóng được rút ra.</li>
                    <li><strong>Hòa:</strong> Nếu Rồng = Hổ.</li>
                </ul>
                <p>Ví dụ: 20 quả bóng được rút ra là: 10, 5, 2, 14, 13, 16, 20, 21, 35, 50, 52, 39, 29, 70, 61, 11, 71, 8, 33, 79. Tổng 20 số này là 639. Ta so sánh chữ số hàng chục và hàng đơn vị của kết quả được rút ra, bên nào lớn hơn thì chiến thắng.</p>
                <p>+ Rồng là chữ số hàng chục: 3 </p>
                <p>+ Hổ là chữ số hàng đơn vị: 9 </p>
                <p>+ Chọn Hổ sẽ thắng.</p>
            </li>
            <li>
                <h6>Bé/ Giữa/ Lớn</h6>
                <ul>
                    <li><strong>Bé:</strong> Nếu trong 20 quả bóng được rút ra có hơn 10 con số trong khoản từ 1-40.</li>
                    <li><strong>Giữa:</strong> Nếu trong 20 quả bóng được rút ra có 10 con số trong khoản 1-40 và 10 con số trong khoản 41-80.</li>
                    <li><strong>Lớn:</strong> Nếu trong 20 quả bóng được rút ra có hơn 10 con số trong khoản 41-80.</li>
                </ul>
                <p>Ví dụ: 20 quả bóng được rút ra là: 10, 5, 2, 14, 13, 16, 20, 21, 35, 50, 52, 39, 29, 70, 61, 11, 71, 8, 33, 79. Ta có 1 4 con số trong khoản 1-40( 10, 5, 2, 14, 13, 16, 20, 21, 35, 39, 29, 11, 8, 33) và 6 số được rút ra trong khoản 41-80(50, 52, 70, 61, 71, 79). Chọn Trên sẽ thắng.</p>
            </li>
            <li>
                <h6>Tài Lẻ/ Xỉu Lẻ/ Tài Chẵn/ Xỉu Chẵn</h6>
                <ul>
                    <li><strong>Tài Lẻ:</strong> Tổng 20 quả bóng được rút ra >=811 và là con số lẻ. Ví dụ: 811, 813, 815, …</li>
                    <li><strong>Xỉu Lẻ:</strong> Tổng 20 quả bóng được rút ra =< 810 và là con số lẻ. Ví dụ: 789, 573, 639,…</li>
                    <li><strong>Tài Chẵn:</strong>  Tổng 20 quả bóng được rút ra >= 811 và là con số chẵn. Ví dụ: 812, 814, 852…</li>
                    <li><strong>Xỉu Chẵn:</strong>  Tổng 20 quả bóng được rút ra =< 810 và là con số chẵn. Ví dụ: 810, 798. 780,…</li>
                </ul>
                <p>Ví dụ: 20 quả bóng được rút ra là: 10, 5, 2, 14, 13, 16, 20, 21, 35, 50, 52, 39, 29, 70, 61, 11, 71, 8, 33, 79. Tổng 20 số này là 639. 639 nhỏ hơn 810 và là số lẻ. Chọn Xỉu Lẻ sẽ thắng.</p>
            <li>
                <h6>Kim/ Mộc/ Thủy/ Hỏa/ Thổ</h6>
                <ul>
                    <li><strong>Kim:</strong> Tổng 20 quả bóng được rút ra trong khoản 210-695.</li>
                    <li><strong>Mộc:</strong> Tổng 20 quả bóng được rút ra trong khoản 696-763.</li>
                    <li><strong>Thủy:</strong> Tổng 20 quả bóng được rút ra trong khoản 764-855.</li>
                    <li><strong>Hỏa:</strong> Tổng 20 quả bóng được rút ra trong khoản 856-923.</li>
            <li><strong>Thổ:</strong>  Tổng 20 quả bóng được rút ra trong khoản 924- 1410.</li>
            </ul>
            <p>Ví dụ: 20 quả bóng được rút ra là: 10, 5, 2, 14, 13, 16, 20, 21, 35, 50, 52, 39, 29, 70, 61, 11, 71, 8, 33, 79. Tổng 20 số này là 639. 639 trong khoản 210-695. Chọn Kim sẽ thắng.</p>
            </li>
        </ol>
    </div>
</div>
					</div>
						<div class="tab-pane active" id="rules_content">
							<div class="panel panel-default">
								<div class="panel-heading">
									<p style="margin-top:-10px;">Mô tả cách chơi</p>
								</div>
								<div class="panel-body">
									<div class="rull_inner">
										<ul>
											<li class="mdotb">Có thể luật của nơi bạn đang sống có quy định rằng cá cược trên mạng là bất hợp pháp; Nếu thật sự xảy ra tình trạng này, Công ty chúng tôi sẽ không chịu trách nhiệm về những sự cố mà khách hàng gặp phải.</li>
											<li class="mdotb">Nếu khách hàng nghi ngờ rằng dữ liệu cùa mình bị đánh cắp, nên thộng báo ngay cho Đại lý cấp trên hoặc Văn phòng Công ty để được hỗ trợ kịp thời tránh những thiệt hại đáng tiếc.</li>
											<li class="mdotb">Khách hàng có trách nhiệm bảo mật về tài khoản và thông tin đăng nhập của mình. Đối với những cá cược trực tuyến bằng tên người sử dụng và mật khẩu hợp lệ, thì Công ty sẽ coi là có hiệu lực.</li>
											<li class="mdotb">Khi phát hành tỷ lệ cược mà xảy ra bất kỳ lỗi đánh máy hoặc lỗi không chủ ý của con người, bất kể trước hoặc sau khi có kết quả mở thưởng, Công ty có quyền xoá hoặc huỷ bỏ những mã cược bị lỗi ở bất cứ lúc nào và thông báo qua Thông báo điện tử (Công ty sẽ không thông báo cho từng cá nhân).</li>
											<li class="mdotb">Mỗi lần đăng nhập khách hàng nên kiểm tra số dư tài khoản của mình. Nếu có bất kỳ vấn đề về số dư tài khoản, xin hãy thông báo cho Đại lý cấp trên hoặc Văn phòng Công ty ngay lập tức.</li>
											<li class="mdotb">Một khi đặt cược được chấp nhận (đã qua thời gian hủy đặt cược), người chơi sẽ không thể sửa hay hủy bỏ các mã đặt cược.</li>
											<li class="mdotb">Tỷ lệ cược của các con số sẽ được biến đổi thường xuyên, Khi phát thưởng sẽ áp dụng theo tỷ lệ cược mà ban đầu bạn đã đặt cược.</li>
											<li class="mdotb">Tất cả các cá cược đều phải tiến hành trước thời gian mở thưởng Xỗ Số nếu không các mã cược đó sẽ được xem là không hợp lệ.</li>
										</ul>
										
									</div>
								</div>
							</div>
							<div class="rull_inner">
<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 14px;">
<thead>
<tr>
<th colspan="16">THỜI GIAN MỞ CƯỢC – ĐÓNG CƯỢC</th>
</tr>
</thead>
<tbody>
<tr>
<td rowspan="4" style="vertical-align: middle;"><strong>Miền Bắc</strong></td>
<td rowspan="4" style="vertical-align: middle;">Tất cả các ngày</td>
<td colspan="3"><strong>Đề</strong></td>
<td colspan="3"><strong>Lô</strong></td>
</tr>
<tr>
<td colspan="3">00:00 – 18:30</td>
<td colspan="3">00:00 – 18:15</td>
</tr>
<tr>
<td colspan="3"><strong>Đề Giải nhất</strong></td>
<td colspan="3"><strong>Đề Thần Tài</strong></td>
</tr>
<tr>
<td colspan="3">00:00 – 18:15</td>
<td colspan="3">00:00 – 18:08</td>
</tr>

<tr>
<td rowspan="16" style="vertical-align: middle;"><strong>Miền Nam</strong></td>
<td rowspan="2">&nbsp;</td>
<td colspan="2"><strong>Đài 1</strong></td>
<td colspan="2"><strong>Đài 2</strong></td>
<td colspan="2"><strong>Đài 3</strong></td>
</tr>
<tr>

</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Hai</strong></td>
<td colspan="2"><strong>TP. Hồ Chí Minh</strong></td>
<td colspan="2"><strong>Đồng Tháp</strong></td>
<td colspan="2"><strong>Cà Mau</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Ba</strong></td>
<td colspan="2"><strong>Bến Tre</strong></td>
<td colspan="2"><strong>Vũng Tàu</strong></td>
<td colspan="2"><strong>Bạc Liêu</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Tư</strong></td>
<td colspan="2"><strong>Đồng Nai</strong></td>
<td colspan="2"><strong>Cần Thơ</strong></td>
<td colspan="2"><strong>Sóc Trăng</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Năm</strong></td>
<td colspan="2"><strong>Tây Ninh</strong></td>
<td colspan="2"><strong>An Giang</strong></td>
<td colspan="2"><strong>Bình Thuận</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Sáu</strong></td>
<td colspan="2"><strong>Vĩnh Long</strong></td>
<td colspan="2"><strong>Bình Dương</strong></td>
<td colspan="2"><strong>Trà Vinh</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Bảy</strong></td>
<td colspan="2"><strong>TP. Hồ Chí Minh</strong></td>
<td colspan="2"><strong>Long An</strong></td>
<td colspan="2"><strong>Bình Phước</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Chủ Nhật</strong></td>
<td colspan="2"><strong>Tiền Giang</strong></td>
<td colspan="2"><strong>Kiên Giang</strong></td>
<td colspan="2"><strong>Đà Lạt</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
<td>00:00</td>
<td>16:13</td>
</tr>

<tr>
<td rowspan="16" style="vertical-align: middle;"><strong>Miền Trung</strong></td>
<td rowspan="2">&nbsp;</td>
<td colspan="2"><strong>Đài 1</strong></td>
<td colspan="2"><strong>Đài 2</strong></td>
<td colspan="2"><strong>Đài 3</strong></td>
</tr>
<tr>

</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Hai</strong></td>
<td colspan="2"><strong>Thừa T. Huế</strong></td>
<td colspan="2"><strong> Phú Yên </strong></td>
<td colspan="2"><strong></strong></td>
</tr>
<tr>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
<td></td>
<td></td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Ba</strong></td>
<td colspan="2"><strong>Đắk Lắk</strong></td>
<td colspan="2"><strong>Quảng Nam</strong></td>
<td colspan="2"><strong></strong></td>
</tr>
<tr>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
<td></td>
<td></td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Tư</strong></td>
<td colspan="2"><strong>Đà Nẵng</strong></td>
<td colspan="2"><strong> Khánh Hòa </strong></td>
<td colspan="2"><strong></strong></td>
</tr>
<tr>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
<td></td>
<td></td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Năm</strong></td>
<td colspan="2"><strong>Bình Định</strong></td>
<td colspan="2"><strong>Quảng Trị</strong></td>
<td colspan="2"><strong>Quảng Bình </strong></td>
</tr>
<tr>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Sáu</strong></td>
<td colspan="2"><strong>Gia Lai</strong></td>
<td colspan="2"><strong>Ninh Thuận</strong></td>
<td colspan="2"><strong></strong></td>
</tr>
<tr>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
<td></td>
<td></td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Thứ Bảy</strong></td>
<td colspan="2"><strong>Đà Nẵng</strong></td>
<td colspan="2"><strong> Quảng Ngãi </strong></td>
<td colspan="2"><strong>Đắk Nông</strong></td>
</tr>
<tr>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
</tr>
<tr>
<td rowspan="2" style="vertical-align: middle;"><strong>Chủ Nhật</strong></td>
<td colspan="2"><strong>Khánh Hòa</strong></td>
<td colspan="2"><strong> Kon Tum </strong></td>
<td colspan="2"><strong></strong></td>
</tr>
<tr>
<td>00:00</td>
<td>17:13</td>
<td>00:00</td>
<td>17:13</td>
<td></td>
<td></td>
</tr>

</tbody>
</table>
</div>
<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 14px;">
											<thead>
											<tr class="subtitle">
												<th>Thể loại cược</th>
												<th>Trả thưởng</th>
												<th>Nháy</th>
											</tr>
											</thead>
											<tbody>
											<tr class="mtitle">
												<td colspan="3">Xổ Số Miền Bắc</td>
											</tr>
											<tr>
												<th width="100">Đề</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th width="100">Đầu Đề</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th width="100">Nhất</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th width="100">Đầu Nhất</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th>Lô 2 số</th>
												<td>1 x 80</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>Lô 3 số</th>
												<td>1 x 500</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>Xiên 2</th>
												<td>1 x 10</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Xiên 3</th>
												<td>1 x 40</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Xiên 4</th>
												<td>1 x 100</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Xiên Nháy</th>
												<td>1 x 10</td>
												<td>Có</td>
											</tr>
											<tr>
												<th width="100">3 Càng</th>
												<td width="100">1 x 400</td>
												<td width="100">Không</td>
											</tr>
																						<tr>
												<th width="100">3 Càng Nhất</th>
												<td width="100">1 x 400</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th>Đề trượt</th>
												<td>1 x 0.66</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Lô trượt 1</th>
												<td>1 x 21</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>Lô trượt 4</th>
												<td>1 x 2</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Lô trượt 8</th>
												<td>1 x 7</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Lô trượt 10</th>
												<td>1 x 10</td>
												<td>Không</td>
											</tr>
											<tr>
											<tr>
												<th width="100">Đầu Thần Tài</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th width="100">Đuôi Thần Tài</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
												<th>Các giải khác</th>
												<td>1 x 70</td>
												<td>Không</td>
											</tr>
											
											<tr class="mtitle">
												<td colspan="3">Xổ Số Miền Nam + Miền Trung</td>
											</tr>
											<tr>
												<th width="100">Đề</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th width="100">Đầu Đề</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>								
											<tr>
												<th width="100">3 Càng</th>
												<td width="100">1 x 400</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th width="100">Giải Bảy</th>
												<td width="100">1 x 400</td>
												<td width="100">Không</td>
											</tr>											
											<tr>
												<th width="100">Giải Tám</th>
												<td width="100">1 x 70</td>
												<td width="100">Không</td>
											</tr>
											<tr>
												<th>Lô 2 số</th>
												<td>1 x 80</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>Lô 3 số</th>
												<td>1 x 500</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>Xiên 2</th>
												<td>1 x 20</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Xiên 3</th>
												<td>1 x 100</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Xiên 4</th>
												<td>1 x 400</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Đề trượt</th>
												<td>1 x 0.66</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>Lô trượt 1</th>
												<td>1 x 14</td>
												<td>Có</td>
											</tr>										
										
											
											</tbody>
										</table>
							
						</div>
						<div class="tab-pane" id="rules_north_1">
							<div class="panel panel-default">
								<div class="panel-heading">
									Mô tả cách chơi
								</div>
								<div class="panel-body">
									<h2>Các khái niệm</h2>
									<div class="rull_inner">
										<ul>
											<li class="mdotb"><em>Tỉ lệ trả thưởng : </em>là tỉ lệ nhân khi khách thắng cược (1:70 là bỏ 1 ăn 70 hay còn gọi là x70 lần).</li>
											<li class="mdotb"><em>Nháy : </em>là số lần xuất hiện của số hoặc cặp số đặt cược. Tùy từng hình thức, người chơi được trả thưởng nhân 2, 3 hoặc 4 lần dựa vào số lần xuất hiện.</li>
										</ul>
									</div>
									<div class="rull_inner">
										<p class="rull_subtitle">ĐỀ : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy 2 số cuối giải Đặc Biệt làm kết quả.</li>
												
											</ul>
										</div>
										<p class="rull_subtitle">ĐỀ ĐẦU : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy 2 số đầu giải Đặc Biệt làm kết quả.</li>
												
											</ul>
										</div>
										<p class="rull_subtitle">LÔ 2 SỐ : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy 2 số cuối tất cả các Giải làm kết quả.</li>
												
											</ul>
										</div>
										
										<p class="rull_subtitle">LÔ 3 SỐ : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy 2 số cuối các Giải có từ 3 số trở lên làm kết quả.</li>
												
											</ul>
										</div>
										
										<p class="rull_subtitle">LÔ LIVE : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy 2 số cuối tất cả các Giải làm kết quả.</li>
												<li>Khi số đánh là 60(20) 60 là số đánh, 20 là số giải còn lại khi đánh </li>
												<li>Khi đó số đánh sẽ tính 20 giải còn lại</li>
											</ul>
										</div>
										<p class="rull_subtitle">LÔ XIÊN : </p>
										<div class="rull_subinner">
											<ul>
												<li class="mdotbbk">Là cách cược đồng thời 2, 3 hoặc 4 con số.</li>
												<li class="mdotbbk">Điều kiện trúng là kết quả đồng thời phải có 2, 3 hoặc 4 con số đã chọn.</li>
											</ul>
										</div>
										<p class="rull_subtitle">3 CÀNG : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy 3 số cuối giải Đặc Biệt làm kết quả.</li>
												
											</ul>
										</div>
										<p class="rull_subtitle">3 CÀNG NHẤT : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy 3 số cuối giải Nhất làm kết quả.</li>
												
											</ul>
										</div>
										<p class="rull_subtitle">LÔ TRƯỢT 1 : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược bạn chọn 1 con số và số đó không có trong kết quả .</li>
											</ul>
										</div>
										<p class="rull_subtitle">LÔ TRƯỢT 4 : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược bạn chọn 4 con số và 4 số đó đều không có trong kết quả .</li>
											</ul>
										</div>
										<p class="rull_subtitle">LÔ TRƯỢT 8 : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược bạn chọn 8 con số và 8 số đó đều không có trong kết quả .</li>
											</ul>
										</div>
										<p class="rull_subtitle">LÔ TRƯỢT 10 : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược bạn chọn 10 con số và 10 số đó đều không có trong kết quả .</li>
											</ul>
										</div>
										<p class="rull_subtitle">CÁC GIẢI : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy 2 số cuối giải đã cược làm kết quả.</li>
												
											</ul>
										</div>
										<p class="rull_subtitle">ĐỀ TRƯỢT : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược bạn chọn 1 con số và con số đó không có trong kết quả giải Đặc biệt.</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="rules_north_2">
							<div class="panel panel-default">
								<div class="panel-heading">
									Mô tả cách chơi
								</div>
								<div class="panel-body">
									<h2>2D</h2>
									<img src="./XS8386_files/mb2_1.png" width="360" height="604" align="right">
									<div class="rull_inner" style="min-height: 600px;">
										<p class="rull_subtitle">2D - Đầu : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99. Lấy Giải Bảy làm kết quả.</li>
												<li>Số tiền thanh toán = Tiền cược x 4 lần</li>
											</ul>
										</div>
										<p class="rull_subtitle">2D - Đuôi : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99. Lấy 2 số cuối Giải Đặc Biệt làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">2D - 27 lô : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99. Lấy 2 số cuối của toàn bộ 27 giải làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">2D - Xiên 2( Số đá 2) : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược đồng thời 2 con số từ 00 đến 99. Toàn bộ 27 giải đồng thời có cả 2 con số đặt cược thì trúng.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 54 lần</li>
												<li class="mdotbbk"><em>Lưu ý : </em>khi mua xiên 2 cho 2 số cược, nếu 2 số cược đều xuất hiện 2 lần trong tất cả các giải thì tiền thắng cược được tính nhân 2, tương tự xuất hiện 3 lần được tính nhân 3. Khi 1 số chỉ về 1 lần còn các số khác về nhiều lần thì tiền thắng cược cũng chỉ được tính 1 lần.</li>
											</ul>
										</div>
										<p class="rull_subtitle">2D - Xiên 3( Số đá 3) : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược đồng thời 3 con số từ 00 đến 99. Toàn bộ 27 giải đồng thời có cả 3 con số đặt cược thì trúng.</li>
												<li class="mdotbbk"><em>Lưu ý : </em>khi mua xiên 3 cho 3 số cược, nếu cả 3 số cược đều xuất hiện 2 lần trong tất cả các giải thì tiền thắng cược được tính nhân 2, tương tự xuất hiện 3 lần được tính nhân 3. Khi 1 số chỉ về 1 lần còn các số khác về nhiều lần thì tiền thắng cược cũng chỉ được tính 1 lần.</li>
											</ul>
										</div>
									</div>
									<h2>3D</h2>
									<div class="rull_inner">
										<p class="rull_subtitle">3D - Đầu : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy Giải Sáu làm kết quả.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 3 lần</li>
											</ul>
										</div>
										<p class="rull_subtitle">3D - Đuôi : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy 3 số cuối Giải Đặc Biệt làm kết quả</li>
											</ul>
										</div>
										<p class="rull_subtitle">3D - 23 lô : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy 3 số cuối tất cả các giải (trừ Giải Bảy) làm kết quả</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 23 lần</li>
											</ul>
										</div>
										<img src="./XS8386_files/mb2_2.png" width="602" height="384" align="center">
									</div>
									<h2>4D</h2>
									<div class="rull_inner">
										<p class="rull_subtitle">4D - Đuôi : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 0000 đến 9999, lấy 4 số cuối Giải Đặc Biệt làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">4D - 20 Lô (Bao Lô) : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 0000 đến 9999, lấy 4 số cuối tất cả các Giải (trừ Giải Sáu, Giải Bảy) làm kết quả.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 20 lần.</li>
											</ul>
										</div>
										<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
											<thead>
											<tr>
												<th colspan="3">Tỉ lệ trả thưởng xổ số Miền Bắc 2</th>
											</tr>
											</thead>
											<tbody>
											<tr class="subtitle">
												<td>Thể loại cược</td>
												<td>Trả thưởng</td>
												<td>Nháy</td>
											</tr>
											<tr>
												<th width="100">2D – Đầu</th>
												<td width="100">1 : 75</td>
												<td width="100">Có</td>
											</tr>
											<tr>
												<th>2D – Đuôi</th>
												<td>1 : 75</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>2D – 27 Lô</th>
												<td>1 : 75</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>3D – Đầu</th>
												<td>1 : 650</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>3D – Đuôi</th>
												<td>1 : 650</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>3D – 23 Lô</th>
												<td>1 : 650</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>4D – Đuôi</th>
												<td>1 : 6000</td>
												<td>Không</td>
											</tr>
											<tr>
												<th>4D – 20 Lô</th>
												<td>1 : 6000</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>2D – Xiên 2</th>
												<td>1 : 680</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>2D – Xiên 3</th>
												<td>1 : 4000</td>
												<td>Có</td>
											</tr>
											<tr>
												<th>2D – Xiên 4</th>
												<td>1 : 15000</td>
												<td>Có</td>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="rules_south">
							<div class="panel panel-default">
								<div class="panel-heading">
									Mô tả cách chơi
								</div>
								<div class="panel-body">
									<h2>2D</h2>
									<div class="rull_inner">
										<p class="rull_subtitle">2D - Đầu : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy Giải Tám làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">2D - Đuôi : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy 2 số cuối giải Đặc Biệt làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">2D - 18 LÔ : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 00 đến 99, lấy 2 số cuối tất cả các Giải làm kết quả.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 18 lần</li>
											</ul>
										</div>
										<p class="rull_subtitle">XIÊN : </p>
										<div class="rull_subinner">
											<ul>
												<li class="mdotbbk"><em>Xiên 2 : </em>là hình thức cược đồng thời 2 con số từ 00 đến 99. Toàn bộ 18 giải đồng thời có cả 2 con số đặt cược thì trúng.
													<ul>
														<li class="mdotsc">Số tiền thanh toán = Tiền cược x 36 lần</li>
													</ul>
												</li>
												<li class="mdotbbk"><em>Xiên 3 : </em>là hình thức cược đồng thời 3 con số từ 00 đến 99. Toàn bộ 18 giải đồng thời có cả 3 con số đặt cược thì trúng.
													<ul>
														<li class="mdotsc">Số tiền thanh toán = Tiền cược x 54 lần</li>
													</ul>
												</li>
												<li class="mdotbbk"><em>Xiên 4 : </em>là hình thức cược đồng thời 4 con số từ 00 đến 99. Toàn bộ 18 giải đồng thời có cả 4 con số đặt cược thì trúng.
													<ul>
														<li class="mdotsc">Số tiền thanh toán = Tiền cược x 72 lần</li>
													</ul>
												</li>
												<li class="mdotbbk"><em>Lưu ý : </em>nếu cặp xiên đặt cược đều xuất hiện 2, 3 hoặc 4 lần trong tất cả các giải thì tiền thắng cược được tính nhân 2, 3 hoặc 4 lần tùy theo số lần xuất hiện.</li>
											</ul>
										</div>
										<p class="rull_subtitle">2D 7 Lô : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược một số từ 00 đến 99. Lấy 2 số cuối các giải Năm, Sáu, Bảy, Tám và giải Đặc Biệt làm kết quả.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 7 lần</li>
												<li><img src="./XS8386_files/mn_1.png" width="399" height="217"></li>
											</ul>
										</div>
									</div>
									<h2>3D</h2>
									<div class="rull_inner">
										<p class="rull_subtitle">3D - Đầu : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy Giải Bảy làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">3D - Đuôi : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy 3 số cuối Giải Đặc Biệt làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">3D – Đầu Đuôi (Xỉu Chủ): </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy Giải Bảy và 3 số cuối Giải Đặc Biệt làm kết quả.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 2 lần</li>
											</ul>
										</div>
										<p class="rull_subtitle">3D - 17 LÔ : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 000 đến 999, lấy 3 số cuối tất cả các giải (trừ Giải Tám) làm kết quả.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 17 lần</li>
											</ul>
										</div>
										<p class="rull_subtitle">3D - 7 Lô : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược một số từ 000 đến 999. Lấy 3 số cuối các giải Năm, Sáu, Bảy, Đặc Biệt và giải đầu tiên của Giải Tư làm kết quả.</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 7 lần</li>
												<li><img src="./XS8386_files/mn_2.png" width="397" height="327"></li>
											</ul>
										</div>
									</div>
									<h2>4D</h2>
									<div class="rull_inner">
										<p class="rull_subtitle">4D - Đuôi : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 0000 đến 9999, lấy 4 số cuối giải Đặc Biệt làm kết quả.</li>
											</ul>
										</div>
										<p class="rull_subtitle">4D - 16 LÔ : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược 1 số từ 0000 đến 9999, lấy 4 số cuối tất cả các Giải (trừ giải Bảy, Tám) làm kết quả.</li>
											</ul>
										</div>
									</div>
									<h2>Xiên 18A + 18B</h2>
									<div class="rull_inner">
										<p class="rull_subtitle">Xiên 2 : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược đồng thời 2 con số từ 00 đến 99. Toàn bộ 18 giải của đài A và 18 giải của đài B đồng thời có cả 2 con số đặt cược thì trúng</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 72 lần</li>
											</ul>
										</div>
										<p class="rull_subtitle">Xiên 3 : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược đồng thời 2 con số từ 00 đến 99. Toàn bộ 18 giải của đài A và 18 giải của đài B đồng thời có cả 3 con số đặt cược thì trúng</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 108 lần</li>
											</ul>
										</div>
										<p class="rull_subtitle">Xiên 4 : </p>
										<div class="rull_subinner">
											<ul>
												<li>Là hình thức cược đồng thời 2 con số từ 00 đến 99. Toàn bộ 18 giải của đài A và 18 giải của đài B đồng thời có cả 4 con số đặt cược thì trúng</li>
												<li class="mdotbbk">Số tiền thanh toán = Tiền cược x 144 lần</li>
											</ul>
										</div>
									</div>
									<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
										<thead>
										<tr>
											<th colspan="3">Tỉ lệ trả thưởng xổ số Miền Nam</th>
										</tr>
										</thead>
										<tbody>
										<tr class="subtitle">
											<td>Thể loại cược</td>
											<td>Trả thưởng</td>
											<td>Nháy</td>
										</tr>
										<tr>
											<th width="100">2D – Đầu</th>
											<td width="100">1 : 75</td>
											<td width="100">Có</td>
										</tr>
										<tr>
											<th>2D – Đuôi</th>
											<td>1 : 75</td>
											<td>Không</td>
										</tr>
										<tr>
											<th>2D – 18 Lô</th>
											<td>1 : 75</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>2D – 7 Lô</th>
											<td>1 : 75</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>3D – Đầu</th>
											<td>1 : 650</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>3D – Đuôi</th>
											<td>1 : 650</td>
											<td>Không</td>
										</tr>
										<tr>
											<th>3D – 18 Lô</th>
											<td>1 : 650</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>3D – 7 Lô</th>
											<td>1 : 650</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>4D – Đuôi</th>
											<td>1 : 6000</td>
											<td>Không</td>
										</tr>
										<tr>
											<th>4D – 16 Lô</th>
											<td>1 : 6000</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>2D – Xiên 2</th>
											<td>1 : 800</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>2D – Xiên 3</th>
											<td>1 : 5000</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>2D – Xiên 4</th>
											<td>1 : 20000</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>Xiên 2 (18A + 18B)</th>
											<td>1 : 570</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>Xiên 3 (18A + 18B)</th>
											<td>1 : 2.800</td>
											<td>Có</td>
										</tr>
										<tr>
											<th>Xiên 4 (18A + 18B)</th>
											<td>1 : 11.000</td>
											<td>Có</td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ps-scrollbar-x-rail" style="width: 720px; left: 0px; bottom: 3px; display: block;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="display: inherit; top: 0px; right: 3px;"><div class="ps-scrollbar-y" style="top: 0px; height: 123px;"></div></div></div></div>
	</div>
	<input type="hidden" id="current_game" value="">
	<input type="hidden" id="url" value="{{url('/games')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
	<a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã tạo thành công')"></a>

	
@endsection
<script type="text/javascript">
	function refreshHistory() {
		$('#history').fadeOut();
		$('#history').load("{{url('/refresh-history')}}", function() {
			$('#history').fadeIn();
		});
	}
</script>
