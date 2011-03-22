<script type="text/javascript">
    function backToUserOverview(){
        var data = {
            module: "User",
            request: "overview"
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<div style='float: left;'>
    <br />
    {if $avatar != ""}
    <img src="{$avatar}" style="float: left; margin: 5px; border: grey 2px solid;" /><br />
    {/if}
    <img src="{$roleIcon}" width="20" height="20" title="{$role}" />
    {if $sex != ""}
    <img src="{$sex}" />
    {/if}
    {if $myownprofile != true}
    <span class='ui-icon ui-icon-comment' style='float: left; cursor: pointer;' onclick="javascript:createChatWindow('{$nickname}', '{$openid}');"></span>
    {/if}
</div>
<div style='float: left; vertical-align: top; text-align: top;'>
    <h2>{$nickname}
        {if $usertitle != ""}
        <i>"{$usertitle}"</i>
        {/if}
    </h2>
    {$firstname} {$lastname}, {$birthday}.{$birthmonth}.{$birthyear}, Zuletzt online {$lastonline}<br />
    {if $motto}
    Aktuelles Motto: <b>"{$motto}"</b><br />
    {/if}
</div>
<div style='clear: both;' />
{if $signature != ""}
<h3>Signatur:</h3>
<pre>
{$signature}
</pre>
{/if}
<br />
<br />
<table id="ImbaAjaxBlindTable" cellpadding="3" cellspacing="0" border="0">
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
{if $myownprofile != true}
<br /><a href="javascript:void(0)" onclick="javascript: backToUserOverview();">back</a>
{/if}