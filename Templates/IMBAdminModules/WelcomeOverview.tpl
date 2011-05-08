<style type="text/css">
    #ImbaContentClickable {
        padding: 3px;
        margin: 3px;
        border: 2px grey solid;
        cursor: pointer;
        height: 105px; 
        width: 305px;
        float: left;
        padding: 3px;
        text-align: center;
        /*        clear: both; */
    }
    #ImbaContentClickable:hover {
        border: 2px lightgrey solid;
        background-color: #222222;
    }
</style>
<script>
    $(function() {
        $( ".imbaPortletColumn" ).sortable({
            connectWith: ".imbaPortletColumn"
        });

        $( ".imbaPortlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
        .find( ".imbaPortlet-header" )
        .addClass( "ui-widget-header ui-corner-all" )
        .prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
        .end()
        .find( ".imbaPortlet-content" );

        $( ".imbaPortlet-header .ui-icon" ).click(function() {
            $( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
            $( this ).parents( ".imbaPortlet:first" ).find( ".imbaPortlet-content" ).toggle();
        });

        $( ".imbaPortletColumn" ).disableSelection();
    });
</script>
{foreach $navs as $nav}
{*
<div id="ImbaContentClickable" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');"><h3>{$nav.name}</h3>{$nav.comment}</div>
*}
<div class="imbaPortletColumn">
    <div class="imbaPortlet">
        <div class="imbaPortlet-header">{$nav.name}</div>
        <div class="imbaPortlet-content" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');">{$nav.comment}</div>
    </div>
</div>
{/foreach}