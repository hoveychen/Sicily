<?php
require("./navigation.php");
?>

<link type="text/css" rel="stylesheet" href="../css/data_table.css"/>
<script type="text/javascript" src="../js/jquery.dataTables.min.js" > </script>
<script type="text/javascript">
	$(document).ready(function(){
		jQuery.fn.dataTableExt.oPagination.iFullNumbersShowPages = 8;
		$(".advtable_fix").dataTable({
			"bProcessing": true,
			"sAjaxSource": '../problem_data.php',
			"sPaginationType": "full_numbers",
			"aaSorting": [[ 1, "asc" ]],
			"bStateSave": true,
			"oLanguage": {
				"sLengthMenu": "Display _MENU_ problems per page",
				"sZeroRecords": "No Problems are matched",
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ problems",
				"sInfoEmtpy": "No problems are showed",
				"sInfoFiltered": "(filtered from _MAX_ total problems)"
			}, 
			//"sDom": '<"vol_navigate"fp<"clear">>rt<"vol_navigate"li<"clear">>',
			"asStripClasses": [ 'tr_odd', 'tr_even' ],
			"iDisplayLength": 100,
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
					"sClass": "place_center"
					//"sWidth": "6%"
				},
				{ "sClass": "place_center", "sWidth": "6%"  },
				{ 
					"fnRender": function ( oObj ) {
						return "<a class='black' target='_blank' href='problem_edit.php?pid=" + oObj.aData[1] + "'>"
							+ oObj.aData[2] + "</a>" +
							"&nbsp;&nbsp;<a class='black' href='process.php?act=ExportProblem&pid=" + oObj.aData[1] + "'> (Export) </a>" +
							"&nbsp;&nbsp;<a class='black' href='process.php?act=RejudgeProblem&pid=" + oObj.aData[1] + "'> (Rejudge) </a>";
					},
					"sClass": "place_left", 
					"bUseRendered": false,
					"sWidth": "40%"
				},
				{ 
					"bSearchable": false,
					"sClass": "place_center",
					"bUseRendered": false
					//"sWidth": "10%"
				},
				{ "bSearchable": false, "sClass": "place_center" },
				{ "bSearchable": false, "sClass": "place_center" },
				{ "bSearchable": false, "sClass": "place_center" }
			],

			"fnServerData": function ( sSource, aoData, fnCallback ) {
				$.ajax( {
					"dataType": 'json', 
					"type": "POST", 
					"url": sSource, 
					"data": aoData, 
					"success": fnCallback
				} );
			}

		
		});
	});	


</script>
<link type="text/css" href="../css/jquery-ui-1.8.5.custom.css" rel="stylesheet" />

<div id="problem_list"> 
	<table class="display advtable_fix">
		<thead class="tr_header"><tr><th>Solved</th><th>ID</th><th class="place_left">Title</th><th>Accepted</th><th>Submissions</th><th>Ratio</th><th>Rating</th></tr></thead>
		<tbody></tbody>
	</table>
</div>


<?php
require("../footer.php");
?>
