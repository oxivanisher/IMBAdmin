<html>
    <head>
        <title>IMBAdmin is Redirecting you to {$redirectDomain}</title>
        <link type='text/css' href='{$thrustRoot}ImbaLoader.php?load=css' rel='Stylesheet' />
        <script type="text/javascript">
            /*
            $(document).ready(function() {    
                $("#imbaRedirectDialog").dialog({
                    autoOpen: false
                })
                .dialog("option", "width", 500)
                .dialog("option", "height", 400);
            }
            */
        </script>
    </head>
    <body onload="location.href='{$redirectUrl}'" style="background-color: 333333; color: #999999;">
        <div id='imbaContentDialog' title='IMBAdmin' style='padding: 3px;'>
                <div id='imbaContentNav' style="text-align: center; height: 98%; width: 100%; overflow: auto;">
                    <br />
                    <br />
                    <br />
                    <br />
                    <h2>Redirecting to {$redirectDomain}</h2>
                    <br />
                    <br />
                    <br />
                    If the automatic redirection doesn't work,<br />
                    please klick <a href="{$redirectUrl}">this link to go to {$redirectDomain}</a>.<br />

                    {if $internalCode != ""}
                    <br />
                    <br />
                    <small>
                        <pre>{$internalMessage} ({$internalCode})</pre>
                    </small>
                    {/if}
                </div>
            </div>
        </div>
    </body>
</html>