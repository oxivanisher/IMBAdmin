<ul>
    
    {foreach $topnavs as $navs}
    <li><a href="javascript:void(0)" onclick="javascript: loadImbaAdminModule('{$navs.identifier}');" title="{$navs.comment}">{$navs.name}</a></li>
    <ul>
        {foreach $navs.options as $nav}
        <li><a href="javascript:void(0)" onclick="javascript: loadImbaAdminModule('{$nav.module}', '{$nav.identifier}');" title="{$nav.comment}">{$nav.name}</a></li>
        {/foreach}
    </ul>   
    {/foreach}
</ul>