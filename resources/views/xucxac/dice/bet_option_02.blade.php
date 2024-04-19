<?php

?>
<div class="row">
    <?php $array = [
        4 => 60,
        5 => 30,
        6 => 17,
        7 => 12,
        8 => 8,
        9 => 6,
        10 => 6,
        11 => 6,
        12 => 6,
        13 => 8,
        14 => 12,
        15 => 17,
        16 => 30,
        17 => 60,
    ]?>
    @for($i = 4; $i<18; $i++)
        <div class="dice-content sum">
            <div class="dice-group" id="sum{!! $i !!}">
                <p><strong>{!! $i !!}</strong>
                    <br>
                    1 WINS {!! $array[$i] !!}</p>
            </div>
        </div>
    @endfor
</div>
