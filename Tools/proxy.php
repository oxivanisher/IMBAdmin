<?php

if ($_GET["site"]) {
    $site = $_GET["site"];
} else {
    $site = "http://braingoo.de";
}
/*
 * This file should be kind of a fake proxy for overloading imbadmin oder other sites
 */
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"';
echo '  http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
echo '<html><head><title>Knowledge Base</title>';
echo '<link type="text/css" href="http://alptroeim.ch/IMBAdmin/ImbaLoader.php?load=css" rel="Stylesheet" />';
echo '<script type="text/javascript" src="http://alptroeim.ch/IMBAdmin/ImbaLoader.php?load=js"></script>';
echo '</head><bod>';
echo '<iframe src="'.$site.'" style="height:98%; width:100%; border:0px; overflow: auto;" frameborder="no" scrolling="auto"></iframe>';
echo '</body></html>';
?>
