<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaWebUsersOverviewTable').dataTable( {
            "bFilter": true,
            "sPaginationType": "full_numbers",
            "bJQueryUI": true
        } );
    } );   
    
    function loadUserProfile(openid){
        var data = {
            action: "module",
            module: "User",
            tabId: "viewprofile",
            openid: openid
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<table id="ImbaWebUsersOverviewTable" class="display">
    <thead>
        <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr>
    </thead>
    <tbody>

        {foreach $susers as $user}
        <tr onclick="javascript: loadUserProfile('{$user.openid}');"><td>{$user.nickname}</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        {/foreach}

    </tbody>
</table>