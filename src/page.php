<?php

/*
 * TODO HERE:
 * - add functionality for cookies
 * - keep testing and tidying things up
*/

require_once("config.php");

// REVIEW: use relative directories properly you idiot
require_once(__DIR__ . "/../lib/simple_html_dom.php");
require_once(__DIR__ . "/../lib/helper.php");

$requestGETData = (object) [
	"url" => base64_decode($_GET["url"]),
	"scripts" => $_GET["scripts"],
	"cookies" => $_GET["cookies"],
	"objects" => $_GET["objects"],
	"base" => base64_decode($_GET["base"])
];

info("info", "URL: " . $requestGETData->url);
$reqMimeType = mime_type($requestGETData->url);
info("info", "Content-type: " . $reqMimeType);

$convertURL = function ($original) use ($requestGETData) {
	
	info("info", "Converting " . $original);
	$parsedurl = parse_url($original);
	
	if ($requestGETData->base) {
		// if the request is to a root directory, make sure two slashes don't go next to eachother
		if (substr($original, -1, 1) === "/") {
			$original = $requestGETData->base . substr($original, 1);
		} else {
			$original = $requestGETData->base . $original;
		}
	} else {
		// adds a scheme if there isn't one already (REVIEW: is this necessary?)
		if (strlen($parsedurl->scheme) < 1) {
			$original = "http:" + $original;
		}
	}
	
	$convTo = "page.php?url=" . urlencode(base64_encode($original)) .
	"&scripts=" . $requestGETData->scripts .
	"&cookies=" . $requestGETData->cookies .
	"&objects=" . $requestGETData->objects .
	"&base=" . urlencode($requestGETData->base ? base64_encode($requestGETData->base) : $requestGETData->url);
	// the base is used to convert relative urls to absolute ones, the initial requests won't have a base but the subsequent ones will
	
	info("info", "<b>Converted to</b> " . $convTo);
	//info("info", "<b>With raw url of:</b> " . $original . " <b>and base of</b> " . $requestGETData->base);
	return $convTo;
	
};

$processJS = function ($code) use ($convertURL) {
	
	info("info", "<b>Processing JS:</b> " . $code);
	
	$process = $code;
	
	$process = preg_replace_callback(
		"#(src\s*=\s*[\"\'])(.+)([\"|\'])#",
		function ($m) use ($convertURL) {
			info("info", "<b>Found URL</b>");
			return $m[1];
		},
		$process
	);
	$process = preg_replace_callback(
		"#(href\s*=\s*[\"\'])(.+)([\"|\'])#",
		function ($m) use ($convertURL) {
			info("info", "<b>Found URL</b>");
			return $m[1];
		},
		$process
	);
	
	return $process;
	
};
$processCSS = function ($code) use ($convertURL) {
	
	info("info", "<b>Processing CSS</b> with " . strlen($code) . " length");
	
	return preg_replace_callback(
		"#(url\([\"|\']?)([\w\s/.&=%?]+)(?=[\"|\']?\);?)#",
		function ($m) use ($convertURL) {
			$replace = $convertURL(str_replace(array('"', "'", "(", ")"), '', $m[2])) . "'"; // the str_replace is for removing quotes and brackets around the urls
			info("info", "<b>Found URL:</b> $m[2], replacing with $replace");
			return "url('" . $replace;
		},
		$code
	);
	
};

$file = file_get_contents($requestGETData->url);
if (isset($file) && $file !== null) {
	info("info", "Page retrieved successfully<br>");
} else {
	echo file_get_contents("errordocs/failed_page_loading.php");
	die();
}

$mime_type_whitelist = [
	"image/png",
	"image/gif",
	"image/x-icon",
	"image/jpeg",
	"image/svg+xml",
	"application/json",
	"text/xml",
	"application/xml",
];
$mime_type_objectlist = [
	"application/x-shockwave-flash"
];

switch ($_SERVER["REQUEST_METHOD"]) {
	case "POST":
		// TODO: find a way to handle headers
		break;
	case "GET":
		
		if (
			$reqMimeType == "text/javascript" ||
			$reqMimeType == "application/javascript"
		) {
			
			if ($requestGETData->scripts == "false") break;
			// TODO: work on this idk
			header("Content-Type: " . $reqMimeType);
			echo $file;
			
		} else if (in_array($reqMimeType, $mime_type_objectlist)) {
			if ($requestGETData->objects == "false") break;
			header("Content-Type: " . $reqMimeType);
			echo $file;
		} else if (
			$reqMimeType == "text/css" ||
			$reqMimeType == "application/css"
		) {
			
			$file = $processCSS($file);
			header("Content-Type: " . $reqMimeType);
			echo $file;
			
		} else if (in_array($reqMimeType, $mime_type_whitelist)) {
			header("Content-Type: " . $reqMimeType);
			echo $file;
		} else {

			$html = @str_get_html($file);
			if (!$html) {
				die(file_get_contents("errordocs/failed_page_loading.php")); // REVIEW: is this good practice?
			}
			$elems = [];
			
			// convert all hrefs
			$elems = $html->find("*[href]");
			foreach ($elems as $thiselem) {
				if ($thiselem->tag !== "a") {
					$thiselem->href = $convertURL($thiselem->href);
				}
			}
			// convert all srcs
			$elems = $html->find("*[src]");
			foreach ($elems as $thiselem) {
				$thiselem->src = $convertURL($thiselem->src);
			}
			
			// convert all form actions
			$elems = $html->find("form[action]");
			foreach ($elems as $thiselem) {
				$thiselem->action = $convertURL($thiselem->action);
			}
			
			// convert all url()s in css
			$elems = $html->find("style");
			foreach ($elems as $thiselem) {
				$thiselem->innertext = $processCSS($thiselem->innertext);
			}
			
			// process in-tag css
			$elems = $html->find("*[style]");
			foreach ($elems as $thiselem) {
				$thiselem->style = $processCSS($thiselem->style);
			}
			
			if ($requestGETData->scripts == "false") {
				
				// remove scripts$elems = 
				$elems = $html->find("script");
				foreach ($elems as $thiselem) {
					$thiselem->outertext = "";
				}
				
			}
			
			if ($requestGETData->objects == "false") {
				
				// remove objects
				$elems = $html->find("object, embed");
				foreach ($elems as $thiselem) {
					$thiselem->outertext = "";
				}
				
			}
			
			if (DEBUGGING) {
				info("info", "Processed page successfully");
			} else {
				echo $html->outertext;
			};
			
		}
		
		break;
		
	default:
		echo "other";
		break;
}

?>