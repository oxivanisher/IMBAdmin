<style type="text/css">
    #ImbaContentClickable {
        padding: 1px;
        border: 1px grey solid;
        cursor: pointer;
        height: 120px; 
        width: 250px;
        float: left;
        clear: both;
    }
</style>
<h2>Hallo {$nickname}</h2>
Folgende Module stehen dir zur verf&uuml;gung:
<br />    
{foreach $navs as $nav}
<div class="ImbaContentClickable" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');"><h4>{$nav.name}</h4>{$nav.comment}</div>
<br />
<br />
{/foreach}
