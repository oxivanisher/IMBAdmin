<script type="text/javascript">
    $(document).ready(function() {
        $('#ImbaWebUsersOverviewTable').dataTable( {
            "bFilter": true,
            "sPaginationType": "full_numbers",
            "bJQueryUI": true
        } );
    } );
    
    function viewUserProfile ($user) {
        $.post(ajaxEntry, {
            action: "module",
            module: "User",
            tabId: "viewprofile",
            openid: $user
        }, function (response){
            if (response != ""){
//                ImbaContentContainer.innerHTML = response;
                $("#viewprofile").html(response);

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