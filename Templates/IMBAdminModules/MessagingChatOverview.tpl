<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaAjaxChathistoryOverviewTable').dataTable( {
            "iDisplayLength": 16,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
    } );   
    
    function loadChatHistory(channelId){
        var data = {
            module: "Messaging",
            request: "viewchathistory",
            channelId: channelId
        };
        loadImbaAdminTabContent(data);
    }
    
</script>
<table id="ImbaAjaxChathistoryOverviewTable" class="dataTableDisplay">
    <thead>
        <tr><th>Name</th><th>Letzte Nachricht</th><th>Anzahl Nachrichten</th></tr>
    </thead>
    <tbody>

        {foreach $channels as $channel}
        <tr onclick="javascript: loadChatHistory('{$channel.id}');"><td>{$channel.name}</td><td>{$channel.lastmessage}</td><td>{$channel.nummessages}</td></tr>
        {/foreach}

    </tbody>
</table>




