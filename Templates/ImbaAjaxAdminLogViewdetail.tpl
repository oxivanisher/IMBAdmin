<script type="text/javascript">
  
    function showLogDetail(id){
        var data = {
            module: "Admin",
            request: "viewlogdetail",
            id: id
        };
        loadImbaAdminTabContent(data);
    }

    function backToLogOverview(){
        var data = {
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
        <td{if $id == $log.id} style="background-color: #333333;"{/if}>{$log.date}</td>
        <td{if $id == $log.id} style="background-color: #333333;"{/if}>{$log.module}</td>
        <td{if $id == $log.id} style="background-color: #333333;"{/if}>{$log.message}</td>
        <td{if $id == $log.id} style="background-color: #333333;"{/if}>{$log.level}</td>
    </tr>
    {/foreach}
</table>
<br />
<a href="javascript:void(0)" onclick="javascript: backToLogOverview();">Back to Log Overview</a>