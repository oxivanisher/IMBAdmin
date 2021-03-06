<script type="text/javascript">
    $(document).ready(function() {
        $("#imbaSsoOpenIdSubmit").button();
    });
</script>

<h1>Bei Alptr&ouml;im registrieren!</h1>
Um dich bei unserer Gamecommunity zu Bewerben ben&ouml;tigst du eine <a class="regLink" href="http://de.wikipedia.org/wiki/OpenID" target="_blank">OpenID</a>.<br />
Wenn du noch keine <a class="regLink" href="http://de.wikipedia.org/wiki/OpenID" target="_blank">OpenID</a>
besitzt darfst du dir gerne bei unseren <a href="https://oom.ch/openid/" target="_blank">OpenID-Provider</a><br>
eine ID erstellen indem du folgenden Link aufrufst: <a href="https://oom.ch/openid/users/register" target="_blank">OpenID bei OOM.ch registrieren</a>
<br /><br />
<form id='imbaSsoLoginForm' action='/IMBAdmin/ImbaAuth.php' method='post'>
    <input id='imbaSsoOpenId' name='openid' type='text' class='imbaInput' />
    <input id='imbaSsoOpenIdSubmit' type='submit' value='Registrieren' />
</form>
<h3>Was ist eine OpenID und wieso brauchen wir das?</h3>
Eine OpenID ist eine art Schl&uuml;ssel welcher f&uuml;r mehrere Web-basierte Dienste benutzt werden kann.<br />
alptr&ouml;im.ch bietet verschiedene Dienste wie Forum oder Blog die alle eine g&uuml;ltige Authentifizierung erfordern,<br />
um zu vermeiden das immer wieder ein Passwort beim zugriff auf die verschiedenen Dienste eingegeben<br />
werden muss. Genau daf&uuml;r ist der OpenID Schl&uuml;ssel da, welcher eine dezentrale anmeldung erm&ouml;glicht.<br />
<br  />
<h3>Habe ich vielleicht schon eine OpenID und wie sehen die aus?</h3>
<h4>Google</h4>
Wenn du einen Google-Account hast, zum Beispiel f&uuml;r dein gMail Konto oder alle anderen Google-Dienste,,<br />
hast du bereits eine OpenId. Diese kannst du auch f&uuml;r das Anmelden auf alptroeim.ch verwenden..<br /><br />
<h5>Wie sehen Google OpenIDs aus?</h5>
<b>Beispiel 1: </b> <i class="regExample">http://openid-provider.appspot.com/DeinGoogleBenutzerName@googlemail.com</i><br />
<b>Beispiel 2: </b> <i class="regExample">http://openid-provider.appspot.com/wasauchimmer@gmx.net</i><br />
<br />
<h4>OOM</h4>
OOM OpenIDs sind von unserem eigenen OpenID-Provider, wer sich hier eine OpenID macht hat den<br />
Vorteil das jeweils nur der Benutzername und nicht die ganze OpenID angegebenen werden muss.<br />
(Achtung! Gross/Kleinschreibung wird unterschieden)<br />
<a href="https://oom.ch/openid/users/register" target="_blank">jetzt bei OOM.ch registrieren</a><br><br>
<h5>Wie sehen OOM OpenIDs aus?</h5>
<b>Beispiel 1:</b> <i class="regExample">https://oom.ch/openid/identity/Smogg</i><br />
<b>Beispiel 2:</b> <i class="regExample">https://oom.ch/openid/identity/deinewahl@beispiel.com</i>