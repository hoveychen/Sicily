// Localized Message Variable
// var msg_pending,msg_contestends;
// var msg_months, msg_days, msg_hours,msg_mins,msg_secs,msg_contestfinished;
// // =============
// Contest Timer
// =============
var counting = 0;
var timerInt;
var showHint = 0;
function updateTime() {
	var t;
	++counting;
	if (counting/60 >= 10 && showHint) {
		$("#hintClock").text("This page was last updated " + Math.ceil(counting / 60) + " minutes ago.")
	}
	currentTime.setTime(currentTime.getTime() + 1000);
	if (currentTime.getTime() < startTime.getTime()) {
		t = new Date(startTime.getTime() - currentTime.getTime());
		$("#contestClock").text(msg_pending);
	} else if (currentTime.getTime() < endTime.getTime()) {
		t = new Date(endTime.getTime() - currentTime.getTime());
		var display_msg = msg_contestends;
		if (t.getMonth() > 0) {
			display_msg += t.getMonth() + msg_months + (t.getDate() - 1) + msg_days;
		} else if ((t.getDate()-1) > 0) {
			display_msg += (t.getDate()-1) + msg_days + t.getUTCHours() + msg_hours;
		} else if (t.getUTCHours() > 0) {
			display_msg += t.getUTCHours() + msg_hours + t.getUTCMinutes() + msg_mins;
		} else {
			display_msg += t.getUTCMinutes() + msg_mins + t.getUTCSeconds() + msg_secs;
		}
		$("#contestClock").text(display_msg);
		
	} else {
		$("#contestClock").text(msg_contestfinished);
	}
}

function displayTime() {
	updateTime();
	setInterval("updateTime()", 1000);
}

$(function(){
	displayTime();
});
