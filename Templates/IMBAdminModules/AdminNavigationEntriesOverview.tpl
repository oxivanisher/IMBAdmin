<script type="text/javascript">
    $(document).ready(function() {
        // Init DataTable
        var oTable = $('#ImbaAjaxAdminNavigationEntriesTable').dataTable( {
            "iDisplayLength": 13,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
        
        // Apply the jEditable handlers to the table
        $("td[editable|='true']", oTable.fnGetNodes()).editable(ajaxEntry, {
            "callback": function( sValue, y ) {
                var aPos = oTable.fnGetPosition( this );
                oTable.fnUpdate( sValue, aPos[0], aPos[1] );
            },
            "submitdata": function ( value, settings ) {
                return {
                    secSession: phpSessionID,
                    action: "module",
                    module: "Admin",
                    request: "updateportalentry",
                    portalentryid: this.parentNode.getAttribute('id').substr(6),
                    portalentrycolumn: getColumnHeadByIndex("ImbaAjaxAdminNavigationEntriesTable", oTable.fnGetPosition(this)[2])                                                                 
                };
            },
            "height": "14px"
        } );
        
        $("#ImbaAjaxAdminNavigationEntriesTable tr td span").click(function(){
            if(confirm("Should the Portal Entry really be deleted?")){               
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "deleteportalentry",
                    secSession: phpSessionID,
                    portalentryid: this.parentNode.parentNode.getAttribute('id').substr(6)
                }, function(response){
                    var data = {
                        module: "Admin",
                        request: "portalentry",
                        secSession: phpSessionID
                    };
                    loadImbaAdminTabContent(data);
                });

  
            }            
        });

        $("#ImbaAddNavigationEntriesOK").click( function() {
            if ((ImbaAddNavigationEntriesHandle.value.valueOf() != "")      
                && (ImbaAddNavigationEntriesName.valueOf() != "")
                && (ImbaAddNavigationEntriesTarget.valueOf() != "")
                && (ImbaAddNavigationEntriesUrl.valueOf() != "")
                && (ImbaAddNavigationEntriesComment.valueOf() != "")
                && (ImbaAddNavigationEntriesLoggedin.value.valueOf() != "")) {
                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "addportalentry",
                    secSession: phpSessionID,
                    handle: ImbaAddNavigationEntriesHandle.value.valueOf(),
                    name: ImbaAddNavigationEntriesName.value.valueOf(),
                    target: ImbaAddNavigationEntriesTarget.value.valueOf(),
                    url: ImbaAddNavigationEntriesUrl.value.valueOf(),
                    comment: ImbaAddNavigationEntriesComment.value.valueOf(),
                    loggedin: ImbaAddNavigationEntriesLoggedin.value.valueOf(),
                    role: ImbaAddNavigationEntriesRole.value.valueOf()
                }, function(response){
                    if (response != "Ok"){
                        $.jGrowl(response, { header: 'Error' });
                    } else {
                        $.jGrowl('Daten wurden gespeichert!', { header: 'Erfolg' });
                    }
                    var data = {
                        module: "Admin",
                        request: "portalentry",
                        secSession: phpSessionID
                    };
                    loadImbaAdminTabContent(data);
                }); 
            } else {
                alert('Please fill out all the fields');
            }
                
        });
    } );
    
</script>

<table id="ImbaAjaxAdminNavigationEntriesTable" class="dataTableDisplay">
    <thead>
        <tr>
            <th title="Interner Handle">Handle</th>
            <th title="Name">Name</th>
            <th title="Target">Target</th>
            <th title="Url">Url</th>
            <th title="Comment">Comment</th>
            <th title="Only show if logged in">Loggedin</th>
            <th title="Which role is allowed">Role</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

        {foreach $entries as $entry}
        <tr id="entry_{$entry.id}">
            <td editable="true">{$entry.handle}</td>
            <td editable="true">{$entry.name}</td>
            <td editable="true">{$entry.target}</td>
            <td editable="true">{$entry.url}</td>
            <td editable="true">{$entry.comment}</td>
            <td editable="true">{$entry.loggedin}</td>
            <td editable="true">{$entry.role}</td>
            <td editable="false" class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td><input id="ImbaAddNavigationEntriesHandle" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddNavigationEntriesName" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddNavigationEntriesTarget" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddNavigationEntriesUrl" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddNavigationEntriesComment" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddNavigationEntriesLoggedin" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddNavigationEntriesRole" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td id="ImbaAddNavigationEntriesOK" style="cursor: pointer;"><b>OK</b></td>
        </tr>
    </tfoot>
</table>