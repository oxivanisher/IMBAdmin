<script type="text/javascript">
    function backToMaintenance(){
        var data = {
            action: "module",
            module: "Admin",
            request: "maintenance"
        };
        loadImbaAdminTabContent(data);
    }
   
</script><h3>Job <i>"{$name}"</i> running</h3>
<pre>
{$message}
</pre>
<br />
<a href="javascript:void(0)" onclick="javascript: backToMaintenance();">Back to Maintenance Overview</a>
