{* 
    
    passing down our javascript vars 

*}
var ajaxEntry = '{$ajaxPath}';
var phpSessionID = '{$phpSessionID}';
{* 

    include library javascript files
    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>
*}
{fetch file='Libs/jQuery/js/jquery-1.4.4.min.js'}
{fetch file='Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js'}
{fetch file='Libs/DataTables/media/js/jquery.dataTables.min.js'}
{fetch file='Libs/jquery_jeditable/jquery.jeditable.js'}
{fetch file='Libs/jgrowl/jquery.jgrowl_compressed.js'}
{* 

    include imba javascript files

*}
{fetch file='Media/ImbaBaseMethods.js'}
{fetch file='Media/ImbaLogin.js'}
{fetch file='Media/ImbaAdmin.js'}
{fetch file='Media/ImbaGame.js'}
{fetch file='Media/ImbaMessaging.js'}
{* 

    fill our imbaAdminContainerWorld container with ImbaIndex.tpl
    
*}
htmlContent = "<div id='imbaAdminContainerWorld'><div id='imbaMenu'><ul class='topnav'> \
{strip}
{$PortalNavigation}
{$ImbaAdminNavigation}
{$ImbaGameNavigation}
{$PortalChooser}
{/strip}</ul> \
</div>{include file="ImbaLoaderDivConstruct.tpl"}</div>";
{* 

    and inject it into the page
    
*}
document.write(htmlContent);