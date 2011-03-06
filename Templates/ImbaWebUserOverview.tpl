<script>alert('huhu')</script>
<table>
    <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr>
    {foreach $susers as $user}
    <tr><td><a href="?action={$action}&tabId={$tabId}&module={$module}&openid={$user.openid}">{$user.nickname}</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    {/foreach}
</table>