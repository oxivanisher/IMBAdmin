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

    function backToLogOverview(){
        var data = {
            action: "module",
            module: "Admin",
            request: "log"
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<b>{$user}{if $openid} ({$openid}){/if}</b><br />
{$city} ({$ip})<br />
<i>Session: {$session}</i>
<br />
<br />
<table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
    <tr><th>Date</th><th>Module</th><th>Message</th><th>Level</th></tr>

    {foreach $logs as $log}
    <tr onclick="javascript: showLogDetail('{$log.id}');" style="cursor: pointer;">
        <td{if $id == $log.id} style="border: 1px white solid;"{/if}>{$log.date}</td>
        <td{if $id == $log.id} style="border: 1px white solid;"{/if}>{$log.module}</td>
        <td{if $id == $log.id} style="border: 1px white solid;"{/if}>{$log.message}</td>
        <td{if $id == $log.id} style="border: 1px white solid;"{/if}>{$log.level}</td>
    </tr>
    {/foreach}
</table>
<br />
<a href="javascript:void(0)" onclick="javascript: backToLogOverview();">Back to Log Overview</a>