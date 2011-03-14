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
    {if $motto != ""}
    <i>"{$motto}"</i>
    {/fi}
    {if $avatar != ""}
    <img src="{$avatar}" style="float: right;" />
    {/fi}
</h2></td></tr>
({$firstname} {$lastname}, {$birthday}.{$birthmonth}.{$birthyear})<br />
<i>Letzter Login {$lastLogin}</i>
<br />
<br />
{if $signature != ""}
{$signature}
<br />
<br />
{/if}
<table id="ImbaWebUsersViewprofileTable" cellpadding="0" cellspacing="0" border="0">
    <tbody>
        {if $icq != "0"}
        <tr><td>ICQ:</td><td>{$icq}</td></tr>
        {/if}
        {if $msn != ""}
        <tr><td>MSN:</td><td>{$msn}</td></tr>
        {/if}
        {if $skype != ""}
        <tr><td>Skype:</td><td>{$skype}</td></tr>
        {/if}
        {if $website != ""}
        <tr><td>Webseite:</td><td><a href="$website" target="_blank">{$website}</a></td></tr>
        {/if}
        {if $games != ""}
        <tr><td>Games:</td><td>{$games}</td></tr>
        {/if}

    </tbody>
</table>
<br />
<a href="javascript:void(0)" onclick="javascript: backToUserOverview();">back</a>