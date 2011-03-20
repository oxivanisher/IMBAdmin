<script type="text/javascript">
  
    function startMaintenanceJob(handle){
        var data = {
            action: "module",
            module: "Admin",
            request: "runMaintenanceJob",
            jobHandle: handle
        };
        loadImbaAdminTabContent(data);
    }
   
</script>
<h3>Maintenance Jobs</h3>
<ul>
    {foreach $jobs as $job}
    <li><a onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a></li>
    {/foreach}
</ul>
