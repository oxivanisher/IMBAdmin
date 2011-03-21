<script type="text/javascript">
    $(document).ready(function() {
        // Init DataTable
        var oTable = $('#ImbaAjaxAdminSettingsTable').dataTable( {
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
                    request: "updatesetting",
                    settingid: this.parentNode.getAttribute('id').substr(7),
                    settingcolumn: getColumnHeadByIndex("ImbaAjaxAdminSettingsTable", oTable.fnGetPosition(this)[2])
                };
            },
            "height": "14px"
        } );
        
        $("#ImbaAjaxAdminSettingsTable tr td span").click(function(){
            if(confirm("Soll die Einstellung wirklich gelöscht werden?")){                
                $.post(ajaxEntry, {
                    action: "module",
                    module: "Admin",
                    request: "deletesetting",
                    roleid: this.parentNode.parentNode.getAttribute('id').substr(7)
                });
                
                var data = {
                    module: "Admin",
                    request: "settings"
                };
                loadImbaAdminTabContent(data);
            }            
        });
        
    } );  
</script>
<table id="ImbaAjaxAdminSettingsTable" class="display">
    <thead>
        <tr>
            <th title="Role">Name</th>
            <th title="Name">Wert</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

        {foreach $settings as $setting}
        <tr id="settingid_{$setting.name}">
            <td editable="false">{$setting.name}</td>
            <td editable="true">{$setting.value}</td>
            <td editable="false" class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
        </tr>
        {/foreach}

        <tr>
            <td><input type="text" style="width: 80px;"></td>
            <td><input type="text" style="width: 80px;"></td>
            <td> OK </td>
        </tr>

    </tbody>
</table>