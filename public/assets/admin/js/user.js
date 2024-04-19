/**
 * Created by AnNH8 on 9/23/2016.
 */
/**
 * Create User Load
 */
$(document).ready(function() {
    $('.autonumber').autoNumeric('init');
    $('#create-user-form').parsley();
    $('#edit-user-form').parsley();
    $('#username').blur(function() {
        // check('username');
    });
});
/**
 * List User Load
 */
$(document).ready(function() {
    // var t = $('#datatable').DataTable({
    //     "paging": false,
    //     "oLanguage": { "sZeroRecords": "Chưa có dữ liệu", "sEmptyTable": "Chưa có dữ liệu" },
    //     "bLengthChange": false,
    //     "ordering": false,
    //     "info": false,
    //     "columnDefs": [{
    //         "searchable": false,
    //         "orderable": false,
    //         "targets": 0
    //     }],
    //     "order": [
    //         [1, 'asc']
    //     ],
    // });
    // t.on('order.dt search.dt', function() {
    //     t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
    //         // cell.innerHTML = i+1;
    //     });
    // }).draw();

    // function filterGlobal() {
    //     $('#datatable').DataTable().search(
    //         $('#input_search').val()
    //     ).draw();
    // }
    // $('#input_search').keyup(function(event) {
    //     filterGlobal();
    // });
    // $('.dataTables_filter').hide();

});
/**
 * Hàm set giá trị id cho biến hidden đế thực hiện xóa
 * @param id
 */
function setId(id) {
    $('#user-id-delete').val(id);
}
/**
 * Sự kiện khi ấn button delete
 */
$('.btn_delete').click(function() {
    // swal({
    //     title: "Bạn có chắc chắn xóa?",
    //     text: "",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#DD6B55",
    //     confirmButtonText: "Xóa",
    //     cancelButtonText: "Hủy",
    //     closeOnConfirm: false
    // }, function(isConfirm) {
    //     if (isConfirm) {
    //         $_token = $('#token').val();
    //         $.ajax({
    //             url: $('#url').val() + "/destroy/" + $('#user-id-delete').val(),
    //             method: 'POST',
    //             dataType: 'json',
    //             data: {
    //                 _token: $_token,
    //             },
    //             success: function(data) {
    //                 swal("Đã xóa!", "Bạn đã thành công", "success");
    //                 refreshTable();
    //             },
    //             error: function(data) {}
    //         });
    //     } else {
    //         // swal("Cancelled", "Your imaginary file is safe :)", "error");
    //     }
    // });

    swal("Bạn có chắc chắn xóa?", {
        buttons: {
          cancel: "Hủy",
          defeat: "Xóa",
        },
      })
      .then((value) => {
        switch (value) {
       
          case "defeat":
            $_token = $('#token').val();
            $.ajax({
                url: $('#url').val() + "/destroy/" + $('#user-id-delete').val(),
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: $_token,
                },
                success: function(data) {
                    swal("Đã xóa!", "Bạn đã thành công", "success");
                    // refreshTable();
                    location.reload();
                },
                error: function(data) {}
            });
            break;
       
          default:
            break;
        }
      });
});

$('.btn_delete_second').click(function() {
    // swal({
    //     title: "Bạn có chắc chắn xóa?",
    //     text: "",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#DD6B55",
    //     confirmButtonText: "Xóa",
    //     cancelButtonText: "Hủy",
    //     closeOnConfirm: false
    // }, function(isConfirm) {
    //     if (isConfirm) {
    //         $_token = $('#token').val();
    //         $.ajax({
    //             url: $('#url').val() + "/destroy/" + $('#user-id-delete').val(),
    //             method: 'POST',
    //             dataType: 'json',
    //             data: {
    //                 _token: $_token,
    //             },
    //             success: function(data) {
    //                 swal("Đã xóa!", "Bạn đã thành công", "success");
    //                 // refreshTable();
    //                 location.reload();
    //             },
    //             error: function(data) {}
    //         });
    //     } else {
    //         // swal("Cancelled", "Your imaginary file is safe :)", "error");
    //     }
    // });

    swal("Bạn có chắc chắn xóa?", {
        buttons: {
          cancel: "Hủy",
          defeat: "Xóa",
        },
      })
      .then((value) => {
        switch (value) {
       
          case "defeat":
            $_token = $('#token').val();
            $.ajax({
                url: $('#url').val() + "/destroy/" + $('#user-id-delete').val(),
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: $_token,
                },
                success: function(data) {
                    swal("Đã xóa!", "Bạn đã thành công", "success");
                    // refreshTable();
                    location.reload();
                },
                error: function(data) {}
            });
            break;
       
          default:
            break;
        }
      });

});

$('.btn_lock_second').click(function() {
    // swal({
    //     title: "Bạn có khóa/mở tài khoản?",
    //     text: "",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#DD6B55",
    //     confirmButtonText: "Khóa/Mở",
    //     cancelButtonText: "Hủy",
    //     closeOnConfirm: false
    // }, function(isConfirm) {
    //     if (isConfirm) {
    //         $_token = $('#token').val();
    //         $.ajax({
    //             url: $('#url').val() + "/locksecond/" + $('#user-id-delete').val(),
    //             method: 'POST',
    //             dataType: 'json',
    //             data: {
    //                 _token: $_token,
    //             },
    //             success: function(data) {
    //                 swal("Đã xử lý!", "Xử lý đã thành công", "success");
    //                 // refreshTable();
    //                 location.reload();
    //             },
    //             error: function(data) {}
    //         });
    //     } else {
    //         // swal("Cancelled", "Your imaginary file is safe :)", "error");
    //     }
    // });

    swal("Bạn có khóa/mở tài khoản?", {
        buttons: {
          cancel: "Hủy",
          defeat: "Khóa/Mở",
        },
      })
      .then((value) => {
        switch (value) {
       
          case "defeat":
            $_token = $('#token').val();
            $.ajax({
                url: $('#url').val() + "/locksecond/" + $('#user-id-delete').val(),
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: $_token,
                },
                success: function(data) {
                    swal("Đã xử lý!", "Xử lý đã thành công", "success");
                    // refreshTable();
                    location.reload();
                },
                error: function(data) {}
            });
            break;
       
          default:
            break;
        }
      });

});

$('.btn_reset_otp').click(function() {

    swal("Bạn có chắc chắn reset OTP?", {
        buttons: {
          cancel: "Hủy",
          defeat: "Reset",
        },
      })
      .then((value) => {
        switch (value) {
       
          case "defeat":
            $_token = $('#token').val();
            $.ajax({
                url: $('#url').val() + "/resetotp/" + $('#user-id-delete').val(),
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: $_token,
                },
                success: function(data) {
                    swal("Đã reset!", "Bạn đã reset OTP thành công", "success");
                    refreshTable();
                },
                error: function(data) {}
            });
            break;
       
          default:
            break;
        }
      });

    // swal({
    //     title: "Bạn có chắc chắn reset OTP?",
    //     text: "",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#DD6B55",
    //     confirmButtonText: "Reset",
    //     cancelButtonText: "Hủy",
    //     closeOnConfirm: false
    // }, function(isConfirm) {
    //     if (isConfirm) {
            
    //     } else {
    //         // swal("Cancelled", "Your imaginary file is safe :)", "error");
    //     }
    // });
});

$('.btn_reset_token_telegram').click(function() {

  swal("Bạn có chắc chắn Reset Token Telegram cho "+ $('#username'+$('#user-id-delete').val()).html() +" ?", {
      buttons: {
        cancel: "Hủy",
        defeat: "Reset",
      },
    })
    .then((value) => {
      switch (value) {
     
        case "defeat":
          $_token = $('#token').val();
          $.ajax({
              url: $('#url').val() + "/reset-token-telegram/" + $('#user-id-delete').val(),
              method: 'POST',
              dataType: 'json',
              data: {
                  _token: $_token,
              },
              success: function(data) {
                  swal("Đã reset!", "Bạn đã reset token telegram thành công", "success");
                  location.reload();
              },
              error: function(data) {}
          });
          break;
     
        default:
          break;
      }
    });

  // swal({
  //     title: "Bạn có chắc chắn reset OTP?",
  //     text: "",
  //     type: "warning",
  //     showCancelButton: true,
  //     confirmButtonColor: "#DD6B55",
  //     confirmButtonText: "Reset",
  //     cancelButtonText: "Hủy",
  //     closeOnConfirm: false
  // }, function(isConfirm) {
  //     if (isConfirm) {
          
  //     } else {
  //         // swal("Cancelled", "Your imaginary file is safe :)", "error");
  //     }
  // });
});

const unsecuredCopyToClipboard = (text) => { const textArea = document.createElement("textarea"); textArea.value=text; document.body.appendChild(textArea); textArea.focus();textArea.select(); try{document.execCommand('copy')}catch(err){console.error('Unable to copy to clipboard',err)}document.body.removeChild(textArea)};

/**
 * Copies the text passed as param to the system clipboard
 * Check if using HTTPS and navigator.clipboard is available
 * Then uses standard clipboard API, otherwise uses fallback
*/
const copyToClipboard = (content) => {
  if (window.isSecureContext && navigator.clipboard) {
    navigator.clipboard.writeText(content);
  } else {
    unsecuredCopyToClipboard(content);
  }
};

$('.btn_create_token_telegram').click(function() {

  $_token = $('#token').val();
  $.ajax({
    url: $('#url').val() + "/create-token-tele/" + $('#user-id-delete').val(),
    method: 'POST',
    dataType: 'json',
    data: {
        _token: $_token,
    },
    success: function(data) {
      console.log(data)
        swal("Token đã được copy vào clipboard!", data.data, "success");
        // refreshTable();
        copyToClipboard(data.data)
        // Copy the text inside the text field
        // navigator.clipboard.writeText(copyText.value);
    },
    error: function(data) {}
});
});