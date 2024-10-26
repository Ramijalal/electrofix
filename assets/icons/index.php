<?php
require_once 'includes/funcs.inc.php';
require_once 'includes/auth.inc.php';


$htmlcontent = file_get_contents('pages/shop.html');

// Define accessible pages for each role
$guest_pages = [ 'signup', 'login'];
$seller_pages = ['dashboard'];
$admin_pages = [ 'dashboard'];


$universal_pages = ['shop', 'product','cart'];

$page = $_GET['page'] ?? 'shop';


$htmlcontent = getPageContent($page, $user_role);

function getPageContent($page, $user_role)
{
    return 'Error 404 Page Not Found!!!' .  $page;
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <title>Document</title>


</head>

<body>


    <?php include 'layouts/navbar.layout.php'; ?>
    <?php echo $htmlcontent; ?>






    <script src="js/easyRequest.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>



<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>

</html>