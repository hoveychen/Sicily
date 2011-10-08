$(function() {
    $( "#tabs" ).tabs({
        cookie: {
            expires: 7
        },
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html(
                    "Couldn't load this problem. We'll try to fix this as soon as possible. ");
            }
        },
        load:function(e, ui){
            jQuery.fn.dataTableExt.oPagination.iFullNumbersShowPages = 8;
            var advtable_fix = $(ui.panel).find(".advtable_fix").dataTable({
                //"bProcessing": true,
                //"sAjaxSource": 'problem_data.php',
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
                "sDom": '<"H"pfr>t<"F"il>',
                "asStripClasses": [ 'tr_odd', 'tr_even' ],
                "iDisplayLength": 200,
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
                },
                {
                    "sClass": "place_center", 
                    "sWidth": "8%"
                },
                { 
                    "fnRender": function ( oObj ) {
                        return "<a class='black' href='problem.php?pid=" + oObj.aData[1] + "'>"
                        + oObj.aData[2] + "</a>";
                    },
                    "sClass": "place_left", 
                    "bUseRendered": false,
                    "sWidth": "40%"
                },
                { 
                    "bSearchable": false,
                    "fnRender": function ( oObj ) {
                        return "<a class='black' href='problem_status.php?pid=" + oObj.aData[1] + "'>"
                        + oObj.aData[3] + "</a>"
                    },
                    "sClass": "place_center",
                    "bUseRendered": false
                },
                {
                    "bSearchable": false, 
                    "sClass": "place_center"
                },
                { 
                    "bSearchable": false, 
                    "sClass": "place_center",
                    "bUseRendered": false,
                    "fnRender": function ( oObj ) {
                        return oObj.aData[5] + "%";
                    }
                },
                {
                    "bSearchable": false, 
                    "sClass": "place_center"
                }
                ]
            /*
                "fnServerData": function ( sSource, aoData, fnCallback ) {
                        $.ajax( {
                                "dataType": 'json', 
                                "type": "POST", 
                                "url": sSource, 
                                "data": aoData, 
                                "success": fnCallback
                        } );
                }
                     */
		
            });
        //new FixedHeader( advtable_fix );
        }
    });
});