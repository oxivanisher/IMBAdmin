<script type="text/javascript">
  
    function cancleRegistration(){
        alert('cancle');
        var data = {
            module: "Register",
            request: "abort"
        };
        loadImbaAdminTabContent(data);
    }

    function sendRegistration(){
        alert('send');
        var data = {
            module: "Register",
            request: "registerme"
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<form id='imbaSsoRegisterForm' action='ImbaAuth.php' method='post'>
<!--    <table id="ImbaAjaxBlindTable" style="cellspacing: 1px;"> -->

<i>({$openid})</i>
<hr />
<b>Bitte zeige uns das du ein Mensch bist.</b><br />

{if $return != ""}
<h3>{$return}</h3>
{/if}


{$captchaContent}

<input type="submit" onClick="javascript:cancleRegistration();" value="Stop it" />
<input type="submit" onClick="javascript:sendRegistration();" value="Do it" />
</form>

