<div id='test' style='position: absolute; left: 0; top: 0;'></div>

<div id='imbaSsoLoginBorder' class='ui-widget-content ui-corner-all'>
    <div id='imbaSsoLogin' class='imbaFont'>
        <img id='imbaSsoLogo' src='/IMBAdmin/Images/guild_logo.png' alt='Guild Logo' title='Show/Hide Menu' />
        <div id='imbaSsoLoginInner'>
            <form id='imbaSsoLoginForm' action='ImbaAuth.php' method='get'>
                <input id='imbaSsoOpenId' name='openid' type='text' class='imbaInput' />
                <br />
                <input id='imbaSsoOpenIdSubmit' type='submit' value='Login' class='imbaInput' />
            </form>
        </div>
        <div style='clear: both;' ></div>
        <div id='imbaUsersOnline' ></div>
        <div id='imbaOpenMessaging' class='ui-icon ui-icon-comment'>Open Messaging</div>
        <div id='imbaGotMessage' class='ui-icon ui-icon-mail-closed' title='Open Messaging' >M</div>        
    </div>
</div>
<div id='imbaMessagesDialog' title='IMBA Messaging' class='imbaFont' style='padding: 3px;'>
    <div id='imbaMessages' style='height: 98%; overflow: auto;'>
        <ul></ul>
        <div id='imbaMessageTextDiv'>
            <form action='' method='post'>
                Message: <input id='imbaMessageText' type='text' autocomplete='off' />
                <input id='imbaMessageTextSubmit' type='submit' value='Send'/>
            </form>
        </div>
    </div>
</div>

<div id='imbaContentDialog' title='IMBAdmin' class='imbaFont' style='padding: 3px;'>
    <div id='imbaContentNav' style='height: 98%; overflow: auto;'>
        <ul></ul>
    </div>
</div>