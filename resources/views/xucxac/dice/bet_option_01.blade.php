<?php

?>
<div class="row">
    <div class="dice-content small-big small">
        <div class="dice-group" id="small">
            <p><strong>SMALL</strong>
                <br>
                ARE NUMBERS
                <br>
                4 TO 10
                <br>
                1 WINS 1
                <br>
                LOSE IF ANY
                <br>
                TRIPPLE APPEARS
            </p>
        </div>
    </div>
    <div class="dice-content even-odd even">
        <div class="dice-group" id="even">
            <p><strong>
                    E
                    <br>
                    V
                    <br>
                    E
                    <br>
                    N
                </strong></p>
        </div>
    </div>
    <div class="dice-content double">
        @for($i = 1; $i <=3; $i++)
            <?php $strPath = 'xucxac.dice.dice_0'.$i ?>
            <div class="col-xs-4 no-padding">
                <div class="dice-group" id="double0{!! $i !!}">
                    @include($strPath)
                    @include($strPath)
                </div>
            </div>
        @endfor
    </div>

    <div class="dice-content tripple">
        @for($i = 1; $i <=3; $i++)
            <div class="dice-group" id="tripple0{!! $i !!}">
                <?php $strPath = 'xucxac.dice.dice_0'.$i ?>
                @for($j = 1; $j <=3; $j++)
                    <div class="col-xs-4 no-padding">
                        @include($strPath)
                    </div>
                @endfor
            </div>
        @endfor
    </div>

    <div class="dice-content tripple-random">
        <div class="dice-group" id="tripple-random">
            @for($i = 1; $i <=6; $i++)
                <?php $strPath = 'xucxac.dice.dice_0'.$i ?>
                @for($j = 1; $j <=3; $j++)
                    <div class="col-xs-4 no-padding">
                        @include($strPath)
                    </div>
                @endfor
            @endfor
        </div>
    </div>

    <div class="dice-content tripple">
        @for($i = 4; $i <=6; $i++)
            <div class="dice-group" id="tripple0{!! $i !!}">
                <?php $strPath = 'xucxac.dice.dice_0'.$i ?>
                @for($j = 1; $j <=3; $j++)
                    <div class="col-xs-4 no-padding">
                        @include($strPath)
                    </div>
                @endfor
            </div>
        @endfor
    </div>

    <div class="dice-content double">
        @for($i = 4; $i <=6; $i++)
            <?php $strPath = 'xucxac.dice.dice_0'.$i ?>
            <div class="col-xs-4 no-padding">
                <div class="dice-group" id="double0{!! $i !!}">
                    @include($strPath)
                    @include($strPath)
                </div>
            </div>
        @endfor
    </div>
    <div class="dice-content even-odd odd">
        <div class="dice-group" id="odd">
            <p><strong>
                    O
                    <br>
                    D
                    <br>
                    D
                </strong></p>
        </div>
    </div>
    <div class="dice-content small-big big">
        <div class="dice-group" id="big">
            <p><strong>BIG</strong>
                <br>
                ARE NUMBERS
                <br>
                11 TO 17
                <br>
                1 WINS 1
                <br>
                LOSE IF ANY
                <br>
                TRIPPLE APPEARS
            </p>
        </div>
    </div>

</div>
