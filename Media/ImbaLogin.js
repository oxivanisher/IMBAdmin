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
    // setting old openid
    var oldOpenId = unescape(decodeURIComponent(readCookie("ImbaSsoLastLoginName")));
    if (oldOpenId != null && oldOpenId != "null" && oldOpenId != ""){
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
            $("#imbaSsoShowOpenId").val(response);
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
    .dialog("option", "height", 600);
    
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