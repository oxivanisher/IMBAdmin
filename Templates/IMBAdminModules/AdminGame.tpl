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
	
        $("#ImbaAjaxAdminGameTable tr td span").click(function(){
            if(confirm("Soll das Game wirklich geloescht werden?")){               
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    secSession: phpSessionID,
                    request: "deletegame",
                    gameid: this.parentNode.parentNode.getAttribute('id').substr(7)
                });

                var data = {
                    module: "Admin",
                    request: "game",
                    secSession: phpSessionID
                };
                loadImbaAdminTabContent(data);
            }            
        });
        
        $("#ImbaAddGameOK").click( function() {
            if ((ImbaAddGameName.value.valueOf() != "")              
                && (ImbaAddGameIcon.value.valueOf() != "")) {
                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "addgame",
                    secSession: phpSessionID,
                    name: ImbaAddGameName.value.valueOf(),
                    icon: ImbaAddGameIcon.value.valueOf()                    
                });

                var data = {
                    module: "Admin",
                    request: "game",
                    secSession: phpSessionID
                };
                loadImbaAdminTabContent(data);
                
            } else {
                alert('Please fill out all the fields');
            }
                
        });        
    } );
    
    function showGameDetail(id){
        var data = {
            module: "Admin",
            request: "viewgamedetail",
            secSession: phpSessionID,
            id: id
        };
        loadImbaAdminTabContent(data);
    }
    
</script>
<table id="ImbaAjaxAdminGameTable" class="dataTableDisplay">
    <thead>
        <tr>
            <th title="Icon">Icon</th>
            <th title="Name">Name</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

        {foreach $games as $game}
        <tr id="gameid_{$game.id}" style="cursor: pointer;">
            <td style="width: 15%" onclick="javascript: showGameDetail('{$game.id}');"><img src="{$game.icon}" alt="{$game.name}" title="{$game.name}" height="48" /></td>
            <td style="width: 75%" onclick="javascript: showGameDetail('{$game.id}');">{$game.name}</td>
            <td style="width: 10%" class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td><input id="ImbaAddGameIcon" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddGameName" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td id="ImbaAddGameOK" style="cursor: pointer;"><b>OK</b></td>
        </tr>
    </tfoot>
</table>