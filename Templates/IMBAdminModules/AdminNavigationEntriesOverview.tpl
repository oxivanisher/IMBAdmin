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

        {foreach $portals as $portal}
        <tr id="portalid_{$portal.id}">
            <td>Handle</td>
            <td>Name</td>
            <td>Target</td>
            <td>Url</td>
            <td>Comment</td>
            <td>Loggedin</td>
            <td>Role</td>
            <td>&nbsp;</td>
            <td class="ui-state-error"><span class="ui-icon ui-icon-closethick">X</span></td>
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