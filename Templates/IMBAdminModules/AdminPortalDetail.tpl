<script type="text/javascript">
    $(document).ready(function() {
        // User submits the ImbaAjaxAdminProfileForm
        $("#ImbaAjaxAdminProfileBackToOverview").button();
        $("#ImbaAjaxAdminProfileSave").button();
        $("#ImbaAjaxAdminProfileSave").click(function(){
            // submit the change
            $.post(ajaxEntry, {
                action: "module",
                module: "Admin",
                secSession: phpSessionID,
                request: "updateportal",
                portalid: $("#myPortalId").val(),
                name: $("#myPortalName").val(),
                icon: $("#myPortalIcon").val(),
                comment: $("#myProfileComment").val()
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
    
    function backToPortalOverview(){
        var data = {
            module: "Admin",
            request: "portaloverview",
            secSession: phpSessionID
        };
        loadImbaAdminTabContent(data);
    }
    
</script>
<form id="ImbaAjaxAdminProfileForm" action="post">
    <input id="myPortalId" type="hidden" name="id" value="{$id}" />
    <table class="ImbaAjaxBlindTable" style="cellspacing: 1px;">
        <tbody>
            <tr>
                <td>Name</td>
                <td><input id="myPortalName" type="text" name="name" value="{$name}" /></td>
            </tr>
            <tr>
                <td>Icon</td>
                <td><input id="myPortalIcon" type="text" name="icon" value="{$icon}" /></td>
            </tr>
            <tr>
                <td>Comment:</td>
                <td><textarea id="myProfileComment" name="comment" rows="4" cols="50">{$comment}</textarea></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Aliases</td>
                <td>
                    <ul>
                        {foreach $aliases as $alias}
                        <li>{$alias}</li>
                        {/foreach}
                    </ul>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Portal Entries</td>
                <td>
                    <ul>
                        {foreach $portalentries as $portalentry}
                        <li>{$portalentry.id} => {$portalentry.name}</li>
                        {/foreach}
                    </ul>
                </td>
            </tr>

            <tr>
                <td><a id="ImbaAjaxAdminProfileBackToOverview" href="javascript:void(0)" onclick="javascript: backToPortalOverview();">Back</a></td>
                <td><input id="ImbaAjaxAdminProfileSave" type="submit" value="Save" /></td>
            </tr>
        </tbody>
    </table>
</form>