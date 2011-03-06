<table>
    <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr>
    {foreach $susers as $user}
    <tr><td><a href="{$user.openid}">{$user.nickname}</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    {/foreach}
</table>