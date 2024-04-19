/**
 * Created by AnNH8 on 9/23/2016.
 */
/**
 * Create User Load
 */
$(document).ready(function() {

  //   $("select").selectBoxIt({

  //       // Uses the jQuery 'fadeIn' effect when opening the drop down
  //       showEffect: "fadeIn",

  //       // Sets the jQuery 'fadeIn' effect speed to 400 milleseconds
  //       showEffectSpeed: 400,

  //       // Uses the jQuery 'fadeOut' effect when closing the drop down
  //       hideEffect: "fadeOut",

  //       // Sets the jQuery 'fadeOut' effect speed to 400 milleseconds
  //       hideEffectSpeed: 400

  // });

    $('#change-control-form').parsley();
    $('.refresh').hide();
    // g_count();
    $('.popover-markup>.trigger').popover({
        html: true,
        title: function () {
            return $(this).parent().find('.head').html();
        },
        content: function () {
            return $(this).parent().find('.content').html();
        }
    });
    var $container = $('.portfolioContainer');
    $container.isotope({
        filter: '*',
        animationOptions: {
            duration: 750,
            easing: 'linear',
            queue: false
        }
    });
    $('.portfolioFilter a').click(function(){
        $('.port').show();
        $('.portfolioFilter .current').removeClass('current');
        $(this).addClass('current');
        var selector = $(this).attr('data-filter');
        $container.isotope({
            filter: selector,
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
        return false;
    });
    $('.port').hide();
    time_remain();
});

function highlight(elem) {
  var left = 0
  function frame() {
    left++  // update parameters
    if (left%2 !=0)
        elem.addClass('highlight');
    else
        elem.removeClass('highlight');
    if (left == 10)  // check finish condition
      clearInterval(id)
  }
  var id = setInterval(frame, 300) // draw every 10ms
}


function time_remain(){

    if ($('#gamecode').val() != "")
    {
        game_code_temp = $('#gamecode').val()
        $_token = $('#token').val();
        $.ajax({
            url: $('#url').val()+"/getnewdata",
            method: 'POST',
            dataType: 'json',
            data: {
                game_code:$('#gamecode').val(),
                _token: $_token,
            },
            success: function(data)
            {
                // $('#'+gamecode+"_"+i+j+k).fadeOut();
                // $('#'+gamecode+"_"+i+j+k).load($('#url').val()+"/refresh-number1000/"+gamecode+"/"+i+"/"+j+"/"+k, function() {
                //     $('#'+gamecode+"_"+i+j+k).fadeIn();
                // });
                // alert(data.size);
                if ($('#gamecode').val() != game_code_temp) return;
                if ($('#gamecode').val() == "8" || $('#gamecode').val() == "108" || $('#gamecode').val() == "17" || $('#gamecode').val() == "117"){
                    try {
                    for(i=0;i<10;i++)
                        for(j=0;j<10;j++)
                            for(k=0;j<10;k++)
                        {
                            
                            var isHL = false;
                            if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][0] ){
                                var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_chg').attr('onclick');
                                var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange').html().replace(/[^0-9\.]+/g,""));
                                $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j+''+k][0]));
                                $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_input').attr('value',data[i+''+j+''+k][0]);
                                
                                $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange').html(data[i+''+j+''+k][0].toLocaleString('en'));

                                
                                    isHL = true;
                            }
                            if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .y').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][1] ){
                                $('#'+$('#gamecode').val()+'_'+i+''+j+' .y').html('+ '+data[i+''+j+''+k][1]);
                                    isHL = true;
                            }
                            if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .x').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][2] ){
                                $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .x').html(data[i+''+j+''+k][2]);
                                    isHL = true;
                            }
                            try{
                                if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .total').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][3] ){
                                    $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .total').html(data[i+''+j+''+k][3].toLocaleString('en'));
                                        isHL = true;
                                }

                                if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .totalThau').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][4] ){
                                    $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .totalThau').html('* '+data[i+''+j+''+k][4].toLocaleString('en'));
                                        isHL = true;
                                }
                            }catch(err) {}
                            if (isHL)
                                highlight($('#'+$('#gamecode').val()+'_'+i+''+j+''+k));
                            // 
                            }
                        }
                    catch(err) {
                                
                            }

                }else{
                    for(i=0;i<10;i++)
                        for(j=0;j<10;j++)
                        {
                            var isHL = false;
                            if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][0] ){
                                var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick');
                                var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
                                $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j][0]));
                                $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_input').attr('value',data[i+''+j][0]);
                                
                                $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange').html(data[i+''+j][0]);

                                
                                    isHL = true;
                            }
                            if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .y').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][1] ){
                                $('#'+$('#gamecode').val()+'_'+i+''+j+' .y').html('+ '+ data[i+''+j][1]);
                                    //isHL = true;
                            }
                            if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][2] ){
                                $('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html(data[i+''+j][2]);
                                    isHL = true;
                            }
                            try{
                                if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][3] ){
                                    $('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html(data[i+''+j][3].toLocaleString('en'));
                                        isHL = true;
                                }
                                
                                if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .totalThau').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][4] ){
                                    $('#'+$('#gamecode').val()+'_'+i+''+j+' .totalThau').html('* '+data[i+''+j][4].toLocaleString('en'));
                                        isHL = true;
                                }
                            }catch(err) {}

                            if (isHL)
                                highlight($('#'+$('#gamecode').val()+'_'+i+''+j));
                            // 
                        }
                }
            },
            error: function (data) {
            }
        });
    }
    setTimeout('time_remain()',Number($('#custype').val())*1000);
}

function ChangeEx(btn,i,j,gamecode,type,min) {
    var t = parseInt($(btn).parent().parent().children(':first-child').val());
    if (gamecode=='exchange_rates' && (t<=0 || t<min))
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + min);
        return;
    }
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val()+"/update",
        method: 'POST',
        dataType: 'json',
        data: {
            value:t,
            number: i+j,
            game_code:gamecode,
            type:type,
            _token: $_token,
        },
        success: function(data)
        {
            $('#'+gamecode+"_"+i+j).fadeOut();
            $('#'+gamecode+"_"+i+j).load($('#url').val()+"/refresh-number/"+gamecode+"/"+i+"/"+j, function() {
                $('#'+gamecode+"_"+i+j).fadeIn();
            });
        },
        error: function (data) {
        }
    });

}

function ChangeY(btn,i,j,gamecode,type,min) {
    // console.log(btn);
    // console.log($(btn).parent().parent());
    // console.log($(btn).parent().parent().children(':first-child').children(':nth-child(2)'));
    var t = parseInt($(btn).parent().parent().children(':first-child').children(':nth-child(2)').val());
    var ex = Number($('#'+gamecode+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
    if (gamecode=='exchange_rates' && (t<=0 || t<min))
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + min);
        return;
    }
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val()+"/update",
        method: 'POST',
        dataType: 'json',
        data: {
            y:t,
            number: i+j,
            game_code:gamecode,
            ex: ex,
            type:type,
            _token: $_token,
        },
        success: function(data)
        {
            $('#'+gamecode+"_"+i+j).fadeOut();
            $('#'+gamecode+"_"+i+j).load($('#url').val()+"/refresh-number/"+gamecode+"/"+i+"/"+j, function() {
                $('#'+gamecode+"_"+i+j).fadeIn();
            });
        },
        error: function (data) {
        }
    });

}

function ConfirmDialogQuickLock(btn,type,status) {
    numberStr = $('#number_select_text').val()
    gamecode = $('#gamecode').val()
    message = "Bạn có muốn khóa cược mã " + numberStr + " không?"
    if (status == "1"){
        message = "Bạn có muốn mở khóa cược mã " + numberStr + " không?"
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: 'Confirm message',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                Yes: function() {
                    // $(obj).removeAttr('onclick');                                
                    // $(obj).parents('.Parent').remove();
        
                    // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    QuickLockNumbers(btn,numberStr,gamecode,type,status)
                    $(this).dialog("close");
                    $('#number_select_text').val('')
                },
                No: function() {
                    // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                    $(this).dialog("close");
                }
                },
                close: function(event, ui) {
                $(this).remove();
                }
            });
    }else{
        message = "Bạn có muốn khóa cược mã " + numberStr + " không?"
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: 'Confirm message',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                "Khoá đen": function() {
                    // $(obj).removeAttr('onclick');                                
                    // $(obj).parents('.Parent').remove();
        
                    // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    QuickLockNumbers(btn,numberStr,gamecode,type+"black",status)
                    $(this).dialog("close");
                    $('#number_select_text').val('')
                },
                "Khoá đỏ": function() {
                    // $(obj).removeAttr('onclick');                                
                    // $(obj).parents('.Parent').remove();
        
                    // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    QuickLockNumbers(btn,numberStr,gamecode,type+"red",status)
                    $(this).dialog("close");
                    $('#number_select_text').val('')
                },
                "Huỷ": function() {
                    // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                    $(this).dialog("close");
                }
                },
                close: function(event, ui) {
                $(this).remove();
                }
            });
    }
  };

  function ConfirmDialogQuickLockRed(btn,type,status) {
    numberStr = $('#number_select_text').val()
    gamecode = $('#gamecode').val()
    message = "Bạn có muốn khóa cược mã " + numberStr + " không?"
    if (status == "1"){
        message = "Bạn có muốn mở khóa cược mã " + numberStr + " không?"
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: 'Confirm message',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                Yes: function() {
                    // $(obj).removeAttr('onclick');                                
                    // $(obj).parents('.Parent').remove();
        
                    // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    QuickLockNumbers(btn,numberStr,gamecode,type,status)
                    $(this).dialog("close");
                    $('#number_select_text').val('')
                },
                No: function() {
                    // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                    $(this).dialog("close");
                }
                },
                close: function(event, ui) {
                $(this).remove();
                }
            });
    }else{
        message = "Bạn có muốn khóa cược mã " + numberStr + " không?"
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: 'Confirm message',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                // "Khoá đen": function() {
                //     // $(obj).removeAttr('onclick');                                
                //     // $(obj).parents('.Parent').remove();
        
                //     // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                //     QuickLockNumbers(btn,numberStr,gamecode,type+"black",status)
                //     $(this).dialog("close");
                //     $('#number_select_text').val('')
                // },
                "Khoá đỏ": function() {
                    // $(obj).removeAttr('onclick');                                
                    // $(obj).parents('.Parent').remove();
        
                    // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    QuickLockNumbers(btn,numberStr,gamecode,type+"red",status)
                    $(this).dialog("close");
                    $('#number_select_text').val('')
                },
                "Huỷ": function() {
                    // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                    $(this).dialog("close");
                }
                },
                close: function(event, ui) {
                $(this).remove();
                }
            });
    }
  };

  function ConfirmDialogQuickLockBlack(btn,type,status) {
    numberStr = $('#number_select_text').val()
    gamecode = $('#gamecode').val()
    message = "Bạn có muốn khóa cược mã " + numberStr + " không?"
    if (status == "1"){
        message = "Bạn có muốn mở khóa cược mã " + numberStr + " không?"
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: 'Confirm message',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                Yes: function() {
                    // $(obj).removeAttr('onclick');                                
                    // $(obj).parents('.Parent').remove();
        
                    // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    QuickLockNumbers(btn,numberStr,gamecode,type,status)
                    $(this).dialog("close");
                    $('#number_select_text').val('')
                },
                No: function() {
                    // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                    $(this).dialog("close");
                }
                },
                close: function(event, ui) {
                $(this).remove();
                }
            });
    }else{
        message = "Bạn có muốn khóa cược mã " + numberStr + " không?"
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: 'Confirm message',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                "Khoá đen": function() {
                    // $(obj).removeAttr('onclick');                                
                    // $(obj).parents('.Parent').remove();
        
                    // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    QuickLockNumbers(btn,numberStr,gamecode,type+"black",status)
                    $(this).dialog("close");
                    $('#number_select_text').val('')
                },
                // "Khoá đỏ": function() {
                //     // $(obj).removeAttr('onclick');                                
                //     // $(obj).parents('.Parent').remove();
        
                //     // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                //     QuickLockNumbers(btn,numberStr,gamecode,type+"red",status)
                //     $(this).dialog("close");
                //     $('#number_select_text').val('')
                // },
                "Huỷ": function() {
                    // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                    $(this).dialog("close");
                }
                },
                close: function(event, ui) {
                $(this).remove();
                }
            });
    }
  };

function ConfirmDialog(btn,i,j,gamecode,type,status) {
    message = "Bạn có muốn khóa cược mã " + i+j + " không?"
    if (status == "1"){
        message = "Bạn có muốn mở khóa cược mã " + i+j + " không?"
        $('<div></div>').appendTo('body')
          .html('<div><h6>' + message + '?</h6></div>')
          .dialog({
            modal: true,
            title: 'Confirm message',
            zIndex: 10000,
            autoOpen: true,
            width: 'auto',
            resizable: false,
            buttons: {
              "Mở khoá": function() {
                // $(obj).removeAttr('onclick');                                
                // $(obj).parents('.Parent').remove();
      
                // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                LockNumber(btn,i,j,gamecode,'unlocknumberblackred',status)
                $(this).dialog("close");
              },
              "Huỷ": function() {
                // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                $(this).dialog("close");
              }
            },
            close: function(event, ui) {
              $(this).remove();
            }
          });
    }else{
        message = "Bạn có muốn khóa cược mã " + i+j + " không?"
        $('<div></div>').appendTo('body')
        .html('<div><h6>' + message + '?</h6></div>')
        .dialog({
            modal: true,
            title: 'Confirm message',
            zIndex: 10000,
            autoOpen: true,
            width: 'auto',
            resizable: false,
            buttons: {
            "Khoá đen": function() {
                // $(obj).removeAttr('onclick');                                
                // $(obj).parents('.Parent').remove();
    
                // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                LockNumber(btn,i,j,gamecode,type+'black',status)
                $(this).dialog("close");
            },
            "Khoá đỏ": function() {
                // $(obj).removeAttr('onclick');                                
                // $(obj).parents('.Parent').remove();
    
                // $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                LockNumber(btn,i,j,gamecode,type+'red',status)
                $(this).dialog("close");
            },
            "Huỷ": function() {
                // $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                $(this).dialog("close");
            }
            },
            close: function(event, ui) {
            $(this).remove();
            }
        });
        }
        
  };

function LockNumber(btn,i,j,gamecode,type,status) {
    console.log(type);
    // console.log($(btn).parent().parent());
    // console.log($(btn).parent().parent().children(':first-child').children(':nth-child(2)'));
    // var t = parseInt($(btn).parent().parent().children(':first-child').children(':nth-child(2)').val());
    // var ex = Number($('#'+gamecode+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
    // if (gamecode=='exchange_rates' && (t<=0 || t<min))
    // {
    //     $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + min);
    //     return;
    // }
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val()+"/update",
        method: 'POST',
        dataType: 'json',
        data: {
            // y:t,
            number: i+j,
            game_code:gamecode,
            status: status,
            type:type,
            _token: $_token,
        },
        success: function(data)
        {
            $('#'+gamecode+"_"+i+j).fadeOut();
            $('#'+gamecode+"_"+i+j).load($('#url').val()+"/refresh-number/"+gamecode+"/"+i+"/"+j, function() {
                $('#'+gamecode+"_"+i+j).fadeIn();
            });
        },
        error: function (data) {
        }
    });

}

function QuickLockNumbers(btn,numberStr,gamecode,type,status) {
    // console.log(btn);
    // console.log($(btn).parent().parent());
    // console.log($(btn).parent().parent().children(':first-child').children(':nth-child(2)'));
    // var t = parseInt($(btn).parent().parent().children(':first-child').children(':nth-child(2)').val());
    // var ex = Number($('#'+gamecode+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
    // if (gamecode=='exchange_rates' && (t<=0 || t<min))
    // {
    //     $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + min);
    //     return;
    // }
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val()+"/update",
        method: 'POST',
        dataType: 'json',
        data: {
            // y:t,
            number: numberStr,
            game_code:gamecode,
            status: status,
            type:type,
            _token: $_token,
        },
        success: function(data)
        {
            var result = numberStr.split(',');
            result.forEach(element => {
                $('#' + gamecode + "_" + element).fadeOut();
                $('#' + gamecode + "_" + element).load($('#url').val() + "/refresh-number/" + gamecode + "/" + parseInt(element / 10) + "/" + (element - parseInt(element / 10)*10), function () {
                    $('#' + gamecode + "_" + element).fadeIn();
                });
            });
            
        },
        error: function (data) {
        }
    });

}

function ChangeInputY(btn,i,j,gamecode,type,min) {
    // console.log(parseInt($(btn).parent().parent().children(':nth-child(2)').val()));
    var ex = Number($('#'+gamecode+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
    changeValue = 0;
    if (ex > 10000 )
        changeValue = 100;
    else changeValue = 10;

    if (type == 'ChangeInputAddY')
        $(btn).parent().parent().children(':nth-child(2)').val ( parseInt($(btn).parent().parent().children(':nth-child(2)').val()) + changeValue);
    else
        $(btn).parent().parent().children(':nth-child(2)').val ( parseInt($(btn).parent().parent().children(':nth-child(2)').val()) - changeValue);
    // var ex = Number($('#'+gamecode+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
    // if (gamecode=='exchange_rates' && (t<=0 || t<min))
    // {
    //     $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + min);
    //     return;
    // }
    // $_token = $('#token').val();
    // $.ajax({
    //     url: $('#url').val()+"/update",
    //     method: 'POST',
    //     dataType: 'json',
    //     data: {
    //         y:t,
    //         number: i+j,
    //         game_code:gamecode,
    //         ex: ex,
    //         type:type,
    //         _token: $_token,
    //     },
    //     success: function(data)
    //     {
    //         $('#'+gamecode+"_"+i+j).fadeOut();
    //         $('#'+gamecode+"_"+i+j).load($('#url').val()+"/refresh-number/"+gamecode+"/"+i+"/"+j, function() {
    //             $('#'+gamecode+"_"+i+j).fadeIn();
    //         });
    //     },
    //     error: function (data) {
    //     }
    // });

}

function ChangeEx1000(btn,i,j,k,gamecode,type) {
    var t = $(btn).parent().parent().children(':first-child').val();
    if (gamecode=='exchange_rates' && (t<=0 || t<min))
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + min);
        return;
    }
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val()+"/update",
        method: 'POST',
        dataType: 'json',
        data: {
            value:t,
            number: i+j+k,
            game_code:gamecode,
            type:type,
            _token: $_token,
        },
        success: function(data)
        {
            $('#'+gamecode+"_"+i+j+k).fadeOut();
            $('#'+gamecode+"_"+i+j+k).load($('#url').val()+"/refresh-number1000/"+gamecode+"/"+i+"/"+j+"/"+k, function() {
                $('#'+gamecode+"_"+i+j+k).fadeIn();
            });
        },
        error: function (data) {
        }
    });
}
function LoadContentNumber(gamecode) {
    if(gamecode != 1 && gamecode!=2 && gamecode!=3  && gamecode!=101 && gamecode!=102 && gamecode!=103
        && gamecode != 301 && gamecode!=302 && gamecode!=303 && gamecode!=700 && gamecode!=702) {
        // && gamecode != 24
        $('.refresh').show();
        $('#' + gamecode).fadeOut();
        $('#' + gamecode).load($('#url').val() + "/load-number/" + gamecode, function () {
            $('#' + gamecode).fadeIn();
            // if (gamecode==22 || gamecode==23 || gamecode==25 || gamecode==26 || gamecode==27 || gamecode==28)
            //     gamecode=24;
            $('#gamecode').val(gamecode);
            $('.refresh').hide();
        });
    }
}

