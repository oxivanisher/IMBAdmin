
<h3>Base Settings for Game</h3>
<table id="ImbaAjaxBlindTable" style="cellspacing: 1px;">
    <!-- ID:{$id} -->
    <tr><td>n</td><td>{$name}</td></tr>
    <tr><td>c</td><td>{$comment}</td></tr>
    <tr><td>i</td><td>{$icon}</td></tr>
    <tr><td>u</td><td>{$url}</td></tr>
    <tr><td>f</td><td>{$forumlink}</td></tr>
</table>







<pre>
{foreach $categories as $category}
c:{$category.id} = {$category.name}
{/foreach}

{foreach $categoriesSelected as $categorySelected}
cs:{$categorySelected.id} = {$categorySelected.name}
{/foreach}
</pre>