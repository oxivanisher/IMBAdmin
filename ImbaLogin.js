/**
 * TODO: Beschreibung erstellen
 */
// Single point of Ajax entry   
var ajaxEntry = "ajax.php";
var currentModule = "User";

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
            action : "module", 
            tabId : tmpTabId
        }, function(response){
            $(tmpTabId).html(response);
        });        
    });

    $.post(ajaxEntry, {
        action: "navigation",
        request: "nav",
        module: currentModule
    }, function (response){
        $.each($.parseJSON(response), function(key, value){            
            $("#imbaContentNav").tabs("add", "#" + value.id, value.name);
            if (key == 0){
                $.post(ajaxEntry, {
                    action : "module", 
                    tabId : "#" + value.id
                }, function(response){
                    $("#" + value.id).html(response);
                });  
            }
        });
    });
    // Huhu aggra :) wenn man hier noch nen request mit "request = name" abschickt, kriegst du den titel fÃ¼r den dialog (IMBAdmin: XXXX oder sowas)
    
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
        var subNav = $(this).parent().find("ul.subnav");
        if (subNav.is(":hidden")){
            subNav.slideDown('fast').show();
        }else {
            subNav.slideUp('fast').show();
        }
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
        $("#imbaMenu").show("slide", {
            direction: "right"
        });

        $("#imbaUsers").show("slide", {
            direction: "up"
        });
    }
    
    function hideMenu() {        
        // run the effect
        $("#imbaMenu").hide("slide", {
            direction: "right"
        });

        $("#imbaUsers").hide("slide", {
            direction: "up"
        });
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

/**
     * Returns the current selected tab index
     */
function getSelectedImbaAdminTabIndex(){
    return $('#imbaContentNav').tabs('option', 'selected');
}

/**
     * Return the Id of a tab from a tabIndex
     * 
     */
function getImbaAdminTabIdFromTabIndex(tabIndex){
    var result = "";
    $.each($("#imbaContentNav a"), function (k, v) {
        if (k == tabIndex){
            var tmp = v.toString().split("#");
            result = "#" + tmp[1];
        }
    });

    return result;
}
    
function loadImbaAdminTabContent(data) {
    $.post(ajaxEntry, data, function (response){
        if (response != ""){
            $(getImbaAdminTabIdFromTabIndex(getSelectedImbaAdminTabIndex())).html(response);
        }
    });
}

function loadImbaAdminModule(myModule){
    currentModule = myModule;
    var data = {
        action: "module",
        module: currentModule
    };
    loadImbaAdminTabContent(data);
    $("#imbaContentDialog").dialog("open");
}