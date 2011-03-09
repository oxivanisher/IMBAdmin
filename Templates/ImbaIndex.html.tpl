<div id='test'></div>

<div class='imbaSsoLoginBorder ui-widget ui-widget-content ui-corner-all'>
    <div id='imbaSsoLogin'>
        <div id='imbaSsoLoginInner'>
            <img id='imbaSsoLogo' src='Images/guild_logo.png' alt='Guild Logo' title='Show/Hide Menu' />
            <form id='imbaSsoLoginForm' action='ImbaAuth.php' method='get'>
                <input name='openid' type='text' style='width: 120px; margin-right: 6px;' />
                <input id='imbaSsoOpenIdSubmit' type='submit' />
            </form>
            <img id='imbaGotMessage' src='Images/message.png' alt='M' title='Open Messaging' />
        </div>
        <span id='imbaOpenMessaging' class='ui-icon ui-icon-comment'>Open Messaging</span>
        <div id='imbaUsersOnline'>1 On 3 AFK von 74</div>
        <select id='imbaUsers' size='5' ></select>        
    </div>
</div>

<div id='imbaMessagesDialog' title='IMBA Messaging'>
    <div id='imbaMessages'>
        <ul></ul>
        <div id='imbaMessageTextDiv'>
            <form action='' method='post'>
                Message: <input id='imbaMessageText' type='text' autocomplete='off' />
                <input id='imbaMessageTextSubmit' type='submit' value='Send'/>
            </form>
        </div>
    </div>
</div>

<div id='imbaContentDialog' title='IMBAdmin'>
    <div id='imbaContentNav'>
        <ul></ul>
    </div>
</div>