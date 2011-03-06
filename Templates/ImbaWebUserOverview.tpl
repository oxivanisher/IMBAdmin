<script type="text/javascript">
$(document).ready(function() {
	$('#ImbaWebUsersOverviewTable').dataTable( {
            "bFilter": true,
            "sPaginationType": "full_numbers",
            "bJQueryUI": true,
        } );
} );
</script>
<table id="ImbaWebUsersOverviewTable" cellpadding="0" cellspacing="0" border="0" class="display">
    <thead>
        <tr><th>Nickname</th><th>Last Online</th><th>Jabber</th><th>Games</th></tr>
    </thead>
    <tbody>

        {foreach $susers as $user}
        <tr><td><a href="{$link}&openid={$user.openid}">{$user.nickname}</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        {/foreach}

    </tbody>
</table>