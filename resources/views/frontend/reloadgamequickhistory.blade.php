<br>
<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
	<thead>
		<tr>
		<th style="width:5%">STT</th>
		<th style="width:15%">Thời gian</th>
		<th style="width:40%">Nội dung cược nhanh</th>
		<th style="width:40%">Nội dung cược hủy</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = count($quickplayhistory);
		?>
		@for($i=0;$i< count($quickplayhistory);$i++) <tr>
			<td width='50px'>{{$count--}}</td>
			<td width='150px'>{{explode(" ",$quickplayhistory[$i]->created_at)[1] }}</td>
			<td id="quickbeth{{$i}}" style="word-break: break-all; user-select: text;" onclick="selectTextOnly('quickbeth{{$i}}')">{{$quickplayhistory[$i]->content}}</td>
			<td id="quickbeth{{$i}}-2" style='color: red;user-select: text;' onclick="selectTextOnly('quickbeth{{$i}}-2')">{{$quickplayhistory[$i]->cancel}}</td>
			</tr>
			@endfor
	</tbody>
	<tfoot>
		<tr>
		</tr>
	</tfoot>
	</div>