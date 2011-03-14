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

        <tr><td>&nbsp;</td><td><img src="{$roleIcon}" width="20" height="20" title="{$role}" /> {$nickname}</td></tr>
        <tr><td colspan="2"><i>({$firstname} {$lastname})</i></td></tr>
        <tr><td>Geburtsdatum:</td><td>{$birthday}.{$birthmonth}.{$birthyear}</td></tr>
        {if $icq != ""}
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
        <tr><td>Letzter Login:</td><td>{$lastLogin}</td></tr>

    </tbody>
</table>
<br />
<a href="javascript:void(0)" onclick="javascript: backToUserOverview();">back</a>