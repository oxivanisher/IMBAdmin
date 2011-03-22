<script type="text/javascript">
    
    //http://www.datatables.net/plug-ins/sorting#how_to_type -> Hidden title numeric sorting
    jQuery.fn.dataTableExt.oSort['title-numeric-asc']  = function(a,b) {
        var x = a.match(/title="*(-?[0-9]+)/)[1];
        var y = b.match(/title="*(-?[0-9]+)/)[1];
        x = parseFloat( x );
        y = parseFloat( y );
        return ((x < y) ? -1 : ((x > y) ?  1 : 0));
    };

    jQuery.fn.dataTableExt.oSort['title-numeric-desc'] = function(a,b) {
        var x = a.match(/title="*(-?[0-9]+)/)[1];
        var y = b.match(/title="*(-?[0-9]+)/)[1];
        x = parseFloat( x );
        y = parseFloat( y );
        return ((x < y) ?  1 : ((x > y) ? -1 : 0));
    };
    
    $(document).ready(function() {
        $('#ImbaAjaxAdminLogTable').dataTable( {
            /*            "aoColumns": [
                { "sType": "title-numeric-asc" },
                null,
                null,
                null,
                null
            ], */
            "iDisplayLength": 16,
            "bFilter": true,
            "sPaginationType": "two_button",
            "bJQueryUI": true,
            "bLengthChange": false
        } );
        
  /*      $("td[editable|='true']", oTable.fnGetNodes()).editable(ajaxEntry, {
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
        } );*/

        
    } );   
    
    function showLogDetail(id){
        var data = {
            module: "Admin",
            request: "viewlogdetail",
            id: id
        };
        loadImbaAdminTabContent(data);
    }

</script>
<table id="ImbaAjaxAdminLogTable" class="dataTableDisplay">
    <thead>
        <tr>
            <th>When</th>
            <th>User</th>
            <th>Module</th>
            <th>Message</th>
            <th>Level</th>
        </tr>
    </thead>
    <tbody>

        {foreach $logs as $log}
        <tr onclick="javascript: showLogDetail('{$log.id}');">
            <td><span title="{$log.timestamp}"></span>{$log.timestamp} {$log.age}</td>
            <td>{$log.user}</td>
            <td>{$log.module}</td>
            <td>{$log.message}</td>
            <td>{$log.lvl}</td>
        </tr>
        {/foreach}

    </tbody>
</table>