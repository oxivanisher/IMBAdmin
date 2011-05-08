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
            connectWith: ".imbaPortletColumn",
            tolerance: "pointer"
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
    
    $(function($) {
        $('.jclock').jclock();
    });
</script>
<div>
    <div class="imbaTitle" style="float: left;">
        <b>Hallo {$nickname}</b>
    </div>
    <div style="float: right; padding: 5px;">
        <iframe src="http://www.facebook.com/plugins/like.php?href={$thrustRoot}&amp;send=true&amp;layout=button_count&amp;width=190&amp;show_faces=true&amp;action=like&amp;colorscheme=dark&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:190px; height:90px;" allowTransparency="true"></iframe>
    </div>
    <div class="imbaTitle" style="float: right; align: center;">
        <i>Heute ist der {$today} um <span class="jclock"></span></i>.
    </div>
</div>
<div style="clear: both;"></div>
<div>
    <div class="imbaPortletColumn">
        <div class="imbaPortlet">
            <div class="imbaPortlet-header">N&auml;chste Geburtstage</div>
            <div class="imbaPortlet-content">{$birthdays}</div>
        </div>
    </div>
    <div class="imbaPortletColumn">
        <div class="imbaPortlet">
            <div class="imbaPortlet-header">Events</div>
            <div class="imbaPortlet-content">{$events}</div>
        </div>
    </div>
    <div class="imbaPortletColumn">
        <div class="imbaPortlet">
            <div class="imbaPortlet-header">Neue Mitglieder</div>
            <div class="imbaPortlet-content">{$newMembers}</div>
        </div>
    </div>
    <div class="imbaPortletColumn">
        <div class="imbaPortlet">
            <div class="imbaPortlet-header">Aufgaben</div>
            <div class="imbaPortlet-content">{$todo}</div>
        </div>
    </div>
    <div class="imbaPortletColumn">
        <div class="imbaPortlet">
            <div class="imbaPortlet-header">Spenden</div>
            <div class="imbaPortlet-content"></div>
        </div>
    </div>
    <div class="imbaPortletColumn">
        <div class="imbaPortlet">
            <div class="imbaPortlet-header">Pic of the Moment</div>
            <div class="imbaPortlet-content"></div>
        </div>
    </div>
    <div class="imbaPortletColumn">
        <div class="imbaPortlet">
            <div class="imbaPortlet-header">Navigation</div>
            <div class="imbaPortlet-content">
                {foreach $navs as $nav}
                <a href="javascript:void();"  onclick="javascript: loadImbaAdminModule('{$nav.identifier}');" title="{$nav.comment}">{$nav.name}</a><br />
                {/foreach}
            </div>
        </div>
    </div>
</div>