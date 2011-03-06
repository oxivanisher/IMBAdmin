/**
 * TODO: Beschreibung erstellen
 */
// Single point of Ajax entry   
var ajaxEntry = "ajax.php";

// Test if user is online, if then show chat, else hide
$(document).ready(function() {

    $("ul.subnav").parent().append("<span></span>"); 

    $("ul.topnav li span").click(function() { 

        $(this).parent().find("ul.subnav").slideDown('fast').show(); 

        $(this).parent().hover(function() {
            }, function(){
                $(this).parent().find("ul.subnav").slideUp('slow');
            });         
    });
    
    $("#imbaMenu").hide();    
    var menuIsThere = false;
    $("#imbaSsoLogo").click(function() {
        if (!menuIsThere){
            showMenu();
            menuIsThere = true;
        }
        else {
            hideMenu();
            menuIsThere = false;
        }
        
        return false;
    });
    
    function showMenu() {        
        // run the effect
        $("#imbaMenu").effect("slide", {
            direction: "right"
        }, 1000, null);
    }
    
    function hideMenu() {        
        // run the effect
        $("#imbaMenu").hide();
    }
    
    $.post(ajaxEntry, {
        action: "user"
    }, function (response){
        if (response == "Not logged in"){
            $("#imbaUsers").hide();
            $("#imbaMessagesDialog").hide();
            $("#imbaContentDialog").hide();
        } 
    });
});

String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};

// Write ImbaOutput
// TODO: Mach mich schoen!
htmlContent = " \
    <div id='test'></div> \
    <select id='imbaUsers' size='10' ></select>\
       <div id='imbaMenu'>\
            <ul class='topnav'>\
                <li>\
                    <a href='#'>Communication</a>\
                    <ul class='subnav'>\
                        <li><a href='#'>Chat Kan&auml;le konfigurieren</a></li>\
                        <li><a href='#'>Private Nachrichten</a></li>\
                        <li><a href='#'>Benutzer &Uuml;bersicht</a></li>\
                    </ul>\
                </li>\
                <li>\
                    <a href='#'>Community Funktionen</a>\
                    <ul class='subnav'>\
                        <li><a href='#'>Multigaming konfigurieren</a></li>\
                        <li><a href='#'>Benutzer Profil</a></li>\
                    </ul>\
                </li>\
                <li>\
                    <a href='#'>Spiele</a>\
                    <ul class='subnav'>\
                        <li><a href='#'>WoW - Top 10</a></li>\
                        <li><a href='#'>WoW - Armory</a></li>\
                        <li><a href='#'>Eve - irgendwas</a></li>\
                    </ul>\
                </li>\
                <li>\
                    <a href='#'>Offizier Funktionen</a>\
                    <ul class='subnav'>\
                        <li><a href='#'>Registrieren / Best&auml;tigen</a></li>\
                        <li><a href='#'>Main / Twink Check</a></li>\
                        <li><a href='#'>Admin User Manager</a></li>\
                    </ul>\
                </li>\
            </ul>\
        </div>\
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
    <div id='imbaMessagesDialog' title='IMBA Messaging'> \
        <div id='imbaMessages'> \
            <ul></ul> \
            <div id='imbaMessageTextDiv'> \
                <form action='' method='post'> \
                    <input id='imbaMessageText' type='text' autocomplete='off' /> \
                    <input id='imbaMessageTextSubmit' type='submit' value='Send'/> \
                </form> \
            </div> \
        </div> \
    </div> \
    <div id='imbaContentDialog' title='IMBAdmin'> \
        <div id='imbaContent'> \
            <ul></ul> \
            <a href='ImbaAuth.php?logout=true'>destroy session</a> \
        </div> \
    <hr /> \
    </div> \
    ";
document.write(htmlContent);

/**
* Backup for if the cosmetics did not work

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
*/