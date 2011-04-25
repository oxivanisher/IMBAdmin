<script type="text/javascript">

    $(document).ready(function() {
        $("#imbaMaintenanceLogViewdetailBack").button();
    });
    
    function showLogDetail(id){
        var data = {
            module: "Maintenance",
            request: "viewlogdetail",
            secSession: "{$secSession}",
            id: id
        };
        loadImbaAdminTabContent(data);
    }

    function backToLogOverview(){
        var data = {
            module: "Maintenance",
            request: "log",
            secSession: "{$secSession}"
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<b><span{if $openid} onclick="javascript: showUserProfile('{$openid}')" style="cursor: pointer;";{/if}>{$user}</span>{if $openid} ({$openid}){/if}</b><br />
{$city} ({$ip})<br />
<i>Session: {$session}</i>
<br />
<br />
<table class="ImbaAjaxBlindTable" style="cellspacing: 1px;">
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
<a id="imbaMaintenanceLogViewdetailBack" href="javascript:void(0)" onclick="javascript: backToLogOverview();">Back to Log Overview</a>