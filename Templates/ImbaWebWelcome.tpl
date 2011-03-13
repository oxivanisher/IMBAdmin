<h2>Hallo {$nickname}</h2>
Folgende Module stehen dir zur verf&uuml;gung:
<ul>
    
    {foreach $navs as $nav}
    <li><a href="#" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');">{$nav.name}</a></li>
    {foreach}

</ul>