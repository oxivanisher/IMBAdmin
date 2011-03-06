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
    