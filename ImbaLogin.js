            
// Single point of Ajax entry            
ajaxEntry = "ImbaAuth.php";
var Chats = new Array();           
var currentTabIndex = -1;
            
// TODO: /w mit autocomplete => namen anbieten
// TODO: * für neue nachricht (New == 1)
            
// Reload Chats every 2000 ms
var interval = setInterval('refreshChat()', 2000);
function refreshChat(){
    if (Chats[currentTabIndex]["openid"] != ""){
        $.post(ajaxEntry, {
            reciever: Chats[currentTabIndex]["openid"], 
            loadMessages: "true", 
            action:"messenger"
        }, 
        function(response) {                      
            $("#test").html( Math.random());
            $("#imbaMessagesTab_" + currentTabIndex).html(response);
        });
    }
}    
    
// jQuery DOM-Document wurde geladen
$(document).ready(function(){
                
    // Load the Tabs an inits the Variable for them
    $msgTabs = $('#imbaMessages').tabs({
        collapsible: true
    });
    $( "#imbaMessagesDialog" ).dialog();

    // Load latest Conversation
    $.post(ajaxEntry, {
        chatinit: "true", 
        action:"messenger"
    }, function(response) {
        // Showing the content                    
        var jsonResponse = $.each($.parseJSON(response), function(key, val) {
            // Loading the Chattabs
            var name = val.name;
            var openid = val.openid;
            $msgTabs.tabs("add", "#imbaMessagesTab_" + key, name).find( ".ui-tabs-nav" ).sortable({
                axis: "x"
            });

            // Chats speichern
            Chats[key] = new Object();
            Chats[key]["name"] = name;
            Chats[key]["openid"] = openid;

            if (key == 0){
                // Load First Tab                            
                $.post(ajaxEntry, {
                    reciever: openid, 
                    loadMessages: "true", 
                    action:"messenger"
                }, function(response) {
                    $("#imbaMessagesTab_" + 0).html(response);
                    currentTabIndex = 0;
                });
            }
        });
    });
                
    // Tab selected change Event (Reload content of that chat window
    $msgTabs.bind("tabsselect", function(event, ui) {
        var selectedTab =  ui.index;
        var msgReciver = Chats[selectedTab]["openid"];
        $.post(ajaxEntry, {
            reciever: msgReciver, 
            loadMessages: "true", 
            action:"messenger"
        }, function(response) {
            // Showing the content
            $("#imbaMessagesTab_" + ui.index).html(response);
            currentTabIndex = ui.index;
        });
    });
                
    // User submits the textbox
    $("#imbaMessageTextSubmit").click(function(){
        var selectedTab =  $msgTabs.tabs('option', 'selected');
        var msgReciver = Chats[selectedTab]["openid"];
        var msgText = $("#imbaMessageText").val();
                    
        $.post(ajaxEntry, {
            reciever: msgReciver, 
            message: msgText, 
            action:"messenger"
        }, function(response) {
            if (response != "Message sent"){
                alert(response);
            }
        });

        $.post(ajaxEntry, {
            reciever: msgReciver, 
            loadMessages: "true", 
            action:"messenger"
        }, function(response) {
            // Showing the content
            $("#imbaMessagesTab_" + selectedTab).html(response);
        });

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
        $msgTabs.tabs( "remove", index );
    });
                
});
