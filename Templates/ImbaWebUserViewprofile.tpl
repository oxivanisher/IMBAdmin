{debug}
<table id="ImbaWebUsersViewprofileTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
<!--        <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr> -->
    </thead>
    <tbody>

        <tr><td>Nickname:</td><td>{$user.nickname}</td></tr>
        <tr><td>Vorname:</td><td>{$user.firstname}</td></tr>
        <tr><td>Nachname:</td><td>{$user.lastname}</td></tr>
        <tr><td>Geburtsdatum:</td><td>{$user.birthday}.{$user.birthmonth}.{$user.birthyear}</td></tr>
        <tr><td>ICQ:</td><td>{$user.icq}</td></tr>
        <tr><td>MSN:</td><td>{$user.msn}</td></tr>
        <tr><td>Skype:</td><td>{$user.skype}</td></tr>
        <tr><td>Webseite:</td><td><a href="javascript: viewUserProfile('{$user.website}');">{$user.website}</a></td></tr>
        <tr><td>Rang:</td><td>{$user.role}</td></tr>
        <tr><td>Games:</td><td>{$user.games}</td></tr>
        <tr><td>Letzter Login:</td><td>{$user.lastlogin}</td></tr>
        
    </tbody>
</table>