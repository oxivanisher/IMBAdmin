<?php

chdir("../");
require_once 'Controller/ImbaManagerMultigaming.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Model/ImbaGame.php';
require_once 'Model/ImbaGameCategory.php';

/**
 * Prepare Variables
 */
$managerMultigaming = new ImbaManagerMultigaming();
$output = "";

/**
 * Insert
 */
$gameCategory = new ImbaGameCategory();

try {
    $gameCategory->setName("Kategorie 1");
    $managerMultigaming->insertCategory($gameCategory);
    $output .= "insertCategory working.\n";
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}

/**
 * Select All
 */
try {
    $categories = $managerMultigaming->selectAllCategories();

    if (count($categories) > 0) {
        if ($categories[0]->getId() != null && $categories[0]->getId() != "") {
            $output .= "selectAllCategories working.\n";
        } else {
            throw new Exception("Kategorien fehlerhaft");
        }
    } else {
        throw new Exception("Keine Kategorien gefunden");
    }
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}

/**
 * SelectCategoryById
 */
try {
    foreach ($categories as $cat) {
        if ($cat->getName() == "Kategorie 1") {
            $gameCategory = $cat;
        }
    }

    $gameCategory = $managerMultigaming->selectCategoryById($gameCategory->getId());

    if ($gameCategory->getId() != null && $gameCategory->getId() != "") {
        $output .= "selectCategoryById working.\n";
    } else {
        throw new Exception("Keine Kategorie gefunden");
    }
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}


/**
 * UpdateCategory
 */
try {
    $gameCategory->setName("Kategorie 2");
    $managerMultigaming->updateCategory($gameCategory);
    $output .= "updateCategory working.\n";
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}


/**
 * DeleteCategory
 */
try {
    $managerMultigaming->deleteCategory($gameCategory);
    $output .= "deleteCategory working.\n";
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}

/**
 * Insert Game
 */
$category1 = new ImbaGameCategory();
$category1->setName("MMO");
$managerMultigaming->insertCategory($category1);

$category2 = new ImbaGameCategory();
$category2->setName("Fantasy");
$managerMultigaming->insertCategory($category2);

$categories = $managerMultigaming->selectAllCategories();
$category1 = $categories[0];
$category2 = $categories[1];

try {
    $game = new ImbaGame();
    $game->setComment("Comment wow");
    $game->setForumlink("Forumlink wow");
    $game->setName("World of Warcraft");
    $game->setUrl("www.wow.com");
    $game->setIcon("Wow Icon");
    $game->setCategories(array($category1, $category2));

    $managerMultigaming->insertGame($game);
    $output .= "insertGame working.\n";
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}

/**
 * Update Game
 */
try {
    $game->setComment("uiuiuiuiii");
    $managerMultigaming->updateGame($game);
    $output .= "updateGame working.\n";
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}

/**
 * Delete game and cat
 */
try {
    $managerMultigaming->deleteGame($game);
    $output .= "deleteGame working.\n";
    
    $managerMultigaming->deleteCategory($category1);
    $managerMultigaming->deleteCategory($category2);
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}


echo "<pre>ImbaManagerMultigaming Test:\n" . $output . "</pre>";
?>
