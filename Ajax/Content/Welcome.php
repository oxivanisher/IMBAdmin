<?php

$contentNav = new ImbaContentNavigation();

echo "<ul>";

if ($handle = opendir('Ajax/Content/')) {
    $identifiers = array();
    while (false !== ($file = readdir($handle))) {
        if (strrpos($file, ".Navigation.php") > 0) {
            include 'Ajax/Content/' . $file;
            if (ImbaUserContext::getUserRole() >= $Navigation->getMinUserRole()) {
                $showMe = false;
                if (ImbaUserContext::getLoggedIn() && $Navigation->getShowLoggedIn()) {
                    $showMe = true;
                } elseif ((!ImbaUserContext::getLoggedIn()) && $Navigation->getShowLoggedOff()) {
                    $showMe = true;
                }

                if ($showMe) {
                    $modIdentifier = str_replace(".Navigation.php", "", $file);
                    echo "<li><a href='#' onclick='javascript: loadImbaAdminModule('" . $modIdentifier . "');'>" . $Navigation->getName($nav) . "</a></li>";
                    array_push($identifiers, $modIdentifier);
                    $Navigation = null;
                }
            }
        }
    }
    closedir($handle);
}

echo "</ul>";

?>
