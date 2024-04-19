<?php
$now = \Carbon\Carbon::now();
$yesterday = date('Y-m-d', time() - 86400);
$datepickerXS = date('d-m-Y', time() - 86400);
if (intval(date('H')) < 18 || (intval(date('H')) == 18 && intval(date('i')) < 30)) {
    $rs = xoso::getKetQua(1, $yesterday);
} else {
    $rs = xoso::getKetQua(1, date('Y-m-d'));
    $datepickerXS = date('d-m-Y');
}
$gameList = GameHelpers::GetAllGameByParentID(0);
?>
<style>
    .line-break {
        padding-top: 10px;
        padding-bottom: 5px;
        /* border-bottom: 1px solid rgba(0, 0, 0, 0.05);
							*/
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .title-sub-card {
        font-size: 13px;
        font-weight: 600;
        text-decoration: solid underline purple 1px;
    }

    .card-text-line-break {
        padding-left: 10px;
        padding-top: 5px;
        word-wrap: break-word;
        white-space: -moz-pre-wrap;
        white-space: pre-wrap;
    }
</style>
<style>
    .portlet-heading {
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    /* Optional styling */
    summary::-webkit-details-marker {
        color: blue;
    }

    summary:focus {
        outline-style: none;
    }
</style>

@extends("frontend.frontend-template")
@section('sidebar-menu')
@parent

@stop
@section("content")

<div class="row">
    <div class="col-sm-12">
        <div class="portlet"><!-- /primary heading -->
            <div class="portlet-heading">
                <h3 class="portlet-title text-dark text-uppercase">
                <?php
                    switch( $type ) {
                        case 0: echo 'Bảng cược'; break;
                        case 1: echo 'Sao kê'; break;
                        case 2: echo 'Phiếu cược huỷ'; break;
                    }
                ?>
                {{isset($day) ? $day : ""}}

                </h3>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@if(isset($day))
	<div class="row">
        <div class="col-sm-12">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading" style="display: flex;
        align-items: flex-start !important;
        flex-direction: column;">
                    <label class="portlet-title text-dark text-uppercase" style="font-size:13px;">
                    <a href="/reports" class="">< Quay về</a>
                    </label>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
	</div>
	@endif

@include('frontend.inbets_section')
<input type="hidden" id="current_game" value="">
<input type="hidden" id="loto-id-delete" value="">
<input type="hidden" id="url" value="{{url('/')}}">
<input type="hidden" id="urlH" value="{{url('/history-sk')}}">
<input type="hidden" id="token" value="{{ csrf_token() }}">
<a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
<a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã tạo thành công')"></a>
@endsection

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        $('.modal').each(function() {
            $(this).insertAfter($('#game-play'));
        });

        $(document).ready(function() {
            // $('#btn_view_history_sk').click();
        });

    });

    function refreshHistory() {
        $('#history').fadeOut();
        $('#history').load("{{url('/refresh-history')}}", function() {
            $('#history').fadeIn();
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    }

    /**
     * Hàm set giá trị id cho biến hidden đế thực hiện xóa
     * @param id
     */
    function setId(id, gameid) {
        if ($('#clock_' + gameid).html() == "00:00:00") {
            alert("Hết giờ hủy cược");
        } else {
            $('#loto-id-delete').val(id);
            swal({
                title: "Bạn có chắc chắn hủy? Tự đóng sau 5 giây",
                timer: 5000,
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Hủy",
                cancelButtonText: "Bỏ qua",
                closeOnConfirm: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $_token = $('#token').val();
                    $.ajax({
                        url: $('#url').val() + "/destroy/" + $('#loto-id-delete').val(),
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $_token,
                        },
                        success: function(data) {
                            swal("Đã Hủy!", "Bạn đã hủy thành công", "success");
                            var s = $('#time-zone').html();
                            if (!(!s || 0 === s.length)) {
                                var time_result = $('#time_result').val();
                                var d = s.split(' ');
                                var ddate = d[0].split('-');

                                // refreshHistory(d[0]);
                                location.reload();
                                refreshUser_Info();

                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    // swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
        }
    }
</script>