<!DOCTYPE html>
<html lang="en" style="height: 100%;margin: 0;">


<head>
</head>

<body style="height: 100%;margin: 0;">
	<style>
		.iframe_html,
		.iframe_body {
			height: 100%;
			margin: 0;
		}

		.h_iframe {
			position: relative;
		}

		.h_iframe .ratio {
			display: block;
			width: 1300px;
			height: 100vh;
		}

		.h_iframe iframe {
			position: absolute;
			top: 0;
			left: 0;
			width: 1300px;
			height: 100%;
		}
	</style>
	<div class="wrapper">
		<div class="h_iframe">
			<img class="ratio" src="http://placehold.it/16x9" />
			<iframe src="{{$url_target}}" frameborder="0" allowfullscreen></iframe>
		</div>
	</div>
</body>

</html>