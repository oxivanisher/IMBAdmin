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
<ul>
    <li><i>Um deinen Namen das Geschlecht oder dein Geburtstag zu &auml;ndern, kontaktiere bitte einen Administrator.</i></li>
    <li><i>Die Emailadresse wird ausschliesslich gebraucht um mit dir Kontakt aufzunehmen.</i></li>
    <li><i>Dein Nachname wird f&uuml;r alle anderen auf einen Buchstaben gek&uuml;rzt ({$firstname} {$shortlastname}).</i></li>
</ul>
<table id="ImbaWebUsersViewprofileTable" style="cellspacing: 3px;">
    <tbody>
        <tr><td>Aktuelles Motto:</td><td><input type="text" name="motto" value="{$motto}" /></tr>
        <tr><td>Benutzerdefinierter Titel:</td><td>{$usertitle}</tr>
        <tr><td>Avatar URL:</td><td>{$avatar}</tr>
        <tr><td>Webseite:</td><td>{$website}</td></tr>
        <tr><td>Email:</td><td>{$email}</td></tr>
        <tr><td>Skype:</td><td>{$skype}</td></tr>
        <tr><td>ICQ:</td><td>{$icq}</td></tr>
        <tr><td>MSN:</td><td>{$msn}</td></tr>
    </tbody>
</table>
{$signature}