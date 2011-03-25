<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
<script type="text/javascript">
  
    function cancleRegistration(){
        var data = {
            module: "Register",
            request: "abort"
        };
        loadImbaAdminTabContent(data);
    }

    function sendRegistration(){
        var data = {
            module: "Register",
            request: "registerme"
        };
        loadImbaAdminTabContent(data);
    }
    
    function showCaptcha () {
        alert('chalärm!');
        Recaptcha.create("{$public_key}", "ImbaReCaptcha", {
            theme: "black",
            callback: Recaptcha.focus_response_field
        });
    }
      
</script>

<i>(Registrierung von {$openid})</i>
<hr />
<b>Bitte zeige uns das du ein Mensch bist.</b><br />

<!--    <table class="ImbaAjaxBlindTable" style="cellspacing: 1px;"> -->

<div id="ImbaReCaptcha"></div>

<input type="submit" onClick="javascript:showCaptcha();" value="showCaptcha" />

<input type="submit" onClick="javascript:cancleRegistration();" value="Stop it" />
<input type="submit" onClick="javascript:sendRegistration();" value="Do it" />
<a href="/IMBAdmin/ImbaAuth.php?logout=true">logout</a>