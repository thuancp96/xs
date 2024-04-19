@extends("frontend.frontend-template_extend_game")
@section('content')
<style>
#MainSabaGame {
    width:100vw; 
    height: calc(100vh - 56px);
    position: relative;
    margin-bottom: -7px;
    min-height: 575px !important;
    max-height: calc(100dvh - 56px);
    min-width: 275px !important;
    max-width: 100dvw;
    background-color: white;
    }
</style>
<input type="hidden" id="url" value="{{url('/7zball')}}">
<iframe id="MainSabaGame"></iframe>
<script>
    
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('MainSabaGame').src = "/api/7zball";
	});

    document.querySelector("#MainSabaGame").addEventListener( "load", function(e) {
        $('.container.white-bg').show(); 
        $('.loadere').hide();
    });
</script>
@endsection