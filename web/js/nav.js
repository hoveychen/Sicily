function SetCookie (name, value) {  
	var argc = SetCookie.arguments.length; 
	var argv = SetCookie.arguments;     
	var path = (argc > 3) ? argv[3] : null;   
	var domain = (argc > 4) ? argv[4] : null;   
	var secure = (argc > 5) ? argv[5] : false;   
         
         
	document.cookie = name + "=" + value +  
	((path == null) ? "" : ("; path=" + path)) +   
	((domain == null) ? "" : ("; domain=" + domain)) +     
	((secure == true) ? "; secure" : ""); 
} 

function Deletecookie (name) { 
	var exp = new Date();   
	exp.setTime (exp.getTime() - 1);   
	var cval = GetCookie (name);   
	document.cookie = name + "=" + cval + "; expires=" + exp.toGMTString(); 
}  

function getCookieVal (offset) {      
	var endstr = document.cookie.indexOf (";", offset);   
	if (endstr == -1) 
		endstr = document.cookie.length;   
	return unescape(document.cookie.substring(offset, endstr)); 
} 

function GetCookie (name) { 
	var arg = name + "=";   
	var alen = arg.length;   
	var clen = document.cookie.length;   
	var i = 0;   
	while (i < clen) {     
		var j = i + alen;     
		if (document.cookie.substring(i, j) == arg)       
			return getCookieVal (j);     
		i = document.cookie.indexOf(" ", i) + 1;     
		if (i == 0) break;    
	}   
	return null; 
}

function LoginSubmit() {
        var lsession = $("#lsession:checked").val() ? 1 : 0;
	$.post("action.php?act=Login", 
	{
		"username": $("#loginform #username").val(), 
		"password": $("#loginform #password").val(),
		"lsession": lsession
	}, 
	function(data) {
		if (data.success)
		{
			window.location.reload();
		} else {
			$("#sicily_logo #msg").text(data.status).fadeIn("slow").delay(2000).fadeOut();
		}
	}, "json");
	return false;
}

function SearchSubmit()
{
	if (CheckSearch()) {
		document.getElementById("search").submit();
	}
}

function CheckSearch() {
	if ($("#search > #keys").val() == "") {
		alert("Please fill in the keywords");
		return false;
	} else {
		return true;
	}
}



function ToggleWidth() {
	var isWide = GetCookie("wide_style")=='true';
	SetCookie("wide_style", !isWide? "true":"false");
	SetWidth(!isWide);
}

function SetWidth(isWide) {
	if (isWide) {
		$(".main_frame").css("width", "98%");
	} else {
		$(".main_frame").css("width", "960px");
	}
	// hotfix for datatable
	$(".advtable_fix").css("width", "100%");
}

function InitWidth() {
	SetWidth(GetCookie("wide_style")=='true');
}

$(document).ready(function(){
	InitWidth();
	$("#loginform").submit(LoginSubmit);
	if ($.browser.msie) {
		$(".tblcontainer tr:odd").addClass("tr_odd");
		$(".tblcontainer tr:even").addClass("tr_even");
	}
	$("#signature").focus(function(){
		$(this).addClass("isfocus");
	}).blur(function(){
		$(this).removeClass("isfocus")
	})
	.change(function(){
		if($(this).attr("size") < $(this).val().length){
			$(this).attr("size",$(this).val().length);
		}
		if($(this).val().length < 20) {
			$(this).attr("size", 20);
		}
	});
	if (!is_logged) $("#loginform input[name=username]").focus();
});
