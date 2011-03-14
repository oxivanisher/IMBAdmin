<h2><img src="{$roleIcon}" width="20" height="20" title="{$role}" /> {$nickname}
    {if $sex != ""}
    <img src="{$sex}" />
    {/if}
    {if $usertitle != ""}
    <i>"{$usertitle}"</i>
    {/if}
    {if $avatar != ""}
    <img src="{$avatar}" style="float: left; margin: 5px; border: grey 2px solid;" />
    {/if}
</h2>
{$firstname} {$lastname}, {$birthday}.{$birthmonth}.{$birthyear}<br />
<i>(Deine OpenID ist {$openid})</i><br />
<br />

<table id="ImbaWebUsersViewprofileTable" style="cellspacing: 1px;">
    <tbody>
        <tr><td>Aktuelles Motto:</td><td><input type="text" name="motto" value="{$motto}" /></td><td rowspan="8">
                <ul>
                    <li><i>Um deinen Namen das Geschlecht oder dein Geburtstag zu &auml;ndern, kontaktiere bitte einen Administrator.</i></li>
                    <li><i>Die Emailadresse wird ausschliesslich gebraucht um mit dir Kontakt aufzunehmen.</i></li>
                    <li><i>Dein Nachname wird f&uuml;r alle anderen auf einen Buchstaben gek&uuml;rzt ({$firstname} {$shortlastname}).</i></li>
                </ul>                
            </td></tr>
        <tr><td>Nickname:</td><td><input type="text" name="nickname" value="{$nickname}" /></td></tr>
        <tr><td>Titel:</td><td><input type="text" name="usertitle" value="{$usertitle}" /></td></tr>
        <tr><td>Avatar URL:</td><td><input type="text" name="avatar" value="{$avatar}" /></td></tr>
        <tr><td>Webseite:</td><td><input type="text" name="website" value="{$website}" /></td></tr>
        <tr><td>Email:</td><td><input type="text" name="email" value="{$email}" /></td></tr>
        <tr><td>Skype:</td><td><input type="text" name="skype" value="{$skype}" /></td></tr>
        <tr><td>ICQ:</td><td><input type="text" name="icq" value="{$icq}" /></td></tr>
        <tr><td>MSN:</td><td><input type="text" name="msn" value="{$msn}" /></td></tr>
        <tr><td>Signatur:</td><td colspan="2"><textarea name="signature" rows="4" cols="50">{$signature}</textarea></td></tr>
    </tbody>
</table>