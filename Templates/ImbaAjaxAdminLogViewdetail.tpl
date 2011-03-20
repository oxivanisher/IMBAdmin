<script type="text/javascript">
  
    function showLogDetail(id){
        var data = {
            action: "module",
            module: "Admin",
            request: "viewlogdetail",
            id: id
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<b>User: {$user}{if $openid} ({$openid}){/if}: {$city} ({$ip})</b><br />
<i>Session: {$session}</i>
<table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
    <tr><th>Date</th><th>Module</th><th>Message</th><th>Level</th></tr>

    {foreach $logs as $log}
    <tr onclick="javascript: showLogDetail('{$log.id}');" style="cursor: pointer;">
        <td>{if $id == $log.id}&gt; {/if}{$log.date}</td>
        <td>{$log.id}{$log.module}</td>
        <td>{$log.message}</td>
        <td>{$log.level}</td>
    </tr>
    {/foreach}

</table>