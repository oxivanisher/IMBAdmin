<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaAjaxMessagehistoryOverviewTable').dataTable( {
            "aoColumns": [
                null,
                { "sType": "title-numeric" },
                null
            ], 
            "iDisplayLength": 16,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false,
            "aaSorting": [[1, "desc" ]]
        } );
    } );   
    
    function loadMessageHistory(id){
        var data = {
            module: "Messaging",
            request: "viewmessagehistory",
            userid: id
        };
        loadImbaAdminTabContent(data);
    }

</script>
<table id="ImbaAjaxMessagehistoryOverviewTable" class="dataTableDisplay">
    <thead>
        <tr><th>Nickname</th><th>Letzte Konversation</th><th>Anzahl Nachrichten</th></tr>
    </thead>
    <tbody>

        {foreach $users as $user}
        <tr onclick="javascript: loadMessageHistory('{$user.id}');" style="cursor: pointer;">
            <td>{$user.nickname}</td>
            <td><span title="{$user.lastmessagets}">{$user.lastmessagestr}</span></td>
            <td>{$user.nummessages}</td>
        </tr>
        {/foreach}

    </tbody>
</table>



