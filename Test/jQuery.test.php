<html>                                                                  
    <head>                                                                  

        <link type="text/css" href="../Libs/jQuery/css/ui-darkness/jquery-ui-1.8.10.custom.css" rel="Stylesheet" />	
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js"></script> 
        <script type="text/javascript">
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

            // jQuery Document
            $(document).ready(function(){
                // Load the Messages as Tabs
                $msgTabs = $('#imbaMessages').tabs();

                $("#imbaMessages").bind("tabsselect", function(event, ui) {
                    var selectedTab =  ui.index;
                    var msgSender = Chats[-1]["openid"];
                    var msgReciver = Chats[selectedTab]["openid"];
                    $.post("../Ajax/Messenger.php", {sender: msgSender, reciever: msgReciver, loadMessages: "true"}, function(response) {
                        // Showing the content
                        $("#imbaMessagesTab_" + ui.index).html(response);
                    });
                });

                // Load First Tab
                var msgSender = Chats[-1]["openid"];
                var msgReciver = Chats[0]["openid"];
                $.post("../Ajax/Messenger.php", {sender: msgSender, reciever: msgReciver, loadMessages: "true"}, function(response) {
                    $("#imbaMessagesTab_" + 0).html(response);
                });

                // Loading the Chattabs
                for (var i = 0; i < Chats.length; i++) {
                    $msgTabs.tabs("add", "#imbaMessagesTab_" + i, Chats[i]["name"]);
                }
                
                // user submits the textbox
                $("#imbaMessageTextSubmit").click(function(){
                    var selectedTab =  $('#imbaMessages').tabs('option', 'selected');
                    var msgSender = Chats[-1]["openid"];
                    var msgReciver = Chats[selectedTab]["openid"];
                    var msgText = $("#imbaMessageText").val();
                    
                    $.post("../Ajax/Messenger.php", {sender: msgSender, reciever: msgReciver, message: msgText},
                    function(data) {
                        if (data != "Message sent"){
                            alert(data);
                        }
                    });

                    $.post("../Ajax/Messenger.php", {sender: msgSender, reciever: msgReciver, loadMessages: "true"}, function(response) {
                        // Showing the content
                        $("#imbaMessagesTab_" + selectedTab).html(response);
                    });

                    $("#imbaMessageText").attr("value", "");
                    return false;
                });
            });

        </script>

        <style type="text/css">
            body{ font: 60% "Trebuchet MS", sans-serif; margin: 50px;}

            #imbaSsoLogo {
                float: left;
                width: 48px;
                width: 48px;
            }

            #imbaSsoLogin {
                position: absolute;
                right: 40px;
                top: 10px;
                padding-top: 10px;
                padding-left: 10px;
                padding-right: 10px;
                border: 1px solid black;

                z-index: 9999;
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
                width: 200px;
            }

            #imbaChatConversation{
                overflow-y: scroll;
                height: 60%;
            }
        </style>
    </head>
    <body>
        <div id="imbaSsoLogin">
            <img id="imbaSsoLogo" src="../Images/guild_logo.png" alt="Guild Logo" />
            <form id="imbaSsoLoginForm" action="../index.php" method="get">
                <input name="openid" type="text" /> 
                <input id="imbaSsoOpenIdSubmit" type="submit" />
            </form>
        </div>

        <div id="imbaMessages">
            <ul></ul>
            <div id="imbaMessageTextDiv">
                <input id="imbaMessageText" type="text"/>
                <input id="imbaMessageTextSubmit" type="submit" value="Send"/>
            </div>
        </div>
    </body>
</html>