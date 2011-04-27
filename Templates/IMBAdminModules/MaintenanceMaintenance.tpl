<script type="text/javascript">
  
    function startMaintenanceJob(handle){
        var data = {
            module: "Maintenance",
            request: "runMaintenanceJob",
            jobHandle: handle
        };
        loadImbaAdminTabContent(data);
    }

    $(document).ready(function() {
        {foreach $maintenanceJobs as $job}
        $("#imbaMaintenanceJob{$job.handle}").button();
        {/foreach}
        {foreach $dbJobs as $job}
        $("#imbaMaintenanceJob{$job.handle}").button();
        {/foreach}
        {foreach $userJobs as $job}
        $("#imbaMaintenanceJob{$job.handle}").button();
        {/foreach}
        {foreach $debugJobs as $job}
        $("#imbaMaintenanceJob{$job.handle}").button();
        {/foreach}
        $("#imbaMaintenanceJobtoggleProxyLog").button();
        $("#imbaMaintenanceJobtoggleProxyLog").click(function () {
            $.post(ajaxEntry, {
                toggleProxyDebug: "true",
                secSession: phpSessionID
            }, function (response){
                $.jGrowl(response, {
                    header: 'Proxy return:',
                    life: 1000
                });
            });
        });
    });
    
</script>
<h3>Maintenance Jobs</h3>
{foreach $maintenanceJobs as $job}
<a id="imbaMaintenanceJob{$job.handle}" href="javascript:void(0)" onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a>
{/foreach}
<br />
<br />
<h3>Database Jobs</h3>
{foreach $dbJobs as $job}
<a id="imbaMaintenanceJob{$job.handle}" href="javascript:void(0)" onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a>
{/foreach}
<br />
<br />
<h3>Usermanagement Jobs</h3>
{foreach $userJobs as $job}
<a id="imbaMaintenanceJob{$job.handle}" href="javascript:void(0)" onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a>
{/foreach}
<br />
<br />
<h3>Debug Jobs</h3>
{foreach $debugJobs as $job}
<a id="imbaMaintenanceJob{$job.handle}" href="javascript:void(0)" onclick="javascript: startMaintenanceJob('{$job.handle}');">{$job.name}</a>
{/foreach}
<a id="imbaMaintenanceJobtoggleProxyLog" href="javascript:void(0)" onclick="javascript: toggleProxyLog();">Toggle Proxy Log</a>