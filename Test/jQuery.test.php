<html>                                                                  
    <head>                                                                  

        <link type="text/css" href="../Libs/jQuery/css/ui-darkness/jquery-ui-1.8.10.custom.css" rel="Stylesheet" />	
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js"></script> 
        <script type="text/javascript">
            // TODO: /w mit autocomplete => namen anbieten
            // TODO: * für neue nachricht (New == 1)
            
            // Single point of Ajax entry            
            ajaxEntry = "../ajax.php";
            var Chats = new Array();
            var ChatsCount = 0;
            var currentTabIndex = -1;
            
            // Reload Chats every 2000 ms
            var interval = setInterval('refreshChat()', 2000);

            // Refreshs the current chatwindow
            function refreshChat() {
                // TODO: Chats ausgeben rausnehmen
                /*var ChatsStr = "";
                $.each(Chats, function(key, value) {
                    ChatsStr += key + "=>" + value.name + "(" + value.openid +") [" + value.namingIndex + "]<br />";
                });
                $("#test").html(ChatsStr);*/

                $.post(ajaxEntry, {gotnewmessages: "true", action: "messenger"},  function(response) {
                    $.each($.parseJSON(response), function(key, val) {
                        //alert(key + val);
                        // look in Chats if there is an open window with val
                        $.each(Chats, function(key1, val1){
                            // ok, there is one window open with that val
                            if (val == val1.openid){
                                loadChatWindowContent(val1.namingIndex);
                            }
                        });
                    });
                });
                
                $("#test").html(Math.random());
            }

            // Refreshs a special chatwindow
            function loadChatWindowContent(tabIndex) {
                currentTabIndex = tabIndex;
                if (Chats[tabIndex]["openid"] != ""){
                    $.post(ajaxEntry, {reciever: Chats[tabIndex]["openid"], loadMessages: "true", action: "messenger"},
                    function(response) {
                        $("#imbaMessagesTab_" + Chats[tabIndex].namingIndex).html(response);
                    });
                }
            }

            // Sends a Message to a reciver
            function sendChatWindowMessage(msgReciver, msgText) {
                $.post(ajaxEntry, {reciever: msgReciver, message: msgText, action: "messenger"}, function(response) {
                    if (response != "Message sent"){
                        alert(response);
                    }
                });
            }

            // Creats a chatwindow
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
                    $('#imbaMessages').tabs("add", "#imbaMessagesTab_" + ChatsCount, name).find( ".ui-tabs-nav" ).sortable({ axis: "x" });

                    //loadChatWindowContent(ChatsCount);
                    $('#imbaMessages').tabs("select", tmp);
                    
                    ChatsCount++;
                }
            }

            // Removes a chatwindow
            function removeChatWindow(tabIndex){
                $('#imbaMessages').tabs( "remove", tabIndex);

                $.each(Chats, function(key, value) {
                    if (key > tabIndex){
                        Chats[key-1] = Chats[key];
                    }
                });
                
                Chats.pop();
            }

            // jQuery DOM-Document wurde geladen
            $(document).ready(function() {
                // Load the Tabs an inits the Variable for them
                $msgTabs = $('#imbaMessages').tabs(/*{collapsible: true}*/);

                // Creats the Dialog around the chattabs
                $( "#imbaMessagesDialog" ).dialog();

                // Load latest Conversation
                $.post(ajaxEntry, {chatinit: "true", action:"messenger"}, function(response) {
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
                $msgTabs.tabs({ tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"});

                // close icon: removing the tab on click
                // note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
                $( "#imbaMessages span.ui-icon-close" ).live( "click", function() {
                    var index = $( "li", $msgTabs ).index( $( this ).parent() );
                    removeChatWindow(index);
                });

                // Fill Users into selectbox
                $.post(ajaxEntry, {action: "user", loaduserlist: "true"}, function(response) {
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

        </script>

        <style type="text/css">
            body{
                font: 60% "Trebuchet MS", sans-serif;
            }

            #imbaSsoLogin {
                position: absolute;
                right: 10px;
                top: 10px;
                width: 250px;
                height: 70px;
                z-index: 9999;
            }

            #imbaSsoLogin input {
                background-color: white;
            }

            .imbaSsoLoginBorder {
                position: absolute;
                right: 10px;
                top: 10px;
                z-index: 9999;
                width: 250px;
                height: 70px;
            }

            #imbaSsoLoginInner
            {
                padding: 12px 12px;
            }

            #imbaSsoLogo {
                float: left;
                width: 48px;
                width: 48px;
            }

            #imbaSsoOpenIdSubmit {
                background: transparent;
                border: 1px solid black;
            }

            #imbaMessages {
            }

            #imbaMessages li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }

            #imbaMessageText {
                background-color: #222222;
                border: 1px solid #999999;
                color: #FFFFFF;
            }

            #imbaChatConversation{
                /*overflow-y: scroll;*/
                /*height: 80%;*/
            }
        </style>
    </head>
    <body>

        <div id="test"></div>

        <select id="imbaUsers" size="10" ></select>

        <div class="imbaSsoLoginBorder ui-widget ui-widget-content ui-corner-all"></div>
        <div id="imbaSsoLogin">
            <div id="imbaSsoLoginInner">
                <img id="imbaSsoLogo" src="../Images/guild_logo.png" alt="Guild Logo" />
                <form id="imbaSsoLoginForm" action="../index.php" method="get">
                    <input name="openid" type="text" />
                    <input id="imbaSsoOpenIdSubmit" type="submit" />
                </form>
            </div>
        </div>

        <div id="imbaMessagesDialog" title="Alptr&ouml;im Messaging">
            <div id="imbaMessages">
                <ul></ul>
                <div id="imbaMessageTextDiv">
                    <form action="" method="post">
                        <textarea id="imbaMessageText" ></textarea>
                        <input id="imbaMessageTextSubmit" type="submit" value="Send"/>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>