<script type="text/javascript">
    $(document).ready(function() {
        // Init DataTable
        var oTable = $('#ImbaAjaxAdminRoleTable').dataTable( {
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
                    action: "module",
                    module: "Admin",
                    request: "updaterole",
                    roleid: this.parentNode.getAttribute('id').substr(7),
                    rolecolumn: getColumnHeadByIndex("ImbaAjaxAdminRoleTable", oTable.fnGetPosition(this)[2])
                };
            },
            "height": "14px"
        } );
        
        $("#ImbaAjaxAdminRoleTable tr td span").click(function(){
            if(confirm("Soll die Rolle wirklich geloescht werden?")){                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "deleterole",
                    secSession: "{$secSession}",
                    roleid: this.parentNode.parentNode.getAttribute('id').substr(7)
                });

                var data = {
                    module: "Admin",
                    request: "role",
                    secSession: "{$secSession}"
                };
                loadImbaAdminTabContent(data);
            }            
        });
        
        $("#ImbaAddRoleOK").click( function() {
            if ((ImbaAddRoleRole.value.valueOf() != "")
                && (ImbaAddRoleHandle.value.valueOf() != "")
                && (ImbaAddRoleName.value.valueOf() != "")
                && (ImbaAddRoleIcon.value.valueOf() != "")
                && (ImbaAddRoleSmf.value.valueOf() != "")
                && (ImbaAddRoleWordpress.value.valueOf() != "")) {
                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "addrole",
                    secSession: "{$secSession}",
                    role: ImbaAddRoleRole.value.valueOf(),
                    handle: ImbaAddRoleHandle.value.valueOf(),
                    name: ImbaAddRoleName.value.valueOf(),
                    icon: ImbaAddRoleIcon.value.valueOf(),
                    smf: ImbaAddRoleSmf.value.valueOf(),
                    wordpress: ImbaAddRoleWordpress.value.valueOf()
                });
                
                var data = {
                    module: "Admin",
                    request: "role",
                    secSession: "{$secSession}"
                };
                loadImbaAdminTabContent(data);
                
            } else {
                alert('Please fill out all the fields');
            }
                
        });        
    } );
</script>
<table id="ImbaAjaxAdminRoleTable" class="dataTableDisplay">
    <thead>
        <tr>
            <th title="Role">Role</th>
            <th title="Name">Name</th>
            <th title="Handle">Handle</th>
            <th title="Icon">Icon</th>
            <th title="SMF">SMF</th>
            <th title="Wordpress">Wordpress</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

        {foreach $roles as $role}
        <tr id="roleid_{$role.id}">
            <td editable="true">{$role.role}</td>
            <td editable="true">{$role.name}</td>
            <td editable="true">{$role.handle}</td>
            <td editable="true">{$role.icon}</td>
            <td editable="true">{$role.smf}</td>
            <td editable="true">{$role.wordpress}</td>
            <td editable="false" class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td><input id="ImbaAddRoleRole" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddRoleName" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddRoleHandle" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddRoleIcon" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddRoleSmf" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddRoleWordpress" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td id="ImbaAddRoleOK" style="cursor: pointer;"><b>OK</b></td>
        </tr>
    </tfoot>
</table>