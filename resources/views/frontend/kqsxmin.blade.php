@if(count($rs)>0)
<!-- <div class="panel panel-color panel-inverse hiddens kqxsmin"> -->
<div class="panel-heading recent-heading">
    <h3 class="panel-title">Kết quả ngày <span class="badge badge-blue">{{$rs['date']}}</span></h3>
    <span id="spec_character" class="badge badge-blue">{{$rs['spec_character']}}</span>
</div>
<div class="panel-body">
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>ĐB</b></div>
        <div class="col-md-10 col-10">
            <div class="col-md-10 col-10 jackpot"><span class="badge badge-blue">{{$rs['DB']}}</span></div>
        </div>
    </div>
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>G1</b></div>
        <div class="col-md-10 col-10">
            <div class="col-md-10 col-10 first"><span  id="firstqq" class="badge badge-blue">{{$rs['1']}}</span></div>
        </div>
    </div>
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>G2</b></div>
        <div class="col-md-10 col-10">
            @foreach($rs['2'] as $item)
                <div class="col-md-6 col-6 second1st"><span class="badge badge-blue">{{$item}}</span></div>
            @endforeach
        </div>
    </div>
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>G3</b></div>
        <div class="col-md-10 col-10">
            @foreach($rs['3'] as $item)
                <div class="col-md-4 col-4 third3rd"><span class="badge badge-blue">{{$item}}</span></div>
            @endforeach
        </div>
    </div>
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>G4</b></div>
        <div class="col-md-10 col-10">
            @foreach($rs['4'] as $item)
                <div class="col-md-3 col-3 fourth4th"><span class="badge badge-blue">{{$item}}</span></div>
            @endforeach
        </div>
    </div>
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>G5</b></div>
        <div class="col-md-10 col-10">
            @foreach($rs['5'] as $item)
                <div class="col-md-4 col-4 fiveth6th"><span class="badge badge-blue">{{$item}}</span></div>
            @endforeach

        </div>
    </div>
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>G6</b></div>
        <div class="col-md-10 col-10">
            @foreach($rs['6'] as $item)
                <div class="col-md-4 col-4 sixth3rd"><span class="badge badge-blue">{{$item}}</span></div>
            @endforeach
        </div>
    </div>
    <div class="row" style="margin:2px;">
        <div class="col-md-2 col-2"><b>G7</b></div>
        <div class="col-md-10 col-10">
            @foreach($rs['7'] as $item)
                <div class="col-md-3 col-3 seventh4th"><span class="badge badge-blue">{{$item}}</span></div>
            @endforeach
        </div>
    </div>
</div>
<!-- </div> -->
@else
    Chưa có kết quả
@endif
