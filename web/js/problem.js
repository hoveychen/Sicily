function flushRating() {
	$.post("action.php?act=QueryRating", {
		"pid":problem_id
	},
	function(data) {
		if (data.success) {
			var sum = parseInt(data.sum);
			var count = parseInt(data.count);
			var rating_board = "<div id=\"rating_board\">";
			rating_board += Math.round(10.0*sum/count)/10.0;			
			rating_board += "/5.0(" + count + " votes)";
			rating_board += "</div>";
			$("#rating_board").replaceWith(rating_board);
					
		} else {
			alert("Failed to get problem rating");
		}
	}, "json");
}

function changeScore() {
	score = parseInt($("#score_select option:selected").text());
	$.post("action.php?act=RateProblem", {
		"pid":problem_id, 
		"score": score
	},
	function(data) {
		if (data.success) {
			ratingInit();
			flushRating();
		} else {
			alert("Failed to rate this problem");
		}
	}, "json");
}

function ratingInit() {
	if (score >= 0) {
		var score_board = "<div id=\"score_board\">";
		score_board += "Your score: " + score;
		score_board += "</div>";
		$("#score_board").replaceWith(score_board);
		$("#score_board").addClass("links").click(function() {
			score = -1;
			ratingInit();
		});
	} else if (score == -1) {
		var score_board = "<div id=\"score_board\">";
		score_board += "<form><select id=\"score_select\" name=\"score_select\">";
		for (var i = 0; i <= 5; ++i) {
			score_board += "<option value=\"" + i + "\">" + i + "</option>";
		}
		score_board += "</select></form>";
		score_board += "</div>";
		$("#score_board").replaceWith(score_board);
		$("#score_select").change(changeScore);
	} else {
		return;
	}
}

function onMyCopyComplete( client, text ) {
	alert("Copied sample input to clipboard");
}



$(document).ready(function() {
	$("#info_board td:even").addClass("alignright");
	$("#info_board td:odd").addClass("alignleft");
	ratingInit();
	ZeroClipboard.setMoviePath( 'js/ZeroClipboard.swf' );
	var clip = new ZeroClipboard.Client();
	clip.setText( $("#sample_input").text() );
	clip.setHandCursor( true );
	clip.glue( 'd_clip_button' );
	clip.addEventListener( 'complete', onMyCopyComplete );
        $(".jqbutton").css('font-size', '15px').button();
});