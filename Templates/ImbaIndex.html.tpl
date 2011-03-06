<div id='test'></div>
<select id='imbaUsers' size='10' ></select>

<div id='imbaMenu'>
    <ul class='topnav'>
        <li>
            <a href='#'>Communication</a>
            <ul class='subnav'>
                <li><a href='#'>Chat Kan&auml;le konfigurieren</a></li>
                <li><a href='#'>Private Nachrichten</a></li>
                <li><a href='#'>Benutzer &Uuml;bersicht</a></li>
            </ul>
        </li>
        <li>
            <a href='#'>Community Funktionen</a>
            <ul class='subnav'>
                <li><a href='#'>Multigaming konfigurieren</a></li>
                <li><a href='#'>Benutzer Profil</a></li>
            </ul>
        </li>
        <li>
            <a href='#'>Spiele</a>
            <ul class='subnav'>
                <li><a href='#'>WoW - Top 10</a></li>
                <li><a href='#'>WoW - Armory</a></li>
                <li><a href='#'>Eve - irgendwas</a></li>
            </ul>
        </li>
        <li>
            <a href='#'>Offizier Funktionen</a>
            <ul class='subnav'>
                <li><a href='#'>Registrieren / Best&auml;tigen</a></li>
                <li><a href='#'>Main / Twink Check</a></li>
                <li><a href='#'>Admin User Manager</a></li>
            </ul>
        </li>
    </ul>
</div>

<div class='imbaSsoLoginBorder ui-widget ui-widget-content ui-corner-all'></div>
<div id='imbaSsoLogin'>
    <div id='imbaSsoLoginInner'>
        <img id='imbaSsoLogo' src='Images/guild_logo.png' alt='Guild Logo' />
        <form id='imbaSsoLoginForm' action='ImbaAuth.php' method='get'>
            <input name='openid' type='text' />
            <input id='imbaSsoOpenIdSubmit' type='submit' />
        </form>
    </div>
</div>
<div id='imbaMessagesDialog' title='IMBA Messaging'>
    <div id='imbaMessages'>
        <ul></ul>
        <div id='imbaMessageTextDiv'>
            <form action='' method='post'>
                <input id='imbaMessageText' type='text' autocomplete='off' />
                <input id='imbaMessageTextSubmit' type='submit' value='Send'/>
            </form>
        </div>
    </div>
</div>
<div id='imbaContentDialog' title='IMBAdmin'>
    <div id='imbaContent'>
        <ul></ul>
        <a href='ImbaAuth.php?logout=true'>destroy session</a>
    </div>
    <hr />
</div>