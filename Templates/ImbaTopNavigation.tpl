<div id='imbaMenu'> \
    <ul class='topnav'> \
        <li><a href='http://alptroeim.ch/site/viewnews.php'>News/Blog</a></li> \
        <li><a href='http://alptroeim.ch/site/wrapper.php?id=board'>Forum</a></li> \
        <li><a href='#'>Games / Module</a></li> \
        <li> \
            <a id='imbaMenuImbAdmin' href='#'>Auf zum Atem</a> \
            {if $navs} \
            <ul class='subnav'> \
                <!-- FIXME: add imbaadmin open function  --> \
                {foreach $navs as $nav} \
                <li><a href='{$nav.url}'>{$nav.name}</a></li> \
                {/foreach} \
            </ul> \
            {fi}
        </li> \
    </ul> \
</div> \