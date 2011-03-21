<script type="text/javascript">
    $(document).ready(function() {
        // Init DataTable
        var oTable = $('#ImbaAjaxAdminRoleTable').dataTable( {
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
            if(confirm("Soll die Rolle wirklich gelöscht werden?")){                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "deleterole",
                    roleid: this.parentNode.parentNode.getAttribute('id').substr(7)
                });
                
                var data = {
                    module: "Admin",
                    request: "role"
                };
                loadImbaAdminTabContent(data);
            }            
        });
        
    } );
    
    
</script>
<table id="ImbaAjaxAdminRoleTable" class="display">
    <thead>
        <tr>
            <th title="Role">Role</th>
            <th title="Name">Name</th>
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
            <td editable="true">{$role.icon}</td>
            <td editable="true">{$role.smf}</td>
            <td editable="true">{$role.wordpress}</td>
            <td editable="false" class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td><input type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td> OK </td>
        </tr>
    </tfoot>
</table>