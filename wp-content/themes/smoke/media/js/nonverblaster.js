
window.onload = init;

var jsReady = false;
var flashMovie = "";
var nonverblasterClicked = false;

function init(){
	jsReady = true;
}

function getFlashMovie(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName];
	} else {
		return document[movieName];
	}
}
// broadcasts the actions to the player
function sendToNonverBlaster(value) {
	getFlashMovie(flashMovie).sendToActionScript(value);
}

function registerForJavaScriptCommunication(_flashMovie){
	flashMovie = _flashMovie;
}
// Called if the Player was clicked
function nonverBlasterClickHandler(){
	nonverblasterClicked = true;
	//alert("nonverblasterClicked: " + nonverblasterClicked)
}