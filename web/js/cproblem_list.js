$(document).ready(function(){
	$(".advtable_fix").dataTable({
		"bProcessing": true,
		"aaSorting": [[ 1, "asc" ]],
		"oLanguage": {
			"sLengthMenu": "Display _MENU_ problems per page",
			"sZeroRecords": "No Problems are matched",
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ problems",
			"sInfoEmtpy": "No problems are showed",
			"sInfoFiltered": "(filtered from _MAX_ total problems)"
		}, 
		"asStripClasses": [ 'tr_odd', 'tr_even' ],
		"bJQueryUI": true, 
		"aoColumns": [
		{ 
			"fnRender": function ( oObj ) {
				if (oObj.aData[0] == 'Y') {
					return "<img src='images/yes.gif' />"
				} else if (oObj.aData[0] == '-') {
					return "<img src='images/not_yet.gif' />"
				} else return "";
			},
			"bUseRendered": false, 
			"bSearchable": false,
			"sClass": "place_center",
			"sWidth": "10%" 
		},
		{
			"sClass": "place_center",  
			"sWidth": "10%"
		},
{ 
			"sClass": "place_left"
		},
		{
			"bSearchable": false, 
			"sClass": "place_center", 
			"sWidth": "15%"
		},
{
			"bSearchable": false, 
			"sClass": "place_center", 
			"sWidth": "15%"
		}
		]
		
	});
});	
