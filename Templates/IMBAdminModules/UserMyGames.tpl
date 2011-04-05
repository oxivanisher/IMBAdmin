<script type="text/javascript">
    var gamesIPlayIds = new Array();
    {foreach $games as $game}
    gamesIPlayIds.push("#mygamesplaying_{$game.id}");
    {/foreach}

    $(function() {
        // Set up the accordion
        $("#MyGamesTab").accordion();

        // User submits the ImbaAjaxUsersMyGamesForm
        $("#ImbaAjaxUsersMyGamesFormSubmit").click(function(){
            var gamesIPlay = new Array();
            $.each(gamesIPlayIds, function(index, value) {
                gamesIPlay[index] = new Object();
                gamesIPlay[index]["gameid"] = value.substr(16);
                gamesIPlay[index]["checked"] = $(value).is(':checked');
            });

            // submit the change
            $.post(ajaxEntry, {
                action: "module",
                module: "User",
                request: "updatemygames",
                gamesIPlay: gamesIPlay
            }, function(response){
                if (response != "Ok"){                    
                    $.jGrowl(response, { header: 'Error' });
                } else {
                    $.jGrowl('Daten wurden gespeichert!', { header: 'Erfolg' });
                }
            });
            // TODO: Refresh from Database?
            return false;
        });
    });
</script>
<form id="ImbaAjaxUsersMyGamesForm" action="post">
    <div id="MyGamesTab">
        {foreach $games as $game}
        <h3><a href="#">{$game.name}</a></h3>
        <div>
            <p>
                <input id="mygamesplaying_{$game.id}" type="checkbox" {if $game.selected == 'true'}checked{/if}> Ja, ich spiele {$game.name}
            </p>
            Eigenschaften zu {$game.name}:<br />

            <table class="ImbaAjaxBlindTable" style="cellspacing: 1px;">
                {foreach $game.properties as $property}
                <tr><td>{$property.property}: </td><td><input id="mygamesplaying_{$game.id}_{$property.id}"></td></tr>
                {/foreach}
            </table>
        </div>
        {/foreach}

    </div>

    <br />
    <small>Bisher wird nur gespeichert was ich spiele, keine Properties!</small>
    <br />
    <input id="ImbaAjaxUsersMyGamesFormSubmit" type="submit" value="Speichern" />

</form>