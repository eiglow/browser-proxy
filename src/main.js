"use strict";

const HOMEPAGE = "http://www.google.com"; // TODO: maybe use cookies to set homepage?
var HISTORY = [];

// ================================================================
// DOM
// ================================================================

var pageSettings = [
	document.getElementById("toggleScripts"),
	document.getElementById("toggleCookies"),
	document.getElementById("toggleObjects"),
];
var UIElems = {
	refresh : document.getElementById("refresh"),
	back    : document.getElementById("back"),
	forward : document.getElementById("forward"),
	url     : document.getElementById("url"),
	frame   : document.getElementById("frame")
};

// ================================================================
// FUNCTIONS
// ================================================================

// Formats URLs to look pretty in the URL bar.
// Regex from http://stackoverflow.com/questions/736513/how-do-i-parse-a-url-into-hostname-and-path-in-javascript#answer-21553982
function prettifyURL(toPretty) {
	
	console.log("Prettifying " + toPretty);
	
	toPretty = toPretty.replace(/\r?\n|\r/g, "");
	var match = toPretty.match(/^(https?\:)\/\/(([^:\/?#]*)(?:\:([0-9]+))?)([\/]{0,1}[^?#]*)(\?[^#]*|)(#.*|)$/);
    
	return "<span class='url-faded'>" + match[1] + "//</span>" + match[3] +
	(match[4] ? "<span class='url-faded'>:" + match[4] + "</span>" : "")
	+ match[5] + "<span class='url-faded'>" + match[6] + match[7];
	
}

// Interface for the URL bar. Reasoning for this being a class is to make it into tabs later
// Get the current URL with curBrowserState.curPage()
// Set it with curBrowserState.curPage("http://example.com")
function browserState() {
	this.privateCurPage = "";
	this.curPage = function(newPage) {
		if (newPage) {
			this.privateCurPage = newPage;
			UIElems.url.innerHTML = prettifyURL(newPage);
		}
		return this.privateCurPage;
	};
};
var curBrowserState = new browserState();

// Processes the links in the page within the frame.
// TODO: review and test
function internalNavigate(url) {
	curBrowserState.curPage(url);
	navigateTo();
}
function processLinks() {
	console.log("Processing links...");
	var innerDoc = UIElems.frame.contentDocument || UIElems.frame.contentWindow.document;
	console.log(innerDoc);
	var links = innerDoc.getElementsByTagName("a");
	console.log(links);
	for (var i = 0; i < links.length; i++) {
		let tempurl = links[i].href;
		if (tempurl) {
			links[i].href = ""; // don't remove the href tag because it won't look like a link anymore
			links[i].addEventListener("click", internalNavigate.call(tempurl));
		}
	}
}

// Navigation, differs from refresh, back, and forward functions.
// Set the page with curBrowserState.curPage("http://example.com") before calling this function
function navigateTo() {
	if (url != curBrowserState.curPage()) HISTORY.push(curBrowserState.curPage());
	page(curBrowserState.curPage());
}

function refresh() {
	page(
		curBrowserState.curPage()
	);
}
function back() {
	// checks if the previous item of history exists
	if (HISTORY[HISTORY.indexOf(curBrowserState.curPage()) - 1])
		page(
			HISTORY[HISTORY.indexOf(curBrowserState.curPage()) - 1]
		);
}
function forward() {
	// checks if the next item of history exists
	if (HISTORY[HISTORY.indexOf(curBrowserState.curPage()) + 1])
		page(
			HISTORY[HISTORY.indexOf(curBrowserState.curPage()) + 1]
		);
}

var GETData = [];
function page(url, scriptsState, cookiesState, objectsState) {
	
	scriptsState = scriptsState || pageSettings[0].className;
	cookiesState = cookiesState || pageSettings[1].className;
	objectsState = objectsState || pageSettings[2].className;
	
	GETData.url = encodeURIComponent(window.btoa(url));
	GETData.scripts = pageSettings[0].classList.contains("enabled");
	GETData.cookies = pageSettings[1].classList.contains("enabled");
	GETData.objects = pageSettings[2].classList.contains("enabled");
	GETData.base    = encodeURIComponent(window.btoa(curBrowserState.curPage()));
	curBrowserState.curPage(url);
	UIElems.frame.src = "page.php?url=" + GETData.url +
	"&scripts=" + GETData.scripts +
	"&cookies=" + GETData.cookies +
	"&objects=" + GETData.objects +
	"&base=" + GETData.base;
	
}

// ================================================================
// USER INTERFACE
// ================================================================

function toggleButton() {
	this.classList.toggle("enabled");
	this.classList.toggle("disabled");
}

for (let i = 0; i < 3; i++) {
	pageSettings[i].addEventListener("click", toggleButton);
};
UIElems.refresh.addEventListener("click", refresh);
UIElems.back.addEventListener("click", back);
UIElems.forward.addEventListener("click", forward);

UIElems.url.addEventListener("keypress", function(e) {
	if (e.keyCode == 13) { // if enter pressed
		e.preventDefault();
		curBrowserState.curPage(UIElems.url.innerText);
		navigateTo();
	}
});

UIElems.frame.addEventListener("load", function() {
	console.log("Frame page loaded");
	processLinks();
});
function init() {
	curBrowserState.curPage(HOMEPAGE);
	navigateTo();
}
top.addEventListener("load", init);

/* (WIP TOOLTIP STUFF)
var toolTipElems = document.querySelectorAll("[data-tooltip]");
function toolTipMouseEnter() {
	this.childNodes.querySelector(".tooltip").style.visibility = "visible";
}
function toolTipMouseOut() {
	this.childNodes.querySelector(".tooltip").style.visibility = "hidden";
}
for (let i = 0; i < toolTipElems.length; i++) {
	var newToolTip = document.createElement("DIV");
	newToolTip.className = "tooltip";
	newToolTip.innerHTML = toolTipElems["data-tooltip"];
	toolTipElems[i].appendChild(newToolTip);
	toolTipElems[i].addEventListener("mouseenter", toolTipMouseEnter);
	toolTipElems[i].addEventListener("mouseout", toolTipMouseOut);
}
*/
