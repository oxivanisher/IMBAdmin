<style type="text/css">
    #ImbaContentClickable {
        padding: 3px;
        margin: 3px;
        border: 2px grey solid;
        cursor: pointer;
        height: 120px; 
        width: 250px;
        float: left;
        padding: 3px;
        /*        clear: both; */
    }
    #ImbaContentClickable:hover {
        border: 2px white solid;
        background-color: #333333;
    }
</style>
{foreach $navs as $nav}
<div id="ImbaContentClickable" onclick="javascript: loadImbaAdminModule('{$nav.identifier}');"><h3>{$nav.name}</h3>{$nav.comment}</div>
{/foreach}
