<script type="text/javascript">
    $(document).ready(function() {
        // Init DataTable
        var oTable = $('#ImbaAjaxAdminPortalOverviewTable').dataTable( {
            "iDisplayLength": 13,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
	
        $("#ImbaAjaxAdminPortalOverviewTable tr td span").click(function(){
            if(confirm("Should the Portal really be deleted?")){               
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "deleteportal",
                    secSession: "{$secSession}",
                    portalid: this.parentNode.parentNode.getAttribute('id').substr(9)
                }, function(response){
                    var data = {
                        module: "Admin",
                        request: "viewportaldetail",
                        secSession: "{$secSession}"
                    };
                    loadImbaAdminTabContent(data);
                });

  
            }            
        });
        
        $("#ImbaAddPortalOK").click( function() {
            if ((ImbaAddPortalName.value.valueOf() != "")      
                && (ImbaAddPortalComment.valueOf() != "")
                && (ImbaAddPortalIcon.value.valueOf() != "")) {
                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "addportal",
                    secSession: "{$secSession}",
                    name: ImbaAddPortalName.value.valueOf(),
                    comment: ImbaAddPortalComment.value.valueOf(),
                    icon: ImbaAddPortalIcon.value.valueOf()                    
                }, function(response){
                    if (response != "Ok"){
                        $.jGrowl(response, { header: 'Error' });
                    } else {
                        $.jGrowl('Daten wurden gespeichert!', { header: 'Erfolg' });
                    }
                    var data = {
                        module: "Admin",
                        request: "viewportaldetail",
                        secSession: "{$secSession}"
                    };
                    loadImbaAdminTabContent(data);
                });
 
            } else {
                alert('Please fill out all the fields');
            }
                
        });        
    } );
    
    function showPortalDetail(portalid){
        var data = {
            module: "Admin",
            request: "viewportaldetail",
            secSession: "{$secSession}",
            portalid: portalid
        };
        loadImbaAdminTabContent(data);
    }
    
</script>
<table id="ImbaAjaxAdminPortalOverviewTable" class="dataTableDisplay">
    <thead>
        <tr>
            <th title="Icon">Icon</th>
            <th title="Name">Name</th>
            <th title="Comment">Comment</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

        {foreach $portals as $portal}
        <tr id="portalid_{$portal.id}">
            <td onclick="javascript: showPortalDetail('{$portal.id}');" style="cursor: pointer;">
                <img src="{$portal.icon}" alt="{$portal.name}" title="{$portal.name}" height="48" />
            </td>
            <td onclick="javascript: showPortalDetail('{$portal.id}');" style="cursor: pointer;">{$portal.name}</td>
            <td onclick="javascript: showPortalDetail('{$portal.id}');" style="cursor: pointer;">{$portal.comment}</td>
            <td class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td><input id="ImbaAddPortalIcon" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddPortalName" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddPortalComment" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td id="ImbaAddPortalOK" style="cursor: pointer;"><b>OK</b></td>
        </tr>
    </tfoot>
</table>