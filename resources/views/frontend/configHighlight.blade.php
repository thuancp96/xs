<?php if (strpos($_SERVER['HTTP_HOST'], 'ag') !== false ){
    echo view ('admin.changepassadmin');
}
else{

?>
<div id="highlight-price-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				 <h6 class="modal-title" style="color:white" id="exampleModalLabel"></h6>
				 <button type="button" class="btn-close btn-close-white highlight-price-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="change-highlight-form" class="form-horizontal" data-parsley-validate novalidate>
				<div class="modal-body">
				<div class="row">
					<div class="row">
						<label for="field-4" style="margin-bottom:10px;" class="col-md-6 col-sm-6 col-xs-6">Áp dụng khi giá lớn hơn</label>
						<div class="col-md-3 col-sm-3 col-xs-3">
							<!-- <span class="input-group-addon"><i class="fa fa-dollar"></i></span> -->
							<input type="tel" name="credit" data-a-sign="" class="form-control" value="" id="highlight-price" 
								placeholder="giá" data-toggle="tooltip" data-placement="top" title="" 
								data-original-title="0" data-parsley-id="16"
								style="margin-top:-5px;"
								>
						</div>
					</div>
				</div>
					<div class="row">
						<label id='validate-highlight-price' class="col-md-6 col-sm-6 col-xs-6 hidden" style='color:red'></label>
					</row>

					<div class="row" style="margin-top: 10px;">
						<label for="field-4" class="col-md-6 col-sm-6 col-xs-6">Highlight giá</label>
						<div class="col-md-3 col-sm-3 col-xs-3">
							<input type="checkbox" name="highlight-price-switch" id="highlight-price-switch" data-plugin="switchery" data-color="#f05050" data-size="small"/>
						</div>
					</div>
					
					<div class="row">
			        <div class="modal-footer d-block  text-center">
                        <button type="button" onclick="Save_Change_Highlight()" class="btn btnlogin">OK</button>
                    </div>
					</div>
				
				</div>

				<div class="modal-footer hidden" style="text-align: center !important;">
					<!-- <ins data-dismiss="modal">Đóng</ins> -->
					<input type="hidden" id="sa-success">
					<input type="hidden" id="user_edit_id_cpw">
				</div>
			</form>
		</div>
	</div>
</div>

<link href="/assets/admin/plugins/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="/assets/admin/plugins/switchery/dist/switchery.min.js"></script>
<script type="text/javascript">
@if(!Auth::guest())
	function Save_Change_Highlight() {
		if ($('#highlight-price').val() < $('#exchange_rates_raw').val()){
			$('#validate-highlight-price').html('Nhập giá >= ' + $('#exchange_rates_raw').val())
			$('#validate-highlight-price').removeClass('hidden');
			return;
		}
		$('#validate-highlight-price').addClass('hidden');
		$('.highlight-price-close').click();
		// $('#highlight-price-switch').click();$('#lock_edit1').is(":checked")
		// console.log($('#highlight-price-switch').is(":checked"))
		// console.log($('#highlight-price').val())
		// if ()
		localStorage.setItem('highlight-price-' + $('#current_game').val() + '{{Auth::user()->name}}', $('#highlight-price').val())
		localStorage.setItem('highlight-price-status-' + $('#current_game').val() + '{{Auth::user()->name}}', $('#highlight-price-switch').is(":checked"))
	}
	
	function Show_Change_Highlight() {
		// console.log($('#current_game').val())
		price = localStorage.getItem('highlight-price-' + $('#current_game').val() + '{{Auth::user()->name}}')
		status = localStorage.getItem('highlight-price-status-' + $('#current_game').val() + '{{Auth::user()->name}}')
		// console.log(price)
		// console.log(status)
		// console.log($('#exchange_rates_raw').val())
		if (price == null)
			price = $('#exchange_rates_raw').val()
		$('#highlight-price').val(price)
		if(status == 'true')
		{
			if(!$('#highlight-price-switch').is(":checked")){
				$('#highlight-price-switch').click();
			}
		}else{
			$('#highlight-price-switch').click();
			if($('#highlight-price-switch').is(":checked")){
				$('#highlight-price-switch').click();
			}	
		}
		// $('#oldpass').attr('readonly', true);
		// $('#newpass').attr('readonly', true);
		// $('#confirmpass').attr('readonly', true);
	}
@endif
</script>

<?php } ?>