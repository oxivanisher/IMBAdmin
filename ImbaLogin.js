/**
 * TODO: Beschreibung erstellen
 */
// Storring if user is logged in
var isUserLoggedIn = false;

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
 * Sets the user loggedin
 */
function setLoggedIn(isLoggedIn){
    if (isLoggedIn){
        $("#imbaSsoOpenIdSubmit").val("Logout");
        $("#imbaOpenMessaging").show();
    } else {
        $("#imbaSsoOpenIdSubmit").val("Login");
        $("#imbaOpenMessaging").hide();
    }

    isUserLoggedIn = isLoggedIn;
}

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
    
    $("#imbaSsoLoginInner").show("slide", {
        direction: "right"
    });
}

/**
* Hids the Menu and stuff around
*/
function hideMenu() {
    // run the effect
    $("#imbaMenu").hide("slide", {
        direction: "right"
    });

    $("#imbaSsoLoginInner").hide("slide", {
        direction: "right"
    });
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

function loadImbaAdminDefaultModule(moduleName){
    /**
 * Get the default module
 */
    $.post(ajaxEntry, {
        action: "navigation",
        request: "getDefault"
    }, function (response){
        /**
     * Call the loadImbaAdminModule to open the dialog
     */
        loadImbaAdminModule(response.toString());
    });
}
/**
* loads the ImbaAdmin module in the IMBAdmin window
*/
function loadImbaAdminModule(moduleName){
    /**
     * Set the window title
     */
    $.post(ajaxEntry, {
        action: "navigation",
        request: "name",
        module: moduleName
    }, function (response){
        $("#imbaContentDialog").dialog({
            title: "IMBAdmin " + response
        });
    });

    /**
     * Remove all tabs
     */
    $("#imbaContentNav").tabs("destroy");
    
    /**
     * Create new tabs on element
     */
    $("#imbaContentNav").tabs();
    
    /**
     * get and render tabs
     */
    $.post(ajaxEntry, {
        action: "navigation",
        request: "nav",
        module: moduleName
    }, function (response){
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

    // Setting up the content of the Dialog as tabs
    $("#imbaContentNav").tabs().bind("tabsselect", function(event, ui) {
        var tmpTabId = "";
        $.each($("#imbaContentNav a"), function (k, v) {
            if (k == ui.index){
                var tmp = v.toString().split("#");
                tmpTabId = "#" + tmp[1];
            }
        });
    });
    
    /**
 * Show the dialog
 */
    $("#imbaContentDialog").dialog("open");
    showImbadminModuleNav();

}