<?php
session_start();

echo "<hr>ProxySessionTest.php:"."<br />";
if ($_SESSION['sessionTest'] == true) {
    $_SESSION['sessionTest'] = false;
    echo "Flip " . time()."<br />";
} else {
    $_SESSION['sessionTest'] = true;
    echo "Flop " . time()."<br />";
}
echo "PHP SESSION ID: " . session_id();

?>
