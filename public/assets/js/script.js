$(document).ajaxError(function(event, xhr, settings, thrownError) {
    if(xhr.status == "401" && (xhr.statusText == "Unauthorized" || xhr.responseText == "Unauthorized.")) {           
        console.log(xhr)
        window.location.href = "/login";
     }  else{
        console.log(xhr)
     }
});

timeout = 1000

$(document).ready(function() {
    localStorage.setItem('popupUserBBinLock', 0)
    localStorage.setItem('popupUserSabaLock', 0)
    time_remainCheckUpdateOne();
    $('.autonumber').autoNumeric('init');
    
    $('.refresh').hide();
    
    $("#btn_view_kqsx").click(function() {
        refreshKQXS($('#home-datepicker').val());
    });

    $("#btn_view_kqsxmn").click(function() {
        refreshKQXSMN($('#home-datepicker').val(),$('#xsslug').val());
    });

    $("#btn_view_kqsxmt").click(function() {
        refreshKQXSMT($('#home-datepicker').val(),$('#xsslug').val());
    });

    $("#btn_view_kqsxao").click(function() {
        refreshKQXSAO($('#home-xsao-datepicker').val());
    });

    $("#btn_view_kqkeno").click(function() {
        refreshKQKENO($('#home-keno-datepicker').val());
    });

    $('.input_game').hide();
    $('#time-zone').load($('#url_refresh_time').val(), function() {});

    if ($('#url').val().indexOf('games') >= 0) {
        time_remainCheckUpdate();
    }

    g_count_new();

    if (window.location.href.indexOf("xoso/mienbac") != -1
    // || window.location.href.indexOf("play/21") != -1 || window.location.href.indexOf("play/22") != -1
    // || window.location.href.indexOf("play/31") != -1 || window.location.href.indexOf("play/32") != -1
    ){
        g_open_close_game_timer();
    }
    if (window.location.href.indexOf("history/1") != -1){
        g_open_close_game_timer();
    }

    try {
        time_remain();
    } catch (e) {

    }

    // refreshUser_Info();
    
    $('#dp1').datepicker()

    jQuery('#home-datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        language: "vi",
        orientation: "top",
    });

    jQuery('#home-xsao-datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "yyyy-mm-dd",
        language: "vi",
        orientation: "top",
    });

    jQuery('#home-keno-datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "yyyy-mm-dd",
        language: "vi",
        orientation: "top",
    });

    jQuery('#history-datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        language: "vi",
        orientation: "top",
    });
    $("#btn_view_history").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistory(range);
    });

    $("#btn_view_history_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        console.log($('.input-daterange-datepicker').val())
        var startdate = range[0];
        var enddate = range[1];
        refreshHistorySk(range);
        
    });

    $("#btn_homnay_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;

        $('.input-enddate-datepicker').data('daterangepicker').setStartDate(today);
        $('.input-startdate-datepicker').data('daterangepicker').setStartDate(today);
        
        range = GetFormattedDate(today) +'/' + GetFormattedDate(today);
        range = range.split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistorySk(range);
    });

    $("#btn_homqua_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var today = new Date();
        var year = today.getFullYear(); 
        var dd = today.getDate();
        var mm = today.getMonth() + 1;

        var yesterday = new Date(today.setDate(today.getDate() - 1));
        $('.input-enddate-datepicker').data('daterangepicker').setStartDate(yesterday);
        $('.input-startdate-datepicker').data('daterangepicker').setStartDate(yesterday);
        
        range = GetFormattedDate(yesterday) +'/' + GetFormattedDate(yesterday);
        $('.input-daterange-datepicker').val(range);
        range = range.split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistorySk(range);
    });

    $("#btn_tuannay_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var today = new Date();
        var year = today.getFullYear(); 
        var dd = today.getDate();
        var mm = today.getMonth() + 1;

        // var yesterday = new Date(today.getDate() - 1);

        var current = new Date();     // get current date    
        var weekstart = 0;
        var weekend = 0;       // end day is the first day + 6 
        if (current.getDay() == 0){
            weekstart = current.getDate()-6;    
            weekend = current.getDate();
        }
        else{
            weekstart = current.getDate() - current.getDay() +1;
            weekend = weekstart + 6;
        }
        
        var monday = new Date(current.setDate(weekstart));  
        var current = new Date(); 
        var sunday = new Date(current.setDate(weekend));

        $('.input-startdate-datepicker').data('daterangepicker').setStartDate(monday);
        $('.input-enddate-datepicker').data('daterangepicker').setStartDate(sunday);
        
        range = GetFormattedDate(monday)+'/' + GetFormattedDate(sunday);
        $('.input-daterange-datepicker').val(range);
        range = range.split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistorySk(range);
    });

    $("#btn_tuantruoc_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var today = new Date();
        var year = today.getFullYear(); 
        var dd = today.getDate();
        var mm = today.getMonth() + 1;

        // var yesterday = new Date(today.getDate() - 1);

        var current = new Date();     // get current date    
        var weekstart = 0;
        var weekend = 0;
        if (current.getDay() == 0){
            weekstart = current.getDate()-6-7;    
            weekend = current.getDate()-7;
        }
        else{
            weekstart = current.getDate() - current.getDay() +1-7;
            weekend = weekstart + 6;
        }
        // var weekstart = current.getDate() - current.getDay() +1 - 7;    
        // var weekend = weekstart + 6;       // end day is the first day + 6 
        var monday = new Date(current.setDate(weekstart));
        var current = new Date();  
        var sunday = new Date(current.setDate(weekend));

        $('.input-startdate-datepicker').data('daterangepicker').setStartDate(monday);
        $('.input-enddate-datepicker').data('daterangepicker').setStartDate(sunday);
        
        range = GetFormattedDate(monday)+'/' + GetFormattedDate(sunday);
        $('.input-daterange-datepicker').val(range);
        range = range.split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistorySk(range);
    });

    $("#btn_thangnay_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var today = new Date();
        var year = today.getFullYear(); 
        var dd = today.getDate();
        var mm = today.getMonth() + 1;

        // var yesterday = new Date(today.getDate() - 1);

        // var current = new Date();     // get current date    
        // var weekstart = current.getDate() - current.getDay() +1 - 7;    
        // var weekend = weekstart + 6;       // end day is the first day + 6 
        
        var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        var lastDay = new Date(today.getFullYear(), today.getMonth()+1, 0);
        

        $('.input-startdate-datepicker').data('daterangepicker').setStartDate(firstDay);
        $('.input-enddate-datepicker').data('daterangepicker').setStartDate(lastDay);
        
        range = GetFormattedDate(firstDay)+' / ' + GetFormattedDate(lastDay);
        $('.input-daterange-datepicker').val(range);
        range = range.split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistorySk(range);
    });

    $("#btn_thangtruoc_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var today = new Date();
        var year = today.getFullYear(); 
        var dd = today.getDate();
        var mm = today.getMonth() + 1;

        // var yesterday = new Date(today.getDate() - 1);

        // var current = new Date();     // get current date    
        // var weekstart = current.getDate() - current.getDay() +1 - 7;    
        // var weekend = weekstart + 6;       // end day is the first day + 6 
        
        var firstDay = new Date(today.getFullYear(), today.getMonth()-1, 1);
        var lastDay = new Date(today.getFullYear(), today.getMonth(), 0);
        

        $('.input-startdate-datepicker').data('daterangepicker').setStartDate(firstDay);
        $('.input-enddate-datepicker').data('daterangepicker').setStartDate(lastDay);
        
        range = GetFormattedDate(firstDay)+' / ' + GetFormattedDate(lastDay);
        $('.input-daterange-datepicker').val(range);
        range = range.split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistorySk(range);
    });

    function GetFormattedDate(todayTime) {
        var month = todayTime.getMonth() + 1;
        var day = todayTime.getDate();
        var year = todayTime.getFullYear();
        return year + "-" + (month<10?"0"+month:month) + "-" + (day<10?"0"+day:day);
    }
    
    try {
        if ($('#urlH').val().indexOf('quickplayguest') >= 0) {
            $('.input-datepicker').daterangepicker({
                "singleDatePicker": true,
                "linkedCalendars": false,
                "showCustomRangeLabel": false,
                "startDate": today,
                "endDate": today,
                "minDate": moment().subtract(61, 'days'),
                "locale": {
                    format: "DD-MM-YYYY",
                    language: "vi",
                    separator: " / ",
                }
            }, function(start, end, label) {
            });
        }

        if ($('#urlH').val().indexOf('history') >= 0) {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!

            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var today = dd + '-' + mm + '-' + yyyy;

            jQuery('#date-range').datepicker({
                toggleActive: true,
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "vi",
            });

            $('.input-startdate-datepicker').daterangepicker({
                "singleDatePicker": true,
                "linkedCalendars": false,
                "showCustomRangeLabel": false,
                // "startDate": today,
                // "endDate": today,
                "minDate": moment().subtract(21, 'days'),
                "locale": {
                    format: "DD-MM-YYYY",
                    language: "vi",
                    separator: " / ",
                }
            }, function(start, end, label) {
                console.log('input-daterange-datepicker: ' + $('.input-daterange-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + $('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                
                // if ($('.input-daterange-datepicker').data('daterangepicker').startDate < start){
                //     end = $('.input-daterange-datepicker').data('daterangepicker');
                //     $('.input-daterange-datepicker').data('daterangepicker').setEndDate(start);
                //     $('.input-enddate-datepicker').data('daterangepicker').setStartDate(start);
                //     $('.input-enddate-datepicker').data('daterangepicker').setEndDate(start);
                // }
                if(start > $('.input-enddate-datepicker').data('daterangepicker').startDate){
                    $('.input-daterange-datepicker').data('daterangepicker').setEndDate(start);
                    $('.input-enddate-datepicker').data('daterangepicker').setStartDate(start);
                    $('.input-enddate-datepicker').data('daterangepicker').setEndDate(start);
                }
                $('.input-daterange-datepicker').data('daterangepicker').setStartDate(start);
            });

            $('.input-enddate-datepicker').daterangepicker({
                "singleDatePicker": true,
                "linkedCalendars": false,
                "showCustomRangeLabel": false,
                // "startDate": today,
                // "endDate": today,
                "minDate": moment().subtract(21, 'days'),
                "locale": {
                    format: "DD-MM-YYYY",
                    language: "vi",
                    separator: " / ",
                }
            }, function(start, end, label) {
            console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
            if ($('.input-startdate-datepicker').data('daterangepicker').endDate > start){
                // start = end;
                $('.input-daterange-datepicker').data('daterangepicker').setStartDate(start);
                $('.input-startdate-datepicker').data('daterangepicker').setStartDate(start);
                $('.input-startdate-datepicker').data('daterangepicker').setEndDate(start); 
            }
            $('.input-daterange-datepicker').data('daterangepicker').setEndDate(start);
            });

            //Date range picker
            $('.input-daterange-datepicker').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-default',
                cancelClass: 'btn-white',
                minDate: moment().subtract(61, 'days'),
                // maxDate: 'today',
                locale: {
                    format: "DD-MM-YYYY",
                    language: "vi",
                    separator: " / ",
                    applyLabel: "Tiếp",
                    cancelLabel: "Hủy",
                    fromLabel: "From",
                    toLabel: "To",
                    "customRangeLabel": "Tùy chọn",
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Tuần này': [moment().startOf('week'), moment().endOf('week')],
                    'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                    // 'Cách đây 7 ngày': [moment().subtract(6, 'days'), moment()],
                    // 'Cách đây 30 ngày': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: today,
                endDate: today
            });
        }
    } catch (err) {

    }
});

function reloadnewdata(gamecode){
    // return;
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val() + "/getnewdata",
        method: 'POST',
        dataType: 'json',
        data: {
            game_code: gamecode,
            _token: $_token,
        },
        success: function(data) {
            // $('#'+gamecode+"_"+i+j+k).fadeOut();
            // $('#'+gamecode+"_"+i+j+k).load($('#url').val()+"/refresh-number1000/"+gamecode+"/"+i+"/"+j+"/"+k, function() {
            //     $('#'+gamecode+"_"+i+j+k).fadeIn();
            // });
            // alert(data.size);
            if (gamecode != data[0])
                return;
            locknumber = data[data.length-1];

            if (gamecode == "8" || gamecode == "308" || gamecode == "108" || gamecode == "17" || gamecode == "317" || gamecode == "56" || gamecode == "117"
                || gamecode == "408" || gamecode == "417"
                || gamecode == "508" || gamecode == "517"
                || gamecode == "608" || gamecode == "617"
                || gamecode == "352" || gamecode == "452" || gamecode == "552" || gamecode == "652" ) {
                try {
                    for (i = 0; i < 10; i++)
                        for (j = 0; j < 10; j++)
                            for (k = 0; j < 10; k++) {

                                var isHL = false;
                                if (Number($('#' + gamecode + '_' + i + '' + j + '' + k + ' .exchange').html().replace(/[^0-9\.]+/g, "")) != data[1][i + '' + j + '' + k][0]) {
                                    // var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_chg').attr('onclick');
                                    // var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange').html().replace(/[^0-9\.]+/g,""));
                                    // $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j+''+k][0]));
                                    // $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_input').attr('value',data[i+''+j+''+k][0]);

                                    $('#' + gamecode + '_' + i + '' + j + '' + k + ' .exchange').html(data[1][i + '' + j + '' + k][0].toLocaleString('en'));


                                    isHL = true;
                                }
                                // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .a').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][1] ){
                                //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html(data[i+''+j+''+k][1]);
                                //         isHL = true;
                                // }
                                // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .x').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][2] ){
                                //     $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .x').html(data[i+''+j+''+k][2]);
                                //         isHL = true;
                                // }
                                // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .total').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][3] ){
                                //     $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .total').html(data[i+''+j+''+k][3]);
                                //         isHL = true;
                                // }
                                if (isHL){
                                    highlight($('#' + gamecode + '_' + i + '' + j + '' + k));
                                    for (var z = 0; z < choices.length; z++) {
                                        if (choices[z].name == (i+''+j+ '' + k)){
                                            choices[z].exchange = data[1][i + '' + j+ '' + k][0];
                                            recounttotal();
                                            break;
                                        }
                                    }
                                }
                                //
                            }
                } catch (err) {

                }

            } else {
                try {
                    if (gamecode == "18")
                        $('#kqxsdr_badge').html(27-1-data[2]);

                    abc = $('#gamecode').val();
                    if ($('#gamecode').val() == "2" || $('#gamecode').val() == "29" || $('#gamecode').val() == "9" || $('#gamecode').val() == "10" || $('#gamecode').val() == "11")
                        $('#kqxsdr_badge'+$('#gamecode').val()).html(27-data[2]);
                    for (i = 0; i < 10; i++)
                        for (j = 0; j < 10; j++) {
                            var isHL = false;
                            if (Number($('#exchange_' + gamecode + '_' + i + '' + j).html().replace(/[^0-9\.]+/g, "")) != data[1][i + '' + j][0]) {
                                // var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick');
                                // var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
                                // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j][0]));
                                // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_input').attr('value',data[i+''+j][0]);

                                $('#exchange_' + gamecode + '_' + i + '' + j).html(data[1][i + '' + j][0].toLocaleString('en'));


                                isHL = true;
                            }
                            // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][1] ){
                            //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html(data[i+''+j][1]);
                            //         isHL = true;
                            // }
                            // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][2] ){
                            //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html(data[i+''+j][2]);
                            //         isHL = true;
                            // }
                            // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][3] ){
                            //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html(data[i+''+j][3]);
                            //         isHL = true;
                            // }
                            if (isHL){
                                highlight($('#exchange_' + gamecode + '_' + i + '' + j));

                                for (var z = 0; z < choices.length; z++) {
                                    if (choices[z].name == (i+''+j)){
                                        if (gamecode==9)
                                        choices[z].total2 = data[1][i + '' + j][0];
                                        else if (gamecode==10)
                                        choices[z].total3 = data[1][i + '' + j][0];
                                        else if (gamecode==11)
                                        choices[z].total4 = data[1][i + '' + j][0];
                                        else
                                        choices[z].exchange = data[1][i + '' + j][0];
                                        recounttotal();
                                        break;
                                    }
                                }
                            }
                            //
                        }
                } catch (err) {

                }
            }
        },
        error: function(data) {
            // location.reload();
        }
    });
}

function reloadnewdata2(gamecode){
    // return;
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val() + "/getnewdata",
        method: 'POST',
        dataType: 'json',
        data: {
            game_code: gamecode,
            _token: $_token,
        },
        success: function(data) {
            // $('#'+gamecode+"_"+i+j+k).fadeOut();
            // $('#'+gamecode+"_"+i+j+k).load($('#url').val()+"/refresh-number1000/"+gamecode+"/"+i+"/"+j+"/"+k, function() {
            //     $('#'+gamecode+"_"+i+j+k).fadeIn();
            // });
            // alert(data.size);
            locknumber = '';
            for(p = 0; p<3; p++){
                gamecode = data[p][0];
                locknumber += data[data.length-1]+',';
                try {
                    if (gamecode == "18")
                        $('#kqxsdr_badge').html(27-1-data[2]);

                    abc = $('#gamecode').val();
                    if ($('#gamecode').val() == "2" || $('#gamecode').val() == "29" || $('#gamecode').val() == "9" || $('#gamecode').val() == "10" || $('#gamecode').val() == "11")
                        $('#kqxsdr_badge'+$('#gamecode').val()).html(27-data[2]);
                    for (i = 0; i < 10; i++)
                        for (j = 0; j < 10; j++) {
                            var isHL = false;
                            if (Number($('#exchange_' + gamecode + '_' + i + '' + j).html().replace(/[^0-9\.]+/g, "")) != data[1][i + '' + j][0]) {
                                // var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick');
                                // var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
                                // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j][0]));
                                // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_input').attr('value',data[i+''+j][0]);

                                $('#exchange_' + gamecode + '_' + i + '' + j).html(data[1][i + '' + j][0].toLocaleString('en'));


                                isHL = true;
                            }
                            // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][1] ){
                            //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html(data[i+''+j][1]);
                            //         isHL = true;
                            // }
                            // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][2] ){
                            //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html(data[i+''+j][2]);
                            //         isHL = true;
                            // }
                            // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][3] ){
                            //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html(data[i+''+j][3]);
                            //         isHL = true;
                            // }
                            if (isHL){
                                highlight($('#exchange_' + gamecode + '_' + i + '' + j));

                                for (var z = 0; z < choices.length; z++) {
                                    if (choices[z].name == (i+''+j)){
                                        if (gamecode==9)
                                        choices[z].total2 = data[1][i + '' + j][0];
                                        else if (gamecode==10)
                                        choices[z].total3 = data[1][i + '' + j][0];
                                        else if (gamecode==11)
                                        choices[z].total4 = data[1][i + '' + j][0];
                                        else
                                        choices[z].exchange = data[1][i + '' + j][0];
                                        recounttotal();
                                        break;
                                    }
                                }
                            }
                            //
                        }
                } catch (err) {

                }
            }
            LockedNumber(locknumber)
            // if (gamecode != data[0])
            //     return;
        },
        error: function(data) {
            // location.reload();
        }
    });
}

function reloadnewdatakeno(gamecode){
    // return;
    $_token = $('#token').val();
    $.ajax({
        url: $('#url').val() + "/getnewdata",
        method: 'POST',
        dataType: 'json',
        data: {
            game_code: gamecode,
            _token: $_token,
        },
        success: function(data) {
            if (gamecode != data[0])
                return;
            try {
                var isHL = false;
                if (Number($('#exchange_' + gamecode + '_' + '00').html().replace(/[^0-9\.]+/g, "")) != data[1]['00'][0]) {
                    // var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick');
                    // var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
                    // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j][0]));
                    // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_input').attr('value',data[i+''+j][0]);

                    $('#exchange_' + gamecode + '_' + '00').html(data[1]['00'][0].toLocaleString('en'));
                    isHL = true;
                }

                if (isHL){
                    highlight($('#exchange_' + gamecode + '_' + '00'));

                    for (var z = 0; z < choices.length; z++) {
                        if (choices[z].name == '00'){
                            choices[z].exchange = data[1]['00'][0];
                            recounttotal();
                            break;
                        }
                    }
                }
            } catch (err) {
            }
        },
        error: function(data) {
            // location.reload();
            e.preventDefault();
            return;
        }
    });
}

locknumberStore = ''

function LockedNumber(locknumber){
    if (locknumber == null) return;
    locknumberStore=locknumber
    const myArray = locknumber.split(",");
    for(i=0; i< myArray.length; i++){
        $('#'+$('#gamecode').val()+"_"+myArray[i]).css("background-color", "#d1d1b6");
    }
}

function time_remainCheckUpdate() {
    // return;
    time_remainCheckUpdateOne();
    setTimeout('time_remainCheckUpdate()', timeout);
}

function time_remainCheckUpdateOne() {
    // return;
    if ($('#gamecode').val() == "2" || $('#gamecode').val() == "302" 
    || $('#gamecode').val() == "402" || $('#gamecode').val() == "502" || $('#gamecode').val() == "602"
    || $('#gamecode').val() == "702")
    {
        reloadnewdata(9);reloadnewdata(10);reloadnewdata(11);reloadnewdata(29);
        // reloadnewdata2(2)
    }else
    if  ($('#gamecode').val() == "700"){
        reloadnewdatakeno(721);reloadnewdatakeno(722);reloadnewdatakeno(723);
        reloadnewdatakeno(724);reloadnewdatakeno(725);reloadnewdatakeno(726);
        reloadnewdatakeno(727);reloadnewdatakeno(728);reloadnewdatakeno(729);
        reloadnewdatakeno(730);
        reloadnewdatakeno(731);reloadnewdatakeno(732);reloadnewdatakeno(733);
        reloadnewdatakeno(734);reloadnewdatakeno(735);reloadnewdatakeno(736);
        reloadnewdatakeno(737);reloadnewdatakeno(738);reloadnewdatakeno(739);
    }else
    if ($('#gamecode').val() != "" && ( $('#gamecode').val() != "2" || $('#gamecode').val() != "102"
    || $('#gamecode').val() != "302" || $('#gamecode').val() != "402" || $('#gamecode').val() != "502"
    || $('#gamecode').val() != "602" || $('#gamecode').val() != "702" ) ) {
        $_token = $('#token').val();
        $.ajax({
            url: $('#url').val() + "/getnewdata",
            method: 'POST',
            dataType: 'json',
            data: {
                game_code: $('#gamecode').val(),
                _token: $_token,
            },
            success: function(data) {
                // $('#'+gamecode+"_"+i+j+k).fadeOut();
                // $('#'+gamecode+"_"+i+j+k).load($('#url').val()+"/refresh-number1000/"+gamecode+"/"+i+"/"+j+"/"+k, function() {
                //     $('#'+gamecode+"_"+i+j+k).fadeIn();
                // });
                // alert(data.size);
                if ($('#gamecode').val() != data[0])
                    return;
                
                if ($('#gamecode').val() == "8" || $('#gamecode').val() == "308" || $('#gamecode').val() == "108" || $('#gamecode').val() == "17" || $('#gamecode').val() == "317" || $('#gamecode').val() == "56" || $('#gamecode').val() == "117"
                    || $('#gamecode').val() == "408" || $('#gamecode').val() == "417" 
                    || $('#gamecode').val() == "508" || $('#gamecode').val() == "517"
                    || $('#gamecode').val() == "608" || $('#gamecode').val() == "617"  ) {
                    try {
                        for (i = 0; i < 10; i++)
                            for (j = 0; j < 10; j++)
                                for (k = 0; j < 10; k++) {

                                    var isHL = false;

                                    $('#' + $('#gamecode').val() + '_' + i + '' + j + '' + k).css("background-color", "");

                                    if (Number($('#' + $('#gamecode').val() + '_' + i + '' + j + '' + k + ' .exchange').html().replace(/[^0-9\.]+/g, "")) != data[1][i + '' + j + '' + k][0]) {
                                        // var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_chg').attr('onclick');
                                        // var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange').html().replace(/[^0-9\.]+/g,""));
                                        // $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j+''+k][0]));
                                        // $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .exchange_input').attr('value',data[i+''+j+''+k][0]);

                                        $('#' + $('#gamecode').val() + '_' + i + '' + j + '' + k + ' .exchange').html(data[1][i + '' + j + '' + k][0].toLocaleString('en'));


                                        isHL = true;
                                    }
                                    // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .a').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][1] ){
                                    //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html(data[i+''+j+''+k][1]);
                                    //         isHL = true;
                                    // }
                                    // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .x').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][2] ){
                                    //     $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .x').html(data[i+''+j+''+k][2]);
                                    //         isHL = true;
                                    // }
                                    // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .total').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j+''+k][3] ){
                                    //     $('#'+$('#gamecode').val()+'_'+i+''+j+''+k+' .total').html(data[i+''+j+''+k][3]);
                                    //         isHL = true;
                                    // }
                                    if (isHL){
                                        highlight($('#' + $('#gamecode').val() + '_' + i + '' + j + '' + k));
                                        for (var z = 0; z < choices.length; z++) {
                                            if (choices[z].name == (i+''+j+ '' + k)){
                                                choices[z].exchange = data[1][i + '' + j+ '' + k][0];
                                                recounttotal();
                                                break;
                                            }
                                        }
                                    }
                                    //
                                }
                    } catch (err) {
                        // location.reload();
                    }

                } else {
                    try {
                        gamecode = $('#gamecode').val();
                        if (gamecode == "18")
                            $('#kqxsdr_badge').html(27-1-data[2]);

                        if (gamecode == "200" || gamecode == "29" || gamecode == "9" || gamecode == "10" || gamecode == "11")
                            $('#kqxsdr_badge'+gamecode).html(27-data[2]);
                        for (i = 0; i < 10; i++)
                            for (j = 0; j < 10; j++) {
                                var isHL = false;

                                $('#' + $('#gamecode').val() + '_' + i + '' + j).css("background-color", "");

                                if (Number($('#' + $('#gamecode').val() + '_' + i + '' + j + ' .exchange').html().replace(/[^0-9\.]+/g, "")) != data[1][i + '' + j][0]) {
                                    // var stringclick = $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick');
                                    // var currentval = Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange').html().replace(/[^0-9\.]+/g,""));
                                    // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_chg').attr('onclick',stringclick.replace(currentval,data[i+''+j][0]));
                                    // $('#'+$('#gamecode').val()+'_'+i+''+j+' .exchange_input').attr('value',data[i+''+j][0]);

                                    $('#' + $('#gamecode').val() + '_' + i + '' + j + ' .exchange').html(data[1][i + '' + j][0].toLocaleString('en'));


                                    isHL = true;
                                }
                                // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][1] ){
                                //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .a').html(data[i+''+j][1]);
                                //         isHL = true;
                                // }
                                // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][2] ){
                                //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .x').html(data[i+''+j][2]);
                                //         isHL = true;
                                // }
                                // if (Number($('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html().replace(/[^0-9\.]+/g,"")) != data[i+''+j][3] ){
                                //     $('#'+$('#gamecode').val()+'_'+i+''+j+' .total').html(data[i+''+j][3]);
                                //         isHL = true;
                                // }
                                if (isHL){
                                    highlight($('#' + $('#gamecode').val() + '_' + i + '' + j));

                                    for (var z = 0; z < choices.length; z++) {
                                        if (choices[z].name == (i+''+j)){
                                            choices[z].exchange = data[1][i + '' + j][0];
                                            recounttotal();
                                            break;
                                        }
                                    }
                                }
                                //
                            }
                    } catch (err) {
                        // location.reload();
                    }
                }
                LockedNumber(data[data.length-1]);
            },
            error: function(data) {
                // location.reload();
                return;
            }
        });
    }
}

function recounttotal(){
    if ($('#gamecode').val()=="9" || $('#gamecode').val()=="10" || $('#gamecode').val()=="11"
        || $('#gamecode').val()=="309" || $('#gamecode').val()=="310" || $('#gamecode').val()=="311"
        || $('#gamecode').val()=="409" || $('#gamecode').val()=="410" || $('#gamecode').val()=="411"
        || $('#gamecode').val()=="509" || $('#gamecode').val()=="510" || $('#gamecode').val()=="511"
        || $('#gamecode').val()=="609" || $('#gamecode').val()=="610" || $('#gamecode').val()=="611"
        || $('#gamecode').val()=="709" || $('#gamecode').val()=="710" || $('#gamecode').val()=="711"
        ){
        game_code =$('#gamecode').val();
        var t = "";
        var point = Number($('#input_point').val().replace(/[^0-9\.]+/g, ""));
        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            total += Number(choices[i].exchange);
        }
        var Ank = 1;
        if (game_code == "29" || game_code == "329" || game_code == "429" || game_code == "529" || game_code == "629") {
            Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
            xien = 2;
            total = total * 2 / choices.length
        }
        if (game_code == "9" || game_code == "709" || game_code == "309"
        || game_code == "409" || game_code == "509" || game_code == "609") {
            Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
            xien = 2;
            total = total * 2 / choices.length
        }
        if (game_code == "10" || game_code == "310" || game_code == "410" || game_code == "510"
        || game_code == "610" || game_code == "710") {
            Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
            xien = 3;
            total = total * 3 / choices.length
        }
        if (game_code == "11" || game_code == "19"
        || game_code == "311" || game_code == "411" || game_code == "511" || game_code == "611"
        || game_code == "711" ) {
            Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
            xien = 4;
            total = total * 4 / choices.length
        }

        //XSAO

        if (game_code == "109") {
            Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
            xien = 2;
            total = total * 2 / choices.length
        }
        if (game_code == "110") {
            Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
            xien = 3;
            total = total * 3 / choices.length
        }
        if (game_code == "111" || game_code == "119") {
            Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
            xien = 4;
            total = total * 4 / choices.length
        }
        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');
        if (choices.length >= xien) {
            $('#point').html(point);
            if (point == 0) point = 1;
            $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en'));
            $('#number_select_xien').html(Ank);
        } else {
            $('#point').html(0);
            $('#total').html(0);
            $('#number_select_xien').html(0);

        }
    }else
    if ($('#gamecode').val()!=2 || $('#gamecode').val()!=302 || $('#gamecode').val()!=402
    || $('#gamecode').val()!=502 || $('#gamecode').val()!=602 || $('#gamecode').val()!=702){
        point = 0;
        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            point += Number(choices[i].value);
            choices[i].total = choices[i].exchange*choices[i].value;
            total += Number(choices[i].total);
        }
        $('#point').html(point);
        $('#total').html(total.toLocaleString('en'));
    }else{
        var t = "";
        var point2 = Number($('#input_point2').val().replace(/[^0-9\.]+/g, ""));
        var point3 = Number($('#input_point3').val().replace(/[^0-9\.]+/g, ""));
        var point4 = Number($('#input_point4').val().replace(/[^0-9\.]+/g, ""));
        var total2 = 0;
        var total3 = 0;
        var total4 = 0;

        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            total2 += choices[i].total2;
            total3 += choices[i].total3;
            total4 += choices[i].total4;
        }
        var Ank2 = 1;
        var Ank3 = 1;
        var Ank4 = 1;
        // if (game_code == "9") 
        // {
        Ank2 = fact(choices.length) / fact(2) / fact(choices.length - 2);
        xien2 = 2;
        total2 = total2 * 2 / choices.length
            // }
            // if (game_code == "10") {
        Ank3 = fact(choices.length) / fact(3) / fact(choices.length - 3);
        xien3 = 3;
        total3 = total3 * 3 / choices.length
            // }
            // if (game_code == "11" || game_code == "19") {
        Ank4 = fact(choices.length) / fact(4) / fact(choices.length - 4);
        xien4 = 4;
        total4 = total4 * 4 / choices.length
            // }


        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');

        if (choices.length >= 2) {
            $('#point').html(point);
            $('#total2').html(Math.ceil(Ank2 * total2 * point2 / xien2).toLocaleString('en').toLocaleString('en'));
            $('#number_select_xien2').html(Ank2);
            $('#point2').html(point2 * Ank2);
        }
        if (choices.length >= 3) {
            $('#total3').html(Math.ceil(Ank3 * total3 * point3 / xien3).toLocaleString('en').toLocaleString('en'));
            $('#number_select_xien3').html(Ank3);
            $('#point3').html(point3 * Ank3);
        }
        if (choices.length >= 4) {
            $('#total4').html(Math.ceil(Ank4 * total4 * point4 / xien4).toLocaleString('en').toLocaleString('en'));
            $('#number_select_xien4').html(Ank4);
            $('#point4').html(point4 * Ank4);
        }

        $('#total').html((Number($('#total2').html().replace(/[^0-9\.]+/g, "")) + Number($('#total3').html().replace(/[^0-9\.]+/g, "")) + Number($('#total4').html().replace(/[^0-9\.]+/g, ""))).toLocaleString('en'));
        $('#point').html(Number($('#point2').html().replace(/[^0-9\.]+/g, "")) + Number($('#point3').html().replace(/[^0-9\.]+/g, "")) + Number($('#point4').html().replace(/[^0-9\.]+/g, "")));
    }
}

function highlight(elem) {
    var left = 0

    function frame() {
        left++ // update parameters
        if (left % 2 != 0)
            elem.addClass('highlight');
        else
            elem.removeClass('highlight');
        if (left == 10) // check finish condition
            clearInterval(id)
    }
    var id = setInterval(frame, 300) // draw every 10ms
}

$('#input_point').keyup(function(event) {
    if (event.keyCode == 13) {
        if ($('#btn_OK').prop('disabled') == false)
            $("#btn_OK").click();
    }
    if (event.keyCode == 27) {
        $("#btn_Delete").click();
    }
});

$('.input_game').keyup(function(event) {
    if (event.keyCode == 13) {
        $("#btn_OK").click();
    }
    if (event.keyCode == 27) {
        $("#btn_Delete").click();
    }
});

$('#input_point2').keyup(function(event) {
    if (event.keyCode == 13) {
        $("#btn_OK").click();
    }
    if (event.keyCode == 27) {
        $("#btn_Delete").click();
    }
});
$('#input_point3').keyup(function(event) {
    if (event.keyCode == 13) {
        $("#btn_OK").click();
    }
    if (event.keyCode == 27) {
        $("#btn_Delete").click();
    }
});
$('#input_point4').keyup(function(event) {
    if (event.keyCode == 13) {
        $("#btn_OK").click();
    }
    if (event.keyCode == 27) {
        $("#btn_Delete").click();
    }
});
$('#number_select_text').keyup(function(event) {

    var number_length = 1;
    if ($('#current_game').val() == "17" || $('#current_game').val() == "317" || $('#current_game').val() == "56" || $('#current_game').val() == "22" || $('#current_game').val() == "8" || $('#current_game').val() == "308"
    || $('#current_game').val() == "117" || $('#current_game').val() == "122" || $('#current_game').val() == "108" || $('#current_game').val() == "352" || $('#current_game').val() == "452" || $('#current_game').val() == "552" || $('#current_game').val() == "652")
        number_length = 2;

    var value = $(this).val().split(',')
    if (value[value.length - 1].length > number_length) {
        $(this).val(value + ',')
    }
    // $('#number_select').html($('#number_select_text').val())

    if (event.keyCode == 13) {
        $("#enter_array").click();
    }
});

function enter_arrayClick(){
    var number_length = 2;
    if ($('#current_game').val() == "17" || $('#current_game').val() == "317" || $('#current_game').val() == "56" || $('#current_game').val() == "8" || $('#current_game').val() == "308" || $('#current_game').val() == "22"
    || $('#current_game').val() == "117" || $('#current_game').val() == "122" || $('#current_game').val() == "108"
    || $('#current_game').val() == "417" || $('#current_game').val() == "408"
    || $('#current_game').val() == "517" || $('#current_game').val() == "508"
    || $('#current_game').val() == "617" || $('#current_game').val() == "608"
    || $('#current_game').val() == "352" || $('#current_game').val() == "452"
    || $('#current_game').val() == "552" || $('#current_game').val() == "652"
    )
        number_length = 3;

    var array = $('#number_select_text').val();
    if (array.length == 0) {
        choices = [];
        $('.number_block').addClass('number_content');
        $('.number_content').removeClass('number_block');
        $('.input_game').hide();
        $('.label_game').show();
        return;
    }
    array = array.split(',')
    isBlock = ''
    for (i = 0; i < array.length; i++) {
        var number = array[i].slice(-number_length).replace(/\,/g, '')
        if ((number.length < number_length) && (number !== '')) {
            number = '0' + number
        }
        if ((number.length < number_length) && (number !== '')) {
            number = '0' + number
        }
        if ($.isNumeric(number)) {
            // arrInputNumber.push(number)
            // number_select_text_trim += number + ","
            if (locknumberStore.includes(number))
            {
                isBlock += number + ','
            }
        }
    }
    if (isBlock != ''){
        swal({
            title: "Cảnh báo khoá mã.",
            text: "Số "+isBlock +" đang bị khoá. Bạn có muốn vào cược số còn lại không?",
            type: "info",
            timer: 10000,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Vào tiếp",
            cancelButtonText: "Nhập lại",
            allowOutsideClick: false,
            allowEscapeKey: false,
            closeOnConfirm: true
        }, 
        function (dismiss) {
            if (dismiss === null) {
                Huy();
                swal.close();
                return;
            }
            if (dismiss) {
                // Action()
            } else {
                Huy();
                swal.close();
                return;
            }
          }
          ,function(isConfirm) {
            
        });
    }
        // swal({ title: "", text: "Kiểm tra lại mã cược bị khóa: "+isBlock +"", timer: 10000, showConfirmButton: false, closeOnConfirm: false });
    
    var number_select_text_trim = "";
    var arrInputNumber = [];
    for (i = 0; i < array.length; i++) {
        if (($('#current_game').val() == "9" || $('#current_game').val() == "309" || $('#current_game').val() == "409" || $('#current_game').val() == "509" || $('#current_game').val() == "609" || $('#current_game').val() == "709") && arrInputNumber.length == 2)
            break;
        if (($('#current_game').val() == "10" || $('#current_game').val() == "310" || $('#current_game').val() == "410" || $('#current_game').val() == "510" || $('#current_game').val() == "610" || $('#current_game').val() == "710") && arrInputNumber.length == 3)
            break;
        if (($('#current_game').val() == "11" || $('#current_game').val() == "311" || $('#current_game').val() == "411" || $('#current_game').val() == "511" || $('#current_game').val() == "611" || $('#current_game').val() == "711") && arrInputNumber.length == 4)
            break;
            
        var number = array[i].slice(-number_length).replace(/\,/g, '')
        if ((number.length < number_length) && (number !== '')) {
            number = '0' + number
        }
        if ((number.length < number_length) && (number !== '')) {
            number = '0' + number
        }
        if ($.isNumeric(number)) {
            arrInputNumber.push(number)
            number_select_text_trim += number + ","
        }
        if ($('#current_game').val() == "29" || $('#current_game').val() == "329" || $('#current_game').val() == "429" || $('#current_game').val() == "529" || $('#current_game').val() == "629") {
            if (i == 14 - 1)
                break;
        }
        if ($('#current_game').val() == "9" || $('#current_game').val() == "109" 
        || $('#current_game').val() == "309"
        || $('#current_game').val() == "409"
        || $('#current_game').val() == "509"
        || $('#current_game').val() == "609"
        || $('#current_game').val() == "709") {
            if (i == 14 - 1)
                break;
        }
        if ($('#current_game').val() == "10" || $('#current_game').val() == "110" 
        || $('#current_game').val() == "310"
        || $('#current_game').val() == "410"
        || $('#current_game').val() == "510"
        || $('#current_game').val() == "610"
        || $('#current_game').val() == "710") {
            if (i == 10 - 1)
                break;
        }
        if ($('#current_game').val() == "11" || $('#current_game').val() == "111" 
        || $('#current_game').val() == "311"
        || $('#current_game').val() == "411"
        || $('#current_game').val() == "511"
        || $('#current_game').val() == "611"
        || $('#current_game').val() == "711"
        ) {
            if (i == 9 - 1)
                break;
        }
        if ($('#current_game').val() == "19" || $('#current_game').val() == "119") {
            if (i == 9)
                break;
        }
        if ($('#current_game').val() == "20" || $('#current_game').val() == "120") {
            if (i == 7)
                break;
        }
        if ($('#current_game').val() == "21" || $('#current_game').val() == "121") {
            if (i == 9)
                break;
        }


    }

    arrInputNumber = arrInputNumber.filter(function(elem, index, self) {
            return index == self.indexOf(elem);
        })
        //updateArrNumberNorthOne('plus', arrInputNumber)
        // $('#number_select_text').val('');
        //     $('.number_block').addClass('number_content');
        // $('.number_content').removeClass('number_block');
        // $('.input_game').hide();
        // $('.label_game').show();
    choices = [];
    // $('#input_point').val('');
    // $('#point').html(0);
    // $('#total').html(0);
    // $('#number_select_text').val('');
    // $('#number_select').html('');
    // $('.number_block .input_game').val('');
    $('.number_block').addClass('number_content');
    $('.number_content').removeClass('number_block');
    $('.input_game').hide();
    $('.label_game').show();

    for (i = 0; i < arrInputNumber.length; i++) {
        var number = arrInputNumber[i];
        var seletedID = '#select_' + $('#current_game').val() + '_' + number;
        // alert(($(seletedID).attr('class')));
        if (($(seletedID).attr('class')).indexOf('number_block') <= 0)
            $(seletedID).click();
    }

    $('#number_select').html(arrInputNumber.sort().toString().replace(/\,/g, ', '))
}

$("#enter_array").click(function(event) {
    enter_arrayClick()
});

function number_format(number, decimals, decPoint, thousandsSep) {
    decimals = decimals || 0;
    number = parseFloat(number);

    if (!decPoint || !thousandsSep) {
        decPoint = '.';
        thousandsSep = ',';
    }

    var roundedNumber = Math.round(Math.abs(number) * ('1e' + decimals)) + '';
    var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
    var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
    var formattedNumber = "";

    while (numbersString.length > 3) {
        formattedNumber += thousandsSep + numbersString.slice(-3)
        numbersString = numbersString.slice(0, -3);
    }

    return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
}

function dateString2Date(dateString) {
    var dt = dateString.split(/\-|\s/);
    return new Date(dt.slice(0, 3).reverse().join('-') + ' ' + dt[3]);
}

var hour = null; // Giờ
var minus = null; // Phút
var second = null; // Giây

function g_count_new() {
    refreshUser_Info();
    highlightC()
    $('#time-zone').load($('#url_refresh_time').val(), function() {});
    setTimeout('g_count_new()', timeout);
}

function g_open_close_game_timer() {
    $('#open_close_game_timer').load($('#url_open_close_game_timer').val(), function() {});
    setTimeout('g_open_close_game_timer()', timeout);
}

function g_refresh_bets_top5() {
    $('#refresh_bets_top5').load($('#url_refresh_bets_top5').val(), function() {});
}
function g_count() {
    setTimeout('g_count()', timeout);
    var s = $('#time-zone').html();
    if (!(!s || 0 === s.length)) {
        // var d = new Date(s);
        var d = s.split(' ');
        var ddate = d[0].split('-');
        var ttime = d[1].split(':');

        // hour = d.getHours();
        // minus = d.getMinutes();
        // second = d.getSeconds();

        hour = parseInt(ttime[0]);
        minus = parseInt(ttime[1]);
        second = parseInt(ttime[2]);


        /*BƯỚC 1: CHUYỂN ĐỔI DỮ LIỆU*/
        // Nếu số giây = -1 tức là đã chạy ngược hết số giây, lúc này:
        //  - giảm số phút xuống 1 đơn vị
        //  - thiết lập số giây lại 59
        if (second === 59) {
            minus += 1;
            second = 0;
        }

        // Nếu số phút = -1 tức là đã chạy ngược hết số phút, lúc này:
        //  - giảm số giờ xuống 1 đơn vị
        //  - thiết lập số phút lại 59
        if (minus === 59) {
            hour += 1;
            minus = 0;
        }
        if (hour === 24) {
            hour = 0;
        }
        // Nếu số giờ = -1 tức là đã hết giờ, lúc này:
        //  - Dừng chương trình

        var day = parseInt(ddate[2]);
        var month = parseInt(ddate[1]);
        var year = parseInt(ddate[0]);
        second += 1;
        $('#time-zone').html(year + "-" + month + "-" + day + " " + hour + ":" + minus + ":" + second);
    }
}

function time_remain() {
    setTimeout('time_remain()', timeout);
    var s = $('#time-zone').html();
    if (!(!s || 0 === s.length)) {
        var time_result = $('#time_result').val();
        var time = time_result.split(':');
        var time_h = Number(time[0]);
        var time_m = Number(time[1]);
        var dnow = new Date(s);

        var d = s.split(' ');
        var ddate = d[0].split('-');
        var ttime = d[1].split(':');

        chour = dnow.getHours();
        cminus = dnow.getMinutes();
        // second = d.getSeconds();

        hour = parseInt(ttime[0]);
        minus = parseInt(ttime[1]);
        second = parseInt(ttime[2]);

        day = parseInt(ddate[2]);
        month = parseInt(ddate[1]);
        year = parseInt(ddate[0]);

        var h = 0;
        var m = 0;
        var s = 0;
        var list_open = $(".hd_clock_open");
        var list_close = $(".hd_clock_close");
        var clock = $(".clock");


        for (var i = 0; i < list_open.length; i++) {
            var open = $(list_open[i]).val().split(':');
            var h_open = Number(open[0]);
            var m_open = Number(open[1]);
            var close = $(list_close[i]).val().split(':');
            var h_close = Number(close[0]);
            var m_close = Number(close[1]) - 1;
            if ($('#current_location').val() == 4){
                if (minus >=0 && minus <= 14){
                    h_open = hour-1;
                    m_open = 45;
                    h_close = hour;
                    m_close = 14-1;
                }else
                if (minus >=45 && minus <= 59)
                {
                    h_open = hour;
                    m_open = 45;
                    h_close = hour+1;
                    m_close = 14-1;
                }else{
                    $(clock[i]).html("Hết giờ");
                    continue;
                }
                
            }

            if ($('#current_location').val() == 5){
                h_open = hour;
                m_open = minus - minus%10;
                
                if (hour == 21 && minus >=50){
                    $(clock[i]).html("Hết giờ");
                    continue;
                }
                if (minus%10 >=3 && minus%10 < 9 && hour >=6 && hour<22 ){
                    h_close = hour;
                    m_close = minus - minus%10 + 9;
                }else{
                    $(clock[i]).html("Hết giờ");
                    continue;
                }
            }

            var time = "";
            
            if (h_close >= hour && hour >= h_open) {
                var dpcuoc = true;
                if (hour == h_open) {
                    if (m_open > minus) {
                        //chua den gio cuoc
                        dpcuoc=false;
                    }else{
                        //vao cuoc
                        dpcuoc=true;
                    }
                }else
                    //vao cuoc
                    dpcuoc=true;
                if (dpcuoc == true){
                    if (h_close == hour) {
                        if (m_close >= minus) {
                            h = h_close - hour;
                            m = m_close - minus;
                            s = 60 - second;
                        } else {
                            h = h_close - hour - 1;
                            m = 60 - minus + m_close;
                            s = 60 - second;
                            if (h * 60 * 60 + m * 60 + s <= 0) {
                                h = 0;
                                m = 0;
                                s = 0;
                            }
                        }
                        if (h > 9) {
                            time += h;
                        } else {
                            time += "0" + h;
                        }
                        if (m > 9) {
                            time += ":" + m;
                        } else {
                            time += ":" + "0" + m;
                        }
                        if (s > 9) {
                            time += ":" + s;
                        } else {
                            time += ":" + "0" + s;
                        }
                        if (time  == "00:00:00")
                            $(clock[i]).html("Hết giờ");
                        else
                            $(clock[i]).html(time);
                    } else {
                        if (m_close >= minus) {
                            h = h_close - hour;
                            m = m_close - minus;
                            s = 60 - second;
                        } else {
                            h = h_close - hour - 1;
                            m = 60 - minus + m_close;
                            s = 60 - second;
                        }
                        if (h > 9) {
                            time += h;
                        } else {
                            time += "0" + h;
                        }
                        if (m > 9) {
                            time += ":" + m;
                        } else {
                            time += ":" + "0" + m;
                        }
                        if (s > 9) {
                            time += ":" + s;
                        } else {
                            time += ":" + "0" + s;
                        }
                        if (time  == "00:00:00")
                            $(clock[i]).html("Hết giờ");
                        else
                            $(clock[i]).html(time);
                    }
                }else{
                    $(clock[i]).html("Hết giờ");    
                }
            } else {
                $(clock[i]).html("Hết giờ");
            }
        }
    }

    try {
        var list_time_bet = $(".time_bet");
        for (var i = 0; i < list_time_bet.length; i++) {
            try {
            // alert($("#clock_"+$(list_time_bet[i]).attr('game_bet_id')).html());
            var clockremaincode = Number($(list_time_bet[i]).attr('game_bet_id'));
            var cancelmoney = $(list_time_bet[i]).attr('cancel_money');
            if (clockremaincode==18){
                $('#' + $(list_time_bet[i]).attr('gameid')).html('Hết giờ hủy');
                $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc not-active');
                continue;
            }
            // if (clockremaincode == 7 || clockremaincode == 8)
            //     clockremaincode = 1;

            if (clockremaincode == 307 || clockremaincode == 308)
                clockremaincode = 301;
            if (clockremaincode == 407 || clockremaincode == 408)
                clockremaincode = 401;
            if (clockremaincode == 507 || clockremaincode == 508)
                clockremaincode = 501;
            if (clockremaincode == 607 || clockremaincode == 608)
                clockremaincode = 601;

            if (clockremaincode == 29 || clockremaincode == 11 || clockremaincode == 9 || clockremaincode == 10)
                clockremaincode = 2;

            if (clockremaincode == 329 || clockremaincode == 311 || clockremaincode == 309 || clockremaincode == 310)
                clockremaincode = 302;

            if (clockremaincode == 429 || clockremaincode == 411 || clockremaincode == 409 || clockremaincode == 410)
                clockremaincode = 402;

            if (clockremaincode == 529 || clockremaincode == 511 || clockremaincode == 509 || clockremaincode == 510)
                clockremaincode = 502;

            if (clockremaincode == 629 || clockremaincode == 611 || clockremaincode == 609 || clockremaincode == 610)
                clockremaincode = 602;

            if (clockremaincode == 16 || clockremaincode == 19 || clockremaincode == 20 || clockremaincode == 21)
                clockremaincode = 3;

            if (clockremaincode == 316 || clockremaincode == 319 || clockremaincode == 320 || clockremaincode == 321)
                clockremaincode = 303;
            if (clockremaincode == 416 || clockremaincode == 419 || clockremaincode == 420 || clockremaincode == 421)
                clockremaincode = 403;
            if (clockremaincode == 516 || clockremaincode == 519 || clockremaincode == 520 || clockremaincode == 521)
                clockremaincode = 503;

            if (clockremaincode == 616 || clockremaincode == 619 || clockremaincode == 620 || clockremaincode == 621)
                clockremaincode = 603;

            if (clockremaincode == 107 || clockremaincode == 108)
                clockremaincode = 101;

            if (clockremaincode == 111 || clockremaincode == 109 || clockremaincode == 110)
                clockremaincode = 102;

            if (clockremaincode == 116 || clockremaincode == 119 || clockremaincode == 120 || clockremaincode == 121)
                clockremaincode = 103;

            // if (clockremaincode >=  31 && clockremaincode <= 55)
            //     clockremaincode = 24;

            if (clockremaincode == 711 || clockremaincode == 709 || clockremaincode == 710)
                clockremaincode = 702;

            if (clockremaincode >= 721 && clockremaincode <= 739)
                clockremaincode = 700;
                
            var closegame = $('#clock_' + clockremaincode).html().split(':');
            var h_closegame = Number(closegame[0]);
            var m_closegame = Number(closegame[1]);
            var s_closegame = Number(closegame[2]);

            var dateBet = $(list_time_bet[i]).attr('value').split(' ')[0];
            var open = $(list_time_bet[i]).attr('value').split(' ')[1].split(':');
            var h_open = Number(open[0]);
            var m_open = Number(open[1]);
            // var close = $(this).attr('value').split(':');
            // var h_close = Number(close[0]);
            var m_close = m_open + 4;
            var h_close = h_open;
            if (m_close >= 60) {
                h_close++;
                m_close = m_close - 60;
            }

            // $('#'+$(this).attr('gameid')).html('s');

            if ($('#current_location').val() == 4){
                if (minus >=0 && minus <= 14){
                    // h_open = hour-1;
                    // m_open = 45;
                    // h_close = hour;
                    // m_close = 14-1;
                }else
                if (minus >=45 && minus <= 59)
                {
                    // h_open = hour;
                    // m_open = 45;
                    // h_close = hour+1;
                    // m_close = 14-1;
                }else{
                    $('#' + $(list_time_bet[i]).attr('gameid')).html('Hết giờ hủy');
                    $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc not-active');
                    continue;
                }    
            }

            if ($('#current_location').val() == 5){
                if (minus % 10 == 9 || minus % 10 == 0 || minus % 10 == 1 || minus % 10 == 2 || minus % 10 == 3 ){
                    $('#' + $(list_time_bet[i]).attr('gameid')).html('Hết giờ hủy');
                    $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc not-active');
                    continue;
                }
            }

            var time = "";
            if (hour != 18 && h_close >= hour && hour >= h_open && checkDate(dateBet, d[0])) {
                if (h_close == hour) {
                    if (m_close >= minus) {
                        h = h_close - hour;
                        m = m_close - minus;
                        s = 60 - second;
                    } else {
                        h = h_close - hour - 1;
                        m = 60 - minus + m_close;
                        s = 60 - second;
                        if (h * 60 * 60 + m * 60 + s <= 0) {
                            h = 0;
                            m = 0;
                            s = 0;
                        }
                    }
                    if (h > 9) {
                        // time += h;
                    } else {
                        // time += "0"+h;
                    }
                    if (h == h_closegame && m >= m_closegame) {
                        m = m_closegame;
                        s = s_closegame;
                    }
                    if (m > 9) {
                        time += m;
                    } else {
                        time += "0" + m;
                    }
                    if (s > 9) {
                        time += ":" + s;
                    } else {
                        time += ":" + "0" + s;
                    }
                    // $(clock[i]).html(time);
                    if (time == "00:00") {
                        if (hour < 18) {
                            $('#' + $(list_time_bet[i]).attr('gameid')).html('-' + cancelmoney);
                            $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc');
                        }
                        else {
                            $('#' + $(list_time_bet[i]).attr('gameid')).html('Hết giờ hủy');
                            $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc not-active');
                        }
                    } else {
                        $('#' + $(list_time_bet[i]).attr('gameid')).html('Hủy ' + time);

                        $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc');
                    }
                } else {
                    if (m_close >= minus) {
                        h = h_close - hour;
                        m = m_close - minus;
                        s = 60 - second;
                    } else {
                        h = h_close - hour - 1;
                        m = 60 - minus + m_close;
                        s = 60 - second;
                    }
                    if (h > 9) {
                        time += h;
                    } else {
                        time += "0" + h;
                    }
                    if (m > 9) {
                        time += ":" + m;
                    } else {
                        time += ":" + "0" + m;
                    }
                    if (s > 9) {
                        time += ":" + s;
                    } else {
                        time += ":" + "0" + s;
                    }
                    $('#' + $(list_time_bet[i]).attr('gameid')).html('Hủy ' + time);
                    $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc');
                }
            } else {

                if (hour < 18) {
                    $('#' + $(list_time_bet[i]).attr('gameid')).html('-' + cancelmoney);
                    $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc');
                }
                else {
                    $('#' + $(list_time_bet[i]).attr('gameid')).html('Hết giờ hủy');
                    $('#' + $(list_time_bet[i]).attr('gameid')).attr('class', 'btn_huycuoc not-active');
                }
                            
                
            }
        } catch (e) {

        }
    }
    

    } catch (e) {

    }

    try {
        var clockremaincode = $('#current_game').val();
        
        // if (clockremaincode == 7 || clockremaincode == 8)
        //         clockremaincode = 1;

        if (clockremaincode == 307 || clockremaincode == 308)
            clockremaincode = 301;
        if (clockremaincode == 407 || clockremaincode == 408)
            clockremaincode = 401;
        if (clockremaincode == 507 || clockremaincode == 508)
            clockremaincode = 501;
        if (clockremaincode == 607 || clockremaincode == 608)
            clockremaincode = 601;

        if (clockremaincode == 29 || clockremaincode == 11 || clockremaincode == 9 || clockremaincode == 10)
            clockremaincode = 2;

        if (clockremaincode == 329 || clockremaincode == 311 || clockremaincode == 309 || clockremaincode == 310)
            clockremaincode = 302;

        if (clockremaincode == 429 || clockremaincode == 411 || clockremaincode == 409 || clockremaincode == 410)
            clockremaincode = 402;

        if (clockremaincode == 529 || clockremaincode == 511 || clockremaincode == 509 || clockremaincode == 510)
            clockremaincode = 502;

        if (clockremaincode == 629 || clockremaincode == 611 || clockremaincode == 609 || clockremaincode == 610)
            clockremaincode = 602;

        if (clockremaincode == 16 || clockremaincode == 19 || clockremaincode == 20 || clockremaincode == 21)
            clockremaincode = 3;

            if (clockremaincode == 316 || clockremaincode == 319 || clockremaincode == 320 || clockremaincode == 321)
            clockremaincode = 303;
            if (clockremaincode == 416 || clockremaincode == 419 || clockremaincode == 420 || clockremaincode == 421)
            clockremaincode = 403;
            if (clockremaincode == 516 || clockremaincode == 519 || clockremaincode == 520 || clockremaincode == 521)
            clockremaincode = 503;
            if (clockremaincode == 616 || clockremaincode == 619 || clockremaincode == 620 || clockremaincode == 621)
            clockremaincode = 603;

        if (clockremaincode == 107 || clockremaincode == 108)
            clockremaincode = 101;

        if (clockremaincode == 111 || clockremaincode == 109 || clockremaincode == 110)
            clockremaincode = 102;

        if (clockremaincode == 116 || clockremaincode == 119 || clockremaincode == 120 || clockremaincode == 121)
            clockremaincode = 103;

        // if (clockremaincode >=  31 && clockremaincode <= 55)
        //     clockremaincode = 24;

        if (clockremaincode == 711 || clockremaincode == 709 || clockremaincode == 710)
            clockremaincode = 702;

        if (clockremaincode >= 721 && clockremaincode <= 739)
            clockremaincode = 700;

        if ($('#clock_' + clockremaincode).html() == "Hết giờ") {
            $('#btn_Delete').prop('disabled', true);
            $('#btn_OK').prop('disabled', true);
        } else {
            $('#btn_Delete').prop('disabled', false);
            $('#btn_OK').prop('disabled', false);
        }
    } catch (e) {

    }
    if ($('#flag-play').val() == "0") {
        $('#btn_Delete').prop('disabled', true);
        $('#btn_OK').prop('disabled', true);
    }

    if (window.location.href.indexOf("ketqua/4") != -1 || window.location.href.indexOf("ketqua/xoso-ao") != -1){
        if (minus >= 15 && minus <= 22)
            if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#xsaodiv').load($('#url_refresh_xsao').val(), function() {});
            }
    }

    if (window.location.href.indexOf("ketqua/1") != -1 || window.location.href.indexOf("ketqua/xoso") != -1){
        if (hour == 18 && minus >= 14 && minus <= 35)
            // if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#div_kqsx').load($('#url_kqsx').val() + "/" + day+"-"+month+"-"+year, function() {});
            }
    }

    if (window.location.href.indexOf("ketqua/21") != -1 || window.location.href.indexOf("ketqua/xosomiennamdai1") != -1){
        if (hour == 16 && minus >= 14 && minus <= 40)
            // if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#div_kqsx').load($('#url_kqsxmn').val() + "/" + day+"-"+month+"-"+year + "/" + $('#xsslug').val(), function() {});
            }
    }

    if (window.location.href.indexOf("ketqua/22") != -1 || window.location.href.indexOf("ketqua/xosomiennamdai2") != -1){
        if (hour == 16 && minus >= 14 && minus <= 40)
            // if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#div_kqsx').load($('#url_kqsxmn').val() + "/" + day+"-"+month+"-"+year + "/" + $('#xsslug').val(), function() {});
            }
    }

    if (window.location.href.indexOf("ketqua/31") != -1 || window.location.href.indexOf("ketqua/xosomientrungdai1") != -1){
        if (hour == 17 && minus >= 14 && minus <= 40)
            // if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#div_kqsx').load($('#url_kqsxmt').val() + "/" + day+"-"+month+"-"+year + "/" + $('#xsslug').val(), function() {});
            }
    }

    if (window.location.href.indexOf("ketqua/32") != -1 || window.location.href.indexOf("ketqua/xosomientrungdai2") != -1){
        if (hour == 17 && minus >= 14 && minus <= 40)
            // if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#div_kqsx').load($('#url_kqsxmt').val() + "/" + day+"-"+month+"-"+year + "/" + $('#xsslug').val(), function() {});
            }
    }

    if (window.location.href.indexOf("xoso/mienbac") != -1){
        if (hour == 18 && minus >= 14 && minus <= 30)
            // if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#kqsxmin').load($('#url_kqsxmin').val() + "/" + day+"-"+month+"-"+year, function() {});
            }
    }

    if (window.location.href.indexOf("play/5") != -1){
        if (hour >= 6 && hour < 22)
        if (minus%10 >= 9 || minus%10 <= 2)
            // if ((second < 3 && second>0) || (second < 32 && second>29))
                {
                // lastminus = minus;        
                // location.reload();
                $('#kqkenomin').load($('#url_kqkenomin').val() + "/" + hour+":"+minus, function() {});
            }
    }
}
// lastminus=0;
function checkDate(date1, date2) {
    var y1 = Number(date1.split('-')[0]);
    var m1 = Number(date1.split('-')[1]);
    var d1 = Number(date1.split('-')[2]);

    var y2 = Number(date2.split('-')[0]);
    var m2 = Number(date2.split('-')[1]);
    var d2 = Number(date2.split('-')[2]);

    if (y1 != y2 || m1 != m2 || d1 != d2)
        return false;
    return true;
}

function refreshLogin() {

    $('#div_login').load($('#url_refresh_login').val(), function() {});
}

function refreshKQXS(date) {
    if (date == "") {
        alert("Bạn hãy nhập ngày tháng");
        return;
    }
    $('#div_kqsx').load($('#url_kqsx').val() + "/" + date, function() {});
}

function refreshKQXSMN(date,slug) {
    if (date == "") {
        alert("Bạn hãy nhập ngày tháng");
        return;
    }
    $('#div_kqsx').load($('#url_kqsxmn').val() + "/" + date +"/"+slug, function() {});
}

function refreshKQXSMT(date,slug) {
    if (date == "") {
        alert("Bạn hãy nhập ngày tháng");
        return;
    }
    $('#div_kqsx').load($('#url_kqsxmt').val() + "/" + date+"/"+slug, function() {});
}

function refreshKQXSAO(date) {
    if (date == "") {
        alert("Bạn hãy nhập ngày tháng");
        return;
    }
    // $('#div_kqsx').load($('#url_kqsx').val() + "/" + date, function() {});
    $('#btn_view_kqsxao').attr('href','\\ketqua/4/'+date);
    var href = $('#btn_view_kqsxao').attr('href');
    window.location.href = href;
}

function refreshKQKENO(date) {
    if (date == "") {
        alert("Bạn hãy nhập ngày tháng");
        return;
    }
    // $('#div_kqsx').load($('#url_kqsx').val() + "/" + date, function() {});
    $('#btn_view_kqkeno').attr('href','\\ketqua/5/'+date);
    var href = $('#btn_view_kqkeno').attr('href');
    window.location.href = href;
}

function refreshHistory(range) {
    if (range == "") {
        alert("Bạn hãy nhập ngày tháng");
        return;
    }
    $('#div_history').load($('#url').val() + "/history-by-day/" + range[0].trim() + "/" + range[1].trim(), function() {});
}

function refreshHistorySk(range) {
    if (range == "") {
        alert("Bạn hãy nhập ngày tháng");
        return;
    }
    $("#div_history").html('Loading..');
    // $.ajax({
    //     type: 'GET',
    //     url: $('#url').val() + "/history-sk-by-day/" + range[0].trim() + "/" + range[1].trim(),
    //     timeout: 10000,
    //     success: function(data) {
    //         $("#div_history").html(data);
    //         //   $("#notice_div").html(''); 
    //         window.setTimeout(update, 10000);
    //     },
    //     error: function(XMLHttpRequest, textStatus, errorThrown) {
    //         $("#div_history").html('Timeout contacting server..');
    //         window.setTimeout(update, 60000);
    //     }


    // });
    $('#div_history').load($('#url').val() + "/history-sk-by-day/" + range[0].trim() + "/" + range[1].trim(), function() {
        setTimeout(()=>{
            $('.modal').each(function() {
                $(this).insertAfter($('#game-play'));
            });
            $('.modal').each(function() {
                $(this).insertAfter($('#game-play'));
            });
        },200);
    });
}

function LoadContentGameParent(game_code, game_name,alias, max_point, max_point_one, odds, open, close, exchange_rates) {
    console.log('LoadContentGameParent '+alias)
    if (game_code == $('#current_game').val())
        return;

    if ((game_code >=  31 && game_code <= 55) || game_code ==  24)
    {
        // clockremaincode = 24;
        for (var i = 31; i < 56; i++) {
            $('#row_clock_giaikhac_' + i).removeClass('hidden');
        }
    }else{
        for (var i = 31; i < 56; i++) {
            $('#row_clock_giaikhac_' + i).addClass('hidden');
        }
    }
    $('#exchange_rates_raw').val(number_format(exchange_rates));
    $('#tabnew').css('pointer-events','none')
    $('#tabgameContent').css('pointer-events','none')
    $('#spinnerBet'+game_code).css('display','-webkit-inline-box')
    
    if (game_code != 1 && game_code != 2 && game_code != 3 && game_code != 24 && game_code != 101 && game_code != 102 && game_code != 103 
        && game_code != 301 && game_code != 302
        && game_code != 401 && game_code != 402
        && game_code != 501 && game_code != 502
        && game_code != 601 && game_code != 602
        && game_code != 702
        ) {
        $('#box-cuoc-lo-xien').addClass('hidden');
        $('#tongdiem').addClass('hidden');
        // $('#input_point').removeClass('hidden');
        
        LoadContentGame(game_code, game_name, alias, max_point, max_point_one, odds, open, close, exchange_rates);

        if (game_code == 700){
            $('#number_select_div').addClass('hidden');
            $('#quick_input_gameplay').addClass('hidden');
            // $('#input_point').addClass('hidden');
        }else{
            $('#quick_input_gameplay').removeClass('hidden');
            // $('#input_point').removeClass('hidden');ali
            $('#number_select_div').removeClass('hidden');
        }
        
        setTimeout(function(){
            $('#tabnew').css('pointer-events','all')
            $('#tabgameContent').css('pointer-events','all')
            $('#spinnerBet'+game_code).css('display','none')
        }, 500);
        return;
    } 
    if (game_code == 2 || game_code == 302 || game_code == 402 || game_code == 502 || game_code == 602 || game_code == 702) {
        $('#box-cuoc-lo-xien').removeClass('hidden');
        $('#tongdiem').removeClass('hidden');
        $('#input_point').addClass('hidden');
        $('#number_select_div').removeClass('hidden');
        LoadContentGameXien(game_code, game_name,alias, max_point, max_point_one, odds, open, close, exchange_rates);
    } else {
        $('#box-cuoc-lo-xien').addClass('hidden');
        $('#tongdiem').addClass('hidden');
        $('#number_select_div').removeClass('hidden');
        $('#input_point').removeClass('hidden');
    }
    setTimeout(function(){
        $('#tabnew').css('pointer-events','all')
        $('#tabgameContent').css('pointer-events','all')
        $('#spinnerBet'+game_code).css('display','none')
    }, 500);
}

function ClickTabGame(game_code,alias) {
    
    if (game_code == 1) {
        $('#gamecode7').click();
    }
    if (game_code == 301) {
        $('#gamecode307').click();
    }
    if (game_code == 401) {
        $('#gamecode407').click();
    }
    if (game_code == 501) {
        $('#gamecode507').click();
    }
    if (game_code == 601) {
        $('#gamecode607').click();
    }
    if (game_code == 2) {
        $('#gamecode200').click();
    }

    if (game_code == 302) {
        $('#gamecode200').click();
    }
    if (game_code == 402) {
        $('#gamecode200').click();
    }
    if (game_code == 502) {
        $('#gamecode200').click();
    }
    if (game_code == 602) {
        $('#gamecode200').click();
    }
    if (game_code == 702) {
        $('#gamecode200').click();
    }
    if (game_code == 3) {
        $('#gamecode16').click();
    }

    if (game_code == 303) {
        $('#gamecode316').click();
    }
    if (game_code == 403) {
        $('#gamecode416').click();
    }
    if (game_code == 403) {
        $('#gamecode416').click();
    }
    if (game_code == 403) {
        $('#gamecode316').click();
    }

    if (game_code == 101) {
        $('#gamecode107').click();
    }

    if (game_code == 102) {
        $('#gamecode109').click();
    }
    if (game_code == 103) {
        $('#gamecode116').click();
    }

    if ((game_code >=  31 && game_code <= 55) || game_code ==  24)
    {
        // clockremaincode = 24;
        for (var i = 31; i < 56; i++) {
            $('#row_clock_giaikhac_' + i).removeClass('hidden');
        }
    }else{
        for (var i = 31; i < 56; i++) {
            $('#row_clock_giaikhac_' + i).addClass('hidden');
        }
    }
    console.log(alias)
    window.history.pushState(alias, alias, "/xoso/mienbac/" +alias);
}

function LoadContentGameXien(game_code, game_name,alias, max_point, max_point_one, odds2, odds3, odds4,oddsxn, open, close, exchange_rates) {
    console.log("LoadContentGameXien" + alias);
    if (game_code == $('#current_game').val())
        return;

        if (alias != ""){
            console.log("LoadContentGameXien" + alias);
            window.history.pushState(alias, alias, "/xoso/mienbac/" +alias);
        }

    $('#tabnew').css('pointer-events','none')
    $('#tabgameContent').css('pointer-events','none')
    $('#spinnerBet'+game_code).css('display','-webkit-inline-box')
    Huy();
    $('#box-cuoc-lo-xien').removeClass('hidden');
    $('#tongdiem').removeClass('hidden');
    $('#input_point').addClass('hidden');
    $('#panel_bet').attr('class', 'panel panel-color panel-inverse');
    $('#open').val(open);
    $('#close').val(close);
    $('#current_game').val(game_code);
    $('#gamecode').val(game_code);
    $('#game_name').html(game_name);
    $('#max_point').html(max_point);
    $('#max_point_one').html(max_point_one);
    $('#odds').html(number_format(odds2) + ' / ' + number_format(odds3) + ' / ' + number_format(odds4)+ ' / ' + number_format(oddsxn));
    $('#odds2').html(number_format(odds2));
    $('#odds3').html(number_format(odds3));
    $('#odds4').html(number_format(odds4));
    $('#oddsxn').html(number_format(oddsxn));
    $('#number_select_text').val('');

    if ($('#current_game').val() == "17" || $('#current_game').val() == "317" || $('#current_game').val() == "56" || $('#current_game').val() == "8" 
    || $('#current_game').val() == "308" || $('#current_game').val() == "317"
    || $('#current_game').val() == "408" || $('#current_game').val() == "417"
    || $('#current_game').val() == "508" || $('#current_game').val() == "517"
    || $('#current_game').val() == "608" || $('#current_game').val() == "617"
    || $('#current_game').val() == "352" || $('#current_game').val() == "452" || $('#current_game').val() == "552" || $('#current_game').val() == "652"
    ) {
        // $('#quick_input_gameplay').attr('class','row hidden');
    } else {
        $('#quick_input_gameplay').attr('class', 'row');
    }

    if ($('#current_game').val() == "29" || $('#current_game').val() == "329" || $('#current_game').val() == "429" || $('#current_game').val() == "529" || $('#current_game').val() == "629" || $('#current_game').val() == "9" || $('#current_game').val() == "10" || $('#current_game').val() == "11" ||
        $('#current_game').val() == "19" || $('#current_game').val() == "20" || $('#current_game').val() == "21"
        || $('#current_game').val() == "109" || $('#current_game').val() == "110" || $('#current_game').val() == "111" ||
        $('#current_game').val() == "119" || $('#current_game').val() == "120" || $('#current_game').val() == "121"
        || $('#current_game').val() == "309" || $('#current_game').val() == "310" || $('#current_game').val() == "311"
        || $('#current_game').val() == "409" || $('#current_game').val() == "410" || $('#current_game').val() == "411"
        || $('#current_game').val() == "509" || $('#current_game').val() == "510" || $('#current_game').val() == "511"
        || $('#current_game').val() == "609" || $('#current_game').val() == "610" || $('#current_game').val() == "611"
        || $('#current_game').val() == "709" || $('#current_game').val() == "710" || $('#current_game').val() == "711"
        ) {
        $('#tongsoxien').attr('class', 'row');
    } else {
        $('#tongsoxien').attr('class', 'row hidden');
        $('#box-cuoc-lo-truot-xien').addClass('hidden');
    }
    if ($('#current_game').val() == "19" || $('#current_game').val() == "20" || $('#current_game').val() == "21") {

    }

    var clockremaincode = game_code;
    // if (clockremaincode == 7 || clockremaincode == 8)
    //             clockremaincode = 1;

    if (clockremaincode == 307 || clockremaincode == 308)
        clockremaincode = 301;
    if (clockremaincode == 407 || clockremaincode == 408)
        clockremaincode = 401;
    if (clockremaincode == 507 || clockremaincode == 508)
        clockremaincode = 501;
    if (clockremaincode == 607 || clockremaincode == 608)
        clockremaincode = 601;

    if (clockremaincode == 29 || clockremaincode == 11 || clockremaincode == 9 || clockremaincode == 10)
        clockremaincode = 2;

    if (clockremaincode == 329 || clockremaincode == 311 || clockremaincode == 309 || clockremaincode == 310)
        clockremaincode = 302;

    if (clockremaincode == 429 || clockremaincode == 411 || clockremaincode == 409 || clockremaincode == 410)
        clockremaincode = 402;

    if (clockremaincode == 529 || clockremaincode == 511 || clockremaincode == 509 || clockremaincode == 510)
        clockremaincode = 502;

    if (clockremaincode == 629 || clockremaincode == 611 || clockremaincode == 609 || clockremaincode == 610)
        clockremaincode = 602;

    if (clockremaincode == 16 || clockremaincode == 19 || clockremaincode == 20 || clockremaincode == 21)
        clockremaincode = 3;

    if (clockremaincode == 107 || clockremaincode == 108)
        clockremaincode = 101;

    if (clockremaincode == 111 || clockremaincode == 109 || clockremaincode == 110)
        clockremaincode = 102;

    if (clockremaincode == 116 || clockremaincode == 119 || clockremaincode == 120 || clockremaincode == 121)
        clockremaincode = 103;

    if (clockremaincode == 711 || clockremaincode == 709 || clockremaincode == 710)
            clockremaincode = 702;
            
    // if (clockremaincode >=  31 && clockremaincode <= 55)
    //     clockremaincode = 24;

    if ($('#clock_' + clockremaincode).html() == "Hết giờ") {
        $('#btn_Delete').prop('disabled', true);
        $('#btn_OK').prop('disabled', true);
    } else {
        $('#btn_Delete').prop('disabled', false);
        $('#btn_OK').prop('disabled', false);
    }
    $('.refresh').show();
    $('#game-play').addClass('not-active');
    $('#' + 200).fadeOut();
    $('#' + 200).load($('#url').val() + "/load-number/" + game_code, function() {
        $('#' + 200).fadeIn();
        $('.refresh').hide();
        $('#game-play').removeClass('not-active');
        $('.input_game').hide();
        time_remainCheckUpdateOne();
    });
    setTimeout(function(){
        $('#tabnew').css('pointer-events','all')
        $('#tabgameContent').css('pointer-events','all')
        $('#spinnerBet'+game_code).css('display','none')
    }, 500);
}

function LoadContentGame(game_code, game_name,alias, max_point, max_point_one, odds, open, close, exchange_rates) {
    if (alias != ""){
        console.log("LoadContentGame" + alias);
        window.history.pushState(alias, alias, "/xoso/mienbac/" +alias);
    }
    $('#tabnew').css('pointer-events','none')
    $('#tabgameContent').css('pointer-events','none')
    $('#spinnerBet'+game_code).css('display','-webkit-inline-box')

    Huy();
    $('#panel_bet').attr('class', 'panel panel-color panel-inverse');
    $('#open').val(open);
    $('#close').val(close);
    $('#current_game').val(game_code);
    $('#gamecode').val(game_code);
    $('#game_name').html(game_name);
    $('#max_point').html(number_format(max_point));
    $('#exchange_rates_raw').val(number_format(exchange_rates));
    $('#max_point_one').html(number_format(max_point_one));
    $('#odds').html(number_format(odds));
    $('#number_select_text').val('');
    if (game_code != 2 && game_code != 302) {
        $('#box-cuoc-lo-xien').addClass('hidden');
        $('#tongdiem').addClass('hidden');
        $('#input_point').removeClass('hidden');
    }
    // if (game_code == 9 || game_code == 10 || game_code == 11) {
    //     $('#box-cuoc-lo-truot-xien').removeClass('hidden');
    //     // $('#tongdiem').addClass('hidden');
    //     // $('#input_point').removeClass('hidden');
    // }
    
    if ($('#current_game').val() == "17" || $('#current_game').val() == "317" || $('#current_game').val() == "56" || $('#current_game').val() == "8"
    || $('#current_game').val() == "117" || $('#current_game').val() == "108"
    || $('#current_game').val() == "308"
    || $('#current_game').val() == "408" || $('#current_game').val() == "417"
    || $('#current_game').val() == "508" || $('#current_game').val() == "517"
    || $('#current_game').val() == "608" || $('#current_game').val() == "617"
    || $('#current_game').val() == "352" || $('#current_game').val() == "452" || $('#current_game').val() == "552" || $('#current_game').val() == "652"
    ) {
        // $('#quick_input_gameplay').attr('class','row hidden');
        $('#quick_input_gameplay').attr('class', 'row');
    } else {
        $('#quick_input_gameplay').attr('class', 'row');
    }

    if ($('#current_game').val() == "29" || $('#current_game').val() == "329" || $('#current_game').val() == "429" || $('#current_game').val() == "529" || $('#current_game').val() == "629" || $('#current_game').val() == "9" || $('#current_game').val() == "10" || $('#current_game').val() == "11" ||
        $('#current_game').val() == "19" || $('#current_game').val() == "20" || $('#current_game').val() == "21"
    || $('#current_game').val() == "109" || $('#current_game').val() == "110" || $('#current_game').val() == "111" ||
        $('#current_game').val() == "119" || $('#current_game').val() == "120" || $('#current_game').val() == "121"
        || $('#current_game').val() == "309" || $('#current_game').val() == "310" || $('#current_game').val() == "311"
        || $('#current_game').val() == "409" || $('#current_game').val() == "410" || $('#current_game').val() == "411"
        || $('#current_game').val() == "509" || $('#current_game').val() == "510" || $('#current_game').val() == "511"
        || $('#current_game').val() == "609" || $('#current_game').val() == "610" || $('#current_game').val() == "611"
        || $('#current_game').val() == "709" || $('#current_game').val() == "710" || $('#current_game').val() == "711") {
        $('#tongsoxien').attr('class', 'row');
    } else {
        $('#tongsoxien').attr('class', 'row hidden');
    }

    if ($('#current_game').val() == "29" || $('#current_game').val() == "329" || $('#current_game').val() == "429" || $('#current_game').val() == "529" || $('#current_game').val() == "629" || $('#current_game').val() == "9" || $('#current_game').val() == "10" || $('#current_game').val() == "11" ||$('#current_game').val() == "19" || $('#current_game').val() == "20" || $('#current_game').val() == "21"
        || $('#current_game').val() == "109" || $('#current_game').val() == "110" || $('#current_game').val() == "111" ||$('#current_game').val() == "119" || $('#current_game').val() == "120" || $('#current_game').val() == "121"
        || $('#current_game').val() == "309" || $('#current_game').val() == "310" || $('#current_game').val() == "311"
        || $('#current_game').val() == "409" || $('#current_game').val() == "410" || $('#current_game').val() == "411"
        || $('#current_game').val() == "509" || $('#current_game').val() == "510" || $('#current_game').val() == "511"
        || $('#current_game').val() == "609" || $('#current_game').val() == "610" || $('#current_game').val() == "611") {
        $('#box-cuoc-lo-truot-xien').removeClass('hidden');
    } else {
        $('#box-cuoc-lo-truot-xien').addClass('hidden');
    }

    if ($('#current_game').val() == "18" || $('#current_game').val() == "12" || $('#current_game').val() == "14"){
        $('#kqsxmin').removeClass('hidden');
    }else{
        $('#kqsxmin').addClass('hidden');
    }

    if ($('#current_game').val() == "700"
    || ($('#current_game').val() >= "721" && $('#current_game').val() <= "739")){
        $('#kqkenomin').removeClass('hidden');
    }else{
        $('#kqkenomin').addClass('hidden');
    }

    var clockremaincode = game_code;
    // if (clockremaincode == 7 || clockremaincode == 8)
    //             clockremaincode = 1;

    if (clockremaincode == 307 || clockremaincode == 308)
        clockremaincode = 301;
    if (clockremaincode == 407 || clockremaincode == 408)
        clockremaincode = 401;
    if (clockremaincode == 507 || clockremaincode == 508)
        clockremaincode = 501;
    if (clockremaincode == 607 || clockremaincode == 608)
        clockremaincode = 601;

    if (clockremaincode == 29 || clockremaincode == 11 || clockremaincode == 9 || clockremaincode == 10)
        clockremaincode = 2;

    if (clockremaincode == 329 || clockremaincode == 311 || clockremaincode == 309 || clockremaincode == 310)
        clockremaincode = 302;

    if (clockremaincode == 429 || clockremaincode == 411 || clockremaincode == 409 || clockremaincode == 410)
        clockremaincode = 402;

    if (clockremaincode == 529 || clockremaincode == 511 || clockremaincode == 509 || clockremaincode == 510)
        clockremaincode = 502;

    if (clockremaincode == 629 || clockremaincode == 611 || clockremaincode == 609 || clockremaincode == 610)
        clockremaincode = 602;

    if (clockremaincode == 16 || clockremaincode == 19 || clockremaincode == 20 || clockremaincode == 21)
        clockremaincode = 3;

    if (clockremaincode == 107 || clockremaincode == 108)
        clockremaincode = 101;

    if (clockremaincode == 111 || clockremaincode == 109 || clockremaincode == 110)
        clockremaincode = 102;

    if (clockremaincode == 116 || clockremaincode == 119 || clockremaincode == 120 || clockremaincode == 121)
        clockremaincode = 103;

    if (clockremaincode == 711 || clockremaincode == 709 || clockremaincode == 710)
            clockremaincode = 702;

    if ($('#clock_' + clockremaincode).html() == "Hết giờ") {
        $('#btn_Delete').prop('disabled', true);
        $('#btn_OK').prop('disabled', true);
    } else {
        $('#btn_Delete').prop('disabled', false);
        $('#btn_OK').prop('disabled', false);
    }
    $('.refresh').show();
    $('#game-play').addClass('not-active');
    // $('#' + game_code).fadeOut();
    $('#' + game_code).load($('#url').val() + "/load-number/" + game_code, function() {
        // $('#' + game_code).fadeIn();
        $('.refresh').hide();
        $('#game-play').removeClass('not-active');
        $('.input_game').hide();
        time_remainCheckUpdateOne();
        setTimeout(function(){
            $('#tabnew').css('pointer-events','all')
            $('#tabgameContent').css('pointer-events','all')
            $('#spinnerBet'+game_code).css('display','none')
        }, 500);
    });
}
var choices = [];

function Select_Number(numb, game_code, value) {
    if (locknumberStore.includes(value)) return;

    if (game_code == 2 || game_code == 302 || game_code == 402 || game_code == 502 || game_code == 602 || game_code == 702) {
        Select_Number_PlayLoXienCode2(numb, game_code, value, game_code);
        return;
    }
    //Xien Nhay 2
    if (game_code == 29 || game_code == 329 || game_code == 429 || game_code == 529 || game_code == 629) {
        Select_Number_PlayLoXien(numb, game_code, value, 15 - 1);
        return;
    }
    //Xien 2
    if (game_code == 9) {
        Select_Number_PlayLoXien(numb, game_code, value, 15 - 1);
        return;
    }
    //Xien 3
    if (game_code == 10) {
        Select_Number_PlayLoXien(numb, game_code, value, 11 - 1);
        return;
    }
    //Xien 4
    if (game_code == 11) {
        Select_Number_PlayLoXien(numb, game_code, value, 10 - 1);
        return;
    }

    //Xien 2
    if (game_code == 309 || game_code == 409 || game_code == 509 || game_code == 609 || game_code == 709) {
        Select_Number_PlayLoXien(numb, game_code, value, 15 - 1);
        return;
    }
    //Xien 3
    if (game_code == 310 || game_code == 410 || game_code == 510 || game_code == 610 || game_code == 710) {
        Select_Number_PlayLoXien(numb, game_code, value, 11 - 1);
        return;
    }
    //Xien 4
    if (game_code == 311 || game_code == 411 || game_code == 511 || game_code == 611 || game_code == 711) {
        Select_Number_PlayLoXien(numb, game_code, value, 10 - 1);
        return;
    }

    //Trượt Xien 4
    if (game_code == 19) {
        Select_Number_PlayLoXien(numb, game_code, value, 10);
        return;
    }
    //Trượt Xien 8
    if (game_code == 20) {
        Select_Number_PlayLoXien(numb, game_code, value, 8);
        return;
    }
    //Trượt Xien 10
    if (game_code == 21) {
        Select_Number_PlayLoXien(numb, game_code, value, 10);
        return;
    }

    //XSAO

    if (game_code == 102) {
        Select_Number_PlayLoXienCode2(numb, game_code, value, 2);
        return;
    }
    //Xien 2
    if (game_code == 109) {
        Select_Number_PlayLoXien(numb, game_code, value, 15 - 1);
        return;
    }
    //Xien 3
    if (game_code == 110) {
        Select_Number_PlayLoXien(numb, game_code, value, 11 - 1);
        return;
    }
    //Xien 4
    if (game_code == 111) {
        Select_Number_PlayLoXien(numb, game_code, value, 10 - 1);
        return;
    }
    //Trượt Xien 4
    if (game_code == 119) {
        Select_Number_PlayLoXien(numb, game_code, value, 10);
        return;
    }
    //Trượt Xien 8
    if (game_code == 120) {
        Select_Number_PlayLoXien(numb, game_code, value, 8);
        return;
    }
    //Trượt Xien 10
    if (game_code == 121) {
        Select_Number_PlayLoXien(numb, game_code, value, 10);
        return;
    }
    //Lo de nhat cang
    Select_Number_PlayLoDe(numb, game_code, value);
}

function KeyUpInputChange(input, game_code, value,keyCode) {

    if (keyCode == 13) {
        if ($('#btn_OK').prop('disabled') == false)
            $("#btn_OK").click();
    }
    if (keyCode == 27) {
        $("#btn_Delete").click();
    }

    var point = Number($(input).val());
    for (var i = 0; i < choices.length; i++) {
        if (choices[i].name == value) {
            choices[i].value = point;
            choices[i].total = point * choices[i].exchange;
            break;
        }
    }
    var point = 0;
    var total = 0;
    for (var i = 0; i < choices.length; i++) {
        point += Number(choices[i].value);
        total += Number(choices[i].total);
    }
    $('#point').html(point);
    $('#total').html(total.toLocaleString('en'));
}

function Huy() {
    choices = [];
    $('#input_point').val('');
    $('#input_point2').val('');
    $('#input_point3').val('');
    $('#input_point4').val('');
    $('#input_pointxn').val('');
    $('#point').html(0);
    $('#point2').html(0);
    $('#point3').html(0);
    $('#point4').html(0);
    $('#pointxn').html(0);
    $('#total').html(0);
    $('#total2').html(0);
    $('#total3').html(0);
    $('#total4').html(0);
    $('#totalxn').html(0);

    $('#number_select_text').val('');
    $('#number_select_xien2').html(0);
    $('#number_select_xien3').html(0);
    $('#number_select_xien4').html(0);
    $('#number_select_xiennhay').html(0);
    $('#number_select').html('');
    $('.number_block .input_game').val('');
    $('.input_game').val('');
    $('.number_block').addClass('number_content');
    $('.number_content').removeClass('number_block');
    $('.input_game').hide();
    $('.label_game').show();
    $('input:checkbox').removeAttr('checked');
}

function userConfirmBetSuccess(){
    if (localStorage.getItem('userConfirmBetSuccess') == 0){
        console.log('userConfirmBetSuccess')
        Swal.fire({
            title: "Thành công",
            // html: text + '</br>' + text2 + '</br>' + text3,
            html: 'Bạn đã đặt cược thành công',
            type: "info",
            timer: 30000,
            showCancelButton: false,
            // confirmButtonColor: "#DD6B55",
            confirmButtonText: "Tiếp tục",
            // cancelButtonText: "Hủy",
            closeOnConfirm: true,
            reverseButtons:true,
            input: 'checkbox',
            inputValue: 0,
            allowOutsideClick: false,
            allowEscapeKey: false,
            inputPlaceholder:
              'Không hỏi lại cho lần cược sau?',
            inputValidator: (result) => {
                console.log(result)
                localStorage.setItem('userConfirmBetSuccess', result)
            //   return !result && 'You need to agree with T&C'
            },
        });
    }
}

function compare( a, b ) {
    if ( a.name < b.name ){
      return -1;
    }
    if ( a.name > b.name ){
      return 1;
    }
    return 0;
  }

function Action(){
    
    if ($('#current_game').val() == "2" || $('#current_game').val() == "302"
            || $('#current_game').val() == "402" || $('#current_game').val() == "502" || $('#current_game').val() == "602"
            || $('#current_game').val() == "702") {
                DatCuocLoXien2(2)
                return;
            }

            if ($('#current_game').val() == "29" || $('#current_game').val() == "329" || $('#current_game').val() == "429" || $('#current_game').val() == "529" || $('#current_game').val() == "629") {
                DatCuocLoXien(2);
                return;
            }

            if ($('#current_game').val() == "9" || $('#current_game').val() == "309" || $('#current_game').val() == "409" || $('#current_game').val() == "509" || $('#current_game').val() == "609" || $('#current_game').val() == "709") {
                DatCuocLoXien(2);
                return;
            }
            if ($('#current_game').val() == "10" || $('#current_game').val() == "310" || $('#current_game').val() == "410" || $('#current_game').val() == "510" || $('#current_game').val() == "610" || $('#current_game').val() == "710") {
                DatCuocLoXien(3);
                return;
            }
            if ($('#current_game').val() == "11" || $('#current_game').val() == "311" || $('#current_game').val() == "411" || $('#current_game').val() == "511" || $('#current_game').val() == "611" || $('#current_game').val() == "711") {
                DatCuocLoXien(4);
                return;
            }
            if ($('#current_game').val() == "19") {
                DatCuocLoXien(4);
                return;
            }
            if ($('#current_game').val() == "20") {
                DatCuocLoXien(8);
                return;
            }
            if ($('#current_game').val() == "21") {
                DatCuocLoXien(10);
                return;
            }

            //XSAO
            if ($('#current_game').val() == "102") {
                DatCuocLoXien2(2)
                return;
            }

            if ($('#current_game').val() == "109") {
                DatCuocLoXien(2);
                return;
            }
            if ($('#current_game').val() == "110") {
                DatCuocLoXien(3);
                return;
            }
            if ($('#current_game').val() == "111") {
                DatCuocLoXien(4);
                return;
            }
            if ($('#current_game').val() == "119") {
                DatCuocLoXien(4);
                return;
            }
            if ($('#current_game').val() == "120") {
                DatCuocLoXien(8);
                return;
            }
            if ($('#current_game').val() == "121") {
                DatCuocLoXien(10);
                return;
            }

            var total_money = Number($('#total_money').html().replace(/[^0-9\.]+/g, ""));
            var max_point = Number($('#max_point').html().replace(/[^0-9\.]+/g, ""));
            var max_point_one = Number($('#max_point_one').html().replace(/[^0-9\.]+/g, ""));
            var point = 0;
            var total = 0;
            for (var i = 0; i < choices.length; i++) {
                point += Number(choices[i].value);
                if ($('#current_game').val() == "15" || $('#current_game').val() == "315"){
                    if (total < Number(choices[i].total))
                        total = Number(choices[i].total);
                }
                else
                    total += Number(choices[i].total);
                if (Number(choices[i].total) <= 0 )
                {
                    alert("Mã Cược "+choices[i].name+" lỗi. Hãy kiểm tra lại");
                    return;
                }
                if (Number(choices[i].value) > max_point_one) {
                    alert("Số điểm bạn đặt cược lớn hơn giới hạn cho phép "+ max_point_one);
                    return;
                }

            }

            // if (point > max_point) {
            //     alert("Số điểm bạn đặt cược lớn hơn giới hạn cho phép");
            //     return;
            // }

            if (total > total_money) {
                alert("Số tiền bạn đặt cược lớn hơn số dư tài khoản");
                return;
            } else {

                let timerInterval
                Swal.fire({
                title: 'Đang vào cược',
                html: 'Vui lòng chờ trong giây lát.',
                timer: 20000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
                })

                $_token = $('#token').val();
                $.ajax({
                    url: $('#url').val() + "/store",
                    method: 'POST',
                    dataType: 'html',
                    data: {
                        choices: choices,
                        game_code: $('#current_game').val(),
                        odds: $('#odds').html().replace(/[^0-9\.]+/g, ""),
                        _token: $_token,
                        ipaddr: $('#ipaddress').val(),
                    },
                    success: function(data) {
                        Swal.close()
                        if (data == "exchange") {
                            alert("Giá mua có sự thay đổi. Hãy load lại trang để cập nhật giá mới nhất");
                            location.reload();
                        } else
                        if (data.indexOf("maxbet:") >= 0) {
                            alert("Mã cược " + data.replace("maxbet: ", "") + " Vượt quá giới hạn chơi cho phép ");
                            //  + $('#max_point').html());
                        }else
                        if (data.indexOf("maxbetTong:") >= 0) {
                            alert("Mã cược " + data.replace("maxbetTong: ", "") + " Vượt quá giới hạn chơi cho phép. Hãy liên hệ với quản lý. ");
                            //  + $('#max_point').html());
                        } else
                        if (data == "overloadmoney") {
                            alert("Vượt quá giới hạn tiền hoặc có lỗi xảy ra. Hãy load lại trang");
                            location.reload();
                        } else
                        if (data == "overtime") {
                            alert("Hết thời gian đặt cược.");
                            location.reload();
                        } else 
                        if (data == "error001" || data == "error") {
                            alert("Có lỗi xảy ra error001. Đặt cược không thành công!");
                            location.reload();
                        }else
                        if (data == "error021") {
                            alert(" Vượt quá giới hạn chơi cho phép.");
                            //  + $('#max_point').html());
                        } else if (data == "ok")
                        {
                            //$('#btn_CreateOK').click();
                            // swal("Thành công", "Bạn đã đặt cược thành công", "success")
                            // swal({ title: "", text: "Bạn đã đặt cược thành công.", timer: 500, showConfirmButton: false, closeOnConfirm: false });
                            userConfirmBetSuccess()
                            refreshUser_Info();
                            g_refresh_bets_top5();
                            Huy();
                        }else{
                            // location.reload();
                            Swal.fire({
                                title: "Đặt cược không thành công!",
                                // html: text + '</br>' + text2 + '</br>' + text3,
                                html: data,
                                type: "info",
                                timer: 30000,
                                showCancelButton: false,
                                // confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Tiếp tục",
                                // cancelButtonText: "Hủy",
                                closeOnConfirm: true,
                                reverseButtons:true,
                                // input: 'checkbox',
                                // inputValue: 0,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                // inputPlaceholder:
                                //   'Không hỏi lại cho lần cược sau?',
                                // inputValidator: (result) => {
                                //     console.log(result)
                                //     localStorage.setItem('userConfirmBetSuccess', result)
                                // //   return !result && 'You need to agree with T&C'
                                // },
                            });
                        }
                        $('#btn_OK').html('Đặt cược');
                    },
                    error: function(data) {
                        // location.reload();
                        $('#btn_OK').html('Đặt cược');
                    }
                });
            }
}
  
async function NormalC(){
    
    // swal({
    //     title: "Bạn có muốn đặt cược",
    //     text: text,
    //     // html: 'You can use <b>bold text</b>, ' +
    //     // '<a href="//sweetalert2.github.io">links</a> ' +
    //     // 'and other HTML tags',
    //     type: "info",
    //     timer: 10000,
    //     showCancelButton: true,
    //     confirmButtonColor: "#DD6B55",
    //     confirmButtonText: "Đặt cược",
    //     cancelButtonText: "Hủy Cược",
    //     closeOnConfirm: true
    // }, 
    // function (dismiss) {
    //     if (dismiss === null) {
    //         Huy();
    //         swal.close();
    //         return;
    //     }
    //     if (dismiss) {
    //         Action()
    //     } else {
    //         // swal("Cancelled", "Your imaginary file is safe :)", "error");
    //         // Huy();
    //         // swal.close();
    //         // return;
    //     }
    //   }
    //   ,function(isConfirm) {
        
    // });

    // const { value: accept } = await Swal.fire({
    //     title: 'Terms and conditions',
    //     input: 'checkbox',
    //     inputValue: 1,
    //     inputPlaceholder:
    //       'Không hỏi lại cho lần cược sau?',
    //     confirmButtonText:
    //       'Tiếp tục <i class="fa fa-arrow-right"></i>',
    //     inputValidator: (result) => {
    //       return !result && 'You need to agree with T&C'
    //     }
    //   })
    // console.log(localStorage.getItem('userConfirmBet') == false ? 0 : 1)
    if (localStorage.getItem('userConfirmBet') == 0)
    
      Swal.fire({
        title: "Bạn có muốn đặt cược",
        html: text + '</br>' + text2 + '</br>' + text3,
        type: "info",
        timer: 10000,
        showCancelButton: true,
        // confirmButtonColor: "#DD6B55",
        confirmButtonText: "Đặt cược",
        cancelButtonText: "Hủy Cược",
        closeOnConfirm: true,
        reverseButtons: true,
        input: 'checkbox',
        inputValue: 0,
        allowOutsideClick: false,
        allowEscapeKey: false,
        inputPlaceholder:
          'Không hỏi lại cho lần cược sau?',
        inputValidator: (result) => {
            console.log(result)
            localStorage.setItem('userConfirmBet', result)

            Action()
            $('#btn_OK').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Đang vào cược...');
        //   return !result && 'You need to agree with T&C'
        }
    });
    else{
        $('#btn_OK').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Đang vào cược...');
        Action()
    }
}

function length(string){
    var count = 0;
    while(string[count] != undefined)
       count++;
    return count;
  }
  
  function contains(masterString, subString) {
      var masterStringLength = length(masterString);
      var subStringLength = length(subString);
      for(var i = 0; i <= masterStringLength - subStringLength; i++)
      {
          var count = 0;
          for(var k = 0; k < subStringLength; k++)
          {
              if(masterString[i + k] == subString[k])
                 count++;
              else
                 break;
          }
          if(count == subStringLength)
              return true;
  
      }
      return false;
  }

function ExceptionC(){
    let timerInterval
    Swal.fire({
        title: "<div style='font-size:1.2em'> " + "Bạn có muốn đặt cược" + "</div>",
        html: "<div style='font-size:1.2em'> " + text + '</br> Đang chờ cược <b></b> giây.' + "</div>",
        timer: 12000,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Đặt cược",
        cancelButtonText: "<div style='font-size:1.2em'>" + "Hủy Cược" + "</div>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        // closeOnConfirm: true,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                    b.textContent = Math.round(Swal.getTimerLeft()/1000)
                    }
                }
                // console.log('timming')
                // console.log($('#number_select_text').val())
                // (nhat 12, lô 2, lô trượt 3, đầu nhất 28, 3 càng nhất 56 như nhất)
                if ($('#current_game').val() != "9" && $('#current_game').val() != "10" && $('#current_game').val() != "11" && $('#current_game').val() != "200" && $('#current_game').val() != "2"){
                    var str = $('#firstqq').html()
                    // console.log(str)
                    if (str != "-----"){
                        var res = ""

                        if ($('#current_game').val() == "12" || $('#current_game').val() == "2" 
                        // || $('#current_game').val() == "3" 
                        )
                            res = str.length >= 2 ? str.substring(str.length - 2, str.length) : "--"
                        else if ($('#current_game').val() == "28") //dau nhat
                            res = str.length >= 2 ? str.substring(0, 2) : "--"
                        else if ($('#current_game').val() == "29") // 3 cang nhat
                            res = str.length >= 3 ? str.substring(str.length - 3, str.length) : "---"

                        var cc = $('#number_select_text').val()
        
                        if (contains(cc, res) ) 
                        {
                            clearInterval( timerInterval );
                            Swal.close()
                            swal({ title: "", text: "Hết giờ cược.", timer: 2000, showConfirmButton: false, closeOnConfirm: false });
                        }else{
                            clearInterval( timerInterval );
                            Swal.close()
                            Action()
                        }
                    }else{
                        
                    }
                }
                
            }, 1000)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
                Action()
            }
        })
    return;
}

function checkProcessing(){
    var s = $('#time-zone').html();
    if (!(!s || 0 === s.length)) {
        var time_result = $('#time_result').val();
        var time = time_result.split(':');
        var time_h = Number(time[0]);
        var time_m = Number(time[1]);
        var dnow = new Date(s);

        var d = s.split(' ');
        var ddate = d[0].split('-');
        var ttime = d[1].split(':');

        chour = dnow.getHours();
        cminus = dnow.getMinutes();
        // second = d.getSeconds();

        hour = parseInt(ttime[0]);
        minus = parseInt(ttime[1]);
        second = parseInt(ttime[2]);

        day = parseInt(ddate[2]);
        month = parseInt(ddate[1]);
        year = parseInt(ddate[0]);

        if (hour == 18 && minus >= 14 && minus <= 30)
            return true;
        // console.log(second);
    }
    return false;
}

function DatCuoc() {
    if ($('#total').html() == '0') {
        alert("Bạn chưa chọn số hoặc chưa vào tiền.");
        return;
    }

    if ($('#current_game').val() == "16") {
        if (choices.length < 3) {
            alert("Bạn phải chọn " + 3 + " số ");
            return;
        }
    }

    if ($('#current_game').val() == "15") {
        if (choices.length < 10) {
            alert("Bạn phải chọn " + 10 + " số ");
            return;
        }
    }
    
    if (choices.length > 0) {
        if ($('#current_game').val() == "700" || ($('#current_game').val() >= "721" && $('#current_game').val() <= "739")){
            text = "Danh sách: " + $.trim($('#game_name').html()) + "";
            text2 = "Thành tiền :" + $('#total').html() + "";
            text3 = "Tổng điểm :" + $('#point').html() + "";
        }
        else{
            text = "Danh sách: " + $('#number_select_text').val() + "";
            text2 = "Thành tiền : " + $('#total').html() + "";
            text3 = "Tổng điểm :" + $('#point').html() + "";
        }
            // (lô 2, lô trượt 3, đầu nhất 28, 3 càng nhất 56 như nhất)
        if ( ($('#current_game').val() == "12" || $('#current_game').val() == "2"
        || $('#current_game').val() == "200" 
        || $('#current_game').val() == "28" || $('#current_game').val() == "56"
        // ||$('#current_game').val() == "9" || $('#current_game').val() == "10" || $('#current_game').val() == "11"
        )
                && checkProcessing() )
            ExceptionC();
        else
            NormalC();
    } else {
        alert("Bạn chưa chọn số");
    }
}

function refreshUser_Info() {
    $('#user_info').load("/reload-user", function() {});
    $_token = $('#token').val();
    $.ajax({
        url: "/reload-user-data",
        method: 'POST',
        dataType: 'html',
        data: {
            _token: $_token,
        },
        success: function(data) {
            data = JSON.parse(data)
            $("#remain").html(data.remain)
            $("#remain_gameplay").html(data.remain)
            $("#total_money").html(data.remain)
            $("#inbet").html(data.inbet)
            $("#winlose").html(data.winlose)
        },
        error: function(data) {
        }
    });
}

function ChangeTotalPointXN(input) {
    if (choices.length <= 14 && choices.length >= 2) {
        var pointxn = Number($(input).val());
        for (var i = 0; i < choices.length; i++) {
            choices[i].value = 1;
            choices[i].totalxn = choices[i].exchangexn;
        }

        var totalxn = 0;
        for (var i = 0; i < choices.length; i++) {
            totalxn += choices[i].totalxn
        }
        var Ankxn = 1;
        Ankxn = fact(choices.length) / fact(2) / fact(choices.length - 2);
        type = 2;
        totalxn = totalxn * 2 / choices.length;
        // }
        // if (type == 11 - 1) {
        //     Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
        //     type = 3;
        //     total = total * 3 / choices.length;
        // }
        // if (type == 10 - 1) {
        //     Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
        //     type = 4;
        //     total = total * 4 / choices.length;
        // }

        $('#pointxn').html(pointxn * Ankxn);
        //if(point==0)point=1;
        $('#totalxn').html(Math.ceil(Ankxn * totalxn * pointxn / type).toLocaleString('en'));
        $('#number_select_xiennhay').html(Ankxn);
        $('#total').html((Number($('#totalxn').html().replace(/[^0-9\.]+/g, ""))+Number($('#total2').html().replace(/[^0-9\.]+/g, "")) + Number($('#total3').html().replace(/[^0-9\.]+/g, "")) + Number($('#total4').html().replace(/[^0-9\.]+/g, ""))).toLocaleString('en'));
        $('#point').html(Number($('#pointxn').html().replace(/[^0-9\.]+/g, ""))+Number($('#point2').html().replace(/[^0-9\.]+/g, "")) + Number($('#point3').html().replace(/[^0-9\.]+/g, "")) + Number($('#point4').html().replace(/[^0-9\.]+/g, "")));
    }
}

function ChangeTotalPoint2(input) {
    if (choices.length <= 14 && choices.length >= 2) {
        var point2 = Number($(input).val());
        for (var i = 0; i < choices.length; i++) {
            choices[i].value = 1;
            choices[i].total2 = choices[i].exchange2;
        }

        var total2 = 0;
        for (var i = 0; i < choices.length; i++) {
            total2 += choices[i].total2
        }
        var Ank2 = 1;
        Ank2 = fact(choices.length) / fact(2) / fact(choices.length - 2);
        type = 2;
        total2 = total2 * 2 / choices.length;
        // }
        // if (type == 11 - 1) {
        //     Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
        //     type = 3;
        //     total = total * 3 / choices.length;
        // }
        // if (type == 10 - 1) {
        //     Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
        //     type = 4;
        //     total = total * 4 / choices.length;
        // }

        $('#point2').html(point2 * Ank2);
        //if(point==0)point=1;
        $('#total2').html(Math.ceil(Ank2 * total2 * point2 / type).toLocaleString('en'));
        $('#number_select_xien2').html(Ank2);
        $('#total').html((Number($('#totalxn').html().replace(/[^0-9\.]+/g, "")) + Number($('#total2').html().replace(/[^0-9\.]+/g, "")) + Number($('#total3').html().replace(/[^0-9\.]+/g, "")) + Number($('#total4').html().replace(/[^0-9\.]+/g, ""))).toLocaleString('en'));
        $('#point').html(Number($('#pointxn').html().replace(/[^0-9\.]+/g, "")) +Number($('#point2').html().replace(/[^0-9\.]+/g, "")) + Number($('#point3').html().replace(/[^0-9\.]+/g, "")) + Number($('#point4').html().replace(/[^0-9\.]+/g, "")));
    }
}

function ChangeTotalPoint3(input) {
    if (choices.length <= 14 && choices.length >= 3) {
        var point3 = Number($(input).val());
        for (var i = 0; i < choices.length; i++) {
            choices[i].value = 1;
            choices[i].total3 = choices[i].exchange3;
        }

        var total3 = 0;
        for (var i = 0; i < choices.length; i++) {
            total3 += choices[i].total3
        }
        var Ank3 = 1;
        Ank3 = fact(choices.length) / fact(3) / fact(choices.length - 3);
        type = 3;
        total3 = total3 * 3 / choices.length;
        // }
        // if (type == 11 - 1) {
        //     Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
        //     type = 3;
        //     total = total * 3 / choices.length;
        // }
        // if (type == 10 - 1) {
        //     Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
        //     type = 4;
        //     total = total * 4 / choices.length;
        // }

        $('#point3').html(point3 * Ank3);
        //if(point==0)point=1;
        $('#total3').html(Math.ceil(Ank3 * total3 * point3 / type).toLocaleString('en'));
        $('#number_select_xien3').html(Ank3);
        $('#total').html((Number($('#totalxn').html().replace(/[^0-9\.]+/g, "")) + Number($('#total2').html().replace(/[^0-9\.]+/g, "")) + Number($('#total3').html().replace(/[^0-9\.]+/g, "")) + Number($('#total4').html().replace(/[^0-9\.]+/g, ""))).toLocaleString('en'));
        $('#point').html(Number($('#pointxn').html().replace(/[^0-9\.]+/g, "")) + Number($('#point2').html().replace(/[^0-9\.]+/g, "")) + Number($('#point3').html().replace(/[^0-9\.]+/g, "")) + Number($('#point4').html().replace(/[^0-9\.]+/g, "")));
    }
}

function ChangeTotalPoint4(input) {
    if (choices.length <= 14 && choices.length >= 4) {
        var point4 = Number($(input).val());
        for (var i = 0; i < choices.length; i++) {
            choices[i].value = 1;
            choices[i].total4 = choices[i].exchange4;
        }

        var total4 = 0;
        for (var i = 0; i < choices.length; i++) {
            total4 += choices[i].total4
        }
        var Ank4 = 1;
        Ank4 = fact(choices.length) / fact(4) / fact(choices.length - 4);
        type = 4;
        total4 = total4 * 4 / choices.length;
        // }
        // if (type == 11 - 1) {
        //     Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
        //     type = 3;
        //     total = total * 3 / choices.length;
        // }
        // if (type == 10 - 1) {
        //     Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
        //     type = 4;
        //     total = total * 4 / choices.length;
        // }

        $('#point4').html(point4 * Ank4);
        //if(point==0)point=1;
        $('#total4').html(Math.ceil(Ank4 * total4 * point4 / type).toLocaleString('en'));
        $('#number_select_xien4').html(Ank4);
        $('#total').html((Number($('#totalxn').html().replace(/[^0-9\.]+/g, "")) +Number($('#total2').html().replace(/[^0-9\.]+/g, "")) + Number($('#total3').html().replace(/[^0-9\.]+/g, "")) + Number($('#total4').html().replace(/[^0-9\.]+/g, ""))).toLocaleString('en'));
        $('#point').html(Number($('#pointxn').html().replace(/[^0-9\.]+/g, "")) + Number($('#point2').html().replace(/[^0-9\.]+/g, "")) + Number($('#point3').html().replace(/[^0-9\.]+/g, "")) + Number($('#point4').html().replace(/[^0-9\.]+/g, "")));

    }
}

function ChangeTotalPoint(input) {
    // console.log($(input).val().replaceAll(',','').replaceAll('.',''))
    // console.log($('#max_point_one').html().replaceAll(',','').replaceAll('.',''))
    if (Number($(input).val().replaceAll(',','').replaceAll('.','')) > Number($('#max_point_one').html().replaceAll(',','').replaceAll('.','')))
        $(input).val($('#max_point_one').html())
    $(input).val(Number($(input).val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
    if ($('#current_game').val() == "29" || $('#current_game').val() == "329" || $('#current_game').val() == "429" || $('#current_game').val() == "529" || $('#current_game').val() == "629") {
        ChangeTotalPointLoXien(input, 15 - 1);
        return;
    }

    if ($('#current_game').val() == "9" || $('#current_game').val() == "309"
    || $('#current_game').val() == "409" || $('#current_game').val() == "509" || $('#current_game').val() == "609" || $('#current_game').val() == "709") {
        ChangeTotalPointLoXien(input, 15 - 1);
        return;
    }
    if ($('#current_game').val() == "10" || $('#current_game').val() == "310" ||$('#current_game').val() == "410" || $('#current_game').val() == "510" || $('#current_game').val() == "610" || $('#current_game').val() == "710") {
        ChangeTotalPointLoXien(input, 11 - 1);
        return;
    }
    if ($('#current_game').val() == "11" || $('#current_game').val() == "311" || $('#current_game').val() == "411" || $('#current_game').val() == "511" || $('#current_game').val() == "611" || $('#current_game').val() == "711") {
        ChangeTotalPointLoXien(input, 10 - 1);
        return;
    }
    if ($('#current_game').val() == "19") {
        ChangeTotalPointLoTruot(input, 4);
        return;
    }

    if ($('#current_game').val() == "20") {
        ChangeTotalPointLoTruot(input, 8);
        return;
    }

    if ($('#current_game').val() == "21") {
        ChangeTotalPointLoTruot(input, 10);
        return;
    }

    //XSAO

    if ($('#current_game').val() == "109") {
        ChangeTotalPointLoXien(input, 15 - 1);
        return;
    }
    if ($('#current_game').val() == "110") {
        ChangeTotalPointLoXien(input, 11 - 1);
        return;
    }
    if ($('#current_game').val() == "111") {
        ChangeTotalPointLoXien(input, 10 - 1);
        return;
    }
    if ($('#current_game').val() == "119") {
        ChangeTotalPointLoTruot(input, 4);
        return;
    }

    if ($('#current_game').val() == "120") {
        ChangeTotalPointLoTruot(input, 8);
        return;
    }

    if ($('#current_game').val() == "121") {
        ChangeTotalPointLoTruot(input, 10);
        return;
    }

    $('.input_game').val($(input).val().replaceAll(',','').replaceAll('.',''));
    var point = Number($(input).val().replaceAll(',','').replaceAll('.',''));
    for (var i = 0; i < choices.length; i++) {
        choices[i].value = point;
        choices[i].total = point * choices[i].exchange;
    }
    var point = 0;
    var total = 0;
    for (var i = 0; i < choices.length; i++) {
        point += Number(choices[i].value);
        total += Number(choices[i].total);
    }
    $('#point').html(point);
    $('#total').html(total.toLocaleString('en'));
    // $('#input_point').val(($(input).val().toLocaleString('en')));
}

function SelectY(i) {
    var game_code = $('#current_game').val();
    if ($('#Y_' + game_code + '_' + i).is(":checked")) {
        if (i == -1) {
            for (var j = 0; j < 10; j++) {
                var value = j + "" + j;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_content')) {
                    Select_Number(numb, game_code, value);
                }
            }
        } else {
            for (var j = 0; j < 10; j++) {
                var value = j + "" + i;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_content')) {
                    Select_Number(numb, game_code, value);
                }
            }
        }
    } else {
        if (i == -1) {
            for (var j = 0; j < 10; j++) {
                var value = j + "" + j;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_block')) {
                    Select_Number(numb, game_code, value);
                }
            }
        } else {
            for (var j = 0; j < 10; j++) {
                var value = j + "" + i;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_block')) {
                    Select_Number(numb, game_code, value);
                }
            }
        }
    }


}

function SelectX(i) {
    var game_code = $('#current_game').val();
    if ($('#X_' + game_code + '_' + i).is(":checked")) {
        for (var j = 0; j < 10; j++) {
            var value = i + "" + j;
            var numb = $('#select_' + game_code + '_' + value);
            if ($(numb).hasClass('number_content')) {
                Select_Number(numb, game_code, value);
            }
        }
    } else {
        for (var j = 0; j < 10; j++) {
            var value = i + "" + j;
            var numb = $('#select_' + game_code + '_' + value);
            if ($(numb).hasClass('number_block')) {
                Select_Number(numb, game_code, value);
            }
        }

    }
}

//Select keno
function Select_Keno(numb, game_code, value) {
    if ($(numb).hasClass('number_content')) {
        $('.number_block').addClass('number_content');
        $('.number_content').removeClass('number_block');
        $('.input_game').hide();
        choices = [];
        
        $('#game_name').html($('#'+'game_name_'+game_code+'_00').html());
        $('#odds').html( number_format($('#'+'odds_'+game_code+'_00').html()));
        $('#current_game').val(game_code);
        

        $('#exchange_' + game_code + "_" + value).hide();
        $('#input_' + game_code + "_" + value).show();
        $('#' + game_code + "_" + value).addClass('number_block');
        $('#' + game_code + "_" + value).removeClass('number_content');
        $(numb).addClass('number_block');
        $(numb).removeClass('number_content');
        var point = Number($('#input_' + game_code + "_" + value).val());
        var exchange = Number($('#exchange_' + game_code + "_" + value).html().replace(/[^0-9\.]+/g, ""))
        choices.push({
            name: value,
            value: point,
            exchange:exchange,
            total: point * exchange,
            game_code:game_code
        });
        var t = "";
        point = 0;
        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            point += Number(choices[i].value);
            total += Number(choices[i].total);
        }

        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');
        $('#point').html(point);
        $('#total').html(total.toLocaleString('en'));
    } else {
        for (var i = 0; i < choices.length; i++) {
            if (choices[i].name === value) {
                choices.splice(i, 1);
                break;
            }
        }
        var t = "";
        var point = 0;
        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            point += Number(choices[i].value);
            total += Number(choices[i].total);
        }
        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');
        $('#point').html(point);
        $('#total').html(total.toLocaleString('en'));
        $('#exchange_' + game_code + "_" + value).show();
        $('#input_' + game_code + "_" + value).hide();
        $('#' + game_code + "_" + value).addClass('number_content');
        $('#' + game_code + "_" + value).removeClass('number_block');
        $(numb).addClass('number_content');
        $(numb).removeClass('number_block');
    }

}

//region Danh lo De
function Select_Number_PlayLoDe(numb, game_code, value) {
    if ($(numb).hasClass('number_content')) {
        $('#exchange_' + game_code + "_" + value).hide();
        $('#input_' + game_code + "_" + value).show();
        $('#' + game_code + "_" + value).addClass('number_block');
        $('#' + game_code + "_" + value).removeClass('number_content');
        $(numb).addClass('number_block');
        $(numb).removeClass('number_content');
        var point = Number($('#input_' + game_code + "_" + value).val());
        var exchange = Number($('#exchange_' + game_code + "_" + value).html().replace(/[^0-9\.]+/g, ""))
        choices.push({
            name: value,
            value: point,
            exchange: exchange,
            total: point * exchange
        });
        var t = "";
        point = 0;
        var total = 0;
        choices = choices.sort( compare );
        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            point += Number(choices[i].value);
            total += Number(choices[i].total);
        }

        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');
        $('#point').html(point);
        $('#total').html(total.toLocaleString('en'));
    } else {
        for (var i = 0; i < choices.length; i++) {
            if (choices[i].name === value) {
                choices.splice(i, 1);
                break;
            }
        }
        var t = "";
        var point = 0;
        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            point += Number(choices[i].value);
            total += Number(choices[i].total);
        }
        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');
        $('#point').html(point);
        $('#total').html(total.toLocaleString('en'));
        $('#exchange_' + game_code + "_" + value).show();
        $('#input_' + game_code + "_" + value).hide();
        $('#' + game_code + "_" + value).addClass('number_content');
        $('#' + game_code + "_" + value).removeClass('number_block');
        $(numb).addClass('number_content');
        $(numb).removeClass('number_block');
    }

}

function fact(x) {
    if (x <= 0) {
        return 1;
    }
    return x * fact(x - 1);
}

//region Danh lo xien
function Select_Number_PlayLoXienCode2(numb, game_code, value, xien) {
    // Select_Number_PlayLoXien(numb, 9, value, 14);
    // Select_Number_PlayLoXien(numb, 10, value, 11);
    // Select_Number_PlayLoXien(numb, 11, value, 9);
    var Ank = 1;
    if ($(numb).hasClass('number_content')) {
        if (choices.length < 14) {
            $('#' + game_code + "_" + value).addClass('number_block');
            $('#' + game_code + "_" + value).removeClass('number_content');
            $(numb).addClass('number_block');
            $(numb).removeClass('number_content');
            if (game_code == 2) {
                var exchange2 = Number($('#exchange_' + 9 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange3 = Number($('#exchange_' + 10 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange4 = Number($('#exchange_' + 11 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchangexn = Number($('#exchange_' + 29 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            }
            if (game_code == 302) {
                var exchange2 = Number($('#exchange_' + 309 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange3 = Number($('#exchange_' + 310 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange4 = Number($('#exchange_' + 311 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            }
            if (game_code == 402) {
                var exchange2 = Number($('#exchange_' + 409 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange3 = Number($('#exchange_' + 410 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange4 = Number($('#exchange_' + 411 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            }
            if (game_code == 502) {
                var exchange2 = Number($('#exchange_' + 509 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange3 = Number($('#exchange_' + 510 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange4 = Number($('#exchange_' + 511 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            }
            if (game_code == 602) {
                var exchange2 = Number($('#exchange_' + 609 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange3 = Number($('#exchange_' + 610 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange4 = Number($('#exchange_' + 611 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            }
            if (game_code == 702) {
                var exchange2 = Number($('#exchange_' + 709 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange3 = Number($('#exchange_' + 710 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
                var exchange4 = Number($('#exchange_' + 711 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            }
            if (game_code == 102) {
            var exchange2 = Number($('#exchange_' + 9 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            var exchange3 = Number($('#exchange_' + 10 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            var exchange4 = Number($('#exchange_' + 11 + "_" + value).html().replace(/[^0-9\.]+/g, ""))
            }
            choices.push({
                name: value,
                value: 1,
                exchange2: exchange2,
                exchange3: exchange3,
                exchange4: exchange4,
                exchangexn: exchangexn,
                total2: 1 * exchange2,
                total3: 1 * exchange3,
                total4: 1 * exchange4,
                totalxn: 1 * exchangexn
            });


            var t = "";
            var point2 = Number($('#input_point2').val().replace(/[^0-9\.]+/g, ""));
            var point3 = Number($('#input_point3').val().replace(/[^0-9\.]+/g, ""));
            var point4 = Number($('#input_point4').val().replace(/[^0-9\.]+/g, ""));
            var pointxn = Number($('#input_pointxn').val().replace(/[^0-9\.]+/g, ""));
            var total2 = 0;
            var total3 = 0;
            var total4 = 0;
            var totalxn = 0;
            choices = choices.sort( compare );
            for (var i = 0; i < choices.length; i++) {
                t += choices[i].name + ","
                total2 += choices[i].total2;
                total3 += choices[i].total3;
                total4 += choices[i].total4;
                totalxn += choices[i].totalxn;
            }
            var Ank2 = 1;
            var Ank3 = 1;
            var Ank4 = 1;
            // if (game_code == "9") 
            // {
            Ank2 = fact(choices.length) / fact(2) / fact(choices.length - 2);
            xien2 = 2;
            xienxn = 2;
            total2 = total2 * 2 / choices.length
            totalxn = totalxn * 2 / choices.length
                // }
                // if (game_code == "10") {
            Ank3 = fact(choices.length) / fact(3) / fact(choices.length - 3);
            xien3 = 3;
            total3 = total3 * 3 / choices.length
                // }
                // if (game_code == "11" || game_code == "19") {
            Ank4 = fact(choices.length) / fact(4) / fact(choices.length - 4);
            xien4 = 4;
            total4 = total4 * 4 / choices.length
                // }


            t = t.slice(0, -1);
            $('#number_select').html(t);
            $('#number_select_text').val(t + ',');

            if (choices.length >= 2) {
                // $('#point').html(point);
                $('#total2').html(Math.ceil(Ank2 * total2 * point2 / xien2).toLocaleString('en').toLocaleString('en'));
                $('#number_select_xien2').html(Ank2);
                $('#point2').html(point2 * Ank2);

                // $('#point').html(point);
                $('#totalxn').html(Math.ceil(Ank2 * totalxn * pointxn / xienxn).toLocaleString('en').toLocaleString('en'));
                $('#number_select_xiennhay').html(Ank2);
                $('#pointxn').html(pointxn * Ank2);
            }
            if (choices.length >= 3) {
                $('#total3').html(Math.ceil(Ank3 * total3 * point3 / xien3).toLocaleString('en').toLocaleString('en'));
                $('#number_select_xien3').html(Ank3);
                $('#point3').html(point3 * Ank3);
            }
            if (choices.length >= 4) {
                $('#total4').html(Math.ceil(Ank4 * total4 * point4 / xien4).toLocaleString('en').toLocaleString('en'));
                $('#number_select_xien4').html(Ank4);
                $('#point4').html(point4 * Ank4);
            }
        }
    } else {
        for (var i = 0; i < choices.length; i++) {
            if (choices[i].name === value) {
                choices.splice(i, 1);
                break;
            }
        }
        var t = "";
        var point2 = Number($('#input_point2').val().replace(/[^0-9\.]+/g, ""));
        var point3 = Number($('#input_point3').val().replace(/[^0-9\.]+/g, ""));
        var point4 = Number($('#input_point4').val().replace(/[^0-9\.]+/g, ""));
        var pointxn = Number($('#input_pointxn').val().replace(/[^0-9\.]+/g, ""));
        var total2 = 0;
        var total3 = 0;
        var total4 = 0;
        var totalxn = 0;

        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            total2 += choices[i].total2;
            total3 += choices[i].total3;
            total4 += choices[i].total4;
            totalxn += choices[i].totalxn;
        }
        var Ank2 = 1;
        var Ank3 = 1;
        var Ank4 = 1;
        // if (game_code == "9") 
        // {
        Ank2 = fact(choices.length) / fact(2) / fact(choices.length - 2);
        xien2 = 2;
        total2 = total2 * 2 / choices.length

        xienxn = 2;
        totalxn = totalxn * 2 / choices.length
            // }
            // if (game_code == "10") {
        Ank3 = fact(choices.length) / fact(3) / fact(choices.length - 3);
        xien3 = 3;
        total3 = total3 * 3 / choices.length
            // }
            // if (game_code == "11" || game_code == "19") {
        Ank4 = fact(choices.length) / fact(4) / fact(choices.length - 4);
        xien4 = 4;
        total4 = total4 * 4 / choices.length
            // }


        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');

        if (choices.length >= 2) {
            $('#point').html(point);
            $('#total2').html(Math.ceil(Ank2 * total2 * point2 / xien2).toLocaleString('en').toLocaleString('en'));
            $('#number_select_xien2').html(Ank2);
            $('#point2').html(point2 * Ank2);

            $('#totalxn').html(Math.ceil(Ank2 * totalxn * pointxn / xienxn).toLocaleString('en').toLocaleString('en'));
            $('#number_select_xiennhay').html(Ank2);
            $('#pointxn').html(pointxn * Ank2);
        } else {
            $('#total2').html(0);
            $('#number_select_xien2').html(0);
            $('#point2').html(0);

            $('#totalxn').html(0);
            $('#number_select_xiennhay').html(0);
            $('#pointxn').html(0);
        }
        if (choices.length >= 3) {
            $('#total3').html(Math.ceil(Ank3 * total3 * point3 / xien3).toLocaleString('en').toLocaleString('en'));
            $('#number_select_xien3').html(Ank3);
            $('#point3').html(point3 * Ank3);
        } else {
            $('#total3').html(0);
            $('#number_select_xien3').html(0);
            $('#point3').html(0);
        }
        if (choices.length >= 4) {
            $('#total4').html(Math.ceil(Ank4 * total4 * point4 / xien4).toLocaleString('en').toLocaleString('en'));
            $('#number_select_xien4').html(Ank4);
            $('#point4').html(point4 * Ank4);
        } else {
            $('#total4').html(0);
            $('#number_select_xien4').html(0);
            $('#point4').html(0);
        }
        $('#' + game_code + "_" + value).addClass('number_content');
        $('#' + game_code + "_" + value).removeClass('number_block');
        $(numb).addClass('number_content');
        $(numb).removeClass('number_block');
    }
    $('#total').html((Number($('#totalxn').html().replace(/[^0-9\.]+/g, "")) +Number($('#total2').html().replace(/[^0-9\.]+/g, "")) + Number($('#total3').html().replace(/[^0-9\.]+/g, "")) + Number($('#total4').html().replace(/[^0-9\.]+/g, ""))).toLocaleString('en'));
    $('#point').html(Number($('#pointxn').html().replace(/[^0-9\.]+/g, "")) + Number($('#point2').html().replace(/[^0-9\.]+/g, "")) + Number($('#point3').html().replace(/[^0-9\.]+/g, "")) + Number($('#point4').html().replace(/[^0-9\.]+/g, "")));
}

//endregion
//region Danh lo xien
function Select_Number_PlayLoXien(numb, game_code, value, xien) {
    var Ank = 1;
    if ($(numb).hasClass('number_content')) {
        if (choices.length < xien) {
            $('#' + game_code + "_" + value).addClass('number_block');
            $('#' + game_code + "_" + value).removeClass('number_content');
            $(numb).addClass('number_block');
            $(numb).removeClass('number_content');
            var exchange = Number($('#exchange_' + game_code + "_" + value).html().replace(/[^0-9\.]+/g, ""))

            choices.push({
                name: value,
                value: 1,
                exchange: exchange,
                total: 1 * exchange
            });
            var t = "";
            var point = Number($('#input_point').val().replace(/[^0-9\.]+/g, ""));
            var total = 0;
            choices = choices.sort( compare );
            for (var i = 0; i < choices.length; i++) {
                t += choices[i].name + ","
                total += choices[i].total;
            }
            var Ank = 1;
            if (game_code == "29" || game_code == "329" || game_code == "429" || game_code == "529" || game_code == "629") {
                Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
                xien = 2;
                total = total * 2 / choices.length
            }
            if (game_code == "9" || game_code == "309" || game_code == "409" || game_code == "509" || game_code == "609" || game_code == "709") {
                Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
                xien = 2;
                total = total * 2 / choices.length
            }
            if (game_code == "10" || game_code == "310" || game_code == "410" || game_code == "510" || game_code == "610" || game_code == "710") {
                Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
                xien = 3;
                total = total * 3 / choices.length
            }
            if (game_code == "11" || game_code == "19" || game_code == "311" || game_code == "411"|| game_code == "511" || game_code == "611" || game_code == "711") {
                Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
                xien = 4;
                total = total * 4 / choices.length
            }

            if (game_code == "20") {
                Ank = fact(choices.length) / fact(8) / fact(choices.length - 8);
                xien = 8;
                total = total * 8 / choices.length
            }

            if (game_code == "21") {
                Ank = fact(choices.length) / fact(10) / fact(choices.length - 10);
                xien = 10;
                total = total * 10 / choices.length
            }

            //XSAO

            if (game_code == "109") {
                Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
                xien = 2;
                total = total * 2 / choices.length
            }
            if (game_code == "110") {
                Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
                xien = 3;
                total = total * 3 / choices.length
            }
            if (game_code == "111" || game_code == "19") {
                Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
                xien = 4;
                total = total * 4 / choices.length
            }

            if (game_code == "120") {
                Ank = fact(choices.length) / fact(8) / fact(choices.length - 8);
                xien = 8;
                total = total * 8 / choices.length
            }

            if (game_code == "121") {
                Ank = fact(choices.length) / fact(10) / fact(choices.length - 10);
                xien = 10;
                total = total * 10 / choices.length
            }

            t = t.slice(0, -1);
            $('#number_select').html(t);
            $('#number_select_text').val(t + ',');

            if (choices.length >= xien) {
                $('#point').html(point);
                // if(point==0)point=1;
                if (game_code == "29" || game_code == "329" || game_code == "429" || game_code == "529" || game_code == "629") {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien2').html(Ank);
                    $('#number_select_xien').html(Ank);
                } else
                if (game_code == "9" || game_code == "309" || game_code == "409"|| game_code == "509"|| game_code == "609" || game_code == "709") {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien2').html(Ank);
                    $('#number_select_xien').html(Ank);
                } else
                if (game_code == "10" || game_code == "310"|| game_code == "410"|| game_code == "510"|| game_code == "610" || game_code == "710") {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien3').html(Ank);
                    $('#number_select_xien').html(Ank);
                } else
                if (game_code == "11" || game_code == "311"|| game_code == "411"|| game_code == "511"|| game_code == "611" || game_code == "711") {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien4').html(Ank);
                    $('#number_select_xien').html(Ank);
                } else
                //XSAO
                if (game_code == "109") {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien2').html(Ank);
                    $('#number_select_xien').html(Ank);
                } else
                if (game_code == "110") {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien3').html(Ank);
                    $('#number_select_xien').html(Ank);
                } else
                if (game_code == "111") {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien4').html(Ank);
                    $('#number_select_xien').html(Ank);
                } else
                {
                    $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en').toLocaleString('en'));
                    $('#number_select_xien').html(Ank);
                }
            } else {
                $('#point').html(0);
                $('#total').html(0);
                $('#number_select_xien').html(0);
            }
        }
    } else {
        for (var i = 0; i < choices.length; i++) {
            if (choices[i].name === value) {
                choices.splice(i, 1);
                break;
            }
        }
        var t = "";
        var point = Number($('#input_point').val().replace(/[^0-9\.]+/g, ""));
        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            t += choices[i].name + ","
            total += Number(choices[i].total);
        }
        var Ank = 1;
        if (game_code == "29" || game_code == "329" || game_code == "429" || game_code == "529" || game_code == "629") {
            Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
            xien = 2;
            total = total * 2 / choices.length
        }
        if (game_code == "9" || game_code == "309"|| game_code == "409"|| game_code == "509"|| game_code == "609"|| game_code == "709") {
            Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
            xien = 2;
            total = total * 2 / choices.length
        }
        if (game_code == "10" || game_code == "310"|| game_code == "410"|| game_code == "510"|| game_code == "610"|| game_code == "710") {
            Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
            xien = 3;
            total = total * 3 / choices.length
        }
        if (game_code == "11" || game_code == "19" || game_code == "311"|| game_code == "411"|| game_code == "511"|| game_code == "611"|| game_code == "711") {
            Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
            xien = 4;
            total = total * 4 / choices.length
        }

        //XSAO

        if (game_code == "109") {
            Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
            xien = 2;
            total = total * 2 / choices.length
        }
        if (game_code == "110") {
            Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
            xien = 3;
            total = total * 3 / choices.length
        }
        if (game_code == "111" || game_code == "119") {
            Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
            xien = 4;
            total = total * 4 / choices.length
        }
        t = t.slice(0, -1);
        $('#number_select').html(t);
        $('#number_select_text').val(t + ',');
        if (choices.length >= xien) {
            $('#point').html(point);
            if (point == 0) point = 1;
            $('#total').html(Math.ceil(Ank * total * point / xien).toLocaleString('en'));
            $('#number_select_xien').html(Ank);
        } else {
            $('#point').html(0);
            $('#total').html(0);
            $('#number_select_xien').html(0);

        }
        $('#' + game_code + "_" + value).addClass('number_content');
        $('#' + game_code + "_" + value).removeClass('number_block');
        $(numb).addClass('number_content');
        $(numb).removeClass('number_block');
    }
}

function ChangeTotalPointLoTruot(input, type) {
    if (type == 4) maxlength = 10;
    if (type == 8) maxlength = 8;
    if (type == 10) maxlength = 10;

    if (choices.length <= maxlength) {
        var point = Number($(input).val().replaceAll(',','').replaceAll('.',''));
        for (var i = 0; i < choices.length; i++) {
            choices[i].value = 1;
            choices[i].total = choices[i].exchange;
        }

        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            total += choices[i].total
        }
        var Ank = 1;
        // if (type==15)
        // {
        //     Ank = fact(choices.length)/fact(2)/fact(choices.length-2);
        //     type = 2;
        //     total = total*2/choices.length;
        // }
        if (type == 4) {
            Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
            type = 4;
            total = total * 4 / choices.length;
        }
        if (type == 8) {
            Ank = fact(choices.length) / fact(8) / fact(choices.length - 8);
            type = 8;
            total = total * 8 / choices.length;
        }
        if (type == 10) {
            Ank = fact(choices.length) / fact(10) / fact(choices.length - 10);
            type = 10;
            total = total * 10 / choices.length;
        }
        $('#point').html(point);
        //if(point==0)point=1;
        $('#total').html(Math.ceil(Ank * total * point / type).toLocaleString('en'));
        $('#number_select_xien').html(Ank);
    }
}

function ChangeTotalPointLoXien(input, type) {
    if (choices.length <= type) {
        var point = Number($(input).val().replaceAll(',','').replaceAll('.',''));
        for (var i = 0; i < choices.length; i++) {
            choices[i].value = 1;
            choices[i].total = choices[i].exchange;
        }

        var total = 0;
        for (var i = 0; i < choices.length; i++) {
            total += choices[i].total
        }
        var Ank = 1;
        if (type == 15 - 1) {
            Ank = fact(choices.length) / fact(2) / fact(choices.length - 2);
            type = 2;
            total = total * 2 / choices.length;
        }
        if (type == 11 - 1) {
            Ank = fact(choices.length) / fact(3) / fact(choices.length - 3);
            type = 3;
            total = total * 3 / choices.length;
        }
        if (type == 10 - 1) {
            Ank = fact(choices.length) / fact(4) / fact(choices.length - 4);
            type = 4;
            total = total * 4 / choices.length;
        }

        $('#point').html(point);
        //if(point==0)point=1;
        $('#total').html(Math.ceil(Ank * total * point / type).toLocaleString('en'));
        $('#number_select_xien').html(Ank);
    }
}

function DatCuocLoXien2(xien) {
    if (choices.length > 0) {
        if ($('#input_point2').val() === "" && $('#input_point3').val() === "" && $('#input_point4').val() === "" && $('#input_pointxn').val() === "") {
            alert("Bạn chưa chọn số");
            return;
        }
        var total_money = Number($('#total_money').html().replace(/[^0-9\.]+/g, ""));
        var max_point_res = $('#max_point').html().split("/");
        var max_point2 = Number(String(max_point_res[0]).replace(/[^0-9\.]+/g, ""));
        var max_point3 = Number(String(max_point_res[1]).replace(/[^0-9\.]+/g, ""));
        var max_point4 = Number(String(max_point_res[2]).replace(/[^0-9\.]+/g, ""));
        var max_pointxn= Number(String(max_point_res[3]).replace(/[^0-9\.]+/g, ""));
        var max_point_one_res = $('#max_point_one').html().split("/");
        var max_point_one2 = Number(String(max_point_one_res[0]).replace(/[^0-9\.]+/g, ""));
        var max_point_one3 = Number(String(max_point_one_res[1]).replace(/[^0-9\.]+/g, ""));
        var max_point_one4 = Number(String(max_point_one_res[2]).replace(/[^0-9\.]+/g, ""));
        var max_point_onexn = Number(String(max_point_one_res[3]).replace(/[^0-9\.]+/g, ""));
        var point = Number($('#input_point').val().replace(/[^0-9\.]+/g, ""));
        var point2 = Number($('#input_point2').val().replace(/[^0-9\.]+/g, ""));
        var point3 = Number($('#input_point3').val().replace(/[^0-9\.]+/g, ""));
        var point4 = Number($('#input_point4').val().replace(/[^0-9\.]+/g, ""));
        var pointxn = Number($('#input_pointxn').val().replace(/[^0-9\.]+/g, ""));

        var total = Number($('#total').html().replace(/[^0-9\.]+/g, ""));
        var total2 = Number($('#total2').html().replace(/[^0-9\.]+/g, ""));
        var total3 = Number($('#total3').html().replace(/[^0-9\.]+/g, ""));
        var total4 = Number($('#total4').html().replace(/[^0-9\.]+/g, ""));
        var totalxn = Number($('#totalxn').html().replace(/[^0-9\.]+/g, ""));

        var soxien2 = parseInt($('#number_select_xien2').html());
        var soxien3 = parseInt($('#number_select_xien3').html());
        var soxien4 = parseInt($('#number_select_xien4').html());
        var soxienxn = parseInt($('#number_select_xiennhay').html());

        if (choices.length < xien) {
            alert("Bạn phải chọn " + xien + " số ");
            return;
        }

        if (total > total_money) {
            alert("Số tiền bạn đặt cược lớn hơn số dư tài khoản");
            return;
        } else {

            var choicessave2 = [];
            var choicessave3 = [];
            var choicessave4 = [];
            var choicessavexn = [];

            var soxien2 = parseInt($('#number_select_xien2').html());
            var soxien3 = parseInt($('#number_select_xien3').html());
            var soxien4 = parseInt($('#number_select_xien4').html());
            var soxienxn = parseInt($('#number_select_xiennhay').html());

            if (point2 > max_point_one2) {
                alert("Xiên 2 :Số điểm bạn đặt cược lớn hơn giới hạn cho phép "+ max_point_one2);
                // return;
            }else choicessave2.push({
                name: $('#number_select').html(),
                value: point2,
                exchange: total2 / point2 / soxien2,
                total: total2,
                game_code: $('#current_game').val(),
                //odds: $('#odds').html().replace(/[^0-9\.]+/g, ""),
            });

            if (pointxn > max_point_onexn) {
                alert("Xiên 2 :Số điểm bạn đặt cược lớn hơn giới hạn cho phép "+ max_point_onexn);
                // return;
            }else choicessavexn.push({
                name: $('#number_select').html(),
                value: pointxn,
                exchange: totalxn / pointxn / soxienxn,
                total: totalxn,
                game_code: $('#current_game').val(),
                //odds: $('#odds').html().replace(/[^0-9\.]+/g, ""),
            });
            // if (point2 > max_point2) {
            //     alert("Số điểm bạn đặt cược lớn hơn giới hạn cho phép");
            //     // return;
            // }
    
            if (point3 > max_point_one3) {
                alert("Xiên 3 :Số điểm bạn đặt cược lớn hơn giới hạn cho phép "+max_point_one3);
                // return;
            }else choicessave3.push({
                name: $('#number_select').html(),
                value2: point2,
                value: point3,
                value4: point4,
                exchange2: total2 / point2 / soxien2,
                exchange: total3 / point3 / soxien3,
                exchange4: total4 / point4 / soxien4,

                total2: total2,
                total: total3,
                total4: total4,
            });
            // if (point3 > max_point3) {
            //     alert("Số điểm bạn đặt cược lớn hơn giới hạn cho phép");
            //     // return;
            // }
    
            if (point4 > max_point_one4) {
                alert("Xiên 4 :Số điểm bạn đặt cược lớn hơn giới hạn cho phép "+ max_point_one4);
                // return;
            }else choicessave4.push({
                name: $('#number_select').html(),
                value2: point2,
                value3: point3,
                value: point4,
                exchange2: total2 / point2 / soxien2,
                exchange3: total3 / point3 / soxien3,
                exchange: total4 / point4 / soxien4,

                total2: total2,
                total3: total3,
                total: total4,
            });
            // if (point3 > max_point3) {
            //     alert("Số điểm bạn đặt cược lớn hơn giới hạn cho phép");
            //     // return;
            // }
            if (choicessave2.length > 0 || choicessave3.length > 0 || choicessave4.length > 0){
                let timerInterval
                Swal.fire({
                title: 'Đang vào cược',
                html: 'Vui lòng chờ trong giây lát.',
                timer: 20000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
                })

                $_token = $('#token').val();
                $.ajax({
                    url: $('#url').val() + "/storexien2",
                    method: 'POST',
                    dataType: 'html',
                    data: {
                        choices: choicessave2,
                        choices2: choicessave2,
                        choicesxn: choicessavexn,
                        choices3: choicessave3,
                        choices4: choicessave4,
                        game_code: $('#current_game').val(),
                        odds: $('#odds2').html().replace(/[^0-9\.]+/g, ""),
                        odds2: $('#odds2').html().replace(/[^0-9\.]+/g, ""),
                        odds3: $('#odds3').html().replace(/[^0-9\.]+/g, ""),
                        odds4: $('#odds4').html().replace(/[^0-9\.]+/g, ""),
                        oddsxn: $('#oddsxn').html().replace(/[^0-9\.]+/g, ""),
                        _token: $_token,
                        ipaddr: $('#ipaddress').val(),
                    },
                    success: function(data) {
                        Swal.close()
                        if (data == "error001") {
                            alert("Lỗi từ hệ thống. Hãy load lại trang để đặt cược lại.");
                            location.reload();
                        } else
                        if (data == "exchange") {
                            alert("Giá mua có sự thay đổi. Hãy load lại trang để cập nhật giá mới nhất");
                            location.reload();
                        } else
                        if (data.indexOf("maxbet:") >= 0) {
                            $.Notification.notify('error', 'right top', 'Thông báo', "Mã cược " + data.replace("maxbet: ", "") + " Vượt quá giới hạn chơi cho phép " + $('#max_point').html());
                        } else
                        if (data.indexOf("maxbetTong:") >= 0) {
                            alert("Mã cược " + data.replace("maxbetTong: ", "") + " Vượt quá giới hạn chơi cho phép. Hãy liên hệ với quản lý. ");
                            //  + $('#max_point').html());
                        } else
                        if (data == "overloadmoney") {
                            alert("Vượt quá giới hạn tiền hoặc có lỗi xảy ra. Hãy load lại trang");
                            location.reload();
                        } else
                        if (data == "overtimeovertimeovertime") {
                            alert("Hết thời gian đặt cược.");
                            location.reload();
                        } else
                        if (data == "Hết giờ vào cược") {
                            alert("Hết thời gian đặt cược.");
                            location.reload();
                        }else 
                        if (data == "error021") {
                            alert(" Vượt quá giới hạn chơi cho phép.");
                        }
                        else if (data.indexOf("Lô xiên 2: thành công. Lô xiên 3: thành công. Lô xiên 4: thành công. ") >= 0)   {
                            // $('#btn_CreateOK').click();
                            // swal({ title: "", text: "Bạn đã đặt cược thành công.", timer: 2000, showConfirmButton: false, closeOnConfirm: false });
                            userConfirmBetSuccess()
                            refreshUser_Info();
                            g_refresh_bets_top5();
                            Huy();
                        }else{
                            // alert(data);
                            Swal.fire({
                                title: "Thông báo đặt cược",
                                // html: text + '</br>' + text2 + '</br>' + text3,
                                html: data,
                                type: "info",
                                timer: 30000,
                                showCancelButton: false,
                                // confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Tiếp tục",
                                // cancelButtonText: "Hủy",
                                closeOnConfirm: true,
                                reverseButtons:true,
                                // input: 'checkbox',
                                // inputValue: 0,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                // inputPlaceholder:
                                //   'Không hỏi lại cho lần cược sau?',
                                // inputValidator: (result) => {
                                //     console.log(result)
                                //     localStorage.setItem('userConfirmBetSuccess', result)
                                // //   return !result && 'You need to agree with T&C'
                                // },
                            });
                            refreshUser_Info();
                            g_refresh_bets_top5();
                            Huy();
                        }
                        $('#btn_OK').html('Đặt cược');
                    },
                    error: function(data) {
                        // location.reload();
                        $('#btn_OK').html('Đặt cược');
                    }
                });
            }
        }

    } else {
        alert("Bạn chưa chọn số");
    }
}

function DatCuocLoXien(xien) {
    if (choices.length > 0) {
        if ($('#input_point').val() === "") {
            alert("Bạn chưa chọn số");
            return;
        }
        var total_money = parseInt($('#total_money').html().replace(/[^0-9\.]+/g, ""));
        var max_point = parseInt($('#max_point').html().replace(/[^0-9\.]+/g, ""));
        var max_point_one = parseInt($('#max_point_one').html().replace(/[^0-9\.]+/g, ""));
        var point = parseInt($('#input_point').val().replace(/[^0-9\.]+/g, ""));
        var tt1=$('#total').html();
        var tt2=$('#total').html().replace(/[^0-9\.]+/g, "");
        var total = parseInt($('#total').html().replace(/[^0-9\.]+/g, ""));
        if (choices.length < xien) {
            alert("Bạn phải chọn " + xien + " số ");
            return;
        }

        if (point > max_point_one) {
            alert("Số điểm bạn đặt cược lớn hơn giới hạn cho phép "+max_point_one);
            return;
        }
        // if (point > max_point) {
        //     alert("Số điểm bạn đặt cược lớn hơn giới hạn cho phép");
        //     return;
        // }

        if (total > total_money) {
            alert("Số tiền bạn đặt cược lớn hơn số dư tài khoản");
            return;
        } else {

            var choicessave = [];
            var soxien = parseInt($('#number_select_xien').html());
            choicessave.push({
                name: $('#number_select').html(),
                value: point,
                exchange: total / point / soxien,
                total: total
            });
            
            let timerInterval
            Swal.fire({
            title: 'Đang vào cược',
            html: 'Vui lòng chờ trong giây lát.',
            timer: 20000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
                }, 100)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
            }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
            }
            })
            
            $_token = $('#token').val();
            $.ajax({
                url: $('#url').val() + "/store",
                method: 'POST',
                dataType: 'html',
                data: {
                    choices: choicessave,
                    game_code: $('#current_game').val(),
                    odds: $('#odds').html().replace(/[^0-9\.]+/g, ""),
                    _token: $_token,
                    ipaddr: $('#ipaddress').val(),
                },
                success: function(data) {
                    Swal.close()
                    // alert(data);
                    if (data == "exchange") {
                        alert("Giá mua có sự thay đổi. Hãy load lại trang để cập nhật giá mới nhất");
                        location.reload();
                    } else
                    if (data.indexOf("maxbet:") >= 0) {
                        alert("Mã cược " + data.replace("maxbet: ", "") + " Vượt quá giới hạn chơi cho phép ");
                        //  + $('#max_point').html());
                    }else
                    if (data.indexOf("maxbetTong:") >= 0) {
                        alert("Mã cược " + data.replace("maxbetTong: ", "") + " Vượt quá giới hạn chơi cho phép. Hãy liên hệ với quản lý. ");
                        //  + $('#max_point').html());
                    } else
                    if (data == "overloadmoney") {
                        alert("Vượt quá giới hạn tiền hoặc có lỗi xảy ra. Hãy load lại trang");
                        location.reload();
                    } else
                    if (data == "overtime") {
                        alert("Hết thời gian đặt cược.");
                        location.reload();
                    } else 
                    if (data == "error001" || data == "error") {
                        alert("Có lỗi xảy ra error001. Đặt cược không thành công!");
                        location.reload();
                    }else
                    if (data == "error021") {
                        alert(" Vượt quá giới hạn chơi cho phép.");
                        //  + $('#max_point').html());
                    } else if (data == "ok")
                    {
                        //$('#btn_CreateOK').click();
                        // swal("Thành công", "Bạn đã đặt cược thành công", "success")
                        // swal({ title: "", text: "Bạn đã đặt cược thành công.", timer: 500, showConfirmButton: false, closeOnConfirm: false });
                        userConfirmBetSuccess()
                        refreshUser_Info();
                        g_refresh_bets_top5();
                        Huy();
                    }else{
                        // alert("Đặt cược không thành công! "+data);
                        // location.reload();
                        Swal.fire({
                            title: "Đặt cược không thành công!",
                            // html: text + '</br>' + text2 + '</br>' + text3,
                            html: data,
                            type: "info",
                            timer: 30000,
                            showCancelButton: false,
                            // confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Tiếp tục",
                            // cancelButtonText: "Hủy",
                            closeOnConfirm: true,
                            reverseButtons:true,
                            // input: 'checkbox',
                            // inputValue: 0,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            // inputPlaceholder:
                            //   'Không hỏi lại cho lần cược sau?',
                            // inputValidator: (result) => {
                            //     console.log(result)
                            //     localStorage.setItem('userConfirmBetSuccess', result)
                            // //   return !result && 'You need to agree with T&C'
                            // },
                        });
                    }
                    $('#btn_OK').html('Đặt cược');
                },
                error: function(data) {
                    // location.reload();
                    $('#btn_OK').html('Đặt cược');
                }
            });
        }

    } else {
        alert("Bạn chưa chọn số");
    }
}
//endregion

//region 1000
function LoadContent1000(col) {
    Huy();
    $('#current_tab').val(col);
    $('.refresh').show();
    var game_code = $('#current_game').val();
    $('#' + game_code + 'content_' + col).fadeOut();
    $('#' + game_code + 'content_' + col).load($('#url').val() + "/load-game/" + col + "/" + game_code, function() {
        $('#' + game_code + 'content_' + col).fadeIn();
        // $('#1000_'+col).addClass('active');;
        $('.refresh').hide();
        $('.input_game').hide();
    });

}

function SelectY_1000(i) {
    var current_tab = $('#current_tab').val();
    var game_code = $('#current_game').val();
    // console.log( (parseInt(i)+parseInt(current_tab)*10))
    if ($('div#' + game_code + 'content_' + current_tab + ' input#Y_' + game_code + '_' + current_tab+i).is(":checked")) {
        if (i == -1) {
            // console.log( (parseInt(i)+parseInt(current_tab)*10))
            for (var j = 0; j < 10; j++) {
                var value = current_tab + "" + j + "" + j;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_content')) {
                    Select_Number(numb, game_code, value);
                }
            }
        } else {
            for (var j = 0; j < 10; j++) {
                var value = current_tab + "" + j + "" + i;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_content')) {
                    Select_Number(numb, game_code, value);
                }
            }
        }
    } else {
        if (i == -1) {
            for (var j = 0; j < 10; j++) {
                var value = current_tab + "" + j + "" + j;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_block')) {
                    Select_Number(numb, game_code, value);
                }
            }
        } else {
            for (var j = 0; j < 10; j++) {
                var value = current_tab + "" + j + "" + i;
                var numb = $('#select_' + game_code + '_' + value);
                if ($(numb).hasClass('number_block')) {
                    Select_Number(numb, game_code, value);
                }
            }
        }
    }


}

function SelectX_1000(i) {
    var current_tab = $('#current_tab').val();
    var game_code = $('#current_game').val();
    // console.log(parseInt(current_tab)*10)
    // console.log('#X_' + game_code + '_' + (parseInt(i)+parseInt(current_tab)*10))
    if ($('#X_' + game_code + '_' + current_tab+i ).is(":checked")) {
        for (var j = 0; j < 10; j++) {
            var value = current_tab + "" + i + "" + j;
            var numb = $('#select_' + game_code + '_' + value);
            // console.log('#select_' + game_code + '_' + value)
            if ($(numb).hasClass('number_content')) {
                Select_Number(numb, game_code, value);
            }
        }
    } else {
        for (var j = 0; j < 10; j++) {
            var value = current_tab + "" + i + "" + j;
            var numb = $('#select_' + game_code + '_' + value);
            
            if ($(numb).hasClass('number_block')) {
                Select_Number(numb, game_code, value);
            }
        }

    }
}

function SelectNumberByReq(i) {
    if (i == 'tong0') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '19,91,28,82,37,73,46,64,55,00,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('19,91,28,82,37,73,46,64,55,00,', ''));
    }

    if (i == 'tong1') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,10,29,92,38,83,47,74,56,65,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,10,29,92,38,83,47,74,56,65,', ''));
    }

    if (i == 'tong2') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '02,20,39,93,48,84,57,75,11,66,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('02,20,39,93,48,84,57,75,11,66,', ''));
    }

    if (i == 'tong3') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '03,30,12,21,49,94,58,85,67,76,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('03,30,12,21,49,94,58,85,67,76,', ''));
    }

    if (i == 'tong4') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '04,40,13,31,59,95,68,86,22,77,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('04,40,13,31,59,95,68,86,22,77,', ''));
    }

    if (i == 'tong5') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '05,50,14,41,23,32,69,96,78,87,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('05,50,14,41,23,32,69,96,78,87,', ''));
    }

    if (i == 'tong6') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '06,60,15,51,24,42,79,97,33,88,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('06,60,15,51,24,42,79,97,33,88,', ''));
    }

    if (i == 'tong7') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '07,70,16,61,25,52,34,43,89,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('07,70,16,61,25,52,34,43,89,98,', ''));
    }

    if (i == 'tong8') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '08,80,17,71,26,62,35,53,44,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('08,80,17,71,26,62,35,53,44,99,', ''));
    }

    if (i == 'tong9') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '09,90,18,81,27,72,36,63,45,54,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('09,90,18,81,27,72,36,63,45,54,', ''));
    }

    if (i == 'hieu0') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,11,22,33,44,55,66,77,88,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,11,22,33,44,55,66,77,88,99,', ''));
    }
    if (i == 'hieu1') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,10,12,21,23,32,34,43,45,54,56,65,67,76,78,87,89,98,90,09,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,10,12,21,23,32,34,43,45,54,56,65,67,76,78,87,89,98,90,09,', ''));
    }
    if (i == 'hieu2') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '02,20,24,42,46,64,68,86,80,08,13,31,35,53,57,75,79,97,91,19,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('02,20,24,42,46,64,68,86,80,08,13,31,35,53,57,75,79,97,91,19,', ''));
    }
    if (i == 'hieu3') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '03,30,36,63,69,96,92,29,25,52,58,85,81,18,41,14,74,47,07,70,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('03,30,36,63,69,96,92,29,25,52,58,85,81,18,41,14,74,47,07,70,', ''));
    }
    if (i == 'hieu4') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '04,40,48,84,82,28,26,62,60,06,15,51,59,95,93,39,37,73,71,17,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('04,40,48,84,82,28,26,62,60,06,15,51,59,95,93,39,37,73,71,17,', ''));
    }
    if (i == 'hieu5') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '05,50,16,61,27,72,38,83,49,94,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('05,50,16,61,27,72,38,83,49,94,', ''));
    }

    if (i == 'dau0') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,01,02,03,04,05,06,07,08,09,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,01,02,03,04,05,06,07,08,09,', ''));
    }
    if (i == 'dau1') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '10,11,12,13,14,15,16,17,18,19,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('10,11,12,13,14,15,16,17,18,19,', ''));
    }
    if (i == 'dau2') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '20,21,22,23,24,25,26,27,28,29,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('20,21,22,23,24,25,26,27,28,29,', ''));
    }
    if (i == 'dau3') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '30,31,32,33,34,35,36,37,38,39,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('30,31,32,33,34,35,36,37,38,39,', ''));
    }
    if (i == 'dau4') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '40,41,42,43,44,45,46,47,48,49,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('40,41,42,43,44,45,46,47,48,49,', ''));
    }
    if (i == 'dau5') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '50,51,52,53,54,55,56,57,58,59,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('50,51,52,53,54,55,56,57,58,59,', ''));
    }
    if (i == 'dau6') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '60,61,62,63,64,65,66,67,68,69,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('60,61,62,63,64,65,66,67,68,69,', ''));
    }
    if (i == 'dau7') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '70,71,72,73,74,75,76,77,78,79,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('70,71,72,73,74,75,76,77,78,79,', ''));
    }
    if (i == 'dau8') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '80,81,82,83,84,85,86,87,88,89,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('80,81,82,83,84,85,86,87,88,89,', ''));
    }
    if (i == 'dau9') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '90,91,92,93,94,95,96,97,98,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('90,91,92,93,94,95,96,97,98,99,', ''));
    }

    if (i == 'duoi0') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,10,20,30,40,50,60,70,80,90,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,10,20,30,40,50,60,70,80,90,', ''));
    }
    if (i == 'duoi1') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,11,21,31,41,51,61,71,81,91,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,11,21,31,41,51,61,71,81,91,', ''));
    }
    if (i == 'duoi2') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '02,12,22,32,42,52,62,72,82,92,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('02,12,22,32,42,52,62,72,82,92,', ''));
    }
    if (i == 'duoi3') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '03,13,23,33,43,53,63,73,83,93,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('03,13,23,33,43,53,63,73,83,93,', ''));
    }
    if (i == 'duoi4') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '04,14,24,34,44,54,64,74,84,94,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('04,14,24,34,44,54,64,74,84,94,', ''));
    }
    if (i == 'duoi5') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '05,15,25,35,45,55,65,75,85,95,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('05,15,25,35,45,55,65,75,85,95,', ''));
    }
    if (i == 'duoi6') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '06,16,26,36,46,56,66,76,86,96,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('06,16,26,36,46,56,66,76,86,96,', ''));
    }
    if (i == 'duoi7') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '07,17,27,37,47,57,67,77,87,97,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('07,17,27,37,47,57,67,77,87,97,', ''));
    }
    if (i == 'duoi8') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '08,18,28,38,48,58,68,78,88,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('08,18,28,38,48,58,68,78,88,98,', ''));
    }
    if (i == 'duoi9') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '09,19,29,39,49,59,69,79,89,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('09,19,29,39,49,59,69,79,89,99,', ''));
    }

    if (i == 'boso00') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,55,05,50,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,55,05,50,', ''));
    }
    if (i == 'boso11') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,66,16,61,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,66,16,61,', ''));
    }
    if (i == 'boso22') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '22,77,27,72,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('22,77,27,72,', ''));
    }
    if (i == 'boso33') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '33,88,38,83,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('33,88,38,83,', ''));
    }
    if (i == 'boso44') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '44,99,49,94,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('44,99,49,94,', ''));
    }
    if (i == 'boso01') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,10,06,60,51,15,56,65,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,10,06,60,51,15,56,65,', ''));
    }
    if (i == 'boso02') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '02,20,07,70,25,52,57,75,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('02,20,07,70,25,52,57,75,', ''));
    }
    if (i == 'boso03') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '03,30,08,80,35,53,58,85,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('03,30,08,80,35,53,58,85,', ''));
    }
    if (i == 'boso04') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '04,40,09,90,45,54,59,95,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('04,40,09,90,45,54,59,95,', ''));
    }
    if (i == 'boso12') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '12,21,17,71,26,62,67,76,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('12,21,17,71,26,62,67,76,', ''));
    }
    if (i == 'boso13') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '13,31,18,81,36,63,68,86,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('13,31,18,81,36,63,68,86,', ''));
    }
    if (i == 'boso14') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '14,41,19,91,46,64,69,96,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('14,41,19,91,46,64,69,96,', ''));
    }
    if (i == 'boso23') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '23,32,28,82,73,37,78,87,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('23,32,28,82,73,37,78,87,', ''));
    }
    if (i == 'boso24') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '24,42,29,92,74,47,79,97,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('24,42,29,92,74,47,79,97,', ''));
    }
    if (i == 'boso34') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '34,43,39,93,84,48,89,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('34,43,39,93,84,48,89,98,', ''));
    }

    if (i == 'cham0') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,10,02,20,03,30,04,40,05,50,06,60,07,70,08,80,09,90,00,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,10,02,20,03,30,04,40,05,50,06,60,07,70,08,80,09,90,00,', ''));
    }
    if (i == 'cham1') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,10,12,21,13,31,14,41,15,51,16,61,17,71,18,81,19,91,11,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,10,12,21,13,31,14,41,15,51,16,61,17,71,18,81,19,91,11,', ''));
    }
    if (i == 'cham2') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '02,20,12,21,23,32,24,42,25,52,26,62,27,72,28,82,29,92,22,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('02,20,12,21,23,32,24,42,25,52,26,62,27,72,28,82,29,92,22,', ''));
    }
    if (i == 'cham3') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '03,30,13,31,23,32,34,43,35,53,36,63,37,73,38,83,39,93,33,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('03,30,13,31,23,32,34,43,35,53,36,63,37,73,38,83,39,93,33,', ''));
    }
    if (i == 'cham4') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '04,40,14,41,24,42,34,43,45,54,46,64,47,74,48,84,49,94,44,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('04,40,14,41,24,42,34,43,45,54,46,64,47,74,48,84,49,94,44,', ''));
    }
    if (i == 'cham5') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '51,15,52,25,53,35,54,45,05,50,56,65,57,75,58,85,59,95,55,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('51,15,52,25,53,35,54,45,05,50,56,65,57,75,58,85,59,95,55,', ''));
    }
    if (i == 'cham6') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '61,16,62,26,63,36,64,46,65,56,06,60,67,76,68,86,69,96,66,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('61,16,62,26,63,36,64,46,65,56,06,60,67,76,68,86,69,96,66,', ''));
    }
    if (i == 'cham7') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '71,17,72,27,73,37,74,47,75,57,76,67,07,70,78,87,79,97,77,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('71,17,72,27,73,37,74,47,75,57,76,67,07,70,78,87,79,97,77,', ''));
    }
    if (i == 'cham8') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '81,18,82,28,83,38,84,48,85,58,86,68,87,78,08,80,89,98,88,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('81,18,82,28,83,38,84,48,85,58,86,68,87,78,08,80,89,98,88,', ''));
    }
    if (i == 'cham9') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '91,19,92,29,93,39,94,49,95,59,96,69,97,79,98,89,09,90,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('91,19,92,29,93,39,94,49,95,59,96,69,97,79,98,89,09,90,99,', ''));
    }

    if (i == 'danchia3') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,03,06,09,12,15,18,21,24,27,30,33,36,39,42,45,48,51,54,57,60,63,66,69,72,75,78,81,84,87,90,93,96,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,03,06,09,12,15,18,21,24,27,30,33,36,39,42,45,48,51,54,57,60,63,66,69,72,75,78,81,84,87,90,93,96,99,', ''));
    }
    if (i == 'danchia3du1') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,04,07,10,13,16,19,22,25,28,31,34,37,40,43,46,49,52,55,58,61,64,67,70,73,76,79,82,85,88,91,94,97,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,04,07,10,13,16,19,22,25,28,31,34,37,40,43,46,49,52,55,58,61,64,67,70,73,76,79,82,85,88,91,94,97,', ''));
    }
    if (i == 'danchia3du2') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '02,05,08,11,14,17,20,23,26,29,32,35,38,41,44,47,50,53,56,59,62,65,68,71,74,77,80,83,86,89,92,95,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('02,05,08,11,14,17,20,23,26,29,32,35,38,41,44,47,50,53,56,59,62,65,68,71,74,77,80,83,86,89,92,95,98,', ''));
    }
    if (i == 'dan05') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,01,02,03,04,05,10,11,12,13,14,15,20,21,22,23,24,25,30,31,32,33,34,35,40,41,42,43,44,45,50,51,52,53,54,55,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,01,02,03,04,05,10,11,12,13,14,15,20,21,22,23,24,25,30,31,32,33,34,35,40,41,42,43,44,45,50,51,52,53,54,55,', ''));
    }

    if (i == 'dan06') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,01,02,03,04,05,06,10,11,12,13,14,15,16,20,21,22,23,24,25,26,30,31,32,33,34,35,36,40,41,42,43,44,45,46,50,51,52,53,54,55,56,60,61,62,63,64,65,66,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,01,02,03,04,05,06,10,11,12,13,14,15,16,20,21,22,23,24,25,26,30,31,32,33,34,35,36,40,41,42,43,44,45,46,50,51,52,53,54,55,56,60,61,62,63,64,65,66,', ''));
    }

    if (i == 'dan07') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,01,02,03,04,05,06,07,10,11,12,13,14,15,16,17,20,21,22,23,24,25,26,27,30,31,32,33,34,35,36,37,40,41,42,43,44,45,46,47,50,51,52,53,54,55,56,57,60,61,62,63,64,65,66,67,70,71,72,73,74,75,76,77,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,01,02,03,04,05,06,07,10,11,12,13,14,15,16,17,20,21,22,23,24,25,26,27,30,31,32,33,34,35,36,37,40,41,42,43,44,45,46,47,50,51,52,53,54,55,56,57,60,61,62,63,64,65,66,67,70,71,72,73,74,75,76,77,', ''));
    }

    if (i == 'dan08') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,01,02,03,04,05,06,07,08,10,11,12,13,14,15,16,17,18,20,21,22,23,24,25,26,27,28,30,31,32,33,34,35,36,37,38,40,41,42,43,44,45,46,47,48,50,51,52,53,54,55,56,57,58,60,61,62,63,64,65,66,67,68,70,71,72,73,74,75,76,77,78,80,81,82,83,84,85,86,87,88,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,01,02,03,04,05,06,07,08,10,11,12,13,14,15,16,17,18,20,21,22,23,24,25,26,27,28,30,31,32,33,34,35,36,37,38,40,41,42,43,44,45,46,47,48,50,51,52,53,54,55,56,57,58,60,61,62,63,64,65,66,67,68,70,71,72,73,74,75,76,77,78,80,81,82,83,84,85,86,87,88,', ''));
    }

    if (i == 'dan15') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,12,13,14,15,21,22,23,24,25,31,32,33,34,35,41,42,43,44,45,51,52,53,54,55,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,12,13,14,15,21,22,23,24,25,31,32,33,34,35,41,42,43,44,45,51,52,53,54,55,', ''));
    }

    if (i == 'dan16') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,12,13,14,15,16,21,22,23,24,25,26,31,32,33,34,35,36,41,42,43,44,45,46,51,52,53,54,55,56,61,62,63,64,65,66,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,12,13,14,15,16,21,22,23,24,25,26,31,32,33,34,35,36,41,42,43,44,45,46,51,52,53,54,55,56,61,62,63,64,65,66,', ''));
    }

    if (i == 'dan17') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,12,13,14,15,16,17,21,22,23,24,25,26,27,31,32,33,34,35,36,37,41,42,43,44,45,46,47,51,52,53,54,55,56,57,61,62,63,64,65,66,67,71,72,73,74,75,76,77,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,12,13,14,15,16,17,21,22,23,24,25,26,27,31,32,33,34,35,36,37,41,42,43,44,45,46,47,51,52,53,54,55,56,57,61,62,63,64,65,66,67,71,72,73,74,75,76,77,', ''));
    }

    if (i == 'dan18') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,12,13,14,15,16,17,18,21,22,23,24,25,26,27,28,31,32,33,34,35,36,37,38,41,42,43,44,45,46,47,48,51,52,53,54,55,56,57,58,61,62,63,64,65,66,67,68,71,72,73,74,75,76,77,78,81,82,83,84,85,86,87,88,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,12,13,14,15,16,17,18,21,22,23,24,25,26,27,28,31,32,33,34,35,36,37,38,41,42,43,44,45,46,47,48,51,52,53,54,55,56,57,58,61,62,63,64,65,66,67,68,71,72,73,74,75,76,77,78,81,82,83,84,85,86,87,88,', ''));
    }

    if (i == 'dan19') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,12,13,14,15,16,17,18,19,21,22,23,24,25,26,27,28,29,31,32,33,34,35,36,37,38,39,41,42,43,44,45,46,47,48,49,51,52,53,54,55,56,57,58,59,61,62,63,64,65,66,67,68,69,71,72,73,74,75,76,77,78,79,81,82,83,84,85,86,87,88,89,91,92,93,94,95,96,97,98,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,12,13,14,15,16,17,18,19,21,22,23,24,25,26,27,28,29,31,32,33,34,35,36,37,38,39,41,42,43,44,45,46,47,48,49,51,52,53,54,55,56,57,58,59,61,62,63,64,65,66,67,68,69,71,72,73,74,75,76,77,78,79,81,82,83,84,85,86,87,88,89,91,92,93,94,95,96,97,98,99,', ''));
    }

    if (i == 'dan26') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '22,23,24,25,26,32,33,34,35,36,42,43,44,45,46,52,53,54,55,56,62,63,64,65,66,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('22,23,24,25,26,32,33,34,35,36,42,43,44,45,46,52,53,54,55,56,62,63,64,65,66,', ''));
    }

    if (i == 'dan27') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '22,23,24,25,26,27,32,33,34,35,36,37,42,43,44,45,46,47,52,53,54,55,56,57,62,63,64,65,66,67,72,73,74,75,76,77,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('22,23,24,25,26,27,32,33,34,35,36,37,42,43,44,45,46,47,52,53,54,55,56,57,62,63,64,65,66,67,72,73,74,75,76,77,', ''));
    }

    if (i == 'dan28') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '22,23,24,25,26,27,28,32,33,34,35,36,37,38,42,43,44,45,46,47,48,52,53,54,55,56,57,58,62,63,64,65,66,67,68,72,73,74,75,76,77,78,82,83,84,85,86,87,88,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('22,23,24,25,26,27,28,32,33,34,35,36,37,38,42,43,44,45,46,47,48,52,53,54,55,56,57,58,62,63,64,65,66,67,68,72,73,74,75,76,77,78,82,83,84,85,86,87,88,', ''));
    }

    if (i == 'dan29') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '22,23,24,25,26,27,28,29,32,33,34,35,36,37,38,39,42,43,44,45,46,47,48,49,52,53,54,55,56,57,58,59,62,63,64,65,66,67,68,69,72,73,74,75,76,77,78,79,82,83,84,85,86,87,88,89,92,93,94,95,96,97,98,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('22,23,24,25,26,27,28,29,32,33,34,35,36,37,38,39,42,43,44,45,46,47,48,49,52,53,54,55,56,57,58,59,62,63,64,65,66,67,68,69,72,73,74,75,76,77,78,79,82,83,84,85,86,87,88,89,92,93,94,95,96,97,98,99,', ''));
    }

    if (i == 'dan38') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '33,34,35,36,37,38,43,44,45,46,47,48,53,54,55,56,57,58,63,64,65,66,67,68,73,74,75,76,77,78,83,84,85,86,87,88,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('33,34,35,36,37,38,43,44,45,46,47,48,53,54,55,56,57,58,63,64,65,66,67,68,73,74,75,76,77,78,83,84,85,86,87,88,', ''));
    }

    if (i == 'dan39') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '33,34,35,36,37,38,39,43,44,45,46,47,48,49,53,54,55,56,57,58,59,63,64,65,66,67,68,69,73,74,75,76,77,78,79,83,84,85,86,87,88,89,93,94,95,96,97,98,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('33,34,35,36,37,38,39,43,44,45,46,47,48,49,53,54,55,56,57,58,59,63,64,65,66,67,68,69,73,74,75,76,77,78,79,83,84,85,86,87,88,89,93,94,95,96,97,98,99,', ''));
    }

    if (i == 'dan49') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '44,45,46,47,48,49,54,55,56,57,58,59,64,65,66,67,68,69,74,75,76,77,78,79,84,85,86,87,88,89,94,95,96,97,98,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('44,45,46,47,48,49,54,55,56,57,58,59,64,65,66,67,68,69,74,75,76,77,78,79,84,85,86,87,88,89,94,95,96,97,98,99,', ''));
    }

    if (i == 'sokeplech') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '05,50,16,61,27,72,38,83,49,94,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('05,50,16,61,27,72,38,83,49,94,', ''));
    }
    if (i == 'sokepbang') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,55,11,66,22,77,33,88,44,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,55,11,66,22,77,33,88,44,99,', ''));
    }

    if (i == 'sokepam') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '07,70,14,41,29,92,36,63,58,85,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('07,70,14,41,29,92,36,63,58,85,', ''));
    }
    if (i == 'satkepbang') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,10,12,21,23,32,34,43,45,54,56,65,67,76,78,87,89,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,10,12,21,23,32,34,43,45,54,56,65,67,76,78,87,89,98,', ''));
    }
    if (i == 'satkeplech') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '04,06,15,17,26,28,37,39,48,51,60,62,71,73,82,84,93,95,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('04,06,15,17,26,28,37,39,48,51,60,62,71,73,82,84,93,95,', ''));
    }

    if (i == 'bokep') {
        if ($('#' + i).is(":checked")){
            bokep = $('#number_select_text').val();
            var re = /00,/g;  
            var s = bokep.replace(re, "");

            re = /11,/g;  
            s = s.replace(re, "");

            re = /22,/g;  
            s = s.replace(re, "");

            re = /33,/g;  
            s = s.replace(re, "");

            re = /44,/g;  
            s = s.replace(re, "");

            re = /55,/g;  
            s = s.replace(re, "");

            re = /66,/g;  
            s = s.replace(re, "");

            re = /77,/g;  
            s = s.replace(re, "");

            re = /88,/g;  
            s = s.replace(re, "");

            re = /99,/g;  
            s = s.replace(re, "");

            $('#number_select_text').val(s);
        }
        else{
            // $('#number_select_text').val($('#number_select_text').val() + '00,11,22,33,44,55,66,77,88,99,');
        }
            
    }

    if (i == 'chanchan') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,22,44,66,88,02,20,04,40,06,60,08,80,24,42,26,62,28,82,46,64,48,84,68,86,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,22,44,66,88,02,20,04,40,06,60,08,80,24,42,26,62,28,82,46,64,48,84,68,86,', ''));
    }
    if (i == 'lele') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,33,55,77,99,13,31,15,51,17,71,19,91,35,53,37,73,39,93,57,75,59,95,79,97,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,33,55,77,99,13,31,15,51,17,71,19,91,35,53,37,73,39,93,57,75,59,95,79,97,', ''));
    }
    if (i == 'chanle') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,03,05,07,09,21,23,25,27,29,41,43,45,47,49,61,63,65,67,69,81,83,85,87,89,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,03,05,07,09,21,23,25,27,29,41,43,45,47,49,61,63,65,67,69,81,83,85,87,89,', ''));
    }
    if (i == 'lechan') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '10,12,14,16,18,30,32,34,36,38,50,52,54,56,58,70,72,74,76,78,90,92,94,96,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('10,12,14,16,18,30,32,34,36,38,50,52,54,56,58,70,72,74,76,78,90,92,94,96,98,', ''));
    }

    if (i == 'nhonho') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,11,22,33,44,01,10,02,20,03,30,04,40,12,21,13,31,14,41,23,32,24,42,34,43,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,11,22,33,44,01,10,02,20,03,30,04,40,12,21,13,31,14,41,23,32,24,42,34,43,', ''));
    }
    if (i == 'toto') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '55,66,77,88,99,56,65,57,75,58,85,59,95,67,76,68,86,69,96,78,87,79,97,89,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('55,66,77,88,99,56,65,57,75,58,85,59,95,67,76,68,86,69,96,78,87,79,97,89,98,', ''));
    }
    if (i == 'nhoto') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '05,06,07,08,09,15,16,17,18,19,25,26,27,28,29,35,36,37,38,39,45,46,47,48,49,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('05,06,07,08,09,15,16,17,18,19,25,26,27,28,29,35,36,37,38,39,45,46,47,48,49,', ''));
    }
    if (i == 'tonho') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '90,91,92,93,94,80,81,82,83,84,70,71,72,73,74,60,61,62,63,64,50,51,52,53,54,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('90,91,92,93,94,80,81,82,83,84,70,71,72,73,74,60,61,62,63,64,50,51,52,53,54,', ''));
    }

    if (i == 'ty') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '00,12,24,36,48,60,72,84,96,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('00,12,24,36,48,60,72,84,96,', ''));
    }
    if (i == 'suu') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '01,13,25,37,49,61,73,85,97,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('01,13,25,37,49,61,73,85,97,', ''));
    }
    if (i == 'dan9') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '02,14,26,38,50,62,74,86,98,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('02,14,26,38,50,62,74,86,98,', ''));
    }
    if (i == 'mao') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '03,15,27,39,51,63,75,87,99,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('03,15,27,39,51,63,75,87,99,', ''));
    }
    if (i == 'thin') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '04,16,28,40,52,64,76,88,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('04,16,28,40,52,64,76,88,', ''));
    }
    if (i == 'ty8') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '05,17,29,41,53,65,77,89,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('05,17,29,41,53,65,77,89,', ''));
    }
    if (i == 'ngo') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '06,18,30,42,54,66,78,90,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('06,18,30,42,54,66,78,90,', ''));
    }
    if (i == 'mui') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '07,19,31,43,55,67,79,91,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('07,19,31,43,55,67,79,91,', ''));
    }
    if (i == 'than') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '08,20,32,44,56,68,80,92,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('08,20,32,44,56,68,80,92,', ''));
    }
    if (i == 'dau') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '09,21,33,45,57,69,81,93,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('09,21,33,45,57,69,81,93,', ''));
    }
    if (i == 'tuat') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '10,22,34,46,58,70,82,94,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('10,22,34,46,58,70,82,94,', ''));
    }
    if (i == 'hoi') {
        if ($('#' + i).is(":checked"))
            $('#number_select_text').val($('#number_select_text').val() + '11,23,35,47,59,71,83,95,');
        else
            $('#number_select_text').val($('#number_select_text').val().replace('11,23,35,47,59,71,83,95,', ''));
    }


    $('#enter_array').click();

}

$(document).ready(function() {
    $("#dropdown-temp-numb li").click(function() {
        //slide up all the link lists
        // 
        $('#dropdown-temp-numb li').removeClass('active');
    })
})

$(document).click(function(event) {
    // alert('clicked outside');
    if (event.target.localName != 'a' && event.target.className != 'row number_block' && event.target.className != 'btn btn-warning btn-custom waves-effect waves-light btn-xs active' && event.target.localName != 'button')
        $('.tabbable-pane').removeClass('active');
});

$(".tabbable-pane").click(function(event) {
    // alert('clicked inside');
    event.stopPropagation();
});

function highlightC(){
    // console.log('hightlightC')
	statusH = localStorage.getItem('highlight-price-status-' + $('#current_game').val() + localStorage.getItem('current_user'))
    if (statusH == 'true'){
        priceH = localStorage.getItem('highlight-price-' + $('#current_game').val() + localStorage.getItem('current_user'))
        $(".exchange").filter(function() {
            // console.log($(this).text())
            if($(this).text().replace(/[^0-9\.]+/g, "") > priceH.replace(/[^0-9\.]+/g, "")) {
                $(this).css('background-color','yellow');
            }else{
                $(this).css('background-color','rgba(242, 248, 255, 0)');
            }
        });
    }else{
        $(".exchange").filter(function() {
            $(this).css('background-color','rgba(242, 248, 255, 0)');
        });
    }
        
}

async function configHighlight(){
    
}

// $(document).on('keyup', '.number_select_text', function() {
//         var value = $(this).val().split(',')
//         if (value[value.length - 1].length > 1) {
//             $(this).val(value + ',')
//         }
//     })

//endregion

function selectTextOnly(containerid) {
    var text = document.getElementById(containerid);
    if (document.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
        console.log(text)
    } else {
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
        console.log(text)
    } 
}