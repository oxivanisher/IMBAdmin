{* 
    
    passing down our javascript vars 

*}
var ajaxEntry = '{$ajaxPath}';
var phpSessionID = '{$phpSessionID}';
var imbaJsDebug = '{$jsDebug}';
var imbaErrorMessage = '{$imbaErrorMessage}';
var imbaAuthReferer = '{$imbaAuthReferer}';
{* 

    include library javascript files
    
*}
{fetch file='Libs/jQuery/js/jquery-1.5.2.min.js'}
{fetch file='Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js'}
{fetch file='Libs/DataTables/media/js/jquery.dataTables.min.js'}
{fetch file='Libs/jquery_jeditable/jquery.jeditable.js'}
{fetch file='Libs/jgrowl/jquery.jgrowl_compressed.js'}
{* 

    include imba javascript files

*}
{fetch file='Media/Js/ImbaBaseMethods.js'}
{fetch file='Media/Js/ImbaLogin.js'}
{fetch file='Media/Js/ImbaAdmin.js'}
{fetch file='Media/Js/ImbaGame.js'}
{fetch file='Media/Js/ImbaMessaging.js'}
{* 

    fill our imbaAdminContainerWorld container with ImbaIndex.tpl
    
*}
imbaHtmlContent = "<div id='imbaAdminContainerWorld'><div id='imbaMenu'><ul class='topnav'> \
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
document.write(imbaHtmlContent);