<script type="text/javascript">
    //Additional datatable sorting functions are in ImbaBaseMethods.js

    $(document).ready(function() {
        $('#ImbaAjaxAdminLogTable').dataTable( {
            "aoColumns": [
                { "sType": "title-numeric" },
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
            <td><span title="{$log.timestamp}">{$log.age}</span></td>
            <td>{$log.user}</td>
            <td>{$log.module}</td>
            <td>{$log.message}</td>
            <td>{$log.lvl}</td>
        </tr>
        {/foreach}

    </tbody>
</table>