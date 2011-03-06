<?php

if ($_POST["navigation_for_user"] == true) {
    $nav = array();
    array_push($nav, array("id" => "tabId1", "name" => "Tab1"));
    array_push($nav, array("id" => "tabId2", "name" => "Tab2"));
    array_push($nav, array("id" => "tabId3", "name" => "Tab3"));
    array_push($nav, array("id" => "tabId4", "name" => "Tab4"));

    echo json_encode($nav);
}
?>
