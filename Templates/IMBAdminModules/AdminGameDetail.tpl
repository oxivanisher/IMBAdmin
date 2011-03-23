<pre>
{$id}
{$name}
{$comment}
{$icon}
{$url}
{$forumlink}

{foreach $categories as $category}
{$category.id} = {$category.name}
{/foreach}

{foreach $categoriesSelected as $categorySelected}
{$categorySelected.id} = {$categorySelected.name}
{/foreach}



</pre>