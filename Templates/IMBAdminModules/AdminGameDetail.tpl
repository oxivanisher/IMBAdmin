<script type="text/javascript">
    $(document).ready(function() {
        // User submits the ImbaAjaxUsersViewprofileForm
        $("#ImbaAjaxAdminGameDetailFormSubmit").click(function(){
            // submit the change
            $.post(ajaxEntry, {
                action: "module",
                module: "Admin",
                request: "updategame",
                gameid: "{$id}",
                name: $("#myGameName").val(),
                comment: $("#myGameComment").val(),
                icon: $("#myGameIcon").val(),
                url: $("#myGameUrl").val(),
                forumlink: $("#myGameForumlink").val(),
                myGameCategories: $("#myGameCategories").val()
            }, function(response){
                if (response != "Ok"){
                    // $.jGrowl('Daten wurden nicht gespeichert!', { header: 'Error' });
                    $.jGrowl(response, { header: 'Error' });
                } else {
                    $.jGrowl('Daten wurden gespeichert!', { header: 'Erfolg' });
                }
            });
            // TODO: Refresh from Database?
            return false;
        });
        
        $("#ImbaAjaxAdminGameDetailProperty tr td span").click(function(){
            if(confirm("Soll die Eigenschaft wirklich geloescht werden?")){               
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "deletegameproperty",
                    gamepropertyid: this.parentNode.parentNode.getAttribute('id').substr(15)
                }, function(response){
                    if (response != "Ok"){
                        $.jGrowl(response, { header: 'Error' });
                    } else {
                        $.jGrowl('Daten wurden gespeichert! Eigenschaft geloescht.', { header: 'Erfolg' });
                    }
                });               
               reloadGameDetail();
            }            
        });
                     
        $("#myGameAddPropertyOK").click( function() {
            if ($("#myGameAddProperty").val() != "") {                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "addpropertytogame",
                    gameid: "{$id}",
                    property: $("#myGameAddProperty").val()
                }, function(response){
                    if (response != "Ok"){
                        $.jGrowl(response, { header: 'Error' });
                    } else {
                        $.jGrowl('Daten wurden gespeichert! Eigenschaft hinzugefüt.', { header: 'Erfolg' });
                    }
                });               
               reloadGameDetail();
            } else {
                alert('Bitte Eigenschaft eintragen');
            }
                
        }); 
        
    } );   
    
    function reloadGameDetail(){
        var data = {
            module: "Admin",
            request: "viewgamedetail",
            id:  "{$id}"
        };
        loadImbaAdminTabContent(data);
    }
</script>
<h2>Base Settings for Game</h2>
<form id="ImbaAjaxAdminGameDetail" action="post">
    <table class="ImbaAjaxBlindTable" style="cellspacing: 1px;">
        <!-- ID:{$id} -->
        <tr>
            <td>Name</td>
            <td><input id="myGameName" type="text" name="name" value="{$name}" /></td>
            <td rowspan="4">Comment:<br />
                <textarea id="myGameComment" name="comment" rows="5" cols="20">{$comment}</textarea></td>

        </tr>
        <tr>
            <td>Icon</td>
            <td><input id="myGameIcon" type="text" name="icon" value="{$icon}" /></td>
        </tr>
        <tr>
            <td>Url</td>
            <td><input id="myGameUrl" type="text" name="url" value="{$url}" /></td>
        </tr>
        <tr>
            <td>Forumlink</td>
            <td><input id="myGameForumlink" type="text" name="forumlink" value="{$forumlink}" /></td>
        </tr>
        <tr>
            <td> style="vertical-align: top;">Kategorien</td>
            <td>
                <select id="myGameCategories" size="5" multiple="true" style="width: 100%; overflow: auto;">
                    {foreach $categories as $category}
                    <option value="{$category.id}" {if $category.selected == 'true'}selected{/if} >{$category.name}</option>
                    {/foreach}
                </select>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align: top;">Eigenschaften</td>
            <td>
                <input id="myGameAddProperty" type="text" name="addproperty" value="" />
            </td>
            <td id="myGameAddPropertyOK" style="cursor: pointer;"><b>OK</b></td>
        </tr>
        <tr>
            <td>
                <table id="ImbaAjaxAdminGameDetailProperty" class="ImbaAjaxBlindTable">
                    <thead>
                        <tr>
                            <th title="Eigenschaft">Eigenschaft</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $properties as $property}
                        <tr id="gamepropertyid_{$property.id}">
                            <td>{$property.name}</td><td class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>         
                        </tr>
                        {/foreach}
                    </tbody>        
                </table>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input id="ImbaAjaxAdminGameDetailFormSubmit" type="submit" value="Speichern" /></td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>