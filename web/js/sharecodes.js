function stopSharing(button, sid) {
	$.post("action.php?act=DisableCode", {
		"sid":sid
	}, onClickStopShareLink(button), "json"); 
	button.value="processing";
	button.disabled=true;
}

function openCode(sid) {
	window.open("viewsource.php?sid=" + sid, "_blank");
}


function onClickStopShareLink(button) {
	return function (data) {
		if (data.success) {
			button.value = "Deleted";
		} else {
			alert("Fail to stop sharing. Reason: " + data.status);
		}
	}
}           

$(document).ready(function(){
	jQuery.fn.dataTableExt.oPagination.iFullNumbersShowPages = 8;
	$(".advtable_fix").dataTable({
		"aaSorting": [[ 1, "asc" ]],
		//"sDom": '<"vol_navigate"fp<"clear">>rt<"vol_navigate"li<"clear">>',
		"asStripClasses": [ 'tr_odd', 'tr_even' ],
		"iDisplayLength": 100,
		"bJQueryUI": true,
		"aoColumnDefs": [
		{
			"aTargets" : ["stop"],
			"fnRender" : function (oObj) {
				return "<input type='button' onclick='stopSharing(this, " + oObj.aData[0] + ")' value='Stop Sharing' />";
			},
			"bSearchable": false,
			"sClass": "place_center" 
		} ,
{ 
			"bSearchable": false,
			"sClass": "place_center",
			"aTargets" : [0]
		},
		{
			"sClass": "place_center", 
			"aTargets" : [1]
		},
{ 
			"sClass": "place_left", 
			"bUseRendered": false,
			"aTargets" : [2]
		},
		{ 
			"sClass": "place_center",
			"fnRender": function ( oObj ) {
				return "<span class='" + oObj.aData[3] + "'>"
				+ oObj.aData[3] + "</span>";
			},
			"bUseRendered": false,
			"aTargets" : [3] 
		},
		{ 
			"bSearchable": false, 
			"sClass": "place_center" ,
			"fnRender": function ( oObj ) {
				return oObj.aData[4] + "s";
			},
			"bUseRendered": false,
			"aTargets" : [4] 
		},
		{ 
			"bSearchable": false, 
			"sClass": "place_center" ,
			"fnRender": function ( oObj ) {
				return oObj.aData[5] + "KB";
			},
			"bUseRendered": false,
			"aTargets" : [5] 
		},
		{ 
			"bSearchable": false, 
			"sClass": "place_center" ,
			"fnRender": function ( oObj ) {
				return oObj.aData[6] + "Bytes";
			},
			"bUseRendered": false,
			"aTargets" : [6]
		},
		{
			"aTargets" : [7],
			"fnRender" : function (oObj) {
				return "<input type='button' onclick='openCode(" + oObj.aData[0] + ")' value='View' />";
			},
			"bSearchable": false,
			"sClass": "place_center" 
		}
		]
	});
});	
