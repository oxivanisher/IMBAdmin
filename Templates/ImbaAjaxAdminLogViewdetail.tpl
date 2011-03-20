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
<b>User: {$user} ({$openid}), {$city} ({$ip})</b>
<h3>Actual Entry</h3>
<table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
    <tr><td>Date</td><td>{$date} ({$age})</td></tr>
    <tr><td>Module</td><td>{$module}</td></tr>
    <tr><td>Session</td><td>{$session}</td></tr>
    <tr><td>Level</td><td>{$level}</td></tr>
    <tr><td>Message</td><td>{$message}</td></tr>
</table>
<h3>Other Session Messages</h3>
<table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
    <tr><th>Date</th><th>Module</th><th>Message</th><th>Level</th></tr>

    {foreach $logs as $log}
    <tr onclick="javascript: showLogDetail('{$log.id}');" style="cursor: pointer;">
        <td>{if $id == $log.id}&gt; {/if}{$log.date}</td>
        <td>{$log.module}</td>
        <td>{$log.message}</td>
        <td>{$log.level}</td>
    </tr>
    {/foreach}

</table>