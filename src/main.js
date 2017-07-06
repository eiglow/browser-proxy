"use strict";

const HOMEPAGE = "http://www.google.com"; // TODO: maybe use cookies to set homepage?
const LOGGING = true;
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
	
	if (LOGGING) console.log("Prettifying " + toPretty);
	
	toPretty = toPretty.replace(/\r?\n|\r/g, "");
	var match = toPretty.match(/^(https?\:)\/\/(([^:\/?#]*)(?:\:([0-9]+))?)([\/]{0,1}[^?#]*)(\?[^#]*|)(#.*|)$/);
    
	return "<span class='url-faded'>" + match[1] + "//</span>" + match[3] +
	(match[4] ? "<span class='url-faded'>:" + match[4] + "</span>" : "") +
	match[5] + "<span class='url-faded'>" + match[6] + match[7];
	
}

// Interface for the URL bar. Reasoning for this being a class is to make it into tabs later
// Get the current URL with curTab.curPage
// Set it with curTab.setPage("http://example.com")
function Tab() {
	this.curPage = "";
	this.setPage = function(newPage) {
		if (LOGGING) console.log("browserState curPage to '" + newPage + "'");
		if (newPage) {
			this.curPage = newPage;
			UIElems.url.innerHTML = prettifyURL(newPage);
		}
	};
}
var curTab = new Tab();

// Processes the links in the page within the frame.
// TODO: review and test
function processIndividualLink(linkk) {
	var tempurl = linkk.href;
	if (tempurl) {
		linkk.href = ""; // don't remove the href tag because it won't look like a link anymore
		linkk.addEventListener("click", function() {
			if (LOGGING) console.log("internal navigate to '" + tempurl + "'");
			curTab.setPage(tempurl);
			navigateTo();
		});
	}
}
function processLinks() {
	if (LOGGING) console.log("Processing links...");
	var innerDoc = UIElems.frame.contentDocument || UIElems.frame.contentWindow.document;
	console.log(innerDoc);
	var links = innerDoc.getElementsByTagName("a");
	console.log(links);
	for (var i = 0; i < links.length; i++) {
		processIndividualLink(links[i]);
	}
}

// Navigation, differs from refresh, back, and forward functions.
// Set the page with curTab.setPage("http://example.com") before calling this function
function navigateTo() {
	if (LOGGING) console.log("navigateTo");
	HISTORY.push(curTab.curPage);
	page(curTab.curPage);
}

// Reloads the page
function refresh() {
	if (LOGGING) console.log("refreshing");
	page(
		curTab.curPage
	);
}
function back() {
	if (LOGGING) console.log("going back");
	// checks if the previous item of history exists
	if (HISTORY[HISTORY.indexOf(curTab.curPage) - 1])
		page(
			HISTORY[HISTORY.indexOf(curTab.curPage) - 1]
		);
}
function forward() {
	if (LOGGING) console.log("going forward");
	// checks if the next item of history exists
	if (HISTORY[HISTORY.indexOf(curTab.curPage) + 1])
		page(
			HISTORY[HISTORY.indexOf(curTab.curPage) + 1]
		);
}

var GETData = [];
// Loads a page inside the iframe
function page(url, scriptsState, cookiesState, objectsState) {
	
	if (LOGGING) console.log("page '" + url + "'");
	
	scriptsState = scriptsState || pageSettings[0].className;
	cookiesState = cookiesState || pageSettings[1].className;
	objectsState = objectsState || pageSettings[2].className;
	
	GETData.url = encodeURIComponent(window.btoa(url));
	GETData.scripts = pageSettings[0].classList.contains("enabled");
	GETData.cookies = pageSettings[1].classList.contains("enabled");
	GETData.objects = pageSettings[2].classList.contains("enabled");
	GETData.base    = encodeURIComponent(window.btoa(curTab.curPage));
	//curTab.setPage(url);
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
}
UIElems.refresh.addEventListener("click", refresh);
UIElems.back.addEventListener("click", back);
UIElems.forward.addEventListener("click", forward);

UIElems.url.addEventListener("keypress", function(e) {
	if (e.keyCode == 13) { // if enter pressed
		e.preventDefault();
		curTab.setPage(UIElems.url.innerText.trim());
		navigateTo();
	}
});

UIElems.frame.addEventListener("load", function() {
	if (LOGGING) console.log("Frame page loaded");
	processLinks();
});
function init() {
	if (LOGGING) console.log("initialising");
	curTab.setPage(HOMEPAGE);
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
