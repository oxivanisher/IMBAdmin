/**
 * TODO: Beschreibung erstellen
 */
// Storring if user is logged in
var isUserLoggedIn = false;
var currentModule = null;
var currentModuleDo = null;
var currentUserName = null;
var currentUserOpenid = null;


// Reload Online Users every 10000 ms
// TODO Set to 10 Sekunden
setInterval('refreshUsersOnline()', 10000);

// Test if user is online, if then show chat, else hide
$(document).ready(function() {
    //login to wordpress ^^
    //$.post("../wordpress/wp-login.php", {log: "Aggravate",pwd: "test",rememberme: "1"});

    // setting old openid
    var oldOpenId = unescape(decodeURIComponent(readCookie("ImbaSsoLastLoginName")));
    if (oldOpenId != null && oldOpenId != "null" && oldOpenId != ""){
        $("#imbaSsoShowOpenId").val(oldOpenId);
        $("#imbaSsoOpenId").val(oldOpenId);
    }
    
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

    /**
     * Menu
     */
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
    
    /*
     * Module
     */
    // Setting up the content of the Dialog as tabs
    $("#imbaContentNav").tabs().bind("tabsselect", function(event, ui) {
        var tmpTabId = "";
        $.each($("#imbaContentNav a"), function (k, v) {
            if (k == ui.index){
                var tmp = v.toString().split("#");
                
                tmpTabId = "#" + tmp[1];
                
                var data = {
                    action: "module",
                    module: currentModule,
                    moduleDo: currentModuleDo,
                    request: tmp[1]
                };
                loadImbaAdminTabContent(data, tmpTabId); 
            }
        });
    });
    
    /**
     * Setting up the Dialog for the ImbaAdmin
     */
    $("#imbaContentDialog").dialog({
        autoOpen: false
    })
    .dialog("option", "width", 700)
    .dialog("option", "height", 520);   
    
    // Firsttime show users online
    refreshUsersOnline();
});
   
/**
 * refreshing the users online tag cloud
 */ 
function refreshUsersOnline(){
    $.post(ajaxEntry, {
        action: "user",
        loadusersonlinelist : "true"
    }, function (response){
        //create list for tag links
        $("#imbaUsersOnline").html("");
        $("<ul>").attr("id", "imbaUsersOnlineTagList").appendTo("#imbaUsersOnline");
                
        //create tags
        $.each($.parseJSON(response), function(key, value){
            //create item
            var li = $("<li>");            
            li.text(value.name);
            li.appendTo("#imbaUsersOnlineTagList");
            li.css("fontSize", value.fontsize);
            li.css("color", value.color);
            li.attr("title", "Start Chat with " + value.name);
            
            li.click(function (){
                createChatWindow(value.name, value.openid);
            });            

        });        
    });    
    
        
}

/**
 * Sets the user loggedin
 */
function setLoggedIn(isLoggedIn){
    if (isLoggedIn){
        $("#imbaSsoLoginForm").hide();
        $("#imbaSsoLogoutForm").show();
        $("#imbaOpenMessaging").show();
    } else {
        $("#imbaSsoLoginForm").show();
        $("#imbaSsoLogoutForm").hide();
        $("#imbaOpenMessaging").hide();
    }

    isUserLoggedIn = isLoggedIn;
}

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
    
    $("#imbaUsersOnline").show("slide", {
        direction: "up"
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

    $("#imbaUsersOnline").hide("slide", {
        direction: "up"
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
function loadImbaAdminTabContent(data, myTabId) {
    var targetIabId = null;
    if (myTabId == null) {
        targetIabId = getImbaAdminTabIdFromTabIndex(getSelectedImbaAdminTabIndex());
    } else {
        targetIabId = myTabId;
    }
    
    $.post(ajaxEntry, data, function (response){
        if (response != ""){
            $(targetIabId).html(response);
        }
    });
}

/**
 * Show the User Profile in IMBAdmin window
 */
function showUserProfile(openid) {
    loadImbaAdminModule("User", "viewprofile", openid);
}

/**
 * Loads the default ImbaAdminTab
 */
function loadImbaAdminDefaultModule(){
    // Get the default module
    $.post(ajaxEntry, {
        action: "navigation",
        request: "getDefault"
    }, function (response){
        // Call the loadImbaAdminModule to open the dialog         
        loadImbaAdminModule(response.toString());
    });
}

/**
* Fills the variables currentUserName and currentUserOpenid
*/
function loadMyImbaUser() {
    var tmpReturn = null;
    $.post(ajaxEntry, {
        action: "user",
        returnmyself: true
    }, function (response){
        $.each($.parseJSON(response), function(key, value){
            if (key == name) {
                currentUserName = value;
            } else if (key == openid) {
                currentUserOpenid = value;
            }
        });
    });
    alert(currentUserName);
}

/**
 * loads the ImbaAdmin module in the IMBAdmin window
 */
function loadImbaAdminModule(moduleName, moduleDo, payLoad){
    currentModule = moduleName;
    currentModuleDo = moduleDo;
    
    /**
     * Remove all tabs
     */   
    $("#imbaContentNav").tabs("destroy");  
    
    /**
     * Create new tabs on element
     */
    $("#imbaContentNav").tabs();
    
    /**
     * Set the window title
     */
    //tmpResponse = null;
    //    alert("n:"+myName+", o:"+myOpenid);
    $.post(ajaxEntry, {
        action: "navigation",
        request: "name",
        module: moduleName
    }, function (response){
        tmpTitle  = "<a href='javascript:void();' style='text-decoration: none;' onclick='javascript:loadImbaAdminDefaultModule();'>";
        tmpTitle += "<span class='ui-icon ui-icon-home' style='cursor: pointer; float: left;' />&nbsp;&nbsp;";
        myName = loadMyImbaUser('name');
        alert(loadMyImbaUser('name'));
        if (myName != "") {
            tmpTitle += myName + "&nbsp;@";
        }
        tmpTitle += "&nbsp;IMBAdmin</a>";
        //        alert(tmpResponse);
        if (response) {
            tmpTitle += "&nbsp;&nbsp;/&nbsp;&nbsp;";
            tmpTitle += "<a href='javascript:void();' style='text-decoration: none;' onclick='javascript:loadImbaAdminModule(\"" + moduleName + "\");'>" + response + "</a>";
        }
        $("#imbaContentDialog").dialog({
            title: tmpTitle
        });
    });
    //            title: "<img src='Images/user-home.png' style='cursor: pointer;' width='16' height='16' onclick='javascript:loadImbaAdminDefaultModule();' /> IMBAdmin " + response


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
            if ((key == 0) && (moduleDo == null || moduleDo == "")) {
                loadImbaAdminTabContent({
                    action: "module",
                    module: moduleName,
                    request: value.id,
                    payLoad: payLoad
                });
            } else if ((moduleDo != null) && (moduleDo != "")) {
                loadImbaAdminTabContent({
                    action: "module",
                    module: moduleName,
                    request: moduleDo,
                    payLoad: payLoad
                });
            }
        });
    });
    
    
    $("#imbaContentDialog").dialog("open");
}

/**
 * Creats a cookie, with name, value und days of expire
 */
function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

/**
 * Reads a cookie by name
 */
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

/**
 * Erases a cookie by nane
 */
function eraseCookie(name) {
    createCookie(name,"",-1);
}