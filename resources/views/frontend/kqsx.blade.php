@if(count($rs)>0)
    <h2 class="table-header">Xổ số {{$rs['location']}} ngày <span class="badge badge-blue">{{$rs['date']}}</span></h2>
                    <table class="table table-striped">
                        <tr>
                            <td>Giải đặc biệt</td>
                            <td><span class="badge badge-blue">{{$rs['DB']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhất</td>
                            <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhì</td>
                            <td>
                                @foreach($rs['2'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải ba</td>
                            <td>
                                @foreach($rs['3'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tư</td>
                            <td>
                                @foreach($rs['4'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải năm</td>
                            <td>
                                @foreach($rs['5'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải sáu</td>
                            <td>
                                @foreach($rs['6'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải bảy</td>
                            <td>
                                @foreach($rs['7'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>

                        <tr>
                            <td><h5>Xổ số điện toán - Thần tài</h5></td>
                            <td>
                                    <span class="badge badge-blue">{{$rs['than_tai']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                        
                    </table>

                    <h2 class="table-header">Kết quả rút gọn <span class="badge badge-blue">{{$rs['date']}}</span></h2>
                    <div class="row" style="margin:2px;">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-2">Đầu</th>
                                        <th class="col-md-12">Lô tô</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                for($i=0;$i<=9;$i++){
                                    $strketqua = '';
                                    for($j=0;$j<=9;$j++){
                                        $mau = $i.$j;
                                        $count=0;
                                        foreach($rs as $ketquafull){
                                            if ($count>2 && $count<11){
                                                // print_r($ketquafull);
                                                if (count($ketquafull)==1 ){
                                                    $ketqua2so = substr($ketquafull,-2);
                                                    // echo $ketqua2so.' ';
                                                    if ($mau == $ketqua2so)// && !(strpos($strketqua,$mau)!== false))
                                                        $strketqua.=$mau.'; ';
                                                }else
                                                foreach($ketquafull as $ketquatungso){
                                                    $ketqua2so = substr($ketquatungso,-2);
                                                    // echo $ketqua2so.' ';
                                                    if ($mau == $ketqua2so)// && !(strpos($strketqua,$mau)!== false))
                                                        $strketqua.=$mau.'; ';
                                                }
                                            }
                                            $count++;
                                        }
                                    }
                                    echo '<tr>
                                    <td class="col-md-2" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$i.'
                                    </td>
                                    <td class="col-md-12" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$strketqua.'
                                    </td>
                                    </tr>';
                                }
                                ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                        <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Đuôi</th>
                                        <th>Lô tô</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                for($i=0;$i<=9;$i++){
                                    $strketqua = '';
                                    for($j=0;$j<=9;$j++){
                                        $mau = $j.$i;
                                        $count=0;
                                        foreach($rs as $ketquafull){
                                            if ($count>2 && $count<11){
                                                // print_r($ketquafull);
                                                if (count($ketquafull)==1 ){
                                                    $ketqua2so = substr($ketquafull,-2);
                                                    // echo $ketqua2so.' ';
                                                    if ($mau == $ketqua2so)// && !(strpos($strketqua,$mau)!== false))
                                                        $strketqua.=$mau.'; ';
                                                }else
                                                foreach($ketquafull as $ketquatungso){
                                                    $ketqua2so = substr($ketquatungso,-2);
                                                    // echo $ketqua2so.' ';
                                                    if ($mau == $ketqua2so)// && !(strpos($strketqua,$mau)!== false))
                                                        $strketqua.=$mau.'; ';
                                                }
                                            }
                                            $count++;
                                        }
                                    }
                                    echo '<tr>
                                    <td class="col-md-2" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$i.'
                                    </td>
                                    <td class="col-md-12" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$strketqua.'
                                    </td>
                                    </tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
@else
    Chưa có kết quả
@endif
