SyntaxHighlighter.config.clipboardSwf = 'js/sh/clipboard.swf';
SyntaxHighlighter.all();	

function onClickShareLink(data) {
	if (data.success) {
		$("#public").text("Code has been published. Just copy the URL to your friend!");
	} else {
		$("#public").text(data.status);
	}
}

$(document).ready(function() {
	$("#share_link").click(function() {
		$.post("action.php?act=PublishCode", {
			"sid":sid
		}, onClickShareLink, "json");
		return false;
	});
	if (!owner) {
		$("#public").hide();
	}
});

