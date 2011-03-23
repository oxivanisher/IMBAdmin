<script type="text/javascript">
    $(document).ready(function() {
        // User submits the ImbaAjaxUsersViewprofileForm
        $("#ImbaAjaxAdminGameDetailFormSubmit").click(function(){
            // submit the change
            $.post(ajaxEntry, {
                action: "module",
                module: "Admin",
                request: "updategame",
                id: "{$id}",
                name: $("myGameName").val(),
                comment: $("myGameComment").val(),
                icon: $("myGameIcon").val(),
                url: $("myGameUrl").val(),
                forumlink: $("myGameForumlink").val()
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
    } );   
</script>
<h3>Base Settings for Game</h3>
<form id="ImbaAjaxAdminGameDetail" action="post">
    <table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
        <!-- ID:{$id} -->
        <tr>
            <td>Name</td>
            <td><input id="myGameName" type="text" name="name" value="{$name}" /></td>
        </tr>
        <tr>
            <td>Comment</td>
            <td><input id="myGameComment" type="text" name="comment" value="{$comment}" /></td>
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
            <td>&nbsp;</td>
            <td><input id="ImbaAjaxAdminGameDetailFormSubmit" type="submit" value="Speichern" /></td>
        </tr>
    </table>
</form>



<pre>
{foreach $categories as $category}
c:{$category.id} = {$category.name}
{/foreach}

{foreach $categoriesSelected as $categorySelected}
cs:{$categorySelected.id} = {$categorySelected.name}
{/foreach}
</pre>