@if(count($rs)>0)
<h2 class="table-header">Xổ số {{$rs['location']}} - {{GameHelpers::ChuyenDoiDaiByDate($slug,strtotime($rs['date']))}} ngày <span class="badge badge-blue">{{$rs['date']}}</span></h2>
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
                                    <span class="badge badge-blue">{{$rs['2']}}</span> <span class="split"> </span>
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
                                    <span class="badge badge-blue">{{$rs['5']}}</span> <span class="split"> </span>
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
                                    <span class="badge badge-blue">{{$rs['7']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tám</td>
                            <td>
                                    <span class="badge badge-blue">{{$rs['8']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                    </table>

                    

                @else
                    Chưa có kết quả
                @endif
