/*
 * The ImbaManagerMessenger is the Controller Javascript for the Frontend for
 * Messenging and chatting
 */
// Stores how many tabs have already been opend
var tabCount = 0;
var countOpenTabs = 0;
var chatSinceIds = new Array();
var chatCache = new Array();


// Reload Chats every 2000 ms
setInterval('refreshChat()', 2000);

/**
 * Refreshs the current chatwindow
 */
function refreshChat() {
    if (isUserLoggedIn){
        $.post(ajaxEntry, {
            action: "messenger",
            gotnewmessages: "true",
            secSession: phpSessionID
        },  function(response) {
            // reset gotNewMessages
            var gotNewMessages = 0;
            var selectedTab = getSelectedTabIndex();

            $.each($.parseJSON(response), function(key, newMessageFrom) {
                // look in Chats if there is an open window with val
                var foundTab = false;
                $.each($("#imbaMessages a"), function (tabIndex, tabString) {
                    // FIXME: das problem ist wenn ein tab offen ist, der dialog
                    // zugemacht wird, dann eine nachricht kommt blinkt
                    // der message dings nur einmal :/
                    if (tabIndex == selectedTab && ($("#imbaMessagesDialog")
                        .is(':hidden')) == false) {
                        loadChatWindowContent(tabIndex);
                    } else {
                        var tmp = tabString.toString().split("#");
                        tmp = "#" + tmp[1];
                        var tabData = $(tmp).data("tabdata");
                        if (tabData == newMessageFrom.id){
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

        // Refresh if current tab is chat
        var selectedTab = getSelectedTabIndex();
        var tabData = getTabDataFromTabIndex(selectedTab);

        // Check if its a chat
        if (tabData.substr(0, 1) == "#" && ($("#imbaMessagesDialog").is(':hidden')) == false){
            loadChatWindowContent(selectedTab);
        }
    }
}

/**
 * create the info tab
 */
function createInfoTab(){
    // Create new Window
    $("#imbaMessages").tabs("add", "#imbaMessagesTab_" + tabCount, "Info");

    $("#imbaMessagesTab_" + tabCount).data("tabdata", "Info");
    $("#imbaMessagesTab_" + tabCount).data("tabname", "Info");
    $('#imbaMessages').tabs("select", countOpenTabs);
    loadChatWindowContent(countOpenTabs);
    tabCount++;
}

/**
 * Creats a chatwindow
 */
function createChatWindow(name, data) {
    // Run through open chats and check if its not already opend,
    // if so => select that
    var found = false;
    countOpenTabs = 0;

    // Open Dialog, just to be save here
    $("#imbaMessagesDialog").dialog("open");

    // Walk through all the open tabs
    $.each($("#imbaMessages a"), function (k, v) {
        var tmpId = v.toString().split("#");
        var tmpOpenId = $("#" + tmpId[1]).data("tabdata");

        if (tmpOpenId == data) {
            // Select the clicked window
            $('#imbaMessages').tabs("select", k);
            found = true;
        }

        countOpenTabs++;
    });

    if (!found){
        // Create new Window
        $("#imbaMessages").tabs("add", "#imbaMessagesTab_" + tabCount, name);

        $("#imbaMessagesTab_" + tabCount).data("tabdata", data);
        $("#imbaMessagesTab_" + tabCount).data("tabname", name);
        $('#imbaMessages').tabs("select", countOpenTabs);
        loadChatWindowContent(countOpenTabs);

        tabCount++;
    }
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
 * Return the data of a tab from a tabIndex
 */
function getTabDataFromTabIndex(tabIndex){
    var result = "";
    $.each($("#imbaMessages a"), function (k, v) {
        if (k == tabIndex){
            var tmp = v.toString().split("#");
            result = $("#" + tmp[1]).data("tabdata");
        }
    });

    return result;
}

/**
 * Return the name of a tab from a tabIndex
 */
function getTabNameFromTabIndex(tabIndex){
    var result = "";
    $.each($("#imbaMessages a"), function (k, v) {
        if (k == tabIndex){
            var tmp = v.toString().split("#");
            result = $("#" + tmp[1]).data("tabname");
        }
    });

    return result;
}

/**
 * Changes the Title of the ChatWindow
 */
function showStarChatWindowTitle(id, showStar){
    var tab = ($('#imbaMessages a[href|="'+id+'"]'));
    var tabLabel = tab.html();
    if (id != null && tabLabel != null){
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
}

/**
 * Refreshs a special chatwindow
 */
function loadChatWindowContent(tabIndex) {
    if (tabIndex == 0){
        // Load Info Tab
        $("#imbaChatConversation").html("\
        <div style='margin-left: 10px'>\
            <p><b>/w</b> &lt;Username&gt; zum Chatten mit einem User</p>\
            <p><b>/j</b> zum Chatten in einem Channel</p>\
        </div>");
        $("#imbaChatConversationUserlist").html("");
    }else {
        var tabData = getTabDataFromTabIndex(tabIndex);

        // am i a chat or am i a messenger?
        if (tabData.substr(0, 1) == "#"){
            var channelId = tabData.substring(1);

            if (chatSinceIds[channelId] == -1){
                // init chat
                $.post(ajaxEntry, {
                    action: "messenger",
                    initchat: "true",
                    channelid: channelId,
                    secSession: phpSessionID
                },
                function(response) {
                    var htmlConversation = "";

                    $.each($.parseJSON(response), function(key, val) {
                        htmlConversation += "<div>"
                        + val.time + " "
                        + val.nickname + ": "
                        + val.message + "</div>";

                        chatSinceIds[channelId] = val.id;
                    });

                    $("#imbaChatConversation").html(htmlConversation);
                    chatCache[channelId] = htmlConversation;

                    $("#imbaChatConversation").attr({
                        scrollTop: $("#imbaChatConversation").attr("scrollHeight")
                    });

                    $("#imbaChatConversationUserlist").html("You<br />");
                });
            } else {
                // update chat
                $.post(ajaxEntry, {
                    action: "messenger",
                    loadchat: "true",
                    channelid: channelId,
                    since: chatSinceIds[channelId],
                    secSession: phpSessionID
                },
                function(response) {
                    var htmlConversation = chatCache[channelId];

                    $.each($.parseJSON(response), function(key, val) {
                        htmlConversation += "<div>"
                        + val.time + " "
                        + val.nickname + ": "
                        + val.message + "</div>";

                        chatSinceIds[channelId] = val.id;
                    });

                    $("#imbaChatConversation").html(htmlConversation);
                    chatCache[channelId] = htmlConversation;

                    $("#imbaChatConversation").attr({
                        scrollTop: $("#imbaChatConversation").attr("scrollHeight")
                    });

                    $("#imbaChatConversationUserlist").html("You<br />");
                });
            }
        } else {
            // load messenger
            $.post(ajaxEntry, {
                reciever: tabData,
                loadMessages: "true",
                action: "messenger",
                secSession: phpSessionID
            },
            function(response) {
                var htmlConversation = "";

                $.each($.parseJSON(response), function(key, val) {
                    htmlConversation += "<div>"
                    + val.time + " "
                    + val.sender + ": "
                    + val.message + "</div>";
                });

                $("#imbaChatConversation").html(htmlConversation);
                $("#imbaChatConversation").attr({
                    scrollTop: $("#imbaChatConversation").attr("scrollHeight")
                });
                var tabname = getTabNameFromTabIndex(tabIndex);
                $("#imbaChatConversationUserlist").html("You<br />" + tabname);
            });

            // mark as read
            $.post(ajaxEntry, {
                reciever: tabData,
                action: "messenger",
                setread: "true",
                secSession: phpSessionID
            },
            function(response) {
                // nothing to do here
                });
        }
    }
}

/**
 * Sends a message 
 */
function sendChatWindowMessage(msgText, tabIndex) {
    var tabData = getTabDataFromTabIndex(tabIndex);
    var httpPostData = null

    $.ajaxSetup({
        async: false
    });

    if (tabData.substr(0, 1) == "#"){
        httpPostData = {
            action: "messenger",
            channelid: tabData.substring(1),
            message: msgText,
            secSession: phpSessionID
        };
    } else {
        httpPostData = {
            action: "messenger",
            reciever: tabData,
            message: msgText,
            secSession: phpSessionID
        };
    }

    // Send post
    $.post(ajaxEntry, httpPostData , function(response) {
        if (response != "Message sent"){
            alert(response);
        }
    });

    loadChatWindowContent(tabIndex);

    $.ajaxSetup({
        async: true
    });
}

/**
 * Creates all tabs with new Messages
 */
function showTabsWithNewMessage(){
    $.post(ajaxEntry, {
        gotnewmessages: "true",
        action: "messenger",
        secSession: phpSessionID
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
                    var tabData = $(tmp).data("tabdata");
                    if (tabData == newMessageFrom.id){
                        foundTab = true;
                    }
                }
            });

            // show got new messages
            if (!foundTab){
                createChatWindow(newMessageFrom.name, newMessageFrom.id);
            }
        });
    });
}

$(window).bind('beforeunload', function() {
    //alert("ByeBye");
    });

/**
 * jQuery DOM-Document has been loaded
 */
$(document).ready(function() {
    // Load the Tabs an inits the Variable for them and create info tab
    $msgTabs = $('#imbaMessages').tabs();
    createInfoTab();

    // Setting a Template for the tabs, making them closeable
    $msgTabs.tabs({
        tabTemplate: "<li><a href='#{href}'>#{label}</a><div class='ui-icon ui-icon-info' style='cursor: pointer; float: left;'>Info</div><div class='ui-icon ui-icon-close'>Remove Tab</div></li>"
    });

    // close icon: removing the tab on click
    $("#imbaMessages div.ui-icon-close").live("click", function() {
        var index = $("li", $msgTabs).index($(this).parent());
        $msgTabs.tabs("remove", index);
        if (countOpenTabs > 0) {
            countOpenTabs--;
        }

        // load content of new selected Tab
        loadChatWindowContent(getSelectedTabIndex());
    });

    // info icon: showing the ImbAdmin module
    $("#imbaMessages div.ui-icon-info").live("click", function() {
        alert("Chat is not yet implemented.");
    /*var index = $("li", $msgTabs).index($(this).parent());
        var tabData = getTabDataFromTabIndex(index);
        if (tabData.substr(0, 1) == "#"){
            alert("Chat is not yet implemented.");
        } else {
            showUserProfile(getTabDataFromTabIndex(index));
        }*/
    });
    
    // Hide new Message Icon and create Click
    $("#imbaGotMessage").hide().click(function(){
        showTabsWithNewMessage();
        $("#imbaGotMessage").hide();
    });

    // Creats the Dialog around the chattabs
    $("#imbaMessagesDialog").dialog({
        position:  ['left','top'] ,
        autoOpen: false,
        resizable: false,
        height: 270,
        width: 600
    });

    // Setting the hights of the chatcontent and userlist
    $("#imbaChatConversation").height(140);
    $("#imbaChatConversationUserlist").height(140);

    // open messaging on click
    $("#imbaOpenMessaging").click(function(){
        $("#imbaMessagesDialog").dialog("open");
    });

    // Tab selected change Event (Reload content of that chat window
    $msgTabs.bind("tabsselect", function(event, ui) {
        // Load the Content
        loadChatWindowContent(ui.index);

        // Hide the Star
        showStarChatWindowTitle(getTabIdFromTabIndex(ui.index), false);
    });

    // User submits the textbox
    $("#imbaMessageTextSubmit").click(function(){
        var tabIndex = getSelectedTabIndex();
        var msgText = $("#imbaMessageText").val();

        sendChatWindowMessage(msgText, tabIndex);

        $("#imbaMessageText").attr("value", "");
        return false;
    });

    // autocomplete for Chat
    $("#imbaMessageText").autocomplete({
        source: function( request, response ) {
            if (request.term.substr(0,2) == "/w"){
                $.ajax({
                    type: "POST",
                    url: ajaxEntry,
                    dataType: "json",
                    data: {
                        action: "user",
                        loaduser: "true",
                        secSession: phpSessionID,
                        startwith: request.term.substr(3 ,request.term.length)
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.name,
                                value: "/w " + item.name,
                                data: item.id,
                                user: item.user
                            }
                        }));

                    }
                });
            }
            else if (request.term.substr(0,2) == "/j") {
                $.ajax({
                    type: "POST",
                    url: ajaxEntry,
                    dataType: "json",
                    secSession: phpSessionID,
                    data: {
                        action: "messenger",
                        secSession: phpSessionID,
                        loadchannels: "true"
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: "Join Channel: " + item.channel,
                                value: "/j " + item.channel,
                                data: item.channel,
                                data2: item.channelId,
                                user: item.user
                            }
                        }));

                    }
                });
            }
        },
        minLength: 0,
        select: function( event, ui ) {
            if (ui.item.user == true){
                createChatWindow(ui.item.label, ui.item.data);
            } else if (ui.item.user == false){
                chatSinceIds[ui.item.data2] = -1;
                createChatWindow("#" + ui.item.data, "#"+ui.item.data2);
            }
        },
        close: function() {
            $("#imbaMessageText").attr("value", "");
        }
    });
});