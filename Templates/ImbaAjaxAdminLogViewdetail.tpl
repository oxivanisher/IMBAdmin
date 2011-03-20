
<h2>Actual Entry</h2>
<table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
    <tr><th>Date</th><td>{$date} {$age}</td></tr>
    <tr><th>User</th><td>{$user} ({$openid})</td></tr>
    <tr><th>Ip</th><td>{$ip} ({$city})</td></tr>
    <tr><th>Module</th><td>{$module}</td></tr>
    <tr><th>Session</th><td>{$session}</td></tr>
    <tr><th>Level</th><td>{$level}</td></tr>
    <tr><th>Message</th><td>{$message}</td></tr>
</table>
<h2>User Session</h2>
<table>
    <tr><th>Date</th><th>Module</th><th>Message</th><th>Level</th></tr>
    {foreach $logs as $log}
    <tr><td>{$log.date}</td><td>{$log.module}</td><td>{$log.message}</td><td>{$log.level}</td></tr>
    {/foreach}
</table>