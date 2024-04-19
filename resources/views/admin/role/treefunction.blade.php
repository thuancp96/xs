@foreach ($chucnangs as $chucnang)
	<div class="row">
		<div class="col-md-12">
			<div class="checkbox checkbox-primary">
				@if(strpos($function, ",".$chucnang['code'].",") !== false)
					<input id="{{$chucnang['code']}}" checked type="checkbox" disabled>
				@else
					<input id="{{$chucnang['code']}}" type="checkbox" disabled>
				@endif

				<label for="checkbox2">
					{{$chucnang['name']}}
				</label>
			</div>
		</div>
	</div>
	@foreach ($chucnang['children'] as $item)
		<div class="row">
			<div class="col-md-12" >
				<div class="form-group">
					<div style="padding-left: 20px">
						<div class="checkbox checkbox-primary">
							@if(strpos($function,",".$item['code'].",") !== false)
								<input id="{{$item['code']}}" checked type="checkbox" disabled>
							@else
								<input id="{{$item['code']}}" type="checkbox" disabled>
							@endif
							<label for="checkbox2">
								{{$item['name']}}
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
@endforeach
