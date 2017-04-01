<!doctype html>
<html lang="en">
	
	<head>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Maths Learning</title>
		<noscript id="deferred-styles">
			<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300" type="text/css">
		</noscript>
		<link rel="stylesheet" href="main.css" type="text/css">
		
	</head>
	
	<body>
		
		<div id="toolbar">
			<div class="half">
				<div id="URLwrap">
					<div placeholder="URL" id="url" contenteditable="true"></div>
				</div>
				<button id="refresh" class="toolbutton unselectable">⟳</button>
				<button id="forward" class="toolbutton unselectable">→</button>
				<button id="back" class="toolbutton unselectable">←</button>
			</div>
			<div class="half">
				<toggle id="toggleScripts" class="unselectable disabled" data-tooltip="Scripts are what makes many websites functional. It's how sites like Facebook and Twitter send and retrieve messages and posts, among many other actions. However, scripts are often used for tracking, and some are malicious.">Scripts</toggle>
				<toggle id="toggleCookies" class="unselectable disabled" data-tooltip="Cookies are small pieces of text that are stored on your computer and are used by websites to identify your computer, usually for the purpose of remaining logged in between page navigation. However, they are sometimes used by advertisers to track your browsing habits across websites.">Cookies</toggle>
				<toggle id="toggleObjects" class="unselectable disabled" data-tooltip="Objects include plugin content, like Flash player and Java. Most games that are played online use Flash player. However, they are relatively insecure and can put a burden on a slower machine. In addition, they are nearly impossible for a proxy like this one to control.">Objects</toggle>
			</div>
		</div>
		
		<iframe id="frame">
		Your browser does not support IFrames.
		</iframe>
		
		<script type="text/javascript">
		var loadDeferredStyles = function() {
			var addStylesNode = document.getElementById("deferred-styles");
			var replacement = document.createElement("div");
			replacement.innerHTML = addStylesNode.textContent;
			document.body.appendChild(replacement)
			addStylesNode.parentElement.removeChild(addStylesNode);
		};
		var raf = requestAnimationFrame || mozRequestAnimationFrame || webkitRequestAnimationFrame || msRequestAnimationFrame;
		if (raf) {
			raf(function() { window.setTimeout(loadDeferredStyles, 0); });
		} else if (window.addEventListener) {
			window.addEventListener('load', loadDeferredStyles);
		} else if (window.attachEvent) {
			window.attachEvent('onload', loadDeferredStyles);
		} else {
			window.onload = loadDeferredStyles;
		}
		</script>
		
		<script src="main.js" async></script>
		
	</body>
	
</html>
