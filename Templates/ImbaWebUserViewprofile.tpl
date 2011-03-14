<script type="text/javascript">
    function backToUserOverview(){
        var data = {
            action: "module",
            module: "User",
            request: "overview"
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<h2><img src="{$roleIcon}" width="20" height="20" title="{$role}" /> {$nickname}
    {if $sex != ""}
    <img src="{$sex}" />{$sex}
    {/if}
    {if $motto != ""}
    <i>"{$motto}"</i>
    {/if}
    {if $avatar != ""}
    <img src="{$avatar}" style="float: right;" />
    {/if}
</h2>
{$firstname} {$lastname}, {$birthday}.{$birthmonth}.{$birthyear}, Zuletzt online {$lastonline}<br />
{if $signature != ""}
<h3>Signatur:</h3>
<code>
{$signature}
</code>
{/if}
<br />
<br />
<table id="ImbaWebUsersViewprofileTable" cellpadding="3" cellspacing="0" border="0">
    <tbody>
        {if $icq != "0"}
        <tr><td>ICQ:</td><td><img src="http://online.mirabilis.com/scripts/online.dll?icq={$icq}&img=5" alt="" /> {$icq}</td></tr>
        {/if}
        {if $msn != ""}
        <tr><td>MSN:</td><td>{$msn}</td></tr>
        {/if}
        {if $skype != ""}
        <tr><td>Skype:</td><td><a href="skype:{$skype}?call">{$skype}</a></td></tr>
        {/if}
        {if $website != ""}
        <tr><td>Webseite:</td><td><a href="{$website}" target="_blank">{$website}</a></td></tr>
        {/if}
        {if $games != ""}
        <tr><td>Games:</td><td>{$games}</td></tr>
        {/if}

    </tbody>
</table>
<br />
<a href="javascript:void(0)" onclick="javascript: backToUserOverview();">back</a>