<script type="text/javascript">
    function backToMaintenance(){
        var data = {
            action: "module",
            module: "Admin",
            request: "maintenance"
        };
        loadImbaAdminTabContent(data);
    }
   
</script><h3>Job <i>"{$name}"</i> output:</h3>
{$message}
<br />
<a href="javascript:void(0)" onclick="javascript: backToMaintenance();">Back to Maintenance Overview</a>
