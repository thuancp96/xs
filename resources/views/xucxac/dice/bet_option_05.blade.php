<?php

        ?>
<div class="row">
    <?php $array = ['ONE','TWO','THREE','FOUR','FIVE','SIX'] ?>
    @for($i=0;$i<6;$i++)
        <?php $strPath = 'xucxac.dice.dice_0'.($i+1) ?>
        <div class="dice-content single">
            <div class="dice-group" id="single0{!! $i !!}">
                <p>{!! $array[$i] !!}</p>
                @include($strPath)
            </div>
        </div>
    @endfor
</div>
