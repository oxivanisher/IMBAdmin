  
<h2><img src="{$roleIcon}" width="20" height="20" title="{$role}" /> {$nickname}
    {if $sex != ""}
    <img src="{$sex}" />{$sex}
    {/if}
    {if $motto != ""}
    <i>"{$motto}"</i>
    {/if}
    {if $avatar != ""}
    <img src="{$avatar}" style="float: left; margin: 5px; border: grey 2px solid;" />
    {/if}
</h2>
{$firstname} {$lastname}, {$birthday}.{$birthmonth}.{$birthyear}<br />
<i>(Deine OpenID ist {$openid})</i><br />
<br />
<i>Um deinen Namen das Geschlecht oder dein Geburtstag zu &auml;ndern, kontaktiere bitte einen Administrator.</i>

<table id="ImbaWebUsersViewprofileTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
        <!--        <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr> -->
    </thead>
    <tbody>
        <tr><td>Nickname:</td><td>{$email}</td></tr>
        <tr><td>Vorname:</td><td>{$firstname}</td></tr>
        <tr><td>Nachname:</td><td>{$lastname}</td></tr>
        <tr><td>Geburtsdatum:</td><td>{$birthday}.{$birthmonth}.{$birthyear}</td></tr>
        <tr><td>ICQ:</td><td>{$icq}</td></tr>
        <tr><td>MSN:</td><td>{$msn}</td></tr>
        <tr><td>Skype:</td><td>{$skype}</td></tr>
        <tr><td>Webseite:</td><td>{$website}</td></tr>
        <tr><td>Titel:</td><td>{$usertitle}</tr>
        <tr><td>Avatar:</td><td>{$avatar}</tr>
    </tbody>
</table>
{$signature}

