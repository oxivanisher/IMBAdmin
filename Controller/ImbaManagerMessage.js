/* 
 * The ImbaManagerMessenger is the Controller Javascript for the Frontend
 */
// Stores how many tabs have already been opend
var chatsCount = 0;

// Reload Chats every 2000 ms
setInterval('refreshChat()', 2000);

/**
 * Refreshs the current chatwindow
 */
function refreshChat() {    
    $.post(ajaxEntry, {
        gotnewmessages: "true", 
        action: "messenger"
    },  function(response) {
        // reset gotNewMessages
        var gotNewMessages = 0;
        var selectedTab = getSelectedTabIndex();

        $.each($.parseJSON(response), function(key, newMessageFrom) {
            // look in Chats if there is an open window with val            
            var foundTab = false;
            $.each($("#imbaMessages a"), function (tabIndex, tabString) {
                if (tabIndex == selectedTab) {
                    loadChatWindowContent(tabIndex);                    
                } else {
                    var tmp = tabString.toString().split("#");
                    tmp = "#" + tmp[1];
                    var openid = $(tmp).data("openid");
                    if (openid == newMessageFrom.openid){                  
                        showStarChatWindowTitle(tmp, true);
                        foundTab = true;
                    }
                }
            });
            
            // show got new messages
            if (!foundTab){
                gotNewMessages++;
            }
        });

        // show icon for new message
        if (gotNewMessages > 0){
            $("#imbaGotMessage").effect("pulsate", {
                times:3
            }, 2000);
        } else {
            $("#imbaGotMessage").hide();
        }
    });

    $("#test").html(Math.random());
}

/**
 * Fill Users into selectbox
 */
function fillUsers(){    
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
}

/**
 * Returns the current selected tab index
 */
function getSelectedTabIndex(){
    return $('#imbaMessages').tabs('option', 'selected');
}

/**
 * Return the Id of a tab from a tabIndex
 */
function getTabIdFromTabIndex(tabIndex){
    var result = "";
    $.each($("#imbaMessages a"), function (k, v) {
        if (k == tabIndex){
            var tmp = v.toString().split("#");
            result = "#" + tmp[1];
        }
    });

    return result;
}

/**
 * Return the openid of a tab from a tabIndex
 */
function getOpenIdFromTabIndex(tabIndex){
    var result = "";
    $.each($("#imbaMessages a"), function (k, v) {
        if (k == tabIndex){
            var tmp = v.toString().split("#");
            result = $("#" + tmp[1]).data("openid");
        }
    });

    return result;
}

/**
 * Creats a chatwindow
 */
function createChatWindow(name, openid) {
    // Run through open chats and check if its not already opend,
    // if so => select that
    var found = false;
    var countOpenChats = 0;
    
    if (countOpenChats == 0){
        $("#imbaMessagesDialog").dialog("open");
    }
    
    $.each($("#imbaMessages a"), function (k, v) {
        var tmpId = v.toString().split("#");
        var tmpOpenId = $("#" + tmpId[1]).data("openid");
        
        if (tmpOpenId == openid) {
            // Select the clicked window
            $('#imbaMessages').tabs("select", k);
            found = true;
        }

        countOpenChats++;
    });

    if (!found){
        // Create new Window
        $("#imbaMessages")
        .tabs("add", "#imbaMessagesTab_" + chatsCount, name)
        // Sortable (making it to complex)
        /*.find(".ui-tabs-nav")
        .sortable({
            axis: "x"
        })
         */
        ;

        $("#imbaMessagesTab_" + chatsCount).data("openid", openid);
        $('#imbaMessages').tabs("select", countOpenChats);
        loadChatWindowContent(countOpenChats);

        chatsCount++;
    } 
}

/**
 * Refreshs a special chatwindow
 */
function loadChatWindowContent(tabIndex) {
    if (getOpenIdFromTabIndex(tabIndex) != ""){
        var tabReciever = getOpenIdFromTabIndex(tabIndex)
        // load chat
        $.post(ajaxEntry, {
            reciever: tabReciever,
            loadMessages: "true",
            action: "messenger"
        },
        function(response) {
            var htmlConversation = "<div id='imbaChatConversation'>";
            
            $.each($.parseJSON(response), function(key, val) {
                htmlConversation += "<div>" + val.time + " " + val.sender + ": " + val.message + "</div>";
            });

            htmlConversation += "</div>";

            $(getTabIdFromTabIndex(tabIndex)).html(htmlConversation);
        });
        
        // mark as read
        $.post(ajaxEntry, {
            reciever: tabReciever,
            action: "messenger",
            setread: "true"
        },
        function(response) {
            // nothing to do here            
            });
    }
}

/**
 * Sends a message to a reciver
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
 * Changes the Title of the ChatWindow
 */
function showStarChatWindowTitle(id, showStar){
    var tab = ($('#imbaMessages a[href|="'+id+'"]'));
    var tabLabel = tab.html();
    if (showStar){
        if (tabLabel.substr(tabLabel.length-1, 1) != "*"){
            tab.html(tabLabel + "*");
        }
    } else {
        if (tabLabel.substr(tabLabel.length-1, 1) == "*"){
            tab.html(tabLabel.substr(0, tabLabel.length-1));
        }
    }
}

/**
 * Creates all tabs with new Messages
 */
function showTabsWithNewMessage(){
    $.post(ajaxEntry, {
        gotnewmessages: "true",
        action: "messenger"
    },  function(response) {
        $.each($.parseJSON(response), function(key, newMessageFrom) {
            // look in Chats if there is an open window with val
            var foundTab = false;
            $.each($("#imbaMessages a"), function (k, v) {
                if (k == getSelectedTabIndex()) {
                // do nothing here
                } else {
                    var tmp = v.toString().split("#");
                    tmp = "#" + tmp[1];
                    var openid = $(tmp).data("openid");
                    if (openid == newMessageFrom.openid){
                        foundTab = true;
                    }
                }
            });

            // show got new messages
            if (!foundTab){
                createChatWindow(newMessageFrom.name, newMessageFrom.openid);
            } 
        });
    });
}

/**
 * jQuery DOM-Document has been loaded
 */
$(document).ready(function() {
    $.ajaxSetup({
        async:true
    });

    // autocomplete for Chat
    $("#imbaMessageText").autocomplete({
        source: function( request, response ) {
            if (request.term.substr(0,2) == "/w"){
                $.ajax({
                    url: ajaxEntry,
                    dataType: "json",
                    data: {
                        action: "user",
                        loaduser: "true",
                        startwith: request.term.substr(3 ,request.term.length)
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.name,
                                value: "/w " + item.name,
                                data: item.openid
                            }
                        }));

                    }
                });
            }
        },
        minLength: 0,
        select: function( event, ui ) {
            createChatWindow(ui.item.label, ui.item.data);
        },
        close: function() {
            $("#imbaMessageText").attr("value", "");
        }
    });


    // Load the Tabs an inits the Variable for them
    $msgTabs = $('#imbaMessages').tabs();

    // Hide new Message Icon and create Click
    $("#imbaGotMessage").hide().click(function(){
        showTabsWithNewMessage();
        $("#imbaGotMessage").hide();
    });

    // Creats the Dialog around the chattabs
    $("#imbaMessagesDialog").dialog({
        autoOpen: false,
        resizable: false,
        height: 270,
        width: 600
    });


    // open messaging on click
    $("#imbaOpenMessaging").click(function(){
        $("#imbaMessagesDialog").dialog("open");
    });

    // Fill the Selectbox with users
    fillUsers();
    
    // Tab selected change Event (Reload content of that chat window
    $msgTabs.bind("tabsselect", function(event, ui) {
        loadChatWindowContent(ui.index);
        showStarChatWindowTitle(getTabIdFromTabIndex(ui.index), false);       
    });

    // User submits the textbox
    $("#imbaMessageTextSubmit").click(function(){
        var currentTabIndex = getSelectedTabIndex();
        var msgReciver = getOpenIdFromTabIndex(currentTabIndex);
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
    $("#imbaMessages span.ui-icon-close").live("click", function() {
        var index = $("li", $msgTabs).index($(this).parent());
        $msgTabs.tabs("remove", index);
    });
        
});