<html>                                                                  
    <head>                                                                  

        <link type="text/css" href="../Libs/jQuery/css/ui-darkness/jquery-ui-1.8.10.custom.css" rel="Stylesheet" />	
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js"></script> 
        <script type="text/javascript">
            // Single point of Ajax entry            
            ajaxEntry = "../ajax.php";
            
            // TODO: Mit AJAX offene Konversationen und Channel holen
            // TODO: X zum Schliessen
            // TODO: Interval mit Chat2 Nachladen (gucken wo New = 1)
            var Chats = new Array();
            Chats[-1] = new Object();
            Chats[-1]["name"] = "Aggravate";
            Chats[-1]["openid"] = "http://openid-provider.appspot.com/Steffen.So@googlemail.com";

            Chats[0] = new Object();
            Chats[0]["name"] = "Richart";
            Chats[0]["openid"] = "https://oom.ch/openid/identity/richart";

            Chats[1] = new Object();
            Chats[1]["name"] = "Cernu";
            Chats[1]["openid"] = "https://oom.ch/openid/identity/oxi";

            Chats[2] = new Object();
            Chats[2]["name"] = "Mozi";
            Chats[2]["openid"] = "http://openid-provider.appspot.com/m.remmos@googlemail.com";

            // jQuery DOM-Document wurde geladen
            $(document).ready(function(){
                // Load the Tabs an inits the Variable for them
                $msgTabs = $('#imbaMessages').tabs();
                
                // Loading the Chattabs
                for (var i = 0; i < Chats.length; i++) {
                    $msgTabs.tabs("add", "#imbaMessagesTab_" + i, Chats[i]["name"]).find( ".ui-tabs-nav" ).sortable({ axis: "x" });
                }

                // Tab selected change Event (Reload content of that chat window
                $msgTabs.bind("tabsselect", function(event, ui) {
                    var selectedTab =  ui.index;
                    var msgReciver = Chats[selectedTab]["openid"];
                    $.post(ajaxEntry, {reciever: msgReciver, loadMessages: "true", action:"messenger"}, function(response) {
                        // Showing the content
                        $("#imbaMessagesTab_" + ui.index).html(response);
                    });
                });

                // Load First Tab
                var msgReciver = Chats[0]["openid"];
                $.post(ajaxEntry, {reciever: msgReciver, loadMessages: "true", action:"messenger"}, function(response) {
                    $("#imbaMessagesTab_" + 0).html(response);
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

            #imbaMessageText {
                margin-top: 5px;
                margin-left: 10px;
                width: 320px;
            }

            #imbaChatConversation{
                overflow-y: scroll;
                height: 60%;
            }
        </style>
    </head>
    <body>


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