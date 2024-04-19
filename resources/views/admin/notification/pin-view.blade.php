<?php

	use App\Helpers\NotifyHelpers;
	use Illuminate\Support\Facades\Auth;

	$pin_message = NotifyHelpers::showNotificationByPin();
?>
@if (!Auth::guest() && Auth::user()->roleid > 0) 
<script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function($)
		{
            @if (isset($pin_message))
				var delayInMilliseconds = 1000*2; //1 second
				setTimeout(function() {
				//your code to be executed after 1 second
					@if (strpos($_SERVER['HTTP_HOST'], 'ag') !== false )
						swal({
								title: 'Thông báo',
								html:'{!!$pin_message->message!!}',
								timer: 100000,
								icon: "warning",
								button: "Đã hiểu",
							});
					@else
						Swal.fire({
							title: 'Thông báo',
							html:'{!!$pin_message->message!!}',
							timer: 100000,
							confirmButtonText: 'Đã hiểu',
							input: "checkbox",
							inputValue: 0,
							inputPlaceholder: `
								Tôi đã đọc và hiểu thông báo trên.
							`,
							inputValidator: (result) => {
								return !result && "Bạn cần xác nhận đã đọc và hiểu thông báo !";
							}
						}).then((value) => {
							if (value.isConfirmed){
								
								$.ajax({
									url: "/notification/update-read",
									method: 'POST',
									dataType: 'html',
									data: {
										id: '{!!$pin_message->id!!}',
										_token: $_token,
									},
									success: function(data) {
									}
								})
								console.log(value);
							}
							});
					@endif
				}, delayInMilliseconds);
			@endif
    	});

</script>
@endif