<?php ?>

<div id="searchbox">
    <form id="search_form">
        <input id="search_input" class="search_bar" type="text" title="<?=_("Search problem id or title...")?>" />
        <?= create_button(_("Search"), '$("#search_form").submit()') ?>
    </form>
</div>

<script type="text/javascript">
    // jQuery Input Hints plugin
    // Copyright (c) 2009 Rob Volk
    // http://www.robvolk.com

    jQuery.fn.inputHints=function() {
        // hides the input display text stored in the title on focus
        // and sets it on blur if the user hasn't changed it.

        // show the display text
        $(this).each(function(i) {
            $(this).val($(this).attr('title'))
            .addClass('input_hint');
        });

        // hook up the blur & focus
        return $(this).focus(function() {
            if ($(this).val() == $(this).attr('title'))
                $(this).val('')
            .removeClass('input_hint');
        }).blur(function() {
            if ($(this).val() == '')
                $(this).val($(this).attr('title'))
            .addClass('input_hint');
        });
    };



    var first_suggest_item = "";
    function open_problem(id) {
        location.href="problem.php?pid=" + id;
    }
    $(function(){
        $("#search_input[title]").inputHints();
        $("#topbar").append($("#searchbox"));
        $("#search_form").submit(function(){
            if (first_suggest_item) {
                open_problem(first_suggest_item);
            }
            return false;
        });
        
        $("#search_input").autocomplete({
            source: 'fast_json.php?mod=problem&func=search_suggest',
            minLength: 2,
            search: function(event, ui) {
                first_suggest_item = "";
            },
            select: function(event, ui) {
                if (ui.item) {
                    open_problem(ui.item.id);
                } else {
                    alert("no");
                }
            },
            focus: function( event, ui ) {
                $( "#search_input" ).val( ui.item.id );
                first_suggest_item = ui.item.id;
                return false;
            }
        }).data( "autocomplete" )._renderItem = function( ul, item ) {
            var str = item.id + "." + item.title + "<br>" + item.info;
            if (item.match || !first_suggest_item) {
                str += "<hr />";
                first_suggest_item = item.id;
            }
            return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + str + "</a>" )
            .appendTo( ul );
        };        
    });
    
</script>