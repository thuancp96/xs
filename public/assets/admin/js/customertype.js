/**
 * Created by AnNH8 on 9/23/2016.
 */
/**
 * Create User Load
 */
$(document).ready(function() {
    $('.refresh').hide();

});
var changes = [];
function LoadContentGame(code) {
    $('.refresh').show();
    changes = [];
    // $('.type_content').fadeOut();
    $('#'+code).fadeOut();
    $('#'+code).load($('#urlUserpercent').val()+"/load-type-game/"+code, function() {
        $('#'+code).fadeIn();
        $('.refresh').hide();
    });
}

function LoadContentGameOriginal(code) {
    $('.refresh').show();
    changes = [];
    // $('.type_content').fadeOut();
    $('#'+code).fadeOut();
    $('#'+code).load($('#urlUserpercent').val()+"/load-type-game-original/"+code, function() {
        $('#'+code).fadeIn();
        $('.refresh').hide();
    });
}

function LoadContentGame(code,userid) {
    $('.refresh').show();
    changes = [];
    // $('.type_content').fadeOut();
    $('#'+code).fadeOut();
    $('#'+code).load($('#urlUserpercent').val()+"/load-type-game/"+code, function() {
        $('#'+code).fadeIn();
        $('.refresh').hide();
    });
}

function LoadContentGameByUser(code,userid) {
    $('.refresh').show();
    changes = [];
    // $('.type_content').fadeOut();
    $('#'+code).fadeOut();
    $('#'+code).load($('#urlUserpercent').val()+"/load-type-game-by-user/"+code+"/"+userid, function() {
        $('#'+code).fadeIn();
        $('.refresh').hide();
    });
}

function LoadContentGameLowpByUser(code,userid) {
    $('.refresh').show();
    changes = [];
    // $('.type_content').fadeOut();
    $('#giathap_'+code).fadeOut();
    $('#giathap_'+code).load($('#urlUserpercent').val()+"/load-type-game-lowp-by-user/"+code+"/"+userid, function() {
        $('#giathap_'+code).fadeIn();
        $('.refresh').hide();
    });
}

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function SaveChangeAllTypeByUserSuperMaxone(){

    SaveChangeTypeByUserSuperMaxone($('#currentuserid').val());
}

function SaveChangeAllTypeByUserSuperMaxex(){

    SaveChangeTypeByUserSuperMaxex($('#currentuserid').val());
}

function SaveChangeTypeByUserSuperMaxex(userid) {
    if(changes.length >0)
    {
        console.log("userid " + userid)
        $_token = $('#token').val();
        $.ajax({
            url: '/control-ex/store-by-super-maxex',
            method: 'POST',
            dataType: 'html',
            data: {
                changes: changes,
                userid:userid,
                _token: $_token,
            },
            success: function(data)
            {
                // $('#btn_CreateOK').click();
                // sweetAlert("Thông báo", 'Chỉnh sửa thành công', "success");
                swal({
                    title: "Thông báo",
                    text: "Chỉnh sửa thành công",
                    icon: "success",
                    timer: 10000,
                    button: "Đã hiểu",
                  });
                // $('.close').click();
            },
            error: function (data) {
                console.log('Error',data);
            }
        });
    }
}

function SaveChangeTypeByUserSuperMaxone(userid) {
    if(changes.length >0)
    {
        console.log("userid " + userid)
        $_token = $('#token').val();
        $.ajax({
            url: '/control-max/store-by-super-maxone',
            method: 'POST',
            dataType: 'html',
            data: {
                changes: changes,
                userid:userid,
                _token: $_token,
            },
            success: function(data)
            {
                // $('#btn_CreateOK').click();
                // sweetAlert("Thông báo", 'Chỉnh sửa thành công', "success");
                swal({
                    title: "Thông báo",
                    text: "Chỉnh sửa thành công",
                    icon: "success",
                    timer: 10000,
                    button: "Đã hiểu",
                  });
                // $('.close').click();
            },
            error: function (data) {
                console.log('Error',data);
            }
        });
    }
}

function SaveChangeAllType(){
    SaveChangeType();
}

function SaveChangeAllTypeLowp(){
    SaveChangeTypeLowp();
}

function SaveChangeAllTypeByUser(){

    SaveChangeTypeByUser($('#currentuserid').val());
}

function SaveChangeTypeByUser(userid) {
    if(changes.length >0)
    {
        $_token = $('#token').val();
        $.ajax({
            url: $('#urlUserpercent').val()+"/store-by-user",
            method: 'POST',
            dataType: 'html',
            data: {
                changes: changes,
                userid:userid,
                _token: $_token,
            },
            success: function(data)
            {
                // $('#btn_CreateOK').click();
                // sweetAlert("Thông báo", 'Chỉnh sửa thành công', "success");
                // swal({
                //     title: "Thông báo",
                //     text: "Chỉnh sửa thành công",
                //     icon: "success",
                //     timer: 10000,
                //     button: "Đã hiểu",
                //   });
                // $('.close').click();

                swal({
                    title: "Thông báo",
                    text: "Chỉnh sửa thành công",
                    icon: "success",
                    timer: 10000,
                    buttons: {
                      cancel: "Tiếp tục",
                      defeat: "Thoát",
                    },
                  })
                  .then((value) => {
                    switch (value) {
                
                      case "defeat":
                        $('.close').click();
                        break;
                
                      default:
                        break;
                    }
                  });
            },
            error: function (data) {
                console.log('Error',data);
            }
        });
    }
}


function SaveChangeTypeLowp() {
    if(changes.length >0)
    {
        $_token = $('#token').val();
        $.ajax({
            url: $('#urlUserpercent').val()+"/storelowp",
            method: 'POST',
            dataType: 'html',
            data: {
                changes: changes,
                _token: $_token,
            },
            success: function(data)
            {
                // $('#btn_CreateOK').click();
                // sweetAlert("Thông báo", 'Chỉnh sửa thành công', "success");
                swal({
                    title: "Thông báo",
                    text: "Chỉnh sửa thành công",
                    icon: "success",
                    timer: 10000,
                    button: "Đã hiểu",
                  });
            },
            error: function (data) {
                console.log('Error',data);
            }
        });
    }
}

function SaveChangeType() {
    if(changes.length >0)
    {
        $_token = $('#token').val();
        $.ajax({
            url: $('#urlUserpercent').val()+"/store",
            method: 'POST',
            dataType: 'html',
            data: {
                changes: changes,
                _token: $_token,
            },
            success: function(data)
            {
                // $('#btn_CreateOK').click();
                // sweetAlert("Thông báo", 'Chỉnh sửa thành công', "success");
                swal({
                    title: "Thông báo",
                    text: "Chỉnh sửa thành công",
                    icon: "success",
                    timer: 10000,
                    button: "Đã hiểu",
                  });
            },
            error: function (data) {
                console.log('Error',data);
            }
        });
    }
}

function InputChange(input,game_code,type) {
    var min = $('#min_'+game_code+'_'+type).val();
    var max = $('#max_'+game_code+'_'+type).val();
    var odds = $('#odds_'+game_code+'_'+type).val();
    var max_point = $('#max_point_'+game_code+'_'+type).val();
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val();
    var max_max_point_one = $('#max_max_point_one_'+game_code+'_'+type).val();
    var max_max_point = $('#max_max_point_'+game_code+'_'+type).val();
    var change_odds = $('#change_odds_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max = $('#change_max_'+game_code+'_'+type).val()=="1"?true:false;
    var change_ex = $('#change_ex_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max_one = $('#change_max_one_'+game_code+'_'+type).val()=="1"?true:false;
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            if($(input).val() >= min)
            {
                changes[i].exchange =$(input).val();
                flag = false;
            }
            else
            {
                alert("Bạn phải nhập giá trị >= "+min);
                $(input).val(min);
                $(input).focus();
                
            }
        }
    }
    if(flag)
    {

        if($(input).val() >= min)
        {
            changes.push({
                name: game_code,
                exchange: $(input).val(),
                odds: odds,
                type: type,
                min:min,
                max:max,
                max_point:max_point,
                max_point_one:max_point_one,
                max_max_point_one:max_max_point_one,
                max_max_point:max_max_point,
                change_odds:change_odds,
                change_max:change_max,
                change_ex:change_ex,
                change_max_one:change_max_one,
            });
        }
        else
        {
            alert("Bạn phải nhập giá trị >= "+min);
            $(input).val(min);
            $(input).focus();
            
        }

    }
}
function InputChangeOdds(odds,game_code,type) {
    var min = $('#min_'+game_code+'_'+type).val();
    var max = $('#max_'+game_code+'_'+type).val();
    var input = $('#input_'+game_code+'_'+type).val();
    var max_point = $('#max_point_'+game_code+'_'+type).val();
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val();
    var max_max_point_one = $('#max_max_point_one_'+game_code+'_'+type).val();
    var max_max_point = $('#max_max_point_'+game_code+'_'+type).val();
    var change_odds = $('#change_odds_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max = $('#change_max_'+game_code+'_'+type).val()=="1"?true:false;
    var change_ex = $('#change_ex_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max_one = $('#change_max_one_'+game_code+'_'+type).val()=="1"?true:false;
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            if($(odds).val() <= max)
            {
                changes[i].odds =$(odds).val();
                flag = false;
            }
            else
            {
                alert("Bạn phải nhập giá trị <= "+max);
                $(odds).val(max);
                $(odds).focus();
                
            }
        }
    }
    if(flag)
    {
        if($(odds).val() <= max)
        {
            changes.push({
                name: game_code,
                exchange: input,
                odds : $(odds).val(),
                type: type,
                min:min,
                max:max,
                max_point:max_point,
                max_point_one:max_point_one,
                max_max_point_one:max_max_point_one,
                max_max_point:max_max_point,
                change_odds:change_odds,
                change_max:change_max,
                change_ex:change_ex,
                change_max_one:change_max_one,
            });
            flag = false;
        }
        else
        {
            alert("Bạn phải nhập giá trị <= "+max);
            $(odds).val(max);
            $(odds).focus();
            
        }

    }
}
function InputChangeMax(max_point,game_code,type) {
    var min = $('#min_'+game_code+'_'+type).val();
    var max = $('#max_'+game_code+'_'+type).val();
    var odds = $('#odds_'+game_code+'_'+type).val();
    var input = $('#input_'+game_code+'_'+type).val();
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val();
    var max_max_point_one = $('#max_max_point_one_'+game_code+'_'+type).val();
    var max_max_point = $('#max_max_point_'+game_code+'_'+type).val();
    var change_odds = $('#change_odds_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max = $('#change_max_'+game_code+'_'+type).val()=="1"?true:false;
    var change_ex = $('#change_ex_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max_one = $('#change_max_one_'+game_code+'_'+type).val()=="1"?true:false;
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            if($(max_point).val() <= max_max_point)
            {
                changes[i].max_point =$(max_point).val();
                flag = false;
            }
            else
            {
                alert("Bạn phải nhập giá trị <= "+max_max_point);
                $(max_point).val(max_max_point);
                $(max_point).focus();
                
            }
        }
    }
    if(flag)
    {

        if($(max_point).val() <= max_max_point)
        {
            changes.push({
                name: game_code,
                exchange: input,
                odds: odds,
                type: type,
                min:min,
                max:max,
                max_point:$(max_point).val(),
                max_point_one:max_point_one,
                max_max_point_one:max_max_point_one,
                max_max_point:max_max_point,
                change_odds:change_odds,
                change_max:change_max,
                change_ex:change_ex,
                change_max_one:change_max_one,
            });
        }
        else
        {
            alert("Bạn phải nhập giá trị <= "+max_max_point);
            $(max_point).val(max_max_point);
            $(max_point).focus();
            
        }

    }
}
function InputChangeMaxOne(max_point_one,game_code,type) {
    var min = $('#min_'+game_code+'_'+type).val();
    var max = $('#max_'+game_code+'_'+type).val();
    var odds = $('#odds_'+game_code+'_'+type).val();
    var input = $('#input_'+game_code+'_'+type).val();
    var max_point = $('#max_point_'+game_code+'_'+type).val();
    var max_max_point_one = $('#max_max_point_one_'+game_code+'_'+type).val();
    var max_max_point = $('#max_max_point_'+game_code+'_'+type).val();
    var change_odds = $('#change_odds_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max = $('#change_max_'+game_code+'_'+type).val()=="1"?true:false;
    var change_ex = $('#change_ex_'+game_code+'_'+type).val()=="1"?true:false;
    var change_max_one = $('#change_max_one_'+game_code+'_'+type).val()=="1"?true:false;
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            if($(max_point_one).val() <= max_max_point_one)
            {
                changes[i].max_point_one =$(max_point_one).val();
                flag = false;
            }
            else
            {
                alert("Bạn phải nhập giá trị <= "+max_max_point_one);
                $(max_point_one).val(max_max_point_one);
                $(max_point_one).focus();
                
            }
        }
    }
    if(flag)
    {
        if($(max_point_one).val() <= max_max_point_one)
        {
            changes.push({
                name: game_code,
                exchange: input,
                odds : odds,
                type: type,
                min:min,
                max:max,
                max_point:max_point,
                max_point_one:$(max_point_one).val(),
                max_max_point_one:max_max_point_one,
                max_max_point:max_max_point,
                change_odds:change_odds,
                change_max:change_max,
                change_ex:change_ex,
                change_max_one:change_max_one,
            });
            flag = false;
        }
        else
        {
            alert("Bạn phải nhập giá trị <= "+max_max_point_one);
            $(max_point_one).val(max_max_point_one);
            $(max_point_one).focus();
            
        }

    }
}

function AdminInputChange(input,game_code,type,norepeate=true) {
    $(input).val(Number($(input).val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = 0;
    try {
        cbone = $('#change_max_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    }
    catch(err) {
    
    }
    
    var flag = true;
    var exchangee = parseInt($(input).val().replace(/[^0-9\.]+/g,""));
    var minexchangee = parseInt($(input).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    var maxechangee = 1000;
    if (game_code =='7' || game_code == '8' || game_code == '18' || game_code =='107' || game_code == '108' || game_code == '118')
        maxechangee = 23000;
    if (game_code =='29' || game_code == '329' || game_code == '429' || game_code =='529' || game_code == '629')
        maxechangee = 2000;
    

    if (exchangee > maxechangee){
        if (game_code =='7' || game_code == '8' || game_code == '18' || game_code =='107' || game_code == '108' || game_code == '118')
        {
            // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa của Chuẩn '+type+' là ' + 23000);
            // sweetAlert("Lỗi", 'Mức tối đa của Chuẩn '+type+' là ' + 23000, "error");
            swal({
                title: "Thông báo",
                text: 'Mức tối đa của Chuẩn '+type+' là ' + 23000,
                icon: "warning",
                timer: 5000,
                button: "Đã hiểu",
              });
        $(input).val(23000);
           }else
           if (game_code =='29' || game_code == '329' || game_code == '429' || game_code =='529' || game_code == '629'){
                // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa của Chuẩn '+type+' là ' + 2000);
                // sweetAlert("Lỗi", 'Mức tối đa của Chuẩn '+type+' là ' + 2000, "error");
                swal({
                    title: "Thông báo",
                    text: 'Mức tối đa của Chuẩn '+type+' là ' + 2000,
                    icon: "warning",
                    timer: 5000,
                    button: "Đã hiểu",
                  });
                $(input).val(2000);
           }else{
            // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa của Chuẩn '+type+' là ' + 1000);
            // sweetAlert("Lỗi", 'Mức tối đa của Chuẩn '+type+' là ' + 1000, "error");
            swal({
                title: "Thông báo",
                text: 'Mức tối đa của Chuẩn '+type+' là ' + 1000,
                icon: "warning",
                timer: 5000,
                button: "Đã hiểu",
              });

            $(input).val(1000);
           }
        
        // $(input).attr('value',1000);
    }

    if (exchangee < minexchangee)
    {
        // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu của Chuẩn '+type+' là ' + minexchangee);
        // sweetAlert("Lỗi", 'Mức tối thiểu của Chuẩn '+type+' là ' + minexchangee, "error");
        swal({
            title: "Thông báo",
            text: 'Mức tối thiểu của Chuẩn '+type+' là ' + minexchangee,
            icon: "warning",
            timer: 5000,
            button: "Đã hiểu",
          });

        // alert("Mức tối thiểu là " + minexchangee);
        $(input).val(minexchangee);
        
    }
    
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name && type == changes[i].type)
        {
            changes[i].exchange =$(input).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }
    // try{
    // if (norepeate){
    // if (type=='A'){
    //         var upB = Number($('#input_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(input).val().replace(/[^0-9\.]+/g,"")) - Number($(input).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#input_'+game_code+'_'+'B').val(upB);
    //         $('#input_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChange($('#input_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upC = Number($('#input_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(input).val().replace(/[^0-9\.]+/g,"")) - Number($(input).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#input_'+game_code+'_'+'C').val(upC);
    //         $('#input_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChange($('#input_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='B'){
    //         var upA = Number($('#input_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(input).val().replace(/[^0-9\.]+/g,"")) - Number($(input).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#input_'+game_code+'_'+'A').val(upA);
    //         $('#input_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChange($('#input_'+game_code+'_'+'A'),game_code,'A',false);

    //         var upC = Number($('#input_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(input).val().replace(/[^0-9\.]+/g,"")) - Number($(input).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#input_'+game_code+'_'+'C').val(upC);
    //         $('#input_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChange($('#input_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='C'){
    //         var upB = Number($('#input_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(input).val().replace(/[^0-9\.]+/g,"")) - Number($(input).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#input_'+game_code+'_'+'B').val(upB);
    //         $('#input_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChange($('#input_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upA = Number($('#input_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(input).val().replace(/[^0-9\.]+/g,"")) - Number($(input).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#input_'+game_code+'_'+'A').val(upA);
    //         $('#input_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChange($('#input_'+game_code+'_'+'A'),game_code,'A',false);
    //     }
    // }
    // }catch(err){}
    $(input).attr('value',$(input).val());

    if(flag)
    {
        changes.push({
            name: game_code,
            exchange: $(input).val().replace(/[^0-9\.]+/g,""),
            odds: odds,
            type: type,
            max_point:max_point,
            max_point_one:max_point_one,
            change_ex:cbex,
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:cbone,
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#input_'+(Number(game_code)+100)+'_'+type).val($(input).val());
        $('#input_'+(Number(game_code)+100)+'_'+type).attr('value',$(input).val());
        AdminInputChange(input,(Number(game_code)+100)+'',type);

        
        $('#input_'+(Number(game_code)+200)+'_'+type).val($(input).val());
        $('#input_'+(Number(game_code)+200)+'_'+type).attr('value',$(input).val());
        AdminInputChange(input,(Number(game_code)+200)+'',type);
        
        $('#input_'+(Number(game_code)+300)+'_'+type).val($(input).val());
        $('#input_'+(Number(game_code)+300)+'_'+type).attr('value',$(input).val());
        AdminInputChange(input,(Number(game_code)+300)+'',type);
    }

    if (Number(game_code) - Number(game_code)%100 == 700 && type == 'A'){
        $('#input_'+(Number(game_code))+'_'+'B').val($(input).val());
        $('#input_'+(Number(game_code))+'_'+'B').attr('value',$(input).val());
        AdminInputChange(input,(Number(game_code))+'','B');

        
        $('#input_'+(Number(game_code))+'_'+'C').val($(input).val());
        $('#input_'+(Number(game_code))+'_'+'C').attr('value',$(input).val());
        AdminInputChange(input,(Number(game_code))+'','C');
        
        $('#input_'+(Number(game_code))+'_'+'D').val($(input).val());
        $('#input_'+(Number(game_code))+'_'+'D').attr('value',$(input).val());
        AdminInputChange(input,(Number(game_code))+'','D');
    }
}
function AdminInputChangeOdds(odds,game_code,type,norepeate=true) {
    $(odds).val(Number($(odds).val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = 0;
    try {
        cbone = $('#change_max_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    }
    catch(err) {
    
    }
    var flag = true;

    var oddss = parseInt($(odds).val().replace(/[^0-9\.]+/g,""));
    var minoddss = parseInt($(odds).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    var maxoddss = parseInt($(odds).attr("data-parsley-max").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)

    if (oddss < minoddss && (game_code >4000 && game_code < 5000))
    {
        // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + minoddss);
        // sweetAlert("Lỗi", 'Mức tối thiểu là ' + minoddss, "error");
        swal({
            title: "Thông báo",
            text: 'Mức tối thiểu là ' + minoddss,
            icon: "warning",
            timer: 5000,
            button: "Đã hiểu",
          });
        // alert("Mức tối thiểu là " + minexchangee);
        $(odds).val(minoddss);
        return;
    }

    if (oddss > maxoddss)
    {
        // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxoddss);
        // sweetAlert("Lỗi", 'Mức tối đa là ' + maxoddss, "error");
        swal({
            title: "Thông báo",
            text: 'Mức tối đa là ' + maxoddss,
            icon: "warning",
            timer: 5000,
            button: "Đã hiểu",
          });
        // alert("Mức tối thiểu là " + minexchangee);
        $(odds).val(maxoddss);
        return;
    }

    // if (oddss > maxoddss && (game_code != 15 && game_code != 16))
    // {
    //     $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxoddss);

    //     // alert("Mức tối thiểu là " + minexchangee);
    //     $(odds).val(maxoddss);
    //     return;
    // }

    // if (oddss < maxoddss && (game_code == 15 || game_code == 16))
    // {
    //     $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + maxoddss);

    //     // alert("Mức tối thiểu là " + minexchangee);
    //     $(odds).val(maxoddss);
    //     return;
    // }

    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name && type == changes[i].type)
        {
            changes[i].odds =$(odds).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }
    // if (norepeate){
    // if (type=='A'){
    //         var upB = Number($('#odds_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(odds).val().replace(/[^0-9\.]+/g,"")) - Number($(odds).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#odds_'+game_code+'_'+'B').val(upB);
    //         $('#odds_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChangeOdds($('#odds_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upC = Number($('#odds_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(odds).val().replace(/[^0-9\.]+/g,"")) - Number($(odds).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#odds_'+game_code+'_'+'C').val(upC);
    //         $('#odds_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChangeOdds($('#odds_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='B'){
    //         var upA = Number($('#odds_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(odds).val().replace(/[^0-9\.]+/g,"")) - Number($(odds).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#odds_'+game_code+'_'+'A').val(upA);
    //         $('#odds_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChangeOdds($('#odds_'+game_code+'_'+'A'),game_code,'A',false);

    //         var upC = Number($('#odds_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(odds).val().replace(/[^0-9\.]+/g,"")) - Number($(odds).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#odds_'+game_code+'_'+'C').val(upC);
    //         $('#odds_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChangeOdds($('#odds_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='C'){
    //         var upB = Number($('#odds_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(odds).val().replace(/[^0-9\.]+/g,"")) - Number($(odds).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#odds_'+game_code+'_'+'B').val(upB);
    //         $('#odds_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChangeOdds($('#odds_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upA = Number($('#odds_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(odds).val().replace(/[^0-9\.]+/g,"")) - Number($(odds).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#odds_'+game_code+'_'+'A').val(upA);
    //         $('#odds_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChangeOdds($('#odds_'+game_code+'_'+'A'),game_code,'A',false);
    //     }
    // }
    $(odds).attr('value',$(odds).val());
    if(flag)
    {
        changes.push({
            name: game_code,
            exchange: input,
            odds : $(odds).val().replace(/[^0-9\.]+/g,""),
            type: type,
            max_point:max_point,
            max_point_one:max_point_one,
            change_ex:cbex,
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:cbone,
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#odds_'+(Number(game_code)+100)+'_'+type).val($(odds).val());
        $('#odds_'+(Number(game_code)+100)+'_'+type).attr('value',$(odds).val());
        AdminInputChangeOdds(odds,(Number(game_code)+100)+'',type);

        
        $('#odds_'+(Number(game_code)+200)+'_'+type).val($(odds).val());
        $('#odds_'+(Number(game_code)+200)+'_'+type).attr('value',$(odds).val());
        AdminInputChangeOdds(odds,(Number(game_code)+200)+'',type);
        
        $('#odds_'+(Number(game_code)+300)+'_'+type).val($(odds).val());
        $('#odds_'+(Number(game_code)+300)+'_'+type).attr('value',$(odds).val());
        AdminInputChangeOdds(odds,(Number(game_code)+300)+'',type);
    }

    if (Number(game_code) - Number(game_code)%100 == 700 && type == 'A'){
        $('#odds_'+(Number(game_code))+'_'+'B').val($(odds).val());
        $('#odds_'+(Number(game_code))+'_'+'B').attr('value',$(odds).val());
        AdminInputChangeOdds(odds,(Number(game_code))+'','B');

        
        $('#odds_'+(Number(game_code))+'_'+'C').val($(odds).val());
        $('#odds_'+(Number(game_code))+'_'+'C').attr('value',$(odds).val());
        AdminInputChangeOdds(odds,(Number(game_code))+'','C');
        
        $('#odds_'+(Number(game_code))+'_'+'D').val($(odds).val());
        $('#odds_'+(Number(game_code))+'_'+'D').attr('value',$(odds).val());
        AdminInputChangeOdds(odds,(Number(game_code))+'','D');
    }
}
function AdminInputChangeMaxPoint(max,game_code,type) {
    $(max).val(Number($(max).val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = 0;
    try {
        cbone = $('#change_max_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    }
    catch(err) {
    
    }
    var flag = true;

    var maxx = parseInt($(max).val().replace(/[^0-9\.]+/g,""));
    var maxmaxx = parseInt($(max).attr("data-parsley-max").replace(/[^0-9\.]+/g,""));
    var minmaxx = parseInt($(max).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    if (maxx > maxmaxx)
    {
        // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxmaxx);
        // sweetAlert("Lỗi", 'Mức tối đa là ' + maxmaxx, "error");
        swal({
            title: "Thông báo",
            text: 'Mức tối đa là ' + maxmaxx,
            icon: "warning",
            timer: 5000,
            button: "Đã hiểu",
          });
        // alert("Mức tối thiểu là " + minexchangee);
        $(max).val(Number((maxmaxx+"").replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US'));
        // flag = false;
        // return;
    }

    if (maxx < minmaxx && game_code < 100)
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + Number((minmaxx+"").replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US'));

        // alert("Mức tối thiểu là " + minexchangee);
        $(max).val(Number((minmaxx+"").replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US'));
        // flag = false;
        // return;
    }

    if (maxx > max_point_one && game_code > 4000)
    {
        // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + max_point_one);
        // sweetAlert("Lỗi", 'Mức tối đa là ' + max_point_one, "error");
        swal({
            title: "Thông báo",
            text: 'Mức tối đa là ' + max_point_one,
            icon: "warning",
            timer: 5000,
            button: "Đã hiểu",
          });
        // alert("Mức tối thiểu là " + minexchangee);
        $(max).val(max_point_one);
        return;
    }

    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name && type == changes[i].type)
        {
            changes[i].max_point =$(max).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }

    // if (norepeate){
    // if (type=='A'){
    //         var upB = Number($('#max_point_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(max).val().replace(/[^0-9\.]+/g,"")) - Number($(max).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_'+game_code+'_'+'B').val(upB);
    //         $('#max_point_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChangeMaxPoint($('#max_point_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upC = Number($('#max_point_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(max).val().replace(/[^0-9\.]+/g,"")) - Number($(max).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_'+game_code+'_'+'C').val(upC);
    //         $('#max_point_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChangeMaxPoint($('#max_point_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='B'){
    //         var upA = Number($('#max_point_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(max).val().replace(/[^0-9\.]+/g,"")) - Number($(max).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_'+game_code+'_'+'A').val(upA);
    //         $('#max_point_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChangeMaxPoint($('#max_point_'+game_code+'_'+'A'),game_code,'A',false);

    //         var upC = Number($('#max_point_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(max).val().replace(/[^0-9\.]+/g,"")) - Number($(max).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_'+game_code+'_'+'C').val(upC);
    //         $('#max_point_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChangeMaxPoint($('#max_point_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='C'){
    //         var upB = Number($('#max_point_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(max).val().replace(/[^0-9\.]+/g,"")) - Number($(max).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_'+game_code+'_'+'B').val(upB);
    //         $('#max_point_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChangeMaxPoint($('#max_point_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upA = Number($('#max_point_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(max).val().replace(/[^0-9\.]+/g,"")) - Number($(max).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_'+game_code+'_'+'A').val(upA);
    //         $('#max_point_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChangeMaxPoint($('#max_point_'+game_code+'_'+'A'),game_code,'A',false);
    //     }
    // }
    $(max).attr('value',$(max).val());

    if(flag)
    {
        changes.push({
            name: game_code,
            exchange: input,
            odds : odds,
            type: type,
            max_point:$(max).val().replace(/[^0-9\.]+/g,""),
            max_point_one:max_point_one,
            change_ex:cbex,
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:cbone
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#max_point_'+(Number(game_code)+100)+'_'+type).val($(max).val());
        $('#max_point_'+(Number(game_code)+100)+'_'+type).attr('value',$(max).val());
        AdminInputChangeMaxPoint(max,(Number(game_code)+100)+'',type);

        
        $('#max_point_'+(Number(game_code)+200)+'_'+type).val($(max).val());
        $('#max_point_'+(Number(game_code)+200)+'_'+type).attr('value',$(max).val());
        
        AdminInputChangeMaxPoint(max,(Number(game_code)+200)+'',type);
        $('#max_point_'+(Number(game_code)+300)+'_'+type).val($(max).val());
        $('#max_point_'+(Number(game_code)+300)+'_'+type).attr('value',$(max).val());
        AdminInputChangeMaxPoint(max,(Number(game_code)+300)+'',type);
    }

    if (Number(game_code) - Number(game_code)%100 == 700 && type == 'A'){
        $('#max_point_'+(Number(game_code))+'_'+'B').val($(max).val());
        $('#max_point_'+(Number(game_code))+'_'+'B').attr('value',$(max).val());
        AdminInputChangeOdds(max,(Number(game_code))+'','B');

        
        $('#max_point_'+(Number(game_code))+'_'+'C').val($(max).val());
        $('#max_point_'+(Number(game_code))+'_'+'C').attr('value',$(max).val());
        AdminInputChangeOdds(max,(Number(game_code))+'','C');
        
        $('#max_point_'+(Number(game_code))+'_'+'D').val($(max).val());
        $('#max_point_'+(Number(game_code))+'_'+'D').attr('value',$(max).val());
        AdminInputChangeOdds(max,(Number(game_code))+'','D');
    }
}

function AdminInputChangeRatioEx(ratio_ex,game_code,type) {
    // $(ratio_ex).val(Number($(ratio_ex).val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
    var max_ex = $('#max_ex_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var ratio_ex = $('#ratio_ex_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    
    var flag = true;

    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name && type == changes[i].type)
        {
            changes[i].ratio_ex =$(ratio_ex).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }

    $(ratio_ex).attr('value',$(ratio_ex).val());

    if(flag)
    {
        changes.push({
            name: game_code,
            ratio_ex : ratio_ex,
            max_ex : max_ex,
            type: type,
        });
    }
}

function AdminInputChangeMaxpointone(one,game_code,type,norepeate=true) {
    $(one).val(Number($(one).val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = 0;
    try {
        cbone = $('#change_max_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    }
    catch(err) {
    
    }
    var flag = true;

    var onee = parseInt($(one).val().replace(/[^0-9\.]+/g,""));
    var maxonee = parseInt($(one).attr("data-parsley-max").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    if (onee > maxonee)
    {
        // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxonee);
        // sweetAlert("Lỗi", 'Mức tối đa là ' + maxonee, "error");
        swal({
            title: "Thông báo",
            text: 'Mức tối đa là ' + maxonee,
            icon: "warning",
            timer: 5000,
            button: "Đã hiểu",
          });
        // alert("Mức tối thiểu là " + minexchangee);
        $(one).val(Number((maxonee+"").replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US'));
        
    }

    // if (onee < max_point)
    // {
    //     $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + max_point);

    //     // alert("Mức tối thiểu là " + minexchangee);
    //     $(one).val(max_point);
    //     // return;
    // }
    
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name && type == changes[i].type)
        {
            changes[i].max_point_one =$(one).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }

    // if (norepeate){
    // if (type=='A'){
    //         var upB = Number($('#max_point_one_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(one).val().replace(/[^0-9\.]+/g,"")) - Number($(one).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_one_'+game_code+'_'+'B').val(upB);
    //         $('#max_point_one_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChangeMaxpointone($('#max_point_one_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upC = Number($('#max_point_one_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(one).val().replace(/[^0-9\.]+/g,"")) - Number($(one).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_one_'+game_code+'_'+'C').val(upC);
    //         $('#max_point_one_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChangeMaxpointone($('#max_point_one_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='B'){
    //         var upA = Number($('#max_point_one_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(one).val().replace(/[^0-9\.]+/g,"")) - Number($(one).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_one_'+game_code+'_'+'A').val(upA);
    //         $('#max_point_one_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChangeMaxpointone($('#max_point_one_'+game_code+'_'+'A'),game_code,'A',false);

    //         var upC = Number($('#max_point_one_'+game_code+'_'+'C').val().replace(/[^0-9\.]+/g,"")) + Number($(one).val().replace(/[^0-9\.]+/g,"")) - Number($(one).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_one_'+game_code+'_'+'C').val(upC);
    //         $('#max_point_one_'+game_code+'_'+'C').attr('value',upC);
    //         AdminInputChangeMaxpointone($('#max_point_one_'+game_code+'_'+'C'),game_code,'C',false);
    //     }
    // if (type=='C'){
    //         var upB = Number($('#max_point_one_'+game_code+'_'+'B').val().replace(/[^0-9\.]+/g,"")) + Number($(one).val().replace(/[^0-9\.]+/g,"")) - Number($(one).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_one_'+game_code+'_'+'B').val(upB);
    //         $('#max_point_one_'+game_code+'_'+'B').attr('value',upB);
    //         AdminInputChangeMaxpointone($('#max_point_one_'+game_code+'_'+'B'),game_code,'B',false);

    //         var upA = Number($('#max_point_one_'+game_code+'_'+'A').val().replace(/[^0-9\.]+/g,"")) + Number($(one).val().replace(/[^0-9\.]+/g,"")) - Number($(one).attr('value').replace(/[^0-9\.]+/g,""));
    //         $('#max_point_one_'+game_code+'_'+'A').val(upA);
    //         $('#max_point_one_'+game_code+'_'+'A').attr('value',upA);
    //         AdminInputChangeMaxpointone($('#max_point_one_'+game_code+'_'+'A'),game_code,'A',false);
    //     }
    // }
    $(one).attr('value',$(one).val());
    
    if(flag)
    {
        changes.push({ name: game_code,
            exchange: input,
            odds : odds,
            type: type,
            max_point:max_point,
            max_point_one:$(one).val().replace(/[^0-9\.]+/g,""),
            change_ex:cbex,
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:cbone
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#max_point_one_'+(Number(game_code)+100)+'_'+type).val($(one).val());
        $('#max_point_one_'+(Number(game_code)+100)+'_'+type).attr('value',$(one).val());
        AdminInputChangeMaxpointone(one,(Number(game_code)+100)+'',type);

        
        $('#max_point_one_'+(Number(game_code)+200)+'_'+type).val($(one).val());
        $('#max_point_one_'+(Number(game_code)+200)+'_'+type).attr('value',$(one).val());
        AdminInputChangeMaxPoint(one,(Number(game_code)+200)+'',type);
        
        
        $('#max_point_one_'+(Number(game_code)+300)+'_'+type).val($(one).val());
        $('#max_point_one_'+(Number(game_code)+300)+'_'+type).attr('value',$(one).val());
        AdminInputChangeMaxPoint(one,(Number(game_code)+300)+'',type);
    }

    if (Number(game_code) - Number(game_code)%100 == 700 && type == 'A'){
        $('#max_point_one_'+(Number(game_code))+'_'+'B').val($(one).val());
        $('#max_point_one_'+(Number(game_code))+'_'+'B').attr('value',$(one).val());
        AdminInputChangeOdds(one,(Number(game_code))+'','B');

        
        $('#max_point_one_'+(Number(game_code))+'_'+'C').val($(one).val());
        $('#max_point_one_'+(Number(game_code))+'_'+'C').attr('value',$(one).val());
        AdminInputChangeOdds(one,(Number(game_code))+'','C');

        
        $('#max_point_one_'+(Number(game_code))+'_'+'D').val($(one).val());
        $('#max_point_one_'+(Number(game_code))+'_'+'D').attr('value',$(one).val());
        AdminInputChangeOdds(one,(Number(game_code))+'','D');
    }
}
function AdminCbExChange(cbex,game_code,type) {
    var input = $('#input_'+game_code+'_'+type).val();
    var max_point = $('#max_point_'+game_code+'_'+type).val();
    var odds = $('#odds_'+game_code+'_'+type).val();
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val();
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = 0;
    try {
        cbone = $('#change_max_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    }
    catch(err) {
    
    }
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].change_ex =$(cbex).is(":checked");
            flag = false;
        }
    }
    if(flag)
    {
        changes.push({ name: game_code,
            exchange: input,
            odds : odds,
            type: type,
            max_point:max_point,
            max_point_one:max_point_one,
            change_ex:$(cbex).is(":checked"),
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:cbone
        });
    }
}
function AdminCboddsChange(cbodd,game_code,type) {
    var input = $('#input_'+game_code+'_'+type).val();
    var max_point = $('#max_point_'+game_code+'_'+type).val();
    var odds = $('#odds_'+game_code+'_'+type).val();
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val();
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = 0;
    try {
        cbone = $('#change_max_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    }
    catch(err) {
    
    }
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].change_odds =$(cbodd).is(":checked");
            flag = false;
        }
    }
    if(flag)
    {
        changes.push({ name: game_code,
            exchange: input,
            odds : odds,
            type: type,
            max_point:max_point,
            max_point_one:max_point_one,
            change_ex:cbex,
            change_odds:$(cbodd).is(":checked"),
            change_max:cbmax,
            change_max_one:cbone

        });
    }
}
function AdminCbmaxChange(cbmax,game_code,type) {
    var input = $('#input_'+game_code+'_'+type).val();
    var max_point = $('#max_point_'+game_code+'_'+type).val();
    var odds = $('#odds_'+game_code+'_'+type).val();
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val();
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbone = 0;
    try {
        cbone = $('#change_max_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    }
    catch(err) {
    
    }
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].change_max =$(cbmax).is(":checked");
            flag = false;
        }
    }
    if(flag)
    {
        changes.push({ name: game_code,
            exchange: input,
            odds : odds,
            type: type,
            max_point:max_point,
            max_point_one:max_point_one,
            change_ex:cbex,
            change_odds:cbodds,
            change_max:$(cbmax).is(":checked"),
            change_max_one:cbone
        });
    }
}
function AdminCbmaxoneChange(cbone,game_code,type) {
    $(cbone).val(Number($(cbone).val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var flag = true;

    var maxxonee = parseInt($(cbone).val().replace(/[^0-9\.]+/g,""));
    var maxonee = parseInt($(cbone).attr("data-parsley-max").replace(/[^0-9\.]+/g,""));
    // var minonee = parseInt($(cbone).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    if (maxxonee > maxonee)
    {
        // $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxonee);
        // sweetAlert("Lỗi", 'Mức tối đa là ' + maxonee, "error");
        swal({
            title: "Thông báo",
            text: 'Mức tối đa là ' + maxonee,
            icon: "warning",
            timer: 5000,
            button: "Đã hiểu",
          });
        // alert("Mức tối thiểu là " + minexchangee);
        $(cbone).val(maxonee);
        
    }

    // if (maxxonee < minonee)
    // {
    //     $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + max_point);

    //     // alert("Mức tối thiểu là " + minexchangee);
    //     $(cbone).val(minonee);
    //     // return;
    // }

    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].change_max_one =$(cbone).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }
    if(flag)
    {
        changes.push({ name: game_code,
            exchange: input,
            odds : odds,
            type: type,
            max_point:max_point,
            max_point_one:max_point_one,
            change_ex:cbex,
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:$(cbone).val().replace(/[^0-9\.]+/g,"")
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#change_max_one_'+(Number(game_code)+100)+'_'+type).val($(cbone).val());
        $('#change_max_one_'+(Number(game_code)+100)+'_'+type).attr('value',$(cbone).val());
        AdminCbmaxoneChange(cbone,(Number(game_code)+100)+'',type);

        $('#change_max_one_'+(Number(game_code)+200)+'_'+type).val($(cbone).val());
        $('#change_max_one_'+(Number(game_code)+200)+'_'+type).attr('value',$(cbone).val());
        AdminCbmaxoneChange(cbone,(Number(game_code)+200)+'',type);
        
        $('#change_max_one_'+(Number(game_code)+300)+'_'+type).val($(cbone).val());
        $('#change_max_one_'+(Number(game_code)+300)+'_'+type).attr('value',$(cbone).val());
        AdminCbmaxoneChange(cbone,(Number(game_code)+300)+'',type);
    }
}

function AdminAChange(inputA,game_code) {
    // var aa = $(inputA).val().replace(/[^0-9\.]+/g,"");
    var aa = $('#a_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa2 = $('#a2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa3 = $('#a3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx = $('#x_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx2 = $('#x2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx3 = $('#x3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy = $('#y_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy2 = $('#y2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy3 = $('#y3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    
    var flag = true;
    
    if(flag)
    {
        changes.push({
            name: game_code,
            aa: aa,
            aa2: aa2,
            aa3: aa3,
            xx: xx,
            xx2: xx2,
            xx3: xx3,
            yy: yy,
            yy2: yy2,
            yy3: yy3,
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#a_'+(Number(game_code)+100)).val($('#a_'+game_code).val());
        $('#a_'+(Number(game_code)+100)).attr('value',$('#a_'+game_code).val());
        AdminAChange('cbone',(Number(game_code)+100)+'');

        $('#a_'+(Number(game_code)+200)).val($('#a_'+game_code).val());
        $('#a_'+(Number(game_code)+200)).attr('value',$('#a_'+game_code).val());
        AdminAChange('cbone',(Number(game_code)+200)+'');
        
        $('#a_'+(Number(game_code)+300)).val($('#a_'+game_code).val());
        $('#a_'+(Number(game_code)+300)).attr('value',$('#a_'+game_code).val());
        AdminAChange('cbone',(Number(game_code)+300)+'');
    }
}

function AdminXChange(inputX,game_code) {
    // var aa = $('#a_'+game_code).val().replace(/[^0-9\.]+/g,"");
    // var xx = $(inputX).val().replace(/[^0-9\.]+/g,"");
    // var yy = $('#y_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa = $('#a_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa2 = $('#a2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa3 = $('#a3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx = $('#x_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx2 = $('#x2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx3 = $('#x3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy = $('#y_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy2 = $('#y2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy3 = $('#y3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    
    var flag = true;
    
    if(flag)
    {
        changes.push({
            name: game_code,
            aa: aa,
            aa2: aa2,
            aa3: aa3,
            xx: xx,
            xx2: xx2,
            xx3: xx3,
            yy: yy,
            yy2: yy2,
            yy3: yy3,
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#x_'+(Number(game_code)+100)).val($('#x_'+game_code).val());
        $('#x_'+(Number(game_code)+100)).attr('value',$('#x_'+game_code).val());
        AdminXChange('cbone',(Number(game_code)+100)+'');

        $('#x_'+(Number(game_code)+200)).val($('#x_'+game_code).val());
        $('#x_'+(Number(game_code)+200)).attr('value',$('#x_'+game_code).val());
        AdminXChange('cbone',(Number(game_code)+200)+'');
        
        $('#x_'+(Number(game_code)+300)).val($('#x_'+game_code).val());
        $('#x_'+(Number(game_code)+300)).attr('value',$('#x_'+game_code).val());
        AdminXChange('cbone',(Number(game_code)+300)+'');
    }
}

function AdminYChange(inputY,game_code) {
    // var aa = $('#a_'+game_code).val().replace(/[^0-9\.]+/g,"");
    // var xx = $('#x_'+game_code).val().replace(/[^0-9\.]+/g,"");
    // var yy = $(inputY).val().replace(/[^0-9\.]+/g,"");
    
    var aa = $('#a_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa2 = $('#a2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa3 = $('#a3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx = $('#x_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx2 = $('#x2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx3 = $('#x3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy = $('#y_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy2 = $('#y2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy3 = $('#y3_'+game_code).val().replace(/[^0-9\.]+/g,"");

    var flag = true;
    
    if(flag)
    {
        changes.push({
            name: game_code,
            aa: aa,
            aa2: aa2,
            aa3: aa3,
            xx: xx,
            xx2: xx2,
            xx3: xx3,
            yy: yy,
            yy2: yy2,
            yy3: yy3,
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#y_'+(Number(game_code)+100)).val($('#y_'+game_code).val());
        $('#y_'+(Number(game_code)+100)).attr('value',$('#y_'+game_code).val());
        AdminYChange('cbone',(Number(game_code)+100)+'');

        $('#y_'+(Number(game_code)+200)).val($('#y_'+game_code).val());
        $('#y_'+(Number(game_code)+200)).attr('value',$('#y_'+game_code).val());
        AdminYChange('cbone',(Number(game_code)+200)+'');
        
        $('#y_'+(Number(game_code)+300)).val($('#y_'+game_code).val());
        $('#y_'+(Number(game_code)+300)).attr('value',$('#y_'+game_code).val());
        AdminYChange('cbone',(Number(game_code)+300)+'');
    }
}

function AdminA2Change(inputY,game_code) {
    // var aa = $('#a_'+game_code).val().replace(/[^0-9\.]+/g,"");
    // var xx = $('#x_'+game_code).val().replace(/[^0-9\.]+/g,"");
    // var yy = $(inputY).val().replace(/[^0-9\.]+/g,"");
    
    var aa = $('#a_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa2 = $('#a2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var aa3 = $('#a3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx = $('#x_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx2 = $('#x2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var xx3 = $('#x3_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy = $('#y_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy2 = $('#y2_'+game_code).val().replace(/[^0-9\.]+/g,"");
    var yy3 = $('#y3_'+game_code).val().replace(/[^0-9\.]+/g,"");

    var flag = true;
    
    if(flag)
    {
        changes.push({
            name: game_code,
            aa: aa,
            aa2: aa2,
            aa3: aa3,
            xx: xx,
            xx2: xx2,
            xx3: xx3,
            yy: yy,
            yy2: yy2,
            yy3: yy3,
        });
    }

    if (Number(game_code) - Number(game_code)%100 == 300){
        $('#a2_'+(Number(game_code)+100)).val($('#a2_'+game_code).val());
        $('#a2_'+(Number(game_code)+100)).attr('value',$('#a2_'+game_code).val());
        AdminYChange('cbone',(Number(game_code)+100)+'');

        $('#a2_'+(Number(game_code)+200)).val($('#a2_'+game_code).val());
        $('#a2_'+(Number(game_code)+200)).attr('value',$('#a2_'+game_code).val());
        AdminYChange('cbone',(Number(game_code)+200)+'');
        
        $('#a2_'+(Number(game_code)+300)).val($('#a2_'+game_code).val());
        $('#a2_'+(Number(game_code)+300)).attr('value',$('#a2_'+game_code).val());
        AdminYChange('cbone',(Number(game_code)+300)+'');
    }
}

function SaveChangeAXY() {
    if(changes.length >0)
    {
        $_token = $('#token').val();
        $.ajax({
            url: $('#urlUserpercent').val()+"/store",
            method: 'POST',
            dataType: 'html',
            data: {
                changes: changes,
                _token: $_token,
            },
            success: function(data)
            {
                // $('#btn_CreateOK').click();
                swal({
                    title: "Thông báo",
                    text: "Chỉnh sửa thành công",
                    icon: "success",
                    timer: 10000,
                    button: "Đã hiểu",
                  });
                // sweetAlert("Thông báo", 'Chỉnh sửa thành công', "success");

                },
            error: function (data) {
                console.log('Error',data);
            }
        });
    }
}

function AdminCbmaxoneChangeControlmax(cbone,game_code,type) {
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var flag = true;
    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].change_max_one =$(cbone).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }
    if(flag)
    {
        changes.push({ name: game_code,
            exchange: input,
            odds : odds,
            type: type,
            max_point:max_point,
            max_point_one:max_point_one,
            change_ex:cbex,
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:$(cbone).val().replace(/[^0-9\.]+/g,"")
        });
    }
}