<script type="text/javascript">
    $(document).ready(function() {
        // User submits the ImbaAjaxUsersViewprofileForm
        $("#ImbaAjaxUsersViewprofileFormSubmit").click(function(){
            // submit the change
            $.post(ajaxEntry, {
                action: "module",
                module: "User",
                request: "updatemyprofile",
                myProfileOpenId: "{$openid}",
                myProfileMotto: $("#myProfileMotto").val(),
                myProfileUsertitle: $("#myProfileUsertitle").val(),
                myProfileAvatar: $("#myProfileAvatar").val(),
                myProfileWebsite: $("#myProfileWebsite").val(),
                myProfileNickname: $("#myProfileNickname").val(),
                myProfileEmail: $("#myProfileEmail").val(),
                myProfileSkype: $("#myProfileSkype").val(),
                myProfileIcq: $("#myProfileIcq").val(),
                myProfileMsn: $("#myProfileMsn").val(),
                myProfileSignature: $("#myProfileSignature").val()
            }, function(response){
                if (response != "Ok"){
                   // $.jGrowl('Daten wurden nicht gespeichert!', { header: 'Error' });
                   $.jGrowl(response, { header: 'Error' });
                } else {
                    $.jGrowl('Daten wurden gespeichert!', { header: 'Erfolg' });
                }
            });
            // TODO: Refresh from Database?
            return false;
        });
    } );   
</script>
<form id="ImbaAjaxUsersViewprofileForm" action="post">
    <table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
        <tbody>
            <tr>
                <td>
                    <nobr>Aktuelles Motto:</nobr>
                </td>
                <td>
                    <input id="myProfileMotto" type="text" name="motto" value="{$motto}" />
                </td>
                <td rowspan="8">
                    <ul>
                        <li><i>Um deinen Namen das Geschlecht oder dein Geburtstag zu &auml;ndern, kontaktiere bitte einen Administrator.</i></li>
                        <li><i>Die Emailadresse wird ausschliesslich gebraucht um mit dir Kontakt aufzunehmen.</i></li>
                        <li><i>Dein Nachname wird f&uuml;r alle anderen auf einen Buchstaben gek&uuml;rzt ({$firstname} {$shortlastname}).</i></li>
                    </ul>
                </td>
                </tr>

                <tr>
                    <td>Titel:</td>
                    <td><input id="myProfileUsertitle" type="text" name="usertitle" value="{$usertitle}" /></td>
                </tr>
                <tr>
                    <td>Avatar URL:</td>
                    <td><input id="myProfileAvatar" type="text" name="avatar" value="{$avatar}" /></td>
                </tr>
                <tr>
                    <td>Webseite:</td>
                    <td><input id="myProfileWebsite" type="text" name="website" value="{$website}" /></td>
                </tr>
                <tr>
                    <td>Nickname:</td>
                    <td><input id="myProfileNickname" type="text" name="nickname" value="{$nickname}" /></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input id="myProfileEmail" type="text" name="email" value="{$email}" /></td>
                </tr>
                <tr>
                    <td>Skype:</td>
                    <td><input id="myProfileSkype" type="text" name="skype" value="{$skype}" /></td>
                </tr>
                <tr>
                    <td>ICQ:</td>
                    <td><input id="myProfileIcq" type="text" name="icq" value="{$icq}" /></td>
                </tr>
                <tr>
                    <td>MSN:</td>
                    <td><input id="myProfileMsn" type="text" name="msn" value="{$msn}" /></td>
                </tr>
                <tr>
                    <td>Signatur:</td>
                    <td colspan="2"><textarea id="myProfileSignature" name="signature" rows="4" cols="50">{$signature}</textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="2"><input id="ImbaAjaxUsersViewprofileFormSubmit" type="submit" value="Speichern" /></td>
                </tr>
        </tbody>
    </table>
</form>