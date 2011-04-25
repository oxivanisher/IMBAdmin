{* 
    
    passing down our javascript vars 

*}
var ajaxEntry = '{$ajaxPath}';
var phpSessionID = '{$phpSessionID}';
{* 

    include library javascript files
    
*}{strip}
{fetch file='Libs/jQuery/js/jquery-1.4.4.min.js'}
{fetch file='Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js'}
{fetch file='Libs/DataTables/media/js/jquery.dataTables.min.js'}
{fetch file='Libs/jquery_jeditable/jquery.jeditable.js'}
{fetch file='Libs/jgrowl/jquery.jgrowl_compressed.js'}{/strip}{* 

    include our javascript files

*}{strip}
{fetch file='Media/ImbaBaseMethods.js'}
{fetch file='Media/ImbaLogin.js'}
{fetch file='Media/ImbaAdmin.js'}
{fetch file='Media/ImbaGame.js'}
{fetch file='Media/ImbaMessaging.js'}
{/strip}{* 

    fill our imbaAdminContainerWorld container with ImbaIndex.tpl
    
*}
htmlContent = "<div id='imbaAdminContainerWorld'><div id='imbaMenu'><ul class='topnav'> \
{strip}
{$PortalNavigation}
{$ImbaAdminNavigation}
{$ImbaGameNavigation}
{$PortalChooser}
{/strip}</ul> \
</div>{include file="ImbaIndex.tpl"}</div>";
{* 

    and inject it into the page
    
*}
document.write(htmlContent);