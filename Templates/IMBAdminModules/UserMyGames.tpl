<script type="text/javascript">
    var gamesIPlayIds = new Array();
    {foreach $games as $game}
    gamesIPlayIds.push("#mygamesplaying_{$game.id}");
    {/foreach}

    function showAddGameProperty(gameId, propertyId, propertyName){
        $("#ImbaAjaxUsersMyGamesModalPropertyName").html(propertyName);
        $("#ImbaAjaxUsersMyGamesModalProperty").dialog('open');
    }

    $(function() {
        // Set up the accordion
        $("#MyGamesTab").accordion();

        // Set up modal window
        $("#ImbaAjaxUsersMyGamesModalProperty").dialog({ modal: true, autoOpen: false  });

        $("#ImbaAjaxUsersMyGamesModalPropertyOk").click(function(){
            $("#ImbaAjaxUsersMyGamesModalProperty").dialog('close');
        });

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

<div id="ImbaAjaxUsersMyGamesModalProperty">
    <div id="ImbaAjaxUsersMyGamesModalPropertyName" style="float: left;"></div>&nbsp; eingeben: <input type="text"><input id="ImbaAjaxUsersMyGamesModalPropertyOk" type="button" value="Speichern">

</div>

<form id="ImbaAjaxUsersMyGamesForm" action="post">
    <div id="MyGamesTab">
        {foreach $games as $game}
        <h3><a href="#">{$game.name}</a></h3>
        <div>
            <p>
                <input id="mygamesplaying_{$game.id}" type="checkbox" {if $game.selected == 'true'}checked{/if}> Ja, ich spiele {$game.name}
            </p>

            <ul>
                {foreach $game.properties as $property}
                <li>{$property.property} <span onclick="javascript: showAddGameProperty('{$game.id}', '{$property.id}', '{$property.property}');" style="cursor: pointer;"><b>hinzufügen</b></span></li>
                {/foreach}
            </ul>

            Meine Daten zu {$game.name}:
            <ul>
                <li>MyProperty1</li>
                <li>MyProperty2</li>
                <li>MyProperty3</li>

            </ul>
        </div>
        {/foreach}

    </div>

    <br />
    <small>Bisher wird nur gespeichert was ich spiele, keine Properties!</small>
    <br />
    <input id="ImbaAjaxUsersMyGamesFormSubmit" type="submit" value="Speichern" />

</form>