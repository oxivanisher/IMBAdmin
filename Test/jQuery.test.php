<html>                                                                  
    <head>                                                                  

        <link type="text/css" href="../Libs/jQuery/css/ui-darkness/jquery-ui-1.8.10.custom.css" rel="Stylesheet" />	
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js"></script> 
        <script type="text/javascript">
            
        </script>
        <style>
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
            }

            #imbaSsoOpenIdSubmit {
                background: transparent;
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        <div id="imbaSsoLogin">
            <img id="imbaSsoLogo" src="../Images/guild_logo.png" />               
            <form id="imbaSsoLoginForm" action="../index.php" method="get">
                <input name="openid" type="text" /> 
                <input id="imbaSsoOpenIdSubmit" type="submit" />
            </form>
        </div>
    </body>
</html>