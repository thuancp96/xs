<?php

use App\Helpers\MinigameHelpers;

$countI = 0;
?>

@foreach($xosorecords as $key=>$item)
<?php
if (!isset($item)) {
    continue;
}
?>
@if ($item->locationslug == 70)
@include("frontend.inbets_section_7zball")
@endif
@if ($item->locationslug == 1)
@if($type==2)
@include("frontend.inbets_section_number",['typeView'=>2])
@else
@include("frontend.inbets_section_number",['typeView'=>1])
@endif
@endif

@if ($item->locationslug == 80)
@include("frontend.inbets_section_minigame")
@endif

@endforeach

@if($type != 1)
<span style="font-size: 0.85em !important; margin-left:5px;">Tìm thấy <mark>{{count($xosorecords)}}</mark> phiếu cược.</span>
@endif

<script>
    function confirmCancel(e){
		$.ajax({
			url: "/games/estimate-cancel-bet",
			method: 'POST',
			dataType: 'json',
			data: {
				id: e.getAttribute("bet_id"),
				type: "cancel" 
				// _token: $_token,
			},
			complete: function(data) {
				dataResponse = JSON.parse(data.responseText)
                Swal.fire({
                    title: "Thông báo hủy cược",
                    html: "Bạn muốn hủy cược Tin " + e.getAttribute("bet_id_inday") + " của " + e.getAttribute("bet_user_name") + "<br> Phí hủy: " +  dataResponse.fee,
                    type: "info",
                    timer: 10000,
                    showCancelButton: true,
                    // confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Tiếp tục",
                    cancelButtonText: "Quay về",
                    closeOnConfirm: true,
                    reverseButtons: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then((confirm) => {
                    console.log(confirm)
                    if(confirm.isConfirmed){
						actionCancel(e)
					}
                })
			}
		});
	}
	function actionCancel(e){
		$.ajax({
			url: "/games/save-confirm-bet",
			method: 'POST',
			dataType: 'json',
			data: {
				id: e.getAttribute("bet_id"),
				type: "cancel" 
				// _token: $_token,
			},
			complete: function(data) {
				dataResponse = JSON.parse(data.responseText)
                Swal.fire({
                    title: "Thông báo hủy cược",
                    html: dataResponse[1],
                    type: "info",
                    timer: 10000,
                    confirmButtonText: "Đã hiểu",
                    closeOnConfirm: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then((confirm) => {
                    location.reload()
                })
			}
		});
	}
</script>