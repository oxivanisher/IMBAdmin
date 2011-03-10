/**
 * TODO: Beschreibung erstellen
 */
// Storring if user is logged in
var isUserLoggedIn = false;

function setLoggedIn(isLoggedIn){
    isUserLoggedIn = isLoggedIn;
}

// Test if user is online, if then show chat, else hide
$(document).ready(function() {
    //login to wordpress ^^
    //$.post("../wordpress/wp-login.php", {log: "Aggravate",pwd: "test",rememberme: "1"});

    // Checking if user is online
    $.ajaxSetup({
        async:false
    });
    
    $.post(ajaxEntry, {
        action: "user"
    }, function (response){
        if (response == "Not logged in"){
            setLoggedIn(false);
        } else {
            setLoggedIn(true);
        }
    });

    // Setting up the Dialog for the ImbaAdmin
    $("#imbaContentDialog").dialog({
        autoOpen: false
    })
    .dialog("option", "width", 700)
    .dialog( "option", "height", 520 );

    // Hiding the online users div, when not logged in
    if (!isUserLoggedIn){
        $("#imbaUsers").hide();
    }
    
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

    // show ImbAdmin
    $("#imbaMenuImbAdmin").click(function(){
        $("#imbaContentDialog").dialog("open");
    });
    
});

/**
 * String formatting (not working in IE!!!)
 */
String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};

/**
 * Shows the Menu and stuff around
 */
function showMenu() {
    // run the effect
    $("#imbaMenu").show("slide", {
        direction: "right"
    });

    if(isUserLoggedIn){
        $("#imbaUsers").show("slide", {
            direction: "up"
        });
    }
}

/**
 * Hids the Menu and stuff around
 */
function hideMenu() {
    // run the effect
    $("#imbaMenu").hide("slide", {
        direction: "right"
    });

    if(isUserLoggedIn){
        $("#imbaUsers").hide("slide", {
            direction: "up"
        });
    }
}

/**
 * Returns the current selected tab index
 */
function getSelectedImbaAdminTabIndex(){
    return $('#imbaContentNav').tabs('option', 'selected');
}

/**
 * Return the Id of a tab from a tabIndex
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

/**
 * loads the ImbaAdminTab content, depending on the data for the post request
 */
function loadImbaAdminTabContent(data) {
    $.post(ajaxEntry, data, function (response){
        if (response != ""){
            $(getImbaAdminTabIdFromTabIndex(getSelectedImbaAdminTabIndex())).html(response);
        }
    });
}

/**
 * loads the ImbaAdmin module in the IMBAdmin window
 */
function loadImbaAdminModule(moduleName){
    /**
     * get and render tabs
     */
    $.post(ajaxEntry, {
        action: "navigation",
        request: "nav",
        module: moduleName
    }, function (response){
        //        $("#imbaContentNav").tabs() = null;
        //        $.each($("#imbaContentNav").tabs(), function(myId){
        $.each($("#imbaContentNav a"), function (k, v) {
            alert(myId);
            $("#imbaContentNav").tabs("remove", v);
        });
        $.each($.parseJSON(response), function(key, value){
            $("#imbaContentNav").tabs("add", "#" + value.id, value.name);
            if (key == 0){
                $.post(ajaxEntry, {
                    action : "module",
                    module: moduleName,
                    tabId : "#" + value.id
                }, function(response){
                    $("#" + value.id).html(response);
                });  
            }
        });
    });

    /**
     * get and render content
     */
    var data = {
        action: "module",
        module: moduleName
    };
    loadImbaAdminTabContent(data);

    /**
     * getting name for the window title
     * FIXME: this is not working
     */
    $.post(ajaxEntry, {
        action: "navigation",
        request: "name",
        module: moduleName
    }, function (response){
        $("#imbaContentDialog").title("IMBAdmin: " + response);
    });

    // Setting up the content of the Dialog as tabs
    // FIXME: clear tabs first
    $("#imbaContentNav").tabs().bind("tabsselect", function(event, ui) {
        var tmpTabId = "";
        $.each($("#imbaContentNav a"), function (k, v) {
            if (k == ui.index){
                var tmp = v.toString().split("#");
                tmpTabId = "#" + tmp[1];
            }
        });
        
    /*
         *       FIXME: what is this?
         *
         $.post(ajaxEntry, {
            action : "module",
            module: moduleName,
            tabId : tmpTabId
        }, function(response){
            $(tmpTabId).html(response);
        });        */
    });
    

    $("#imbaContentDialog").dialog("open");
}