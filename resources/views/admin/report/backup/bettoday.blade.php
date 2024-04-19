@extends('admin.admin-template')
@section('content')

	@include('admin.report.bet_item',['type_page' => 'cxl'])

@endsection

@section('js_call')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
	<script src="/assets/admin/js/user.js"></script>
	<script src="/assets/admin/js/report.js?v=1.01111"></script>
@endsection
