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
<table id="ImbaWebUsersViewprofileTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
        <!--        <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr> -->
    </thead>
    <tbody>

        <tr><td colspan="2"><h2><img src="{$roleIcon}" width="20" height="20" title="{$role}" /> {$nickname}</h2></td></tr>
        <tr><td colspan="2">({$firstname} {$lastname}, {$birthday}.{$birthmonth}.{$birthyear})</td></tr>
        <tr><td><i>Letzter Login {$lastLogin}</i></td></tr>
        <tr><td><br /><br /></td><td>&nbsp;</td></tr>
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