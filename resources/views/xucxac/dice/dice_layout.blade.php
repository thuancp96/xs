<?php

$pos = $_data['pos'];
        ?>
<div class="dice-content">
@foreach($pos as $row)
    <?php $i = intval(12/count($row)); ?>
    @foreach($row as $column)
        <div class="col-xs-{!! $i !!} no-padding">
            <?php $strPath = 'frontend.dice.dice_0'.$column; ?>
            @include($strPath)
        </div>
    @endforeach
@endforeach
</div>