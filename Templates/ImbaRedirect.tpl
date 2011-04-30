<html>
    <head>
        <title>{$siteTitle} redirecting...</title>
        <link type='text/css' href='{$thrustRoot}ImbaLoader.php?load=css' rel='Stylesheet' />
    </head>
    <body onload='submitForm()' style="background-color: black; color: #999999;">
        <div id='imbaContentDialog' title='IMBAdmin' style='padding: 3px;'>
            <div id='imbaContentNav' style='height: 98%; overflow: auto;'>
                <div style="text-align: center; height: 100%; width: 100%; overflow: auto;">
                    <h2>Redirecting...</h2>
                    Please submit the following form:<br />
                    {$formHtml}
                </div>
                <script type='text/javascript'>document.openid_message.submit();</script>
            </div>
        </div>
    </body>
</html>