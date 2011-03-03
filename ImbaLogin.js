/**
 * TODO: Beschreibung erstellen
 */

// TODO: /w mit autocomplete => namen anbieten
// TODO: * für neue nachricht (New == 1)

// Single point of Ajax entry   
var ajaxEntry = "ajax.php";

// Test if user is online, if then show chat, else hide
$(document).ready(function() {
    $.post(ajaxEntry, {
        action: "user"
    }, function (response){
        if (response == "Not logged in"){
            $("#imbaUsers").hide();         
            $("#imbaMessagesDialog").hide();         
        } 
    })
});

// Write ImbaOutput
// TODO: Mach mich schoen!
/*htmlContent = " \
    <div id='test'></div> \
    <select id='imbaUsers' size='10' ></select> \
    <div class='imbaSsoLoginBorder ui-widget ui-widget-content ui-corner-all'></div> \
    <div id='imbaSsoLogin'> \
        <div id='imbaSsoLoginInner'> \
            <img id='imbaSsoLogo' src='Images/guild_logo.png' alt='Guild Logo' /> \
            <form id='imbaSsoLoginForm' action='ImbaAuth.php' method='get'> \
                <input name='openid' type='text' /> \
                <input id='imbaSsoOpenIdSubmit' type='submit' /> \
            </form> \
        </div> \
    </div> \
    <div id='imbaMessagesDialog' title='Alptr&ouml;im Messaging'> \
        <div id='imbaMessages'> \
            <ul></ul> \
            <div id='imbaMessageTextDiv'> \
                <form action='' method='post'> \
                    <input id='imbaMessageText' type='text' /> \
                    <input id='imbaMessageTextSubmit' type='submit' value='Send'/> \
                </form> \
            </div> \
        </div> \
    </div> \
    ";
document.write(htmlContent);
*/
/**
* Backup for if the cosmetics did not work
*/
document.writeln('<div id="test"></div>');
document.writeln('<select id="imbaUsers" size="10" ></select>');
document.writeln('<div class="imbaSsoLoginBorder ui-widget ui-widget-content ui-corner-all"></div>');
document.writeln('<div id="imbaSsoLogin">');
document.writeln('<div id="imbaSsoLoginInner">');
document.writeln('<img id="imbaSsoLogo" src="Images/guild_logo.png" alt="Guild Logo" />');
document.writeln('<form id="imbaSsoLoginForm" action="ImbaAuth.php" method="get">');
document.writeln('<input name="openid" type="text" />');
document.writeln('<input id="imbaSsoOpenIdSubmit" type="submit" />');
document.writeln('</form>');
document.writeln('</div>');
document.writeln('</div>');
document.writeln('<div id="imbaMessagesDialog" title="Alptr&ouml;im Messaging">');
document.writeln('<div id="imbaMessages">');
document.writeln('<ul></ul>');
document.writeln('<div id="imbaMessageTextDiv">');
document.writeln('<form action="" method="post">');
document.writeln('<input id="imbaMessageText" type="text" />');
document.writeln('<input id="imbaMessageTextSubmit" type="submit" value="Send"/>');
document.writeln('</form>');
document.writeln('</div>');
document.writeln('</div>');
document.writeln('</div>');
