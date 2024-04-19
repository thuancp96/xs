@extends('frontend_mobile.mobile-template')
@section('content')
    <?php
    $now = \Carbon\Carbon::now();
    $yesterday = date('Y-m-d', time()-86400);
    if(intval(date('H') )<19)
        $rs = xoso::getKetQua(1,$yesterday);
    else
        $rs = xoso::getKetQua(1,date('Y-m-d'));
    ?>

    <div id="game-play" class="panel panel-flat">
        <div class="panel-heading">
            <div class="control-box">
                <input type="text" class="datepicker" placeholder="{{date('d-m-Y')}}"  id="home-datepicker"/>
                <a href="javascript:void(0)" class="btn btn-danger" id="btn_view_kqsx">Xem</a>
            </div>
        </div>
        <div class="panel-body">
            <div id="div_kqsx">
                @if(count($rs)>0)
                    <h5 class="table-header">Xổ số {{$rs['location']}} ngày {{$rs['date']}}</h5>

                    <table class="table table-striped table-bordered table-result" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th colspan="2" style="font-weight: bold; text-align: left; padding-left: 10px;">
                                Kết quả xổ số Miền Bắc</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width: 70px;">Đặc Biệt</td>
                            <td><div class="col-xs-12">{{$rs['DB']}}</div></td>
                        </tr>
                        <tr>
                            <td>Giải Nhất</td>
                            <td><div class="col-xs-12">{{$rs['1']}}</div></td>
                        </tr>
                        <tr>
                            <td>Giải Nhì</td>
                            <td>
                                @foreach($rs['2'] as $item)
                                        <div class="col-xs-6">{{$item}}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải Ba</td>
                            <td>
                                @foreach($rs['3'] as $item)
                                    <div class="col-xs-4">{{$item}}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải Tư</td>
                            <td>
                                @foreach($rs['4'] as $item)
                                    <div class="col-xs-3">{{$item}}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải Năm</td>
                            <td>
                                @foreach($rs['5'] as $item)
                                    <div class="col-xs-4">{{$item}}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải Sáu</td>
                            <td>
                                @foreach($rs['6'] as $item)
                                    <div class="col-xs-4">{{$item}}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải Bảy</td>
                            <td>
                                @foreach($rs['7'] as $item)
                                    <div class="col-xs-3">{{$item}}</div>
                                @endforeach
                            </td>
                        </tr>
                        @if(count($rs['8']) > 0)
                            <tr>
                                <td>Giải tám</td>
                                <td>
                                    @foreach($rs['8'] as $item)
                                        <div class="col-xs-3">{{$item}}</div>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                @else
                    Chưa có kết quả
                @endif
            </div>
        </div>
    </div>
    <input type="hidden" id="url_kqsx" value="{{url('/kqsx-by-day')}}">
@endsection