<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
<script type="text/javascript">
    // set the datepicker
    $("#regBirthday").datepicker({ 
        defaultDate: "-25y",
        dateFormat: 'dd.mm.yy', 
        changeMonth: true,
        changeYear: true });
         
    function createCaptcha () {
        Recaptcha.create("{$publicKey}", "ImbaReCaptcha", {
            theme: "blackglass",
            callback: Recaptcha.focus_response_field
        });
    }
  
    function cancleRegistration(){
        window.location.replace('{$authPath}?logout=true');
    };

    function step2 () {
        /**
         * Check if all needed forms are filled out
         * -->> http://docs.jquery.com/Plugins/validation
         */
        
        /**
         * Hide the form and show the captcha div
         */
        $("#ImbaReCaptchaContainer").show();
        $("#ImbaRegisterForm").hide();
        createCaptcha();
    };

    function checkCaptcha () {
        $.post(ajaxEntry, {
            action: "module",
            module: "Register",
            request: "checkCaptcha",
            challenge: $("#recaptcha_challenge_field").val(),
            answer: $("#recaptcha_response_field").val()
                
        }, function(response){
                
            if (response != "Ok"){
                // $.jGrowl('Deine Eingabe war nicht richtig!', { header: 'Error' });
                $.jGrowl(response, { header: 'Error' });
                Recaptcha.destroy();
                createCaptcha();
            } else {
                $.jGrowl('Deine Daten wurden gespeichert!', { header: 'Erfolg' });
    
                var data = {
                    module: "Register",
                    request: "registerme"
                };
                loadImbaAdminTabContent(data);
            }   
        });
    };
</script>
<table class="ImbaAjaxBlindTable" style="width: 100%; overflow: auto;">
    <tr>
        <td><i>Registrierung von {$openid}</i></td>
        <td style="text-align: right;"><input type="submit" onClick="javascript:cancleRegistration();" value="Abbrechen" /></td>
    </tr>
</table>
<hr />
<div id="ImbaRegisterForm">
    <form id='imbaSsoRegisterForm' action='' method='post'>
        <!--    <table class="ImbaAjaxBlindTable" style="cellspacing: 1px;"> -->

        <b>Bitte f&uuml;lle folgenden Felder wahrheitsgetreu aus.</b><br />
        <table class="ImbaAjaxBlindTable" style="width: 100%; overflow: auto;">
            <tr>
                <td>Vorname:</td>
                <td><input id="regFirstname" class="regField" type="text" name="forename" onChange="validateInput('regFirstname');" title="Hans"></td>
            </tr>
            <tr>
                <td>Nachname:</td>
                <td><input id="regLastname" class="regField" type="text" name="surname" onChange="validateInput('regLastname');" title="Muster"></td>
            </tr>
            <tr>
                <td>Geburtsdatum:</td>
                <td><input id="regBirthday" class="regField" type="text" name="birthdate" onChange="validateInput('regBirthdate');" title="01.01.1980"></td>
            </tr>
            <tr>
                <td>Geschlecht</td>
                <td><img src="Images/female.png" title="Weiblich"><input class="regField" style="width:16px;" type="radio" name="sex" value="F">
                    <img src="Images/male.png" title="M&auml;nnlich"><input class="regField" type="radio" style="width:16px;" name="sex" value="M"></td>
            </tr>
            <tr>
                <td>Nickname:</td>
                <td><input id="regNickname" class="regField" type="text" name="nickname" onChange="validateInput('regNickname');" title="Wir als unter anderem als Namen im Forum angezeigt."></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input id="regEmail" class="regField" type="text" name="email" onChange="validateEmail();" title="Du weisst hoffentlich wie eine Emailadresse aussieht!"></td>
            </tr>
        </table>
        <br />
        <b>Die Community Regeln:</b><br />
        <textarea id="regRules" class="regRules" name="regRules" readonly="readonly" style="border:0px; width: 100%; overflow: auto;" rows="7">
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
        <table class="ImbaAjaxBlindTable" style="width: 100%; overflow: auto;">
            <tr>
                <td><input id="regCheckrules" onClick="javascript:$('regRules').style.border = '0px';" class="regField" type="checkbox" name="rulesaccepted" style="width:16px;"> Ich habe die allgemeinen Gildenregeln gelesen und werde mich an sie halten.</td>
                <td style="text-align: right;"><input type="submit" onClick="javascript:step2();" value="Weiter" /></td>
            </tr>
        </table>
    </form>
</div>
<div id="ImbaReCaptchaContainer" style="display: none;">
        <b>Bitte beweise, dass du aus Fleisch und Blut bist.</b><br />
        Mit dem Abschreiben der W&ouml;rter zeigst du uns, dass du kein Programm bist dass unsere Communitysite zuspammen will. :)<br />
        <br />
        <br />
    <div id="ImbaReCaptcha"></div>
    <input type="submit" onClick="javascript:checkCaptcha();" value="Eingabe &Uuml;berpr&uuml;fen" />
    <input type="submit" onClick="javascript:Recaptcha.showhelp();" value="Hilfe! was ist das?" />
</div>