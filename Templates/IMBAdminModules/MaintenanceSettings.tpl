<script type="text/javascript">
    $(document).ready(function() {
        // Init DataTable
        var oTable = $('#ImbaAjaxAdminSettingsTable').dataTable( {
            "iDisplayLength": 16,
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
                    module: "Maintenance",
                    request: "updatesetting",
                    settingid: this.parentNode.getAttribute('id').substr(10),
                    settingcolumn: getColumnHeadByIndex("ImbaAjaxAdminSettingsTable", oTable.fnGetPosition(this)[2])
                };
            },
            "height": "14px"
        } );
        
    $("#ImbaAjaxAdminSettingsTable tr td span").click(function(){
        if(confirm("Soll die Einstellung wirklich gelöscht werden?")){                
            $.post(ajaxEntry, {
                action: "module",
                module: "Maintenance",
                request: "deletesetting",
                secSession: "{$secSession}",
                settingid: this.parentNode.parentNode.getAttribute('id').substr(10)
            });
                
            var data = {
                module: "Maintenance",
                request: "settings"
            };
            loadImbaAdminTabContent(data);
        }            
    });
        
    $("#ImbaAddSettingOK").click( function() {
        if ((ImbaAddSettingName.value.valueOf() != "") && (ImbaAddSettingValue.value.valueOf() != "")) {
            $.post(ajaxEntry, {
                action: "module",
                module: "Maintenance",
                request: "addsetting",
                secSession: "{$secSession}",
                name: ImbaAddSettingName.value.valueOf(),
                value: ImbaAddSettingValue.value.valueOf()
            });

            var data = {
                module: "Maintenance",
                request: "settings"
            };
            loadImbaAdminTabContent(data);
                
        } else {
            alert('Please fill out all the fields');
                
        }
                
    });
        
} );  
</script>
<table id="ImbaAjaxAdminSettingsTable" class="dataTableDisplay">
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
    </tbody>
    <tfoot>
        <tr>
            <td><input id="ImbaAddSettingName" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td><input id="ImbaAddSettingValue" type="text" style="width: 100%; overflow: auto; height: 24px;"></td>
            <td id="ImbaAddSettingOK" style="cursor: pointer;"><b>OK</b></td>
        </tr>
    </tfoot>
</table>