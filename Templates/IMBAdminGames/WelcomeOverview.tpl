<style type="text/css">
    #ImbaContentClickable {
        padding: 3px;
        margin: 3px;
        border: 2px grey solid;
        cursor: pointer;
        height: 105px; 
        width: 305px;
        float: left;
        padding: 3px;
        text-align: center;
        /*        clear: both; */
    }
    #ImbaContentClickable:hover {
        border: 2px lightgrey solid;
        background-color: #222222;
    }
</style>
{foreach $navs as $nav}
<div id="ImbaContentClickable" onclick="javascript: loadImbaGame('{$nav.identifier}');"><h3>{$nav.name}</h3>{$nav.comment}</div>
{/foreach}