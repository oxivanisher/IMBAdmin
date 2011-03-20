<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaAjaxUsersOverviewTable').dataTable( {
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
    } );   
    
    function loadUserProfile(openid){
        var data = {
            action: "module",
            module: "Admin",
            request: "viewedituser",
            openid: openid
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<table id="ImbaAjaxUsersOverviewTable" class="display">
    <thead>
        <tr><th>Nickname</th><th>Zuletzt Online</th><th>Rolle</th></tr>
    </thead>
    <tbody>

        {foreach $susers as $user}
        <tr onclick="javascript: loadUserProfile('{$user.openid}');"><td>{$user.nickname}</td><td>{$user.lastonline}</td><td>{$user.role}</td></tr>
        {/foreach}

    </tbody>
</table>