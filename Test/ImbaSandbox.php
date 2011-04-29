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
$managerGameCategory = ImbaManagerGameCategory::getInstance();
$managerProperty = ImbaManagerGameProperty::getInstance();

$managerGame->selectAll();
$game = $managerGame->selectById(24);

var_dump($game);

$smarty = ImbaSharedFunctions::newSmarty();
$smarty->assign("id", $game->getId());
$smarty->assign("name", $game->getName());
$smarty->assign("comment", $game->getComment());
$smarty->assign("icon", $game->getIcon());
$smarty->assign("url", $game->getUrl());
$smarty->assign("forumlink", $game->getForumlink());

$smarty_categories = array();
foreach ($managerGameCategory->selectAll() as $category) {
    $selected = "false";
    foreach ($game->getCategories() as $selCategory) {
        if ($selCategory->getId() == $category->getId()) {
            $selected = "true";
        }
    }

    array_push($smarty_categories, array(
        'id' => $category->getId(),
        'name' => $category->getName(),
        'selected' => $selected
    ));
}
$smarty->assign('categories', $smarty_categories);

$smarty_properties = array();
foreach ($game->getProperties() as $property) {
    array_push($smarty_properties, array(
        'id' => $property->getId(),
        'name' => $property->getProperty()
    ));
}
$smarty->assign('properties', $smarty_properties);

$smarty->display('IMBAdminModules/AdminGameDetail.tpl');
?>
