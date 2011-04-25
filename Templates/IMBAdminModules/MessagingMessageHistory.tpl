<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaAjaxChatHistoryTable').dataTable( {
            "aoColumns": [
                null,
                { "sType": "title-numeric" },
                null
            ],             "iDisplayLength": 16,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false,
            "bSort": false
        } );
    } );   
    $(document).ready(function() {
        $("#imbaMessagingViewMessageOverview").button();
    });
    
    function backToMessageOverview(){
        var data = {
            module: "Messaging",
            request: "viewmessageoverview",
            secSession: "{$secSession}"
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<table id="ImbaAjaxChatHistoryTable" class="dataTableDisplay">
    <thead>
        <tr><th>Wer</th><th>Wann</th><th>Was</th></tr>
    </thead>
    <tbody>

        {foreach $messages as $message}
        <tr>
            <td><a href="javascript:void();" onclick="javascript:createChatWindow('{$message.nickname}', '{$message.openid}');">{$message.nickname}</td>
            <td><nobr><span title="{$message.timestamp}">{$message.timestring}</span></nobr></td>
<td>{$message.message}</td>
</tr>
{/foreach}

</tbody>
</table>
<a id="imbaMessagingViewMessageOverview" href="javascript:void(0)" onclick="javascript: backToMessageOverview();">Zur&uuml;ck</a>