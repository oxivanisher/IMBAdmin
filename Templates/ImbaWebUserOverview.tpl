<table>
    <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr>
    {foreach $users as $user}
    <tr><td><a href="{$user->getOpenId}">{$user-getNickname}</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    {/foreach}
</table>