<ul>
    
    {foreach $topnavs as $navs}
    <li><a href="javascript:void(0)" onclick="javascript: loadImbaGame('{$navs.identifier}');" title="{$navs.comment}">{$navs.name}</a></li>
    <ul>
        {foreach $navs.options as $nav}
        <li><a href="javascript:void(0)" onclick="javascript: loadImbaGame('{$nav.game}', '{$nav.identifier}');" title="{$nav.comment}">{$nav.name}</a></li>
        {/foreach}
    </ul>   
    {/foreach}
</ul>