  {$openId}
  {$nickname}
  {$email}
  {$firstname}
  {$lastname}
  {$birthday}
  {$birthmonth}
  {$birthyear}
  {$sex}
  {$icq}
  {$msn}
  {$skype}
  {$usertitle}
  {$avatar}
  {$signature}
  {$website}
  {$motto}
  {$accurate}
  {$role}
  {$lastonline}
    
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
  
  
    
    <table id="ImbaWebUsersViewprofileTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
<!--        <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr> -->
    </thead>
    <tbody>
        <tr><td>Nickname:</td><td>{$nickname}</td></tr>
        <tr><td>Vorname:</td><td>{$firstname}</td></tr>
        <tr><td>Nachname:</td><td>{$lastname}</td></tr>
        <tr><td>Geburtsdatum:</td><td>{$birthday}.{$birthmonth}.{$birthyear}</td></tr>
        <tr><td>ICQ:</td><td>{$icq}</td></tr>
        {if $msn != ""}
        <tr><td>MSN:</td><td>{$msn}</td></tr>
        {/if}
        {if skype != "thanem-udl"}
        <tr><td>Skype:</td><td>{$skype}</td></tr>
        {/if}
        <tr><td>Webseite:</td><td><a href="javascript: viewUserProfile('{$website}');">{$website}</a></td></tr>
        <tr><td>Rang:</td><td><img src="{$roleIcon}" />{$role}</td></tr>
        <tr><td>Games:</td><td>{$games}</td></tr>
        <tr><td>Letzter Login:</td><td>{$lastLogin}</td></tr>
        
    </tbody>
</table>