/* 
 * The ImbaManagerMessenger is the Controller Javascript for the Frontend
 */
   
var Chats = new Array();
var ChatsCount = 0;
var currentTabIndex = -1;
            
// Reload Chats every 2000 ms
setInterval('refreshChat()', 2000);

/**
 * Refreshs the current chatwindow
 */
function refreshChat() {
    // TODO: Chats ausgeben rausnehmen
    var ChatsStr = "";
    $.each(Chats, function(key, value) {
        ChatsStr += key + "=>" + value.name + "(" + value.openid +") [" + value.namingIndex + "]<br />";
    });
    ChatsStr += "<br />";
    ChatsStr += "Current tab index: " + currentTabIndex;
    ChatsStr += "<br />";
    ChatsStr += Math.random();
    
    $.post(ajaxEntry, {
        gotnewmessages: "true", 
        action: "messenger"
    },  function(response) {
        $.each($.parseJSON(response), function(key, newMessageFrom) {
            var foundWindow = false;
            // look in Chats if there is an open window with val            
            $.each(Chats, function(key1, val1){
                // ok, there is one window open with that val
                if (newMessageFrom.openid == val1.openid){
                    // if there is a new message in currentTab, then give it to me plix
                    if (val1.namingIndex == currentTabIndex){
                        loadChatWindowContent(val1.namingIndex);
                    }
                    foundWindow = true;
                }
            });

            if (!foundWindow){
                createChatWindow(newMessageFrom.name, newMessageFrom.openid)
            }
        });
    });
    
    $("#test").html(ChatsStr);
}

/**
* Refreshs a special chatwindow
*/
function loadChatWindowContent(tabIndex) {
    currentTabIndex = tabIndex;
    if (Chats[tabIndex]["openid"] != ""){
        $.post(ajaxEntry, {
            reciever: Chats[tabIndex]["openid"],
            loadMessages: "true",
            action: "messenger"
        },
        function(response) {
            $("#imbaMessagesTab_" + Chats[tabIndex]["namingIndex"]).html(response);
        });
    }
}

/**
* Sends a Message to a reciver
*/
function sendChatWindowMessage(msgReciver, msgText) {
    $.post(ajaxEntry, {
        reciever: msgReciver,
        message: msgText,
        action: "messenger"
    }, function(response) {
        if (response != "Message sent"){
            alert(response);
        }
    });
}

/**
* Creats a chatwindow
*/
function createChatWindow(name, openid) {
    // Run through open chats and check if its not already opend,
    // if so => select that
    var found = false;
    $.each(Chats, function(key, value) {
        if (value.openid == openid){
            // Select the clicked window
            $('#imbaMessages').tabs("select", key);
            found = true;
        }
    });

    if (!found){
        // Save Chats
        var tmp = Chats.length;
        Chats[tmp] = new Object();
        Chats[tmp]["name"] = name;
        Chats[tmp]["openid"] = openid;
        Chats[tmp]["namingIndex"] = ChatsCount;
        $('#imbaMessages').tabs("add", "#imbaMessagesTab_" + ChatsCount, name).find( ".ui-tabs-nav" ).sortable({
            axis: "x"
        });

        //loadChatWindowContent(ChatsCount);
        $('#imbaMessages').tabs("select", tmp);
                    
        ChatsCount++;
    }
}

/**
* Removes a chatwindow
*/
function removeChatWindow(tabIndex){
    $('#imbaMessages').tabs( "remove", tabIndex);

    $.each(Chats, function(key, value) {
        if (key > tabIndex){
            Chats[key-1] = Chats[key];
        }
    });
                
    Chats.pop();
}

/**
* jQuery DOM-Document has been loaded
*/
$(document).ready(function() {
    // Load the Tabs an inits the Variable for them
    $msgTabs = $('#imbaMessages').tabs();
    
    // Creats the Dialog around the chattabs
    $("#imbaMessagesDialog").dialog();
    
    // Load latest Conversation
    $.post(ajaxEntry, {
        chatinit: "true",
        action: "messenger"
    }, function(response) {
        // Showing the content
        $.each($.parseJSON(response), function(key, val) {
            // Loading the Chattabs
            createChatWindow(val.name, val.openid);

            // Load First Tab
            if (key == 0){
                loadChatWindowContent(0);
            }
        });
    });
                
    // Tab selected change Event (Reload content of that chat window
    $msgTabs.bind("tabsselect", function(event, ui) {
        loadChatWindowContent(ui.index);
    });
                
    // User submits the textbox
    $("#imbaMessageTextSubmit").click(function(){
        var msgReciver = Chats[currentTabIndex]["openid"];
        var msgText = $("#imbaMessageText").val();

        sendChatWindowMessage(msgReciver, msgText);
        loadChatWindowContent(currentTabIndex);

        $("#imbaMessageText").attr("value", "");
        return false;
    });

    // Setting a Template for the tabs, making them closeable
    $msgTabs.tabs({
        tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"
    });

    // close icon: removing the tab on click
    // note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
    $( "#imbaMessages span.ui-icon-close" ).live( "click", function() {
        var index = $( "li", $msgTabs ).index( $( this ).parent() );
        removeChatWindow(index);
    });

    // Fill Users into selectbox
    $.post(ajaxEntry, {
        action: "user",
        loaduserlist: "true"
    }, function(response) {
        $.each($.parseJSON(response), function(key, val) {
            $("#imbaUsers").append(new Option(val.name, val.openid, false, false));
        });
    });

    // Bind Clickevent to Selectbox
    $("#imbaUsers").bind("click", function(event, ui) {
        var name = $("#imbaUsers option:selected").text();
        var openid= $("#imbaUsers option:selected").val();
        if (name != "" && openid != ""){
            createChatWindow(name, openid);
        }
    });
});
