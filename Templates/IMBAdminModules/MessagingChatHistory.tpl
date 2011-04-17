<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaAjaxMessageHistoryTable').dataTable( {
            "iDisplayLength": 16,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false,
            "bSort": false
        } );
    } );   
        
    $(document).ready(function() {
        $("#imbaMessagingViewChatOverview").button();
    });
    
    function backToChatOverview(){
        var data = {
            module: "Messaging",
            request: "viewchatoverview"
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<table id="ImbaAjaxMessageHistoryTable" class="dataTableDisplay">
    <thead>
        <tr><th>Wer</th><th>Wann</th><th>Was</th></tr>
    </thead>
    <tbody>

        {foreach $messages as $message}
        <tr>
            <td><a href="javascript:void();" onclick="javascript:createChatWindow('{$message.nickname}', '{$message.openid}');">{$message.nickname}</td>
            <td>{$message.timestamp}</td>
            <td>{$message.message}</td>
            </tr>
        {/foreach}

    </tbody>
</table>
<a id="imbaMessagingViewChatOverview" href="javascript:void(0)" onclick="javascript: backToChatOverview();">Zur&uuml;ck</a>