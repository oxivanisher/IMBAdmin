<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaAjaxMessagehistoryOverviewTable').dataTable( {
            "iDisplayLength": 16,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
    } );   
    
    function loadMessageHistory(openid){
        var data = {
            module: "Messaging",
            request: "viewmessagehistory",
            openid: openid
        };
        loadImbaAdminTabContent(data);
    }

</script>
<table id="ImbaAjaxMessagehistoryOverviewTable" class="dataTableDisplay">
    <thead>
        <tr><th>Nickname</th><th>Letzte Konversation</th><th>Anzahl (Neu)</th></tr>
    </thead>
    <tbody>

        {foreach $users as $user}
        <tr onclick="javascript: loadMessageHistory('{$user.openid}');">
            <td>{$user.nickname}</td>
            <td>{$user.lastmessage}</td>
            <td>{$user.nummessages} ({$user.numnewmessages})</td>
        </tr>
        {/foreach}

    </tbody>
</table>



