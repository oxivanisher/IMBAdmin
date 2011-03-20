<h2>User Infos</h2>
{$user} ({$openid}), {$city} ({$ip})
<h2>Actual Entry</h2>
<table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
    <tr><td>Date</td><td>{$date} {$age}</td></tr>
    <tr><td>Module</td><td>{$module}</td></tr>
    <tr><td>Session</td><td>{$session}</td></tr>
    <tr><td>Level</td><td>{$level}</td></tr>
    <tr><td>Message</td><td>{$message}</td></tr>
</table>
<h2>Other Session Messages</h2>
<table>
    <tr><th>Date</th><th>Module</th><th>Message</th><th>Level</th></tr>
    {foreach $logs as $log}
    <tr><td>{$log.date}</td><td>{$log.module}</td><td>{$log.message}</td><td>{$log.level}</td></tr>
    {/foreach}
</table>