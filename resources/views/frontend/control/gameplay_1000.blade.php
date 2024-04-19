<!-- <div class="row"> -->
	<ul class="nav nav-pills m-b-30" >
		@for($i=0;$i<10;$i++)
			<li>
							<a  class="btn btn-warning btn-custom waves-effect waves-light btn-sm" href="#{{$gamecode}}1000_{{$i}}" onclick="LoadContent1000('{{$i}}')" data-toggle="tab" aria-expanded="true" style="line-height: 24px !important;">{{$i}}</a>

				<!-- <a style="line-height: 24px !important;" class="btn btn-warning btn-custom waves-effect waves-light btn-xs" href="#1000_{{$i}}" id="1000_{{$i}}" onclick="LoadContent1000('{{$i}}')" data-toggle="tab" aria-expanded="true">{{$i}}</a> -->
			</li>
		@endfor
	</ul>
<!-- </div> -->
<div class="tab-content br-n pn">
	@for($i=0;$i<10;$i++)
		<div id="{{$gamecode}}1000_{{$i}}" class="tab-pane">
			<div class="row">
				<div class="col-md-12" id="{{$gamecode}}content_{{$i}}">

				</div>
			</div>
		</div>
	@endfor
</div>

<input type="hidden" id="current_tab">

<script type="text/javascript">

    $( document ).ready(function() {
        // LoadContentGame('A');
        // LoadContentGame('B');
        // LoadContentGame('C');
        for (var i = 10 - 1; i >= 0; i--) {
        	LoadContent1000(i+'');
        }
        
    });
</script>