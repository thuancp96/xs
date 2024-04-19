<?php

        ?>
<div class="row">
    <div class="dice-content couple-label">
        <div class="couple-text">
            <p><strong>
                    ON EACH 2 DICE
                    <br>
                    COMBINATION
                    1 WINS 6
                </strong></p>
            <i class="fa fa-long-arrow-right fa-5x fa-pull-right" aria-hidden="true"></i>
        </div>
    </div>
    @for($i = 1; $i < 6; $i++)
        @for($j = $i+1; $j <= 6; $j++)
            <div class="dice-content couple">
                <div class="dice-group" id="couple{!! $i.$j !!}">
                    <?php $strPath = 'xucxac.dice.dice_0'.$i ?>
                    @include($strPath)
                    <p><strong>{!! $i !!} AND {!! $j !!}</strong></p>
                    <?php $strPath = 'xucxac.dice.dice_0'.$j ?>
                    @include($strPath)
                </div>
            </div>
        @endfor
    @endfor
</div>
