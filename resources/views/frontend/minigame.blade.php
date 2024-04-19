@extends("frontend.frontend-template_extend_game")
@section('content')
<style>
#MainSabaGame {
    width:100%; 
    height: calc(100vh - 56px);
    position: relative;
    margin-bottom: -7px;
    min-height: 575px !important;
    background-color: white;
    }
</style>
<input type="hidden" id="url" value="{{url('/minigame')}}">
<iframe id="MainSabaGame"></iframe>
    <a  id="btnClickSaba" onclick='window.location.href = "/api/minigame"' style='display: none'></a>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        $('.container.white-bg').hide(); $('.loadere').show();isloaded = true;
        document.getElementById('MainSabaGame').src = "/api/minigame";
	});

    document.querySelector("#MainSabaGame").addEventListener( "load", function(e) {
        $('.container.white-bg').show();
            $('.loadere').hide();
    });

</script>

@endsection