<!doctype html>
<html lang="en">
	
	<head>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Maths Learning</title>
		<noscript id="deferred-styles">
			<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300">
		</noscript>
		<link rel="stylesheet" href="main.css"> <!--REMEMBER TO OPTIMISE THIS LATER-->
		<!--
		<style>
body{margin:0;overflow:hidden}*{outline:none;box-sizing:border-box}#toolbar{font-family:"Open Sans",verdana,sans-serif;position:fixed;height:100px;width:100%}div.half{height:50px;line-height:50px;border-bottom:1px solid #aaa}#url{border:none;height:49px;font-size:30px;width:calc(100% - 50px);font-weight:300;padding:5px;font-family:"Open Sans",verdana;color:#aaa;transition:.3s}#url:focus,#url:hover{color:#000;transition:.3s}#refresh,toggle{font-size:30px;line-height:50px;cursor:pointer;transition:.3s}#refresh{width:50px;border:none;border-left:1px solid #aaa;background-color:#fff;color:#000;height:49px;float:right}#refresh:hover{background-color:#000}toggle{float:left;padding:0 5px;height:50px;border-right:1px solid #aaa}#refresh:hover,toggle:hover{color:#fff;transition:.3s}toggle.disabled{background-color:#f33;transition:.3s}toggle.enabled{background-color:#3f3;transition:.3s}#frame{border:none;width:100%;height:calc(100vh - 100px);margin-top:100px}
		</style>
		-->
		
	</head>
	
	<body>
		
		<div id="toolbar">
			<div class="half">
				<div placeholder="URL" id="url" contenteditable="true"></div>
				<button id="refresh" class="toolbutton unselectable">⟳</button>
				<button id="forward" class="toolbutton unselectable">→</button>
				<button id="back" class="toolbutton unselectable">←</button>
			</div>
			<div class="half">
				<toggle id="toggleScripts" class="unselectable disabled" data-tooltip="Scripts are what makes many websites functional. It's how sites like Facebook and Twitter send and retrieve messages and posts, among many other actions. However, scripts are often used for tracking, and some are malicious.">Scripts</toggle>
				<toggle id="toggleCookies" class="unselectable disabled" data-tooltip="Cookies are small pieces of text that are stored on your computer and are used by websites to identify your computer, usually for the purpose of remaining logges in between page navigation. However, they are sometimes used by advertisers to track your browsing habits across websites.">Cookies</toggle>
				<toggle id="toggleObjects" class="unselectable disabled" data-tooltip="Objects include plugin content, like Flash player and Java. Most games that are played online use Flash player. However, they are relatively insecure and can put a burden on a slower machine. In addition, they are nearly impossible for a proxy like this one to control.">Objects</toggle>
			</div>
		</div>
		
		<iframe id="frame">
		Your browser does not support IFrames. Seriously, what the hell kind of browser are you using?
		</iframe>
		
		<script type="text/javascript">
      var loadDeferredStyles = function() {
        var addStylesNode = document.getElementById("deferred-styles");
        var replacement = document.createElement("div");
        replacement.innerHTML = addStylesNode.textContent;
        document.body.appendChild(replacement)
        addStylesNode.parentElement.removeChild(addStylesNode);
      };
      var raf = requestAnimationFrame || mozRequestAnimationFrame ||
          webkitRequestAnimationFrame || msRequestAnimationFrame;
      if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
      else window.addEventListener('load', loadDeferredStyles);
		</script>
		
		<script src="main.js" async></script>
		<?php
		/*
		if (MINIFY) {
			echo '<script src="main-min.js" async></script>';
		} else {
			echo '<script src="main.js" async></script>';
		}
		*/
		?>
		
	</body>
	
</html>