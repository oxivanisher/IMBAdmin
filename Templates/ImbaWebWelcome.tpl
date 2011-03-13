<h2>Hallo {$nickname}</h2>
Folgende Module stehen dir zur verf&uuml;gung:
<ul>
    
    {foreach $navs as $nav}
    <li><a href="javascript:void(0)" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');" title="{$nav.comment}">{$nav.name}</a></li>
    {/foreach}

</ul>