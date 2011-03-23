<script type="text/javascript">
    $(document).ready(function() {
        // Init DataTable
        var oTable = $('#ImbaAjaxAdminGameTable').dataTable( {
            "iDisplayLength": 13,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
	
        // Apply the jEditable handlers to the table
        $("td[editable|='true']", oTable.fnGetNodes()).editable(ajaxEntry, {
            "callback": function( sValue, y ) {
                var aPos = oTable.fnGetPosition( this );
                oTable.fnUpdate( sValue, aPos[0], aPos[1] );
            },
            "submitdata": function ( value, settings ) {
                return {
                    action: "module",
                    module: "Admin",
                    request: "updategame",
                    gameid: this.parentNode.getAttribute('id').substr(7),
                    gamecolumn: getColumnHeadByIndex("ImbaAjaxAdminGameTable", oTable.fnGetPosition(this)[2])
                };
            },
            "height": "14px"
        } );
        
        $("#ImbaAjaxAdminGameTable tr td span").click(function(){
            if(confirm("Soll das Game wirklich geloescht werden?")){                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "deletegame",
                    gameid: this.parentNode.parentNode.getAttribute('id').substr(7)
                });

                var data = {
                    module: "Admin",
                    request: "game"
                };
                loadImbaAdminTabContent(data);
            }            
        });
        
        $("#ImbaAddGameOK").click( function() {
            if ((ImbaAddGameName.value.valueOf() != "")
                && (ImbaAddGameIcon.value.valueOf() != "")
                && (ImbaAddGameUrl.value.valueOf() != "")
                && (ImbaAddGameForumlink.value.valueOf() != "")) {
                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "addgame",
                    name: ImbaAddGameName.value.valueOf(),
                    icon: ImbaAddGameIcon.value.valueOf(),
                    url: ImbaAddGameUrl.value.valueOf(),
                    forumlink: ImbaAddGameForumlink.value.valueOf()
                });
                alert('test');
                var data = {
                    module: "Admin",
                    request: "game"
                };
                loadImbaAdminTabContent(data);
                
            } else {
                alert('Please fill out all the fields');
            }
                
        });        
    } );
</script>
<table id="ImbaAjaxAdminGameTable" class="dataTableDisplay">
    <thead>
        <tr>
            <th title="Name">Name</th>
            <th title="Icon">Icon</th>
            <th title="Url">Url</th>
            <th title="Forumlink">Forumlink</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

        {foreach $games as $game}
        <tr id="gameid_{$game.id}">
            <td editable="true">{$game.name}</td>
            <td editable="true">{$game.icon}</td>
            <td editable="true">{$game.url}</td>
            <td editable="true">{$game.forumlink}</td>
            <td editable="false" class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td><input id="ImbaAddGameName" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddGameIcon" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddGameUrl" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddGameForumlink" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td id="ImbaAddGameOK" style="cursor: pointer;"><b>OK</b></td>
        </tr>
    </tfoot>
</table>