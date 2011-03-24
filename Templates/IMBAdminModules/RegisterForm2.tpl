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
<form id="ImbaAjaxUsersViewprofileForm" action="post">
<!--    <table id="ImbaAjaxBlindTable" style="cellspacing: 1px;"> -->

<h3>Deine OpenID {$openid}</h3>

Bitte f&uuml;lle folgenden Felder wahrheitsgetreu aus.
<hr style="clear:both;">

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
<hr style="clear:both;">
<span onClick="javascript:cancleRegistration();" style="font-size:16px;cursor:pointer;padding-left:480px;">Stop it</span>
<span onClick="javascript:sendRegistration();" style="font-size:16px;cursor:pointer;padding-left:480px;">Do it</span>
</form>

