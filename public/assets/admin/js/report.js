$(document).ready(function() {
    // var t = $('#table_winlose').DataTable( {
    //     "paging":   false,
    //     "oLanguage": {"sZeroRecords": "Chưa có dữ liệu", "sEmptyTable": "Chưa có dữ liệu"},
    //     "bLengthChange": false,
    //     "ordering": false,
    //     "info":     false,
    //     "columnDefs": [ {
    //         "searchable": false,
    //         "orderable": false,
    //         "targets": 0
    //     } ],
    //     "order": [[ 1, 'asc' ]],
    //     "footerCallback": function ( row, data, start, end, display ) {
            // var api = this.api(), data;
 
            // // Remove the formatting to get integer data for summation
            // var intVal = function ( i ) {
            //     return typeof i === 'string' ?
            //         i.replace(/[\$,]/g, '')*1 :
            //         typeof i === 'number' ?
            //             i : 0;
            // };
 
            // // Total point over all pages
            // totalPoint = api
            //     .column( 5 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // // Total over this page
            // pageTotalPoint = api
            //     .column( 5, { page: 'current'} )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // // Update footer
            // $( api.column( 5 ).footer() ).html(
            //     pageTotalPoint.toLocaleString() +' / '+ totalPoint.toLocaleString()
            // );

            // // Total money over all pages
            // totalmoney = api
            //     .column( 6 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // // Total money this page
            // pageTotalmoney = api
            //     .column( 6, { page: 'current'} )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // // Update footer
            // $( api.column( 6 ).footer() ).html(
            //     pageTotalmoney.toLocaleString() +' / '+ totalmoney.toLocaleString()
            // );

            // // Total money over all pages
            // totalwin = api
            //     .column( 7 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // // Total money this page
            // pageTotalwin = api
            //     .column( 7, { page: 'current'} )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // // Update footer win
            // $( api.column( 7 ).footer() ).html(
            //     pageTotalwin.toLocaleString() +' / '+ totalwin.toLocaleString()
            // );
    //     }
    // } );

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!

        var yyyy = today.getFullYear();
        if(dd<10){
            dd='0'+dd;
        } 
        if(mm<10){
            mm='0'+mm;
        } 
        

        jQuery('#date-range').datepicker({
                        toggleActive: true,
                        format: "dd-mm-yyyy",
                        todayHighlight: true,
                        language: "vi",
                    });

        //Date range picker
        $('.input-daterange-datepicker').daterangepicker({
                        buttonClasses: ['btn', 'btn-sm'],
                        applyClass: 'btn-default',
                        cancelClass: 'btn-white',
                        minDate: moment().subtract(62,'days'),
                        // maxDate: today,
                        locale: {
                            format: "DD-MM-YYYY",
                            language: "vi",
                            separator: " / ",
                            applyLabel: "Tiếp",
                            cancelLabel: "Hủy",
                            fromLabel: "From",
                            toLabel:"To",
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
        }
                        // startDate: today,
                        // endDate: today
                    },function(start, end, label) {
    //alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        // if (window.location.href.includes('winlose'))
                            $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+start.format('YYYY-MM-DD')+'&enddate='+end.format('YYYY-MM-DD'));
                        // else
                            // $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
                    
                    }
        );

        $('.input-startdate-datepicker').daterangepicker({
                "singleDatePicker": true,
                "linkedCalendars": false,
                "showCustomRangeLabel": false,
                // "startDate": today,
                // "endDate": today,
                "minDate": moment().subtract(61, 'days'),
                "locale": {
                    format: "DD-MM-YYYY",
                    language: "vi",
                    separator: " / ",
                }
            }, function(start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
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
            
            if (window.location.href.includes('winlose'))
                $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
            else
                $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
            });

            $('.input-enddate-datepicker').daterangepicker({
                "singleDatePicker": true,
                "linkedCalendars": false,
                "showCustomRangeLabel": false,
                // "startDate": today,
                // "endDate": today,
                "minDate": moment().subtract(61, 'days'),
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
            if (window.location.href.includes('winlose'))
                $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
            else
                $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
            });

        $('.input-daterange-datepicker').val( $('#datepicker-ngaybatdaudatcuoc').val() +' / ' +$('#datepicker-ngayketthucdatcuoc').val());
    // t.on( 'order.dt search.dt', function () {
    //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         // cell.innerHTML = i+1;
    //     } );
    // } ).draw();
    // filterColumn(3);

    $("#btn_view_history").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var startdate = range[0];
        var enddate = range[1];
        // refreshHistory(range);
        if (window.location.href.includes('winlose'))
            $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        else
            $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        var href = $('#btn_view_by_filter').attr('href');         window.location.href = href;
    });

    $("#btn_view_history_sk").click(function() {
        var range = $('.input-daterange-datepicker').val().split('/');
        var startdate = range[0];
        var enddate = range[1];
        refreshHistory(range);
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
        if (window.location.href.includes('winlose'))
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        else
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        var href = $('#btn_view_by_filter').attr('href');         window.location.href = href;
        $('#quickDatePicker').html("Hôm nay")
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
        // if (window.location.href.includes('winlose'))
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        // else
        // $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        var href = $('#btn_view_by_filter').attr('href');         window.location.href = href;
        $('#quickDatePicker').html("Hôm qua")
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
        // if (window.location.href.includes('winlose'))
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        // else
        // $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        var href = $('#btn_view_by_filter').attr('href');         window.location.href = href;
        $('#quickDatePicker').html("Tuần này")
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
        // if (window.location.href.includes('winlose'))
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        // else
        // $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        var href = $('#btn_view_by_filter').attr('href');         window.location.href = href;
        $('#quickDatePicker').html("Tuần trước")
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
        // if (window.location.href.includes('winlose'))
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        // else
        // $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        var href = $('#btn_view_by_filter').attr('href');         window.location.href = href;
        $('#quickDatePicker').html("Tháng này")
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
        // if (window.location.href.includes('winlose'))
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '?stdate='+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'&enddate='+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        // else
        // $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('.input-startdate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')+'/'+$('.input-enddate-datepicker').data('daterangepicker').startDate.format('YYYY-MM-DD'));
        // var href = $('#btn_view_by_filter').attr('href');         window.location.href = href;
        var href = $('#btn_view_by_filter').attr('href'); window.location.href = href;
        $('#quickDatePicker').html("Tháng trước")
    });

    function GetFormattedDate(todayTime) {
        var month = todayTime.getMonth() + 1;
        var day = todayTime.getDate();
        var year = todayTime.getFullYear();
        return year + "-" + (month<10?"0"+month:month) + "-" + (day<10?"0"+day:day);
    }
    
    function filterGlobal () {
        $('#table_winlose').DataTable().search(
            $('#input_search_history').val()
        ).draw();
    }
    function filterColumn ( i ) {
        if(i==3)
        {
            $('#table_winlose').DataTable().column(i).search(
                $('#datepicker-ngaydatcuoc').val()
            ).draw();
        }

    }
    $('#input_search_history').keyup(function(event) {
        filterGlobal();
    });
    $('#datepicker-ngaydatcuoc').change(function(event) {
        filterColumn(3);
    });

    $('#datepicker-ngaybatdaudatcuoc').change(function(event) {
        $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('#datepicker-ngaybatdaudatcuoc').val()+'/'+$('#datepicker-ngayketthucdatcuoc').val());
    });

    $('#datepicker-ngayketthucdatcuoc').change(function(event) {
       $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+$('#datepicker-ngaybatdaudatcuoc').val()+'/'+$('#datepicker-ngayketthucdatcuoc').val());
    });

    $('.dataTables_filter').hide();
    jQuery('#datepicker-ngaydatcuoc').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd/mm/yyyy",
        language: "vi",
    });

    jQuery('#datepicker-ngaybatdaudatcuoc').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "yyyy-mm-dd",
        language: "vi",
    });

    jQuery('#datepicker-ngayketthucdatcuoc').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "yyyy-mm-dd",
        language: "vi",
    });


} );
function CheckWin(id,url,token) {
    $.ajax({
        url: url+"/"+id,
        method: 'PATCH',
        dataType: 'html',
        data: {
            _token: token,
        },
        success: function(data)
        {
            console.log('Data:', data);
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

// $("#btn_view_by_filter" ).click(function() {
//         refreshHistory($('#history-datepicker').val());
//     });

// function refreshHistory(date) {
//     if(date=="")
//     {
//         alert("Bạn hãy nhập ngày tháng");
//         return;
//     }
//     $('#div_history').load($('#url').val()+"/history-by-day/"+date, function() {
//     });
// }
