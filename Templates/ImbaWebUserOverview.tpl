<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaWebUsersOverviewTable').dataTable( {
            "bFilter": true,
            "sPaginationType": "full_numbers",
            "bJQueryUI": true
        } );
    } );
    
    
    /**
     * Returns the current selected tab index
     */
    function getSelectedTabIndex(){
        return $('#imbaContentNav').tabs('option', 'selected');
    }

    /**
     * Return the Id of a tab from a tabIndex
     */
    function getTabIdFromTabIndex(tabIndex){
        var result = "";
        $.each($("#imbaContentNav a"), function (k, v) {
            if (k == tabIndex){
                var tmp = v.toString().split("#");
                result = "#" + tmp[1];
            }
        });

        return result;
    }
    
    function viewUserProfile ($user) {
        
        alert(getTabIdFromTabIndex(getSelectedTabIndex()));
        
        $.post(ajaxEntry, {
            action: "module",
            module: "User",
            tabId: "viewprofile",
            openid: $user
        }, function (response){
            if (response != ""){
                //                ImbaContentContainer.innerHTML = response;
                $("#overview").html(response);

            }
        });
    }
</script>
<table id="ImbaWebUsersOverviewTable" class="display">
    <thead>
        <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr>
    </thead>
    <tbody>

        {foreach $susers as $user}
        <tr onclick="javascript: viewUserProfile('{$user.openid}');"><td>{$user.nickname}</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        {/foreach}

    </tbody>
</table>