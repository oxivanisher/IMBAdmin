<style type="text/css">
    #ImbaContentClickable {
        padding: 3px;
        margin: 3px;
        border: 1px grey solid;
        cursor: pointer;
        height: 120px; 
        width: 250px;
        float: left;
        padding: 3px;
/*        clear: both; */
    }
</style>
{foreach $navs as $nav}
<div id="ImbaContentClickable" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');"><h3>{$nav.name}</h3>{$nav.comment}</div>
{/foreach}
