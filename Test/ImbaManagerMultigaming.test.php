<?php

chdir("../");
require_once 'Controller/ImbaManagerGame.php';
require_once 'Controller/ImbaManagerGameCategory.php';
require_once 'Controller/ImbaManagerGameProperty.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Model/ImbaGame.php';
require_once 'Model/ImbaGameCategory.php';
require_once 'Model/ImbaGameProperty.php';

/**
 * Prepare Variables
 */
$managerGame = ImbaManagerGame::getInstance();
$managerCategory = ImbaManagerGameCategory::getInstance();
$managerProperty = ImbaManagerGameProperty::getInstance();
$output = "";

/**
 * Game insert
 */
$game = new ImbaGame();
$game->setComment("2");
$game->setForumlink("3");
$game->setIcon("4");
$game->setName("the new game");
$game->setUrl("6");
try {
    $managerGame->insert($game);
    $output.= "ImbaManagerGame insert working.\n";
} catch (Exception $e) {
    $output.= "Error at insert.\n";
}

/**
 * Game select all
 */
try {
    $games = $managerGame->selectAll();
    
    if (count($games) > 0) {
        $output.= "ImbaManagerGame selectAll working.\n";
    } else {
        throw new Exception("No results.");
    }
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGame selectAll.\n";
}

/**
 * Game update
 */
try {
    foreach ($games as $tmp) {
        if ($tmp->getName() == "the new game") {
            $game = $tmp;
        }
    }

    $game->setName("the new game 2");
    $managerGame->update($game);

    $output.= "ImbaManagerGame update working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGame update.\n";
}

/**
 * Game delete
 */
try {
    $managerGame->delete($game->getId());

    $output.= "ImbaManagerGame delete working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGame delete.\n";
}
echo "<pre>Multigaming Test:\n" . $output . "</pre>";

/**
 * Add a property to the game
 */
$output = "";
$property = new ImbaGameProperty();
$property->setGameId($game->getId());
$property->setProperty("property");
try {
    $managerProperty->insert($property);
    $output.= "ImbaManagerGameProperty insert working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameProperty.\n";
}

/**
 * Select all by id
 */
try {
    $properties = $managerProperty->selectAllByGameId($game->getId());

    foreach ($properties as $tmp) {
        if ($tmp->getProperty() == "property") {
            $property = $tmp;
        }
    }

    $output.= "ImbaManagerGameProperty selectAllByGameId working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameProperty.\n";
}

/**
 * Property update
 */
try {
    $property->setProperty("blabla");
    $managerProperty->update($property);

    $output.= "ImbaManagerGameProperty update working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameProperty update.\n";
}

/**
 * Property delete
 */
try {
    $managerProperty->delete($property->getId());

    $output.= "ImbaManagerGameProperty delete working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameProperty delete.\n";
}
echo "<pre>Multigaming Test:\n" . $output . "</pre>";


/**
 * Add a category to the game
 */
$output = "";
$category = new ImbaGameCategory();
$category->setName("categoryName");
try {
    $managerCategory->insert($category);
    $output.= "ImbaManagerGameCategory insert working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameCategory.\n";
}


/**
 * Select all by id
 */
try {
    $categories = $managerCategory->selectAll();

    foreach ($categories as $tmp) {
        if ($tmp->getName() == "categoryName") {
            $category = $tmp;
        }
    }

    $output.= "ImbaManagerGameCategory selectAll working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameCategory.\n";
}

/**
 * Category update
 */
try {
    $category->setName("blablibla");
    $managerCategory->update($category);

    $output.= "ImbaManagerGameCategory update working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameCategory update.\n";
}

/**
 * Category delete
 */
try {
    $managerCategory->delete($category->getId());

    $output.= "ImbaManagerGameCategory delete working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameCategory delete.\n";
}
echo "<pre>Multigaming Test:\n" . $output . "</pre>";


/**
 * Mix it up, baby 
 */
try {
    $output = "";
    
    $category1 = new ImbaGameCategory();
    $category1->setName("c1");
    $managerCategory->insert($category1);

    $category2 = new ImbaGameCategory();
    $category2->setName("c2");
    $managerCategory->insert($category2);
    
    $categories = $managerCategory->selectAll();

    $game = new ImbaGame();
    $game->setComment("2");
    $game->setForumlink("3");
    $game->setIcon("4");
    $game->setName("the new game");
    $game->setUrl("6");
    $game->setCategories(array($categories[0], $categories[1]));
    
    $game = $managerGame->insert($game);
    $managerGame->delete($game->getId());
        
    foreach($categories as $tmp){
        if ($tmp->getName() == "c1" || $tmp->getName() == "c2"){
            $managerCategory->delete($tmp->getId());
        }
    }
    
    $output.= "Mix working.\n";        
} catch (Exception $e) {
    $output.= "Error at Mix. " . $e->getMessage() . "\n";
}
echo "<pre>Multigaming Test:\n" . $output . "</pre>";
?>
