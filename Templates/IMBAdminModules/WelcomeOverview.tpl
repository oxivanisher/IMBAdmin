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

    .column { width: 170px; float: left; padding-bottom: 100px; }
    .portlet { margin: 0 1em 1em 0; }
    .portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
    .portlet-header .ui-icon { float: right; }
    .portlet-content { padding: 0.4em; }
    .ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
    .ui-sortable-placeholder * { visibility: hidden; }
</style>
<script>
    $(function() {
        $( ".column" ).sortable({
            connectWith: ".column"
        });

        $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
        .find( ".portlet-header" )
        .addClass( "ui-widget-header ui-corner-all" )
        .prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
        .end()
        .find( ".portlet-content" );

        $( ".portlet-header .ui-icon" ).click(function() {
            $( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
            $( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
        });

        $( ".column" ).disableSelection();
    });
</script>
{foreach $navs as $nav}
<div id="ImbaContentClickable" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');"><h3>{$nav.name}</h3>{$nav.comment}</div>

<div class="column">

    <div class="portlet">
        <div class="portlet-header">{$nav.name}</div>
        <div class="portlet-content" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');">{$nav.comment}</div>
    </div>

</div>

{/foreach}