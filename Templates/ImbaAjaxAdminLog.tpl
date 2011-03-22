<script type="text/javascript">
    
    $(document).ready(function() {
        $('#ImbaAjaxAdminLogTable').dataTable( {
            "aoColumns": [
                { "sType": "title-numeric-asc" },
                null,
                null,
                null,
                null
            ],
            "iDisplayLength": 16,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
    } );   
    
    function showLogDetail(id){
        var data = {
            module: "Admin",
            request: "viewlogdetail",
            id: id
        };
        loadImbaAdminTabContent(data);
    }

</script>
<table id="ImbaAjaxAdminLogTable" class="dataTableDisplay">
    <thead>
        <tr>
            <th>When</th>
            <th>User</th>
            <th>Module</th>
            <th>Message</th>
            <th>Level</th>
        </tr>
    </thead>
    <tbody>

        {foreach $logs as $log}
        <tr onclick="javascript: showLogDetail('{$log.id}');">
            <td title="{$log.timestamp}">{$log.age}</td>
            <td>{$log.user}</td>
            <td>{$log.module}</td>
            <td>{$log.message}</td>
            <td>{$log.lvl}</td>
        </tr>
        {/foreach}

    </tbody>
</table>