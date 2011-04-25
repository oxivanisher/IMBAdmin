<script type="text/javascript">
    function backToMaintenance(){
        var data = {
            module: "Maintenance",
            request: "maintenance",
            secSession: "{$secSession}"
        };
        loadImbaAdminTabContent(data);
    }
    
    $(document).ready(function() {
        $("#imbaMaintenanceBackToJobOverview").button();
    });
       
</script><h3>Job <i>"{$name}"</i> output:</h3>
{$message}
<br />
<br />
<a id="imbaMaintenanceBackToJobOverview" href="javascript:void(0)" onclick="javascript: backToMaintenance();">Back to Maintenance Overview</a>