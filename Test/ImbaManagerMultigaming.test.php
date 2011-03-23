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
$game->setCategories("1");
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
$property->setGameId($game->getId);
$property->setProperty("property");
try {
    $managerProperty->insert($property);
    $output.= "ImbaManagerGameProperty insert working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGameProperty.\n";
}

/**
 * Property update
 */
try {
    $property->setProperty("blabla");
    $managerProperty->update($property);

    $output.= "ImbaManagerGame update working.\n";
} catch (Exception $e) {
    $output.= "Error at ImbaManagerGame update.\n";
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

?>
