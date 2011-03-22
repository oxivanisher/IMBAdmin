<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaAjaxUsersOverviewTable').dataTable( {
            "iDisplayLength": 15,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
    } );   
    
    function loadUserProfile(openid){
        var data = {
            module: "User",
            request: "viewprofile",
            openid: openid
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<table id="ImbaAjaxUsersOverviewTable" class="dataTableDisplay">
    <thead>
        <tr><th>Nickname</th><th>Zuletzt Online</th><th>Jabber</th><th>Games</th></tr>
    </thead>
    <tbody>

        {foreach $susers as $user}
        <tr onclick="javascript: loadUserProfile('{$user.openid}');"><td>{$user.nickname}</td><td>{$user.lastonline}</td><td>{$user.jabber}</td><td>{$user.games}</td></tr>
        {/foreach}

    </tbody>
</table>