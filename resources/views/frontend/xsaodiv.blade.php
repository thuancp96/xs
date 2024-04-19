<?php
    $now = date('Y-m-d');
    $hour = date('H');
    $min = date('i');
    $sec = date('s');
?>

        <?php
            $rs = xoso::getKetQuaXSA(4,$hour,$now);
        ?>
            @if (isset($rs) && count($rs)>0)
                <h2 class="table-header">{{$rs['location']}} ngày <span class="badge badge-blue">{{$rs['date']}}</span> Kỳ {{$hour}} <span class="badge badge-blue">Đặt cược {{$hour-1}}:45 - {{$hour}}:14</span>  <span class="badge badge-blue">Quay thưởng {{$hour}}:15 - {{$hour}}:25</span></h2>
                    <table class="table table-striped">
                        <tr>
                            <td>Giải đặc biệt</td>
                            @if ($min>22 || $hour > $hour)
                                <td><span class="badge badge-blue" style="background-color: red;">{{$rs['DB']}}</span></td>
                            @elseif ($min==22)
                                @if ($sec >=30)
                                    <td><span class="badge badge-blue" style="background-color: red;">{{$rs['DB']}}</span></td>
                                    @else <td><span class="badge badge-blue" style="background-color: red;">-----</span> <span class="split"> </span></td>
                                    @endif
                            @else
                                <td><span class="badge badge-blue" style="background-color: red;">-----</span> <span class="split"> </span></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Giải nhất</td>
                            @if ($min>15 || $hour > $hour)
                                <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                            @elseif ($min==15)
                                @if ($sec >=30)
                                    <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                                @else <td><span class="badge badge-blue">-----</span> <span class="split"> </span></td>
                                    @endif
                            @else
                                <td><span class="badge badge-blue">-----</span> <span class="split"> </span></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Giải nhì</td>
                            <td>
                                    @if ($min>16 || $hour > $hour)
                                        @foreach($rs['2'] as $hourtem)
                                            <span class="badge badge-blue">{{$hourtem}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==16)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['2'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['2'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['2'] as $hourtem)
                                            <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải ba</td>
                            <td>
                                @if ($min>17 || $hour > $hour)
                                        @foreach($rs['3'] as $hourtem)
                                            <span class="badge badge-blue">{{$hourtem}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==17)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['3'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=10)
                                            <span class="badge badge-blue">{{$rs['3'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=20)
                                            <span class="badge badge-blue">{{$rs['3'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['3'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=40)
                                            <span class="badge badge-blue">{{$rs['3'][4]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=50)
                                            <span class="badge badge-blue">{{$rs['3'][5]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['3'] as $hourtem)
                                            <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tư</td>
                            <td>
                                @if ($min>18 || $hour > $hour)
                                        @foreach($rs['4'] as $hourtem)
                                            <span class="badge badge-blue">{{$hourtem}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==18)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['4'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=15)
                                            <span class="badge badge-blue">{{$rs['4'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['4'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=45)
                                            <span class="badge badge-blue">{{$rs['4'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['4'] as $hourtem)
                                            <span class="badge badge-blue">----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải năm</td>
                            <td>
                                @if ($min>19 || $hour > $hour)
                                        @foreach($rs['5'] as $hourtem)
                                            <span class="badge badge-blue">{{$hourtem}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==19)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['5'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=10)
                                            <span class="badge badge-blue">{{$rs['5'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=20)
                                            <span class="badge badge-blue">{{$rs['5'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['5'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=40)
                                            <span class="badge badge-blue">{{$rs['5'][4]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=50)
                                            <span class="badge badge-blue">{{$rs['5'][5]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['5'] as $hourtem)
                                            <span class="badge badge-blue">----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải sáu</td>
                            <td>
                                @if ($min>20 || $hour > $hour)
                                        @foreach($rs['6'] as $hourtem)
                                            <span class="badge badge-blue">{{$hourtem}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==20)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['6'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">---</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=20)
                                            <span class="badge badge-blue">{{$rs['6'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">---</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=40)
                                            <span class="badge badge-blue">{{$rs['6'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">---</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['6'] as $hourtem)
                                            <span class="badge badge-blue">---</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải bảy</td>
                            <td>
                                @if ($min>21 || $hour > $hour)
                                        @foreach($rs['7'] as $hourtem)
                                            <span class="badge badge-blue">{{$hourtem}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==21)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['7'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=15)
                                            <span class="badge badge-blue">{{$rs['7'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['7'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=45)
                                            <span class="badge badge-blue">{{$rs['7'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['7'] as $hourtem)
                                            <span class="badge badge-blue">--</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        @if(count($rs['8']) > 0)
                            <tr>
                                <td>Giải tám</td>
                                <td>
                                    @if ($min>22 || $hour > $hour)
                                        @foreach($rs['8'] as $hourtem)
                                            <span class="badge badge-blue">{{$hourtem}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==22)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>
                                        @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=15)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=45)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>

                                            @endif
                                    @else
                                        @foreach($rs['8'] as $hourtem)
                                            <span class="badge badge-blue">--</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </table>
        
    @endif
        