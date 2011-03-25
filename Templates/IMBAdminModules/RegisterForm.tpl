<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
<script type="text/javascript">
  
    function cancleRegistration(){
        var data = {
            module: "Register",
            request: "abort"
        };
        loadImbaAdminTabContent(data);
    };

    function sendRegistration(){
        var data = {
            module: "Register",
            request: "registerme"
        };
        loadImbaAdminTabContent(data);
    };
    
    function showCaptcha () {
        $("#ImbaReCaptchaContainer").show();
        $("#ImbaRegisterForm").hide();
        Recaptcha.create("{$publicKey}", "ImbaReCaptcha", {
            theme: "blackglass",
            callback: Recaptcha.focus_response_field
        });
    };

    function checkCaptcha () {
        //send to http://www.google.com/recaptcha/api/verify
        
    }
</script>
<div id="ImbaRegisterForm">
<form id='imbaSsoRegisterForm' action='ImbaAuth.php' method='post'>
<!--    <table class="ImbaAjaxBlindTable" style="cellspacing: 1px;"> -->

<i>(Registrierung von {$openid})</i>
<hr />
<b>Bitte f&uuml;lle folgenden Felder wahrheitsgetreu aus.</b><br />

Vorname: 
<input id="regFirstname" class="regField" type="text" name="forename" onChange="validateInput('regFirstname');" title="Hans">
<br />
Nachname: 
<input id="regLastname" class="regField" type="text" name="surname" onChange="validateInput('regLastname');" title="Muster">


DATEPICKER HERE
<br /><br />
Geschlecht *&nbsp;&nbsp;
<img src="Images/female.png" title="Weiblich"><input class="regField" style="width:16px;" type="radio" name="sex" value="F">
<img src="Images/male.png" title="M&auml;nnlich"><input class="regField" type="radio" style="width:16px;" name="sex" value="M">
<br />
Nickname: <input id="regNickname" class="regField" type="text" name="nickname" onChange="validateInput('regNickname');" title="Wir als unter anderem als Namen im Forum angezeigt."><br />
Email :<input id="regEmail" class="regField" type="text" name="email" onChange="validateEmail();"><br />

<b>Die Community Regeln:</b><br />
<textarea id="regRules" class="regRules" name="regRules" readonly="readonly" style="border:0px; width: 100%; overflow: auto;" rows="10">
Aufnahme / Voraussetzungen:
Mindestalter: 18 (Die Ausnahme best&auml;tigt die Regel)
Nach der Aufnahme wird man zuerst "Anw&auml;rter" und hat solange nur bedingtes Mitspracherecht.

Forum/Gildenchat:
Foren und Gildenchat sollte f&uuml;r jedes Gildenmitglied selbstverst&auml;ndlich sein, jeder muss einen Forum Account haben und sich regelm&auml;ssig einloggen und sich &uuml;ber die aktuellen Geschehnisse zu informieren, sowie an Abstimmungen teilnehmen.

Umgang mit anderen:
Gepflegter Umgangston innerhalb und ausserhalb der Gilde. Die Gilde in fremden Gruppen sowie im Handels und anderen Chats vern&uuml;nftig vertreten, keine Flames, keine Gruppenverlassen ohne eine anst&auml;ndige Abmeldung, kein beschimpfen von anderen Personen.

Konflikte:
Konflikte zwischen Mitgliedern sind untereinander im Teamspeak zu kl&auml;ren. Jemand unparteiisches kann dazugeholt werden falls die Fronten zu verh&auml;rtet sind. Die Gildenleitung ist nicht dazu da um Streitigkeiten unter zwei Mitgliedern zu kl&auml;ren, er wird allenfalls die n&ouml;tigen Konsequenzen ziehen sofern die zwei streitenden Parteien keine L&ouml;sung finden.

Verlassen der Gilde:
Bei verlassen der Gilde ist aus kameradschaft im Forum eine Abmeldung zu hinterlassen. oder ggf. sich bei einem der Gildenr&auml;te abzumelden.
Probleme k&ouml;nnen diskutiert werden. "/gquit ist kein angemessenes Durckmittel"

Inaktivit&auml;t:
Wer weiss dass er l&auml;ngere Zeit nicht online kommen kann hat dies im Forum zu melden, bei Abwesenheit von mehr als einem Monat wird man auf den Rang "Inaktiv" gestellt wenn man das w&uuml;nscht.
Wer den Rang "Inaktiv" hat ist vor Gildenkicks durch l&auml;ngere Abwesenheit gesch&uuml;tzt und kann bis zu einem Jahr in der Gilde bleiben.


Der Verstoss gegen die Allgemeinen Gildenregeln kann eine Verwarnung oder den Ausschluss aus der Gilde zu Folge haben.
</textarea>
<input id="regCheckrules" onClick="javascript:$('regRules').style.border = '0px';" class="regField" type="checkbox" name="rulesaccepted" style="width:16px;"> Ich habe die allgemeinen Gildenregeln gelesen und werde mich an sie halten.
</form>
<hr style="clear:both;">
<input type="submit" onClick="javascript:showCaptcha();" value="Weiter" />
</div>
<div id="ImbaReCaptchaContainer">
<div id="ImbaReCaptcha"></div>
<input type="submit" onClick="javascript:Recaptcha.showhelp();" value="Hilfe! was ist das?" />
</div>
<!-- <input type="submit" onClick="javascript:cancleRegistration();" value="Stop it" />
<input type="submit" onClick="javascript:sendRegistration();" value="Do it" /> -->

