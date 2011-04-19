/*
 * The ImbaManagerMessenger is the Controller Javascript for the Frontend for Messenging and chatting
 */

// Stores how many tabs have already been opend
var tabCount = 0;

/**
 * jQuery DOM-Document has been loaded
 */
$(document).ready(function() {
    // Creates the Dialog around the chattabs
    $("#imbaMessagesDialog").dialog({
        position:  ['left','bottom'] ,
        autoOpen: true,
        resizable: false,
        height: 270,
        width: 600
    });

    // open messaging on click
    $("#imbaOpenMessaging").click(function(){
        $("#imbaMessagesDialog").dialog("open");
    });

    // Load the Tabs an inits the Variable for them
    $msgTabs = $('#imbaMessages').tabs();

    // Setting a Template for the tabs, making them closeable
    $msgTabs.tabs({
        tabTemplate: "<li><a href='#{href}'>#{label}</a><div class='ui-icon ui-icon-info' style='cursor: pointer; float: left;'>Info</div><div class='ui-icon ui-icon-close'>Remove Tab</div></li>"
    });
    
    // Tab selected change Event (Reload content of that chat window
    $msgTabs.bind("tabsselect", function(event, ui) {
        loadChatWindowContent(ui.index);
        showStarChatWindowTitle(getTabIdFromTabIndex(ui.index), false);
    });

    // close icon: removing the tab on click
    $("#imbaMessages div.ui-icon-close").live("click", function() {
        var index = $("li", $msgTabs).index($(this).parent());
        $msgTabs.tabs("remove", index);
    });
    
    // info icon: showing the ImbAdmin module
    $("#imbaMessages div.ui-icon-info").live("click", function() {
        var index = $("li", $msgTabs).index($(this).parent());
        var tabData = getTabDataFromTabIndex(index);
        if (tabData.substr(0, 1) == "#"){
            alert("Chat is not yet implemented.");
        } else {
            showUserProfile(getTabDataFromTabIndex(index));
        }
    });
    
    // User submits the textbox
    $("#imbaMessageTextSubmit").click(function(){
        var currentTabIndex = getSelectedTabIndex();
        var msgReciver = getTabDataFromTabIndex(currentTabIndex);
        var msgText = $("#imbaMessageText").val();

        sendChatWindowMessage(msgReciver, msgText, currentTabIndex);

        $("#imbaMessageText").attr("value", "");
        return false;
    });
});