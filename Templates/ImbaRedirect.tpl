<html>
    <head>
        <title>{$siteTitle} redirecting...</title>
    </head>
    <body onload='submitForm()' style="background-color: black; color: #999999; text-align: center center;">
        <h2>Redirecting...</h2>
        Please submit the following form:<br />
        {$formHtml}
        <script type='text/javascript'>document.openid_message.submit();</script>
    </body>
</html>