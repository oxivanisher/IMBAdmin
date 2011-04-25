<script type="text/javascript">
  
    function startMaintenanceJob(handle){
        var data = {
            module: "Maintenance",
            request: "runMaintenanceJob",
            secSession: "{$secSession}",
            jobHandle: handle
        };
        loadImbaAdminTabContent(data);
    }

    $(document).ready(function() {
        {foreach $jobs as $job}
        $("#imbaMaintenanceJob{$job.handle}").button();
        {/foreach}
    });
    
</script>
<h3>Maintenance Jobs</h3>
{foreach $maintenanceJobs as $job}
<a id="imbaMaintenanceJob{$job.handle}" href="javascript:void(0)" onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a>
{/foreach}

<h3>Database Jobs</h3>
{foreach $dbJobs as $job}
<a id="imbaMaintenanceJob{$job.handle}" href="javascript:void(0)" onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a>
{/foreach}

<h3>Usermanagement Jobs</h3>
{foreach $userJobs as $job}
<a id="imbaMaintenanceJob{$job.handle}" href="javascript:void(0)" onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a>
{/foreach}
