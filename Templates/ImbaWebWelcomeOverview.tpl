<h2>Hallo {$nickname}</h2>
Folgende Module stehen dir zur verf&uuml;gung:
<br />    
{foreach $navs as $nav}
<div onclick="javascript: loadImbaAdminModule('{$nav.identifier}');"><h4>{$nav.name}</h4>{$nav.comment}</div>
{/foreach}
