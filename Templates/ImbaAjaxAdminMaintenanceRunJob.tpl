<script type="text/javascript">
    function backToMaintenance(){
        var data = {
            action: "module",
            module: "Admin",
            request: "maintenance"
        };
        loadImbaAdminTabContent(data);
    }
   
</script><h3>Maintenance Job {$name} running</h3>
<pre>
{$message}
</pre>
<br /><a href="javascript:void(0)" onclick="javascript: backToMaintenance();">back</a>