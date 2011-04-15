<script type="text/javascript">
    var gamesIPlayIds = new Array();
    {foreach $games as $game}
    gamesIPlayIds.push("#mygamesplaying_{$game.id}");
    {/foreach}

    function showAddGameProperty(gameId, propertyId, propertyName){
        $("#ImbaAjaxUsersMyGamesModalPropertyName").html(propertyName + " eingeben:");
        $("#ImbaAjaxUsersMyGamesModalPropertyId").val(propertyId);
        $("#ImbaAjaxUsersMyGamesModalPropertyValue").val("");
        
        $("#ImbaAjaxUsersMyGamesModalProperty").dialog('open');
    }

    $(document).ready(function() {
        // Set up the accordion
        $("#MyGamesTab").accordion({ autoHeight: false });

        // Set up modal window
        $("#ImbaAjaxUsersMyGamesModalProperty").dialog(
        {
            modal: true,
            autoOpen: false, buttons:
                [
                {
                    text: "Ok",
                    click: function() {
                        // submit the change
                        $.post(ajaxEntry, {
                            action: "module",
                            module: "User",
                            request: "addpropertytomygames",
                            propertyid: $("#ImbaAjaxUsersMyGamesModalPropertyId").val(),
                            propertyvalue: $("#ImbaAjaxUsersMyGamesModalPropertyValue").val()
                        }, function(response){
                            if (response != "Ok"){
                                $.jGrowl(response, { header: 'Error' });
                            } else {
                                $.jGrowl('Daten wurden gespeichert!', { header: 'Erfolg' });
                            }
                        });

                        //reload page
                        var data = {
                            action: "module",
                            module: "User",
                            request: "editmygames",
                            hasActiveGame: "true",
                            activeGame: $("#MyGamesTab").accordion( "option", "active" )
                        };
                        loadImbaAdminTabContent(data);
                        $(this).dialog("close");
                    }
                },
                {
                    text: "Abbrechen",
                    click: function() {
                        $(this).dialog("close");
                    }
                }
            ]
        });


        // User submits the ImbaAjaxUsersMyGamesForm
        $("#ImbaAjaxUsersMyGamesFormSubmit").button();
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

<div id="ImbaAjaxUsersMyGamesModalProperty" title="Eigenschaft hinzufügen">
    <table cellpadding="0" cellspacing="0" class="ImbaAjaxBlindTable">
        <tr>
            <td><div id="ImbaAjaxUsersMyGamesModalPropertyName"></div></td>
        </tr>
        <tr>
            <td><input id="ImbaAjaxUsersMyGamesModalPropertyValue" type="text"></td>
        </tr>
        <tr>
            <td><input id="ImbaAjaxUsersMyGamesModalPropertyId" type="hidden" /></td>
        </tr>
    </table>
</div>

{if $smarty.post.hasActiveGame == 'true'}
<script>
    $(function() {		
        $("#MyGamesTab").accordion("option", "active", {$smarty.post.activeGame});
    });
</script>
{/if}

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
            {foreach $game.propertyValues as $propertyValue}
            <ul>
                <li><b>{$propertyValue.property}</b>: {$propertyValue.value}</li>
            </ul>
            {/foreach}
        </div>
        {/foreach}

    </div>

    <br />
    <input id="ImbaAjaxUsersMyGamesFormSubmit" type="submit" value="Speichern" />

</form>