<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dice game</title>
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{!! asset('assets/plugins/bootstrap-3.3.7/css/bootstrap.min.css') !!}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{!! asset('assets/plugins/font-awesome/css/font-awesome.min.css') !!}">

    <link rel="stylesheet" href="{!! asset('assets/plugins/dice3d/dice3d.css') !!}">

    <link rel="stylesheet" href="{!! asset('assets/css/xx.css') !!}">
    <style>
        #dice-rusult{
            height: 400px;
            padding-top: 30px;
        }

        .wrap{width:300px; height:90px; margin:120px auto 30px auto; position:relative}
        .dice-result{display:inline-block;width:90px; height:90px; background:url("{!! asset('assets/images/dice/dice-bg.png') !!}") no-repeat;}
        .dice_1{background-position:-5px -4px}
        .dice_2{background-position:-5px -107px}
        .dice_3{background-position:-5px -212px}
        .dice_4{background-position:-5px -317px}
        .dice_5{background-position:-5px -427px}
        .dice_6{background-position:-5px -535px}
        .dice_t{background-position:-5px -651px}
        .dice_s{background-position:-5px -763px}
        .dice_e{background-position:-5px -876px}
        p#result{text-align:center; font-size:16px}
        p#result span{font-weight:bold; color:#f30; margin:6px}
        #dice_mask{width:90px; height:90px; background:#fff; opacity:0; position:absolute;
            top:0; left:0; z-index:999}
    </style>

</head>
<body>
    <div id="wrapper">
        <div class="main-game">
            @include('xucxac.dice.bet_option_01')

            @include('xucxac.dice.bet_option_02')

            @include('xucxac.dice.bet_option_03')

            @include('xucxac.dice.bet_option_04')

            @include('xucxac.dice.bet_option_05')

            <button type="submit" class="btn btn-primary" id="btnSubmit" data-toggle="modal" data-target="#resultModal">Submit</button>
            <button type="reset" class="btn btn-primary">Reset</button>

        </div>
    </div>
    <div class="container">
        <div id="resultModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Result</h4>
                    </div>
                    <div class="modal-body" id="dice-rusult">
                        <div class="wrap">
                            <div id="dice01" class="dice-result dice_1"></div>
                            <div id="dice02" class="dice-result dice_1"></div>
                            <div id="dice03" class="dice-result dice_1"></div>
                        </div>
                        <p id="result">Please click above dice!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- jQuery 2.2.3 -->
    <script src="{!! asset('assets/plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{!! asset('assets/plugins/bootstrap-3.3.7/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('assets/plugins/dice3d/dice3d.js') !!}"></script>
    <audio id="dice3d-sound" src="{!! asset('assets/plugins/dice3d/nc93322.mp3') !!}"></audio>
    <script>
        // player info
        var currentMoney = 0;

        //count bet
        var currentBetSmall = 0;
        var currentBetBig = 0;
        var currentBetEven = 0;
        var currentBetOdd = 0;
        var currentBetDouble = [0,0,0,0,0];
        var currentBetTripple = [0,0,0,0,0];
        var currentBetTrippleRandom = 0;
        var currentBetSum = 0;
        var currentBetSingle = 0;
        var currentBetQuadra = [0,0,0,0];

        //bet base
        var bet_option_single = 0;
        var bet_option_double = 0;
        var bet_option_sum = 0;
        var bet_option_big_small = 0;
        var bet_option_couple = 0;
        var bet_option_tripple = 0;
        var bet_option_triple_random = 0;
        $(document).ready(function () {
            $(".dice-group").click(function () {
                console.log($(this).attr('id'));
            });
        });

        function initBetCount() {
            currentBetSmall = 0;
            currentBetBig = 0;
            currentBetEven = 0;
            currentBetOdd = 0;
            currentBetDouble = [0,0,0,0,0];
            currentBetTripple = [0,0,0,0,0];
            currentBetTrippleRandom = 0;
            currentBetSum = 0;
            currentBetSingle = 0;
            currentBetQuadra = [0,0,0,0];
        }

        function initBetOption() {
            bet_option_single = 0;
            bet_option_double = 0;
            bet_option_sum = 0;
            bet_option_big_small = 0;
            bet_option_couple = 0;
            bet_option_tripple = 0;
            bet_option_triple_random = 0;
        }

        function updatePlayer() {
            currentMoney = 0;
        }

        function getResult() {
            /*
            current money
            result dice (1,2,3)
            each bet option and result (+/-number)
             */
        }
        var button = document.getElementById('btnSubmit');

        button.addEventListener('click', function(e) {
            e.preventDefault();
            $("#resultModal").show();
            var dice01 = $("#dice01");
            var dice02 = $("#dice02");
            var dice03 = $("#dice03");

            rotateDice(dice01,1);
            rotateDice(dice02,3);
            rotateDice(dice03,5);
//            for (var i = 0; i < 3; ++i) {
//                var r = Math.floor(Math.random() * 6) + 1;
//                dice3d(6, r); // Animate 6 faces dice
//            }
        });

        function rotateDice(obj,num) {
            obj.attr("class","dice-result");//After clearing the last points animation
            obj.css('cursor','default');
            obj.delay(100*(Math.random()%6+1)).animate({left: '+2px'}, 100*(Math.random()%6+1),function(){
                obj.addClass("dice_t");
            }).delay(100*(Math.random()%6+1)).animate({top:'-2px'},100*(Math.random()%6+1),function(){
                obj.removeClass("dice_t").addClass("dice_s");
            }).delay(100*(Math.random()%6+1)).animate({opacity: 'show'},100*(Math.random()%6+1),function(){
                obj.removeClass("dice_s").addClass("dice_e");
            }).delay(100*(Math.random()%6+1)).animate({left: '+2px'}, 100*(Math.random()%6+1),function(){
                obj.removeClass("dice_e").addClass("dice_t");
            }).delay(100*(Math.random()%6+1)).animate({top:'-2px'},100*(Math.random()%6+1),function(){
                obj.removeClass("dice_t").addClass("dice_s");
            }).delay(100*(Math.random()%6+1)).animate({opacity: 'show'},100*(Math.random()%6+1),function(){
                obj.removeClass("dice_s").addClass("dice_e");
            }).delay(100*(Math.random()%6+1)).animate({left:'-2px',top:'2px'},100*(Math.random()%6+1),function(){
                obj.removeClass("dice_e").addClass("dice_"+num);
                obj.css('cursor','pointer');
                $("#dice_mask").remove();//remove mask
            });
        }
    </script>
</body>
</html>
