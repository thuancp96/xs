@extends("frontend.frontend-template")

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.css" integrity="sha512-SZgE3m1he0aEF3tIxxnz/3mXu/u/wlMNxQSnE0Cni9j/O8Gs+TjM9tm1NX34nRQ7GiLwUEzwuE3Wv2FLz2667w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css" integrity="sha512-3q8fi8M0VS+X/3n64Ndpp6Bit7oXSiyCnzmlx6IDBLGlY5euFySyJ46RUlqIVs0DPCGOypqP8IRk/EyPvU28mQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div style="width: 100vw; height: calc(100vh - 80px); position: absolute;">
    <iframe id="MainGame"  width="100%" height="100%"></iframe>
    <a  id="btnClick" onclick='window.open("https://luk79.net/api/livegames", "_blank");' style='display: none'></a>
</div>
<style>

#ex1Slider .slider-selection {
	background: #BABABA;
}

.slider-handle.round {
    position: absolute;
    width: 20px;
    height: 20px;
    border: 1px solid #EFF2F7;
    background: #5b9422 !important;
    cursor: pointer;
}

.card{
    border: 0 !important;
}

.card-body{
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.c-bbin-popup-header-balance{
    display: flex;
    flex-direction: column;
}

.mainbalance{
    font-weight: bold;
}
    
hr{
    margin: 0;
    margin-left: 10px !important;
    margin-right: 10px !important;
    color: #c5c5c5 !important;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.js" ></script>
<script>
    
    isloaded = false;
    function thuhoi(){
        $($('#thuhoi')).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang xử lý');
        $.get("/api/bbin/recall", function(data, status){
            $($('#thuhoi')).html('Thu hồi tất cả');
            if(data.message != true){
                isloaded = false;
                isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
                $("#transfer-modal").modal("hide");
                Swal.fire("",data.message,"error").then((value) => {
                  if(!isloaded){
                      $('.container.white-bg').hide(); $('.loadere').show();
                      if(isMobile.any)   {
                          $('#btnClick').trigger('click');
                          $('.container.white-bg').show(); $('.loadere').hide();
                          $.get("/api/bbin/info", function(data, status){
                                $(".card-text.mainbalance").html(data.mainbalance.toLocaleString('en-US'));
                                $(".card-text.gamebalance").html(data.BBINbalance.toLocaleString('en-US'));
                            });
                          $("#transfer-modal").modal("show");
                      }
                  }
                });;
            }else{
                isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
                $('.container.white-bg').show(); $('.loadere').hide();
                $.get("/api/bbin/info", function(data, status){
                    $(".card-text.mainbalance").html(data.mainbalance.toLocaleString('en-US'));
                    $(".card-text.gamebalance").html(data.BBINbalance.toLocaleString('en-US'));
                });
                Swal.fire("","Thu hồi thành công","success");
            }
        });
    }
    function Chuyen(){
        if (Number($('#moneyTrans').val().replaceAll(',','') == '0' || Number($('#moneyTrans').val().replaceAll(',','') == ''))){
            $("#transfer-modal").modal("hide");
            if (isMobile.any)
                $('#btnClick').trigger('click');
                return;
        }
        $($('#chuyentien')).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang xử lý');
        $.get("/api/bbin/transfer?money=" +  Number($('#moneyTrans').val().replaceAll(',','')), function(data, status){
            $($('#chuyentien')).html('Chuyển tiền');
            
            if(data.message != true){
                isloaded = false;
                isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
                // $('.container.white-bg').hide(); $('.loadere').show();
                $("#transfer-modal").modal("hide");
                Swal.fire("",data.message,"error").then((value) => {
                  if(!isloaded){
                      $('.container.white-bg').hide(); $('.loadere').show();
                  }
                  if(isMobile.any)   {
                      $('#btnClick').trigger('click');
                      $('.container.white-bg').show(); $('.loadere').hide();
                      $("#transfer-modal").modal("show");
                      $.get("/api/bbin/info", function(data, status){
                            $(".card-text.mainbalance").html(data.mainbalance.toLocaleString('en-US'));
                            $(".card-text.gamebalance").html(data.BBINbalance.toLocaleString('en-US'));
                        });
                  }
                });
            }else{
                isloaded = false;
                isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
                // $('.container.white-bg').hide(); $('.loadere').show();
                $("#transfer-modal").modal("hide");
                
                Swal.fire("","Chuyển tiền thành công","success").then((value) => {
                  if(!isloaded){
                      $('.container.white-bg').hide(); $('.loadere').show();
                  }
                  if(isMobile.any)   {
                      $('#btnClick').trigger('click');
                      $('.container.white-bg').show(); $('.loadere').hide();
                      $("#transfer-modal").modal("show");
                      $.get("/api/bbin/info", function(data, status){
                            $(".card-text.mainbalance").html(data.mainbalance.toLocaleString('en-US'));
                            $(".card-text.gamebalance").html(data.BBINbalance.toLocaleString('en-US'));
                        });
                  }
                });
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        // isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
    });
    document.addEventListener('DOMContentLoaded', () => {
        // alert('load bbin')
		try{
            if (window.location.href.indexOf("bbin") != -1){
                $.get("/api/bbin/info", function(data, status) {
                    
                    $("#transfer-modal").modal("show");
                    if (data.BBINbalance > data.MaxBet){
                        ruttienClick()
                        $("#messageToUser").html('Tài khoản của bạn đã vượt quá số tiền giới hạn BBIN. Hãy rút tối thiểu '+ (data.BBINbalance-data.MaxTransfer).toLocaleString('en-US'))
                        $("#messageToUser").show()
                        $("#moneyTrans").val( (data.BBINbalance-data.MaxTransfer).toLocaleString('en-US'))
                        $("#transfer-modal").modal({
                            backdrop: 'static',
                            keyboard: false,
                            // show: true // added property here
                        });
                    }else{
                        isMobile.any ? true : document.getElementById('MainGame').src = "/api/livegames";
                        if(isMobile.any)   {
                            $('#btnClick').trigger('click');
                            $('.container.white-bg').show(); $('.loadere').hide();
                            $("#transfer-modal").modal("show");
                            $.get("/api/bbin/info", function(data, status){
                                    $(".card-text.mainbalance").html(data.mainbalance.toLocaleString('en-US'));
                                    $(".card-text.gamebalance").html(data.BBINbalance.toLocaleString('en-US'));
                                });
                        }
                    }
                    console.log(data)
                    $(".card-text.mainbalance").html(data.mainbalance.toLocaleString('en-US'));
                    $(".card-text.gamebalance").html(data.BBINbalance.toLocaleString('en-US'));
                    $(".card-text.maxtransfer").html(data.MaxTransfer.toLocaleString('en-US'));
                    $(".card-text.maxbet").html(data.MaxBet.toLocaleString('en-US'));
                    $("#total_money_bbin").html(data.BBINbalance.toLocaleString('en-US'));
                    $("#total_money_bbin_menubar").html(data.BBINbalance.toLocaleString('en-US')+ ' bbin');
                    slider = new Slider('#ex1', {
                        max: data.mainbalance
                    });
                    slider.on("slide", function(sliderValue) {
                        $("#moneyTrans").val(sliderValue);
                        $('#moneyTrans').trigger("input");
                    });
                    slider.on("change", function(sliderValue) {
                        seft = this;
                        $("#moneyTrans").val($("#ex1")[0].defaultValue);
                        $('#moneyTrans').trigger("input");
                    });

			// $("#transfer-modal").modal("show");
			});
            }

			// $('#moneyTrans').on('input', function(e) {
            //     $this = $(this);
            //     moneyTarget = Number($this.val().replaceAll(',', '').replaceAll('.', ''))
            //     moneyMax = Number($(".card-text.mainbalance").html().replaceAll(',', '').replaceAll('.', ''))
            //     if (moneyMax < moneyTarget)
            //         moneyTarget = moneyMax
            //     $this.val(moneyTarget.toLocaleString('en-US'));
			// });
			
			
		}catch(err){}
		
	});

    

    document.querySelector("iframe").addEventListener( "load", function(e) {
        $('.container.white-bg').show(); $('.loadere').hide();
        isloaded = true;
    });

    function validate(evt) {
        return;
      var theEvent = evt || window.event;
    
      // Handle paste
      if (theEvent.type === 'paste') {
          key = event.clipboardData.getData('text/plain');
      } else {
      // Handle key press
          var key = theEvent.keyCode || theEvent.which;
          key = String.fromCharCode(key);
      }
      var regex = /[0-9]|\./;
      if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
      }
    }
    
    !function(a){var b=/iPhone/i,c=/iPod/i,d=/iPad/i,e=/(?=.*\bAndroid\b)(?=.*\bMobile\b)/i,f=/Android/i,g=/(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)/i,h=/(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)/i,i=/IEMobile/i,j=/(?=.*\bWindows\b)(?=.*\bARM\b)/i,k=/BlackBerry/i,l=/BB10/i,m=/Opera Mini/i,n=/(CriOS|Chrome)(?=.*\bMobile\b)/i,o=/(?=.*\bFirefox\b)(?=.*\bMobile\b)/i,p=new RegExp("(?:Nexus 7|BNTV250|Kindle Fire|Silk|GT-P1000)","i"),q=function(a,b){return a.test(b)},r=function(a){var r=a||navigator.userAgent,s=r.split("[FBAN");return"undefined"!=typeof s[1]&&(r=s[0]),s=r.split("Twitter"),"undefined"!=typeof s[1]&&(r=s[0]),this.apple={phone:q(b,r),ipod:q(c,r),tablet:!q(b,r)&&q(d,r),device:q(b,r)||q(c,r)||q(d,r)},this.amazon={phone:q(g,r),tablet:!q(g,r)&&q(h,r),device:q(g,r)||q(h,r)},this.android={phone:q(g,r)||q(e,r),tablet:!q(g,r)&&!q(e,r)&&(q(h,r)||q(f,r)),device:q(g,r)||q(h,r)||q(e,r)||q(f,r)},this.windows={phone:q(i,r),tablet:q(j,r),device:q(i,r)||q(j,r)},this.other={blackberry:q(k,r),blackberry10:q(l,r),opera:q(m,r),firefox:q(o,r),chrome:q(n,r),device:q(k,r)||q(l,r)||q(m,r)||q(o,r)||q(n,r)},this.seven_inch=q(p,r),this.any=this.apple.device||this.android.device||this.windows.device||this.other.device||this.seven_inch,this.phone=this.apple.phone||this.android.phone||this.windows.phone,this.tablet=this.apple.tablet||this.android.tablet||this.windows.tablet,"undefined"==typeof window?this:void 0},s=function(){var a=new r;return a.Class=r,a};"undefined"!=typeof module&&module.exports&&"undefined"==typeof window?module.exports=r:"undefined"!=typeof module&&module.exports&&"undefined"!=typeof window?module.exports=s():"function"==typeof define&&define.amd?define("isMobile",[],a.isMobile=s()):a.isMobile=s()}(this);

</script>
@include('frontend.bbinTransfer')