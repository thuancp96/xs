@extends('admin.admin-template')

@section('content')

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Quản lý bộ số</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Quản lý Kiểu cược</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
			  <?php
				$i=0;
				?>
        <div class="" style="background-color:white">
			  	@foreach($bosos as $boso)
				  
				
        <!-- left column -->
        
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <!-- general form elements disabled -->
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Bộ {{$i++}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div role="form">
                <!-- text input -->
                <div class="form-group">
                  <label>Ký hiệu thay thế</label>
                  <div class="row">
                    <div class=" col-md-8">
                    <input id='kyhieu{{$boso->id}}' type="text" class="form-control" placeholder="Enter ..." value="{{$boso->kyhieu}}">
                    </div>
                    <div class="col-md-4">
                      <button class="btn btn-default" onClick="ChangeBoso({{$boso->id}},1)">Xóa</button>
                      <button class="btn btn-info" onClick="ChangeBoso({{$boso->id}},0)" >Lưu</button>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Bộ số</label>
                  <div class="row">
                    <div class="col-md-12">
                      <input id='boso{{$boso->id}}' type="text" class="form-control col-md-4" placeholder="Enter ..." value="{{$boso->boso}}">
                    </div>
                  </div>
                </div>
				<!-- <div class="box-footer"> -->
                <!-- <button class="btn btn-default" onClick="ChangeBoso({{$boso->id}},1)">Xóa</button>
                <button class="btn btn-info pull-right" onClick="ChangeBoso({{$boso->id}},0)" >Lưu thay đổi</button> -->
              <!-- </div> -->
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
	  <!-- </br> -->
	  @endforeach
    </div>
    
	  <div class="row">
    
        <!-- left column -->
        
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-8">
          <!-- general form elements disabled -->
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">
              </br>
    <p>------------------------------------------------------</p>
    </br>
    Thêm mới Bộ</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form">
                <!-- text input -->
                <div class="form-group">
                  <label>Ký hiệu thay thế</label>
                  <div class="row">
                    <div class="col-md-8">
                      <input id='kyhieu0' type="text" class="form-control" placeholder="Enter ...">
                    </div>
                    <div class="col-md-4">
                      <button class="btn btn-info" onClick="ChangeBoso(0,0)" >Lưu</button>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Bộ số</label>
                  <input id='boso0' type="text" class="form-control" placeholder="Enter ...">
                </div>
				<!-- <div class="box-footer"> -->
                <!-- <button class="btn btn-info pull-right" onClick="ChangeBoso(0,0)" >Lưu thay đổi</button> -->
              <!-- </div> -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
	  </br>

              </div>
              <div class="tab-pane" id="tab_2">
              <?php
				$i=0;
				?>
        <div class="" style="background-color:white">
			  	@foreach($kyhieus as $kyhieu)
				  
				<?php
          $strKieucuoc = $kyhieu->kyhieu;
        ?>
        <!-- left column -->
        
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <!-- general form elements disabled -->
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Kiểu cược {{$i++}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div role="form">
                <!-- text input -->
                <div class="form-group">
                  <label>Ký hiệu thay thế</label>
                  <div class="row">
                    <div class=" col-md-8">
                    <input id='kyhieu{{$kyhieu->id}}' type="text" class="form-control hidden" placeholder="Enter ..." value="{{$kyhieu->kyhieu}}">
                    <input type="text" class="form-control" value="{{$strKieucuoc}}" disabled>
                    </div>
                    <div class="col-md-4">
                      <button class="btn btn-default" onClick="ChangeBoso({{$kyhieu->id}},1)">Xóa</button>
                      <button class="btn btn-info" onClick="ChangeBoso({{$kyhieu->id}},0)" >Lưu</button>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Kiểu Cược</label>
                  <div class="row">
                    <div class="col-md-12">
                      <input id='boso{{$kyhieu->id}}' type="text" class="form-control col-md-4" placeholder="Enter ..." value="{{$kyhieu->boso}}">
                    </div>
                  </div>
                </div>
				
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
	  <!-- </br> -->
	  @endforeach
    </div>
    
	  <div class="row">
        
      </div>
	  </br>

              </div>
              
              </div>
            </div>
</div>

<script type="text/javascript">

function ChangeBoso(id,isdelete){
		// alert($('#kyhieu'+id).val());

		$_token = "{{ csrf_token() }}";
				$.ajax({
					url: "{{url('/qlboso')}}",
					method: 'POST',
					dataType: 'html',
					data: {
						kyhieu: $('#kyhieu'+id).val(),
						boso: $('#boso'+id).val(),
						id: id,
						isdelete:isdelete,
						_token: $_token,
					},
					success: function(data)
					{
						if(data == "false")
						{
							// $("#btn_notify_failed" ).click();
							alert('False:', data);
						}
						if(data == "true")
						{
							// $("#btn_notify_success" ).click();
							// console.log('Success:', data);
							window.location.href = "{{url('/qlboso')}}";
						}
					},
					error: function (data) {
						console.log('Error:', data);
					}
				});
	}
		
jQuery(document).ready(function($)
{		
});
</script>

@endsection