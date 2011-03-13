<style type="text/css">
#ImbaContentClickable div {
    padding: 1px;
    border: 1px grey;        
    cursor: pointer;
    height: 100px;
    width: 250px;
    float: left;
    clear:left;
}
</style>
<h2>Hallo {$nickname}</h2>
Folgende Module stehen dir zur verf&uuml;gung:
<br />    
{foreach $navs as $nav}
<div id="ImbaContentClickable" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');"><h4>{$nav.name}</h4>{$nav.comment}</div>
<br />
<br />
{/foreach}
