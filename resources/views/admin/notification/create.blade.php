@extends('admin.admin-template')

@section('content')

<?php

use App\Helpers\UserHelpers;
use Illuminate\Support\Facades\Auth;

$user_current = Auth::user();
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title">Thêm mới thông báo</h4>
</div>
<form id="create-user-form" data-parsley-validate novalidate>
	<div class="modal-body new_user">
		<div class="row form_create">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="field-4" class="col-sm-2 col-xs-2 control-label">Loại Tài Khoản </label>
						<div class="col-sm-2 col-xs-2 input-group">
							<!-- <span class="input-group-addon"><i class="fa fa-group"></i></span> -->
							<select class='form-control form-control-sm' name="type_message" id="type_message">
								<option value="system">Hệ thống</option>
								<option value="supers">supers</option>
								<option value="masters">masters</option>
								<option value="agents">agents</option>
								<option value="members">members</option>
								<option value="personal">Cá nhân</option>
							</select>
						</div>
						
					</div>
				</div>
			</div>

			<div class="row" id="personal_div" hidden>
				<div class="col-md-12">
					<div class="form-group">
						<label for="field-4" class="col-sm-2 col-xs-2 control-label">Chọn Tài Khoản </label>
						
						<div class="col-sm-2 col-xs-2 input-group">
							<div class="autocomplete">
								<input id="personal_name" type="text" name="personal_name" placeholder="Gợi ý chọn tài khoản">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="field-1" class="col-sm-2 col-xs-2 control-label">Nội dung</label>
						<div class="col-sm-10 input-group">
							<input type="text" id="message" name="message" class="form-control" placeholder="Nhập nội dung độ dài từ 10-300 ký tự." required data-parsley-error-message="Nhập nội dung độ dài từ 10-300 ký tự" data-parsley-trigger="keyup" minlength="10" data-parsley-minlength="10" maxlength="300" data-parsley-maxlength="300">
						</div>
						
					</div>
				</div>
			</div>

			<!-- <div class="form-check">
				<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
				<label class="form-check-label" for="defaultCheck1">
					Default checkbox
				</label>
			</div> -->



			<!-- <div class="row">
						<div class="form-group">
							<div class="col-md-6">
								<label for="field-5" class="control-label">Khóa tài khoản</label>
								<br/>
								<input type="checkbox" name="lock" id="lock" data-plugin="switchery" data-color="#f05050"/>
							</div>

						</div>
					</div> -->
		</div>
	</div>
	<div class="modal-footer" style="text-align: right; !important">
		<!-- <ins data-dismiss="modal">Đóng</ins> -->
		<!-- <button class="ladda-button  btn btn-info" data-style="slide-left" id="btn_Save">
					<span class="ladda-label">Lưu</span>
					<span class="ladda-spinner"></span>
				</button> -->

		<button type="button" id="btn_Save" class="btn btn-default btn-custom waves-effect waves-light">Thêm mới</button>
		<button type="button" id="btn_ClearData" class="btn btn-default btn-custom waves-effect waves-light">Nhập lại</button>
		<input type="hidden" id="sa-success">
		<input type="hidden" id="user_current" value="{{$user_current->id}}">
	</div>
</form>
<style>
	* {
		box-sizing: border-box;
	}

	body {
		font: 14px Arial;
	}

	.autocomplete {
		/*the container must be positioned relative:*/
		position: relative;
		display: inline-block;
	}

	input {
		border: 1px solid transparent;
		/* background-color: #f1f1f1; */
		padding: 8px;
		font-size: 14px;
	}

	input[type=text] {
		/* background-color: #f1f1f1; */
		width: 100%;
	}

	input[type=submit] {
		background-color: DodgerBlue;
		color: #fff;
	}

	.autocomplete-items {
		position: absolute;
		border: 1px solid #d4d4d4;
		border-bottom: none;
		border-top: none;
		z-index: 99;
		/*position the autocomplete items to be the same width as the container:*/
		top: 100%;
		left: 0;
		right: 0;
	}

	.autocomplete-items div {
		padding: 8px;
		cursor: pointer;
		background-color: #fff;
		border-bottom: 1px solid #d4d4d4;
	}

	.autocomplete-items div:hover {
		/*when hovering an item:*/
		background-color: #e9e9e9;
	}

	.autocomplete-active {
		/*when navigating through the items using the arrow keys:*/
		background-color: DodgerBlue !important;
		color: #ffffff;
	}
</style>
<?php
	function convertRole($roleid){
		switch ($roleid) {
			case 2:
				return 'Super';
			case 4:
				return 'Master';
			case 5:
				return 'Agent';
			case 6:
				return 'Member';
			default:
				# code...
				break;
		}
		return "ss";
	}
	$userChild = UserHelpers::GetAllUser(Auth::user());

	$stringUser = [];
	foreach($userChild as $item){
		array_push($stringUser,$item->name . '-'.convertRole($item->roleid));
	}
?>
<script>
	var countries = {!!json_encode($stringUser)!!}

	function autocomplete(inp, arr) {
		/*the autocomplete function takes two arguments,
		the text field element and an array of possible autocompleted values:*/
		var currentFocus;
		/*execute a function when someone writes in the text field:*/
		inp.addEventListener("input", function(e) {
			var a, b, i, val = this.value;
			/*close any already open lists of autocompleted values*/
			closeAllLists();
			if (!val) {
				return false;
			}
			currentFocus = -1;
			/*create a DIV element that will contain the items (values):*/
			a = document.createElement("DIV");
			a.setAttribute("id", this.id + "autocomplete-list");
			a.setAttribute("class", "autocomplete-items");
			/*append the DIV element as a child of the autocomplete container:*/
			this.parentNode.appendChild(a);
			/*for each item in the array...*/
			for (i = 0; i < arr.length; i++) {
				/*check if the item starts with the same letters as the text field value:*/
				if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					/*create a DIV element for each matching element:*/
					b = document.createElement("DIV");
					/*make the matching letters bold:*/
					b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					b.innerHTML += arr[i].substr(val.length);
					/*insert a input field that will hold the current array item's value:*/
					b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
					/*execute a function when someone clicks on the item value (DIV element):*/
					b.addEventListener("click", function(e) {
						/*insert the value for the autocomplete text field:*/
						inp.value = this.getElementsByTagName("input")[0].value;
						/*close the list of autocompleted values,
						(or any other open lists of autocompleted values:*/
						closeAllLists();
					});
					a.appendChild(b);
				}
			}
		});
		/*execute a function presses a key on the keyboard:*/
		inp.addEventListener("keydown", function(e) {
			var x = document.getElementById(this.id + "autocomplete-list");
			if (x) x = x.getElementsByTagName("div");
			if (e.keyCode == 40) {
				/*If the arrow DOWN key is pressed,
				increase the currentFocus variable:*/
				currentFocus++;
				/*and and make the current item more visible:*/
				addActive(x);
			} else if (e.keyCode == 38) { //up
				/*If the arrow UP key is pressed,
				decrease the currentFocus variable:*/
				currentFocus--;
				/*and and make the current item more visible:*/
				addActive(x);
			} else if (e.keyCode == 13) {
				/*If the ENTER key is pressed, prevent the form from being submitted,*/
				e.preventDefault();
				if (currentFocus > -1) {
					/*and simulate a click on the "active" item:*/
					if (x) x[currentFocus].click();
				}
			}
		});

		function addActive(x) {
			/*a function to classify an item as "active":*/
			if (!x) return false;
			/*start by removing the "active" class on all items:*/
			removeActive(x);
			if (currentFocus >= x.length) currentFocus = 0;
			if (currentFocus < 0) currentFocus = (x.length - 1);
			/*add class "autocomplete-active":*/
			x[currentFocus].classList.add("autocomplete-active");
		}

		function removeActive(x) {
			/*a function to remove the "active" class from all autocomplete items:*/
			for (var i = 0; i < x.length; i++) {
				x[i].classList.remove("autocomplete-active");
			}
		}

		function closeAllLists(elmnt) {
			/*close all autocomplete lists in the document,
			except the one passed as an argument:*/
			var x = document.getElementsByClassName("autocomplete-items");
			for (var i = 0; i < x.length; i++) {
				if (elmnt != x[i] && elmnt != inp) {
					x[i].parentNode.removeChild(x[i]);
				}
			}
		}
		/*execute a function when someone clicks in the document:*/
		document.addEventListener("click", function(e) {
			closeAllLists(e.target);
		});
	}

	autocomplete(document.getElementById("personal_name"), countries);

	$('select').on('change', function(e){
  		console.log(this.value, this.options[this.selectedIndex].value,$(this).find("option:selected").val(),);
		if (this.value=='personal')
			$('#personal_div').show()
		else
			$('#personal_div').hide()
	});

	function Save() {
		var flag = false;
		if (true === $('#message').parsley().validate()) {
			flag = true;
		}
		else
		{
			flag = false;
		}
		
		if(flag)
		{
			$_token = "{{ csrf_token() }}";
			$.ajax({
				url: "{{url('/notification/store')}}",
				method: 'POST',
				dataType: 'json',
				data: {
					type: $('#type_message').val(),
					message: $('#message').val(),
					personal_name: $('#personal_name').val(),
					_token: $_token,
				},
				success: function(data)
				{
					console.log(data)
                    $("#btn_Save" ).html(`Thêm mới` );
					if (data != false){
						$('#btn_create_success').click();
					}
					else
						$('#btn_checkuser').click();
					ClearData()
				},
				error: function (data) {
					$('#max_credit_show').html($('#max_credit').val());
					console.log('Error:', data);
					$("#btn_Save" ).html(`Thêm mới` );
				}
			});
		}else{
			$('#max_credit_show').html($('#max_credit').val());
			$("#btn_Save" ).html(`Thêm mới` );
		}
	}

	function ClearData(){
		$('#personal_name').val("");
		$('#message').val("");
		// // $('#username').prop("readonly", true);
		// // $('#password').prop("readonly", true);
		// $('#fullname').val("");
		// $('#thau_new').val("");
		// $('#credit').val("");
		// $('#lock').attr("checked",false);
		//$('#max_credit_show').html($('#max_credit').val().toLocateString());
	}
	$("#btn_Save" ).click(function() {
	    $(this).html(
            `<i class="fa fa-spinner fa-spin"></i>Đang tạo...`
         );
		Save();
	});

	$("#btn_ClearData" ).click(function() {
		ClearData();
	});
</script>

@endsection