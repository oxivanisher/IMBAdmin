<html>                                                                  
    <head>                                                                  

        <link type="text/css" href="../Libs/jQuery/css/ui-darkness/jquery-ui-1.8.10.custom.css" rel="Stylesheet" />	
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js"></script> 
        <script type="text/javascript">
            // Single point of Ajax entry            
            ajaxEntry = "../ajax.php";
            var Chats = new Array();           
            var currentTabIndex = -1;
            
            // TODO: X zum Schliessen
            // TODO: Interval mit Chat2 Nachladen (gucken wo New = 1)
            var interval = setInterval('refreshChat()', 2000);
           
            function refreshChat(){
                if (Chats[currentTabIndex]["openid"] != ""){
                    $.post(ajaxEntry, {reciever: Chats[currentTabIndex]["openid"], loadMessages: "true", action:"messenger"}, 
                    function(response) {                      
                        $("#test").html( Math.random());
                        $("#imbaMessagesTab_" + currentTabIndex).html(response);
                    });
                }
            }    
    
            // jQuery DOM-Document wurde geladen
            $(document).ready(function(){
                
                // Load the Tabs an inits the Variable for them
                $msgTabs = $('#imbaMessages').tabs();

                // Load latest Conversation
                $.post(ajaxEntry, {chatinit: "true", action:"messenger"}, function(response) {
                    // Showing the content                    
                    var jsonResponse = $.each($.parseJSON(response), function(key, val) {
                        // Loading the Chattabs
                        var name = val.name;
                        var openid = val.openid;
                        $msgTabs.tabs("add", "#imbaMessagesTab_" + key, name).find( ".ui-tabs-nav" ).sortable({ axis: "x" });

                        // Chats speichern
                        Chats[key] = new Object();
                        Chats[key]["name"] = name;
                        Chats[key]["openid"] = openid;

                        if (key == 0){
                            // Load First Tab                            
                            $.post(ajaxEntry, {reciever: openid, loadMessages: "true", action:"messenger"}, function(response) {
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
                    $.post(ajaxEntry, {reciever: msgReciver, loadMessages: "true", action:"messenger"}, function(response) {
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
                    
                    $.post(ajaxEntry, {reciever: msgReciver, message: msgText, action:"messenger"}, function(response) {
                        if (response != "Message sent"){
                            alert(response);
                        }
                    });

                    $.post(ajaxEntry, {reciever: msgReciver, loadMessages: "true", action:"messenger"}, function(response) {
                        // Showing the content
                        $("#imbaMessagesTab_" + selectedTab).html(response);
                    });

                    $("#imbaMessageText").attr("value", "");
                    return false;
                });

                // Setting a Template for the tabs, making them closeable
                $msgTabs.tabs({ tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"});

                // close icon: removing the tab on click
                // note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
                $( "#imbaMessages span.ui-icon-close" ).live( "click", function() {
                    var index = $( "li", $msgTabs ).index( $( this ).parent() );
                    $msgTabs.tabs( "remove", index );
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
                position: absolute;
                width: 400px;
                height: 200px;
                left: 10px;
                bottom: 10px;

                z-index: 9999;
            }

            #imbaMessages li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }

            #imbaMessageText {
                margin-top: 5px;
                margin-left: 10px;
                width: 320px;
                background-color: #222222;
                border: 1px solid #999999;
                color: #FFFFFF;
            }

            #imbaChatConversation{
                overflow-y: scroll;
                height: 60%;
            }
        </style>
    </head>
    <body>

        <div id="test"></div>

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

        <div id="imbaMessages">
            <ul></ul>
            <div id="imbaMessageTextDiv">
                <form action="" method="post">
                    <input id="imbaMessageText" type="text" />
                    <input id="imbaMessageTextSubmit" type="submit" value="Send"/>
                </form>
            </div>
        </div>
    </body>
</html>