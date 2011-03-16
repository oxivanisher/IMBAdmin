<?php

chdir("../");
require_once 'Controller/ImbaManagerMultigaming.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Model/ImbaGameCategory.php';

/**
 * Prepare Variables
 */
$managerDatabase = ImbaManagerDatabase::getInstance("localhost", "imbadmin", "imbadmin", "ua0Quee2");
$managerMultigaming = new ImbaManagerMultigaming($managerDatabase);
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
 * UpdateCategory
 */
try {
    $managerMultigaming->deleteCategory($gameCategory);
    $output .= "deleteCategory working.\n";
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}


echo "<pre>ImbaManagerMultigaming Test:\n" . $output . "</pre>";
?>
