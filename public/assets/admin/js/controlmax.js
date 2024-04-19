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

function SaveChangeAllType(){
    SaveChangeType();
}

function SaveChangeAllTypeByUser(){

    SaveChangeTypeByUser($('#currentuserid').val());
}

function SaveChangeAllTypeByUserSuperMaxone(){

    SaveChangeTypeByUserSuperMaxone($('#currentuserid').val());
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
                $('#btn_CreateOK').click();
                // $('.close').click();
            },
            error: function (data) {
                console.log('Error',data);
            }
        });
    }
}

function SaveChangeTypeByUser(userid) {
    if(changes.length >0)
    {
        console.log("userid " + userid)
        $_token = $('#token').val();
        $.ajax({
            url: '/control-max/store-by-user',
            method: 'POST',
            dataType: 'html',
            data: {
                changes: changes,
                userid:userid,
                _token: $_token,
            },
            success: function(data)
            {
                $('#btn_CreateOK').click();
                // $('.close').click();
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
                $('#btn_CreateOK').click();
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
                return;
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
            return;
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
                return;
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
            return;
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
                return;
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
            return;
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
                return;
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
            return;
        }

    }
}

function AdminInputChange(input,game_code,type) {
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = $('#check_maxone_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var flag = true;
    var exchangee = parseInt($(input).val().replace(/[^0-9\.]+/g,""));
    var minexchangee = parseInt($(input).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    var maxechangee = 1000;
    if (game_code =='7' || game_code == '8' || game_code == '18')
        maxechangee = 23000;

    if (exchangee > maxechangee){
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + 1000);
        $(input).val(1000);
    }

    if (exchangee < minexchangee)
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + minexchangee);

        // alert("Mức tối thiểu là " + minexchangee);
        $(input).val(minexchangee);
        return;
    }
    
    // for (var i =0; i < changes.length; i++)
    // {
    //     if(game_code == changes[i].name)
    //     {
    //         changes[i].exchange =$(input).val().replace(/[^0-9\.]+/g,"");
    //         flag = false;
    //     }
    // }
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
}
function AdminInputChangeOdds(odds,game_code,type) {
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = $('#check_maxone_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var flag = true;

    var oddss = parseInt($(odds).val().replace(/[^0-9\.]+/g,""));
    var maxoddss = parseInt($(odds).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    if (oddss > maxoddss && (game_code != 15 && game_code != 16))
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxoddss);

        // alert("Mức tối thiểu là " + minexchangee);
        $(odds).val(maxoddss);
        return;
    }

    if (oddss < maxoddss && (game_code == 15 || game_code == 16))
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + maxoddss);

        // alert("Mức tối thiểu là " + minexchangee);
        $(odds).val(maxoddss);
        return;
    }

    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].odds =$(odds).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }
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
}
function AdminInputChangeMaxPoint(max,game_code,type) {
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = $('#check_maxone_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var flag = true;

    var maxx = parseInt($(max).val().replace(/[^0-9\.]+/g,""));
    var maxmaxx = parseInt($(max).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    if (maxx > maxmaxx)
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxmaxx);

        // alert("Mức tối thiểu là " + minexchangee);
        $(max).val(maxmaxx);
        return;
    }

    if (maxx > max_point_one)
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + max_point_one);

        // alert("Mức tối thiểu là " + minexchangee);
        $(max).val(max_point_one);
        return;
    }

    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].max_point =$(max).val().replace(/[^0-9\.]+/g,"");
            flag = false;
        }
    }
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
}
function AdminInputChangeMaxpointone(one,game_code,type) {
    var input = $('#input_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var max_point = $('#max_point_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var odds = $('#odds_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var cbex = $('#check_ex_'+game_code+'_'+type).is(":checked");
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = $('#check_maxone_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
    var flag = true;

    var onee = parseInt($(one).val().replace(/[^0-9\.]+/g,""));
    var maxonee = parseInt($(one).attr("data-parsley-min").replace(/[^0-9\.]+/g,""));
    // if ($('#custom-type-user-form').parsley().validate() == false)
    if (onee > maxonee)
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối đa là ' + maxonee);

        // alert("Mức tối thiểu là " + minexchangee);
        $(one).val(maxonee);
        return;
    }

    if (onee < max_point)
    {
        $.Notification.notify('error','right top', 'Thông báo', 'Mức tối thiểu là ' + max_point);

        // alert("Mức tối thiểu là " + minexchangee);
        $(one).val(max_point);
        return;
    }

    for (var i =0; i < changes.length; i++)
    {
        if(game_code == changes[i].name)
        {
            changes[i].max_point_one =$(one).val().replace(/[^0-9\.]+/g,"");
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
            max_point_one:$(one).val().replace(/[^0-9\.]+/g,""),
            change_ex:cbex,
            change_odds:cbodds,
            change_max:cbmax,
            change_max_one:cbone
        });
    }
}
function AdminCbExChange(cbex,game_code,type) {
    var input = $('#input_'+game_code+'_'+type).val();
    var max_point = $('#max_point_'+game_code+'_'+type).val();
    var odds = $('#odds_'+game_code+'_'+type).val();
    var max_point_one = $('#max_point_one_'+game_code+'_'+type).val();
    var cbodds = $('#check_odds_'+game_code+'_'+type).is(":checked");
    var cbmax = $('#check_max_'+game_code+'_'+type).is(":checked");
    var cbone = $('#check_maxone_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
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
    var cbone = $('#check_maxone_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
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
    var cbone = $('#check_maxone_'+game_code+'_'+type).val().replace(/[^0-9\.]+/g,"");
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