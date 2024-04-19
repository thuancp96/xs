<div class="row" style="
    overflow: auto;
">
	@if($game['game_code']!=8 && $game['game_code']!=308 && $game['game_code']!=17 && $game['game_code']!=317
	&& $game['game_code']!=408 && $game['game_code']!=417
	&& $game['game_code']!=508 && $game['game_code']!=517
	&& $game['game_code']!=608 && $game['game_code']!=617
	&& $game['game_code']!=352 && $game['game_code']!=452 && $game['game_code']!=552 && $game['game_code']!=652
	)
		<div class="col-md-12" style="
    min-width: 900px;
">@include('admin.control.number100',['game'=>$game])</div>
	@else
		<div class="col-md-12" style="
    min-width: 900px;
">@include('admin.control.number1000',['game'=>$game])</div>
	@endif
</div>
<script type="text/javascript">
	$('html').on('mouseup', function(e) {
    if(!$(e.target).closest('.popover').length) {
        $('.popover').each(function(){
            $(this.previousSibling).popover('hide');
        });
    }
});
	$('.popover-markup>.trigger').popover({
		html: true,
		placement: 'auto right',
		title: function () {
			return $(this).parent().find('.head').html();
		},
		content: function () {
			return $(this).parent().find('.content').html();
		}
	});
	$(document).ready(function() {
		var $container = $('.portfolioContainer');
		$container.isotope({
			filter: '*',
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false
			}
		});
		$('.portfolioFilter a').click(function(){
			$('.port').show();
			$('.portfolioFilter .current').removeClass('current');
			$(this).addClass('current');
			var selector = $(this).attr('data-filter');
			$container.isotope({
				filter: selector,
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
			return false;
		});
		$('.port').hide();
	});
</script>
