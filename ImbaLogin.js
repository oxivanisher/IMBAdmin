/**
 * TODO: Beschreibung erstellen
 */
// Single point of Ajax entry   
var ajaxEntry = "ajax.php";

// Test if user is online, if then show chat, else hide
$(document).ready(function() {
    // Init, hide Windows
    $("#imbaContentDialog").dialog({
        autoOpen: false
    })
    .dialog("option", "width", 600)
    .dialog( "option", "height", 480 );
    
    $("#imbaContentNav").tabs().bind("tabsselect", function(event, ui) {
        var tmpTabId = "";
        $.each($("#imbaContentNav a"), function (k, v) {
            if (k == ui.index){
                var tmp = v.toString().split("#");
                tmpTabId = "#" + tmp[1];
            }
        });
        
        $.post(ajaxEntry, {
            action : "mod_user", 
            tabId : tmpTabId
        }, function(response){
            $(tmpTabId).html(response);
        });        
    });

    $.post(ajaxEntry, {
        action: "navigation",
        navigation_for_user : true
    }, function (response){
        $.each($.parseJSON(response), function(key, value){            
            $("#imbaContentNav").tabs("add", "#" + value.id, value.name);
            if (key == 0){
                $.post(ajaxEntry, {
                    action : "mod_user", 
                    tabId : "#" + value.id
                }, function(response){
                    $("#" + value.id).html(response);
                });  
            }
        });
    });
    
    $.post(ajaxEntry, {
        action: "user"
    }, function (response){
        if (response == "Not logged in"){
            $("#imbaUsers").hide();
        } 
    });
    
    // Menu jQuery
    $("ul.subnav").parent().append("<span></span>"); 
    $("ul.topnav li span").click(function() { 

        $(this).parent().find("ul.subnav").slideDown('fast').show(); 

        $(this).parent().hover(function() {
            }, function(){
                $(this).parent().find("ul.subnav").slideUp('slow');
            });         
    }); 
    var menuIsThere = true;
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
    
    // show ImbAdmin
    $("#imbaMenuImbAdmin").click(function(){
        $("#imbaContentDialog").dialog("open");
    });
    
});

String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};
