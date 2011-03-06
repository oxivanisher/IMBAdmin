<div id='test'></div>
<select id='imbaUsers' size='10' ></select>
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