<pre>
i:{$id}
n:{$name}
c:{$comment}
i:{$icon}
u:{$url}
f:{$forumlink}

{foreach $categories as $category}
c:{$category.id} = {$category.name}
{/foreach}

{foreach $categoriesSelected as $categorySelected}
cs:{$categorySelected.id} = {$categorySelected.name}
{/foreach}



</pre>