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
   
</script>
<form id='imbaSsoRegisterForm' action='ImbaAuth.php' method='post'>
<!--    <table class="ImbaAjaxBlindTable" style="cellspacing: 1px;"> -->

<i>(Registrierung von {$openid})</i>
<hr />
<b>Bitte zeige uns das du ein Mensch bist.</b><br />

{if $error != ""}
<h3>{$error}</h3>
{/if}


{$captchaContent}

<input type="submit" onClick="javascript:cancleRegistration();" value="Stop it" />
<input type="submit" onClick="javascript:sendRegistration();" value="Do it" />
<a href="/IMBAdmin/ImbaAuth.php?logout=true">logout</a>
</form>

