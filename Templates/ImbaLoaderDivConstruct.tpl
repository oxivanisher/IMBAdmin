{* 
    Basic div construct which will be injected into the website
    
*}{strip}<div id='imbaSsoLoginBorder' class='ui-widget-content ui-corner-all'>
    <div id='imbaSsoLogin'>
        <img id='imbaSsoLogoImage' src='{$thrustRoot}Images/noicon.png' alt='Guild Logo' title='Show/Hide Menu' />
        <div id='imbaSsoLoginInner'>
            <form id='imbaSsoLoginForm' action='{$thrustRoot}{$authPath}' method='post'>
                <input id='imbaSsoOpenId' name='openid' type='text' />
                <input id='imbaSsoOpenIdLoginReferer' name='imbaSsoOpenIdLoginReferer' value='{$thrustRoot}' type='hidden' />
                <br />
                <span id='imbaSsoOpenIdSubmit'>Login / Registrieren</span>
            </form>
            <form id='imbaSsoLogoutForm' action='{$thrustRoot}{$authPath}' method='post'>
                <span id='imbaSsoShowNickname' onclick='loadImbaAdminModule(\"User\", \"editmyprofile\");'></span>
                <input id='imbaSsoOpenIdLogoutReferer' name='imbaSsoOpenIdLogoutReferer' value='{$thrustRoot}' type='hidden' />
                <input name='logout' value='true' type='hidden' />
                <br />
                <span id='imbaSsoOpenIdSubmitLogout'>Logout</span>
            </form>
        </div>
        <div style='clear: both;' ></div>
        <div id='imbaUsersOnline' ></div>
        <div id='imbaOpenMessaging' class='ui-icon ui-icon-comment'>Open Messaging</div>
        <div id='imbaGotMessage' class='ui-icon ui-icon-mail-closed' title='Open Messaging' >M</div>
    </div>
</div>
<div id='imbaMessagesDialog' title='IMBA Messaging' style='padding: 3px;'>
    <div id='imbaMessages'>
        <ul></ul>
        <form action='' method='post'>
            <div id='imbaChatConversation' style='float: left; width: 80%; overflow: auto;'></div>
            <div id='imbaChatConversationUserlist' style='float: right; width: 19%; overflow: auto;'></div>
            <div style='clear: both;' ></div>
            <div id='imbaChatConversationMessage'>
                Message: <input id='imbaMessageText' type='text' autocomplete='off' style='width: 70%;' /> &nbsp; <input id='imbaMessageTextSubmit' type='submit' value='Send'/>
            </div>
        </form>
    </div>
</div>
<div id='imbaContentDialog' title='IMBAdmin' style='padding: 3px;'>
    <div id='imbaContentNav' style='height: 98%; overflow: auto;'>
        <ul></ul>
    </div>
</div>
<div id='imbaGameDialog' title='IMBAGame' style='padding: 3px;'>
    <div id='imbaGameNav' style='height: 98%; overflow: auto;'>
        <ul></ul>
    </div>
</div>{/strip}