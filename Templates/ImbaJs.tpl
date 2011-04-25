{* 
    passing down our javascript vars 
*}
var ajaxEntry = '{$ajaxPath}';
var phpSessionID = '{$phpSessionID}';
{* 
    include javascript files
*}
{fetch file="file:../Libs/jQuery/js/jquery-1.4.4.min.js"}
{fetch file="file:../Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js"}
{fetch file="file:../Libs/DataTables/media/js/jquery.dataTables.min.js"}
{fetch file="file:../Libs/jquery_jeditable/jquery.jeditable.js"}
{fetch file="file:../Libs/jgrowl/jquery.jgrowl_compressed.js"}
{fetch file="file:../Media/ImbaBaseMethods.js"}
{fetch file="file:../Media/ImbaLogin.js"}
{fetch file="file:../Media/ImbaAdmin.js"}
{fetch file="file:../Media/ImbaGame.js"}
{fetch file="file:../Media/ImbaMessaging.js"}
{* 
    fill our imbaAdminContainerWorld container
*}
htmlContent = "<div id='imbaAdminContainerWorld'> \
<div id='imbaMenu'><ul class='topnav'> \
{$PortalNavigation} \
{$ImbaAdminNavigation} \
{$ImbaGameNavigation} \
{$PortalChooser} \
</ul></div> \
{include file="ImbaIndex.tpl"} \
</div> \";
{* 
    and inject it into the page
*}
document.write(htmlContent);