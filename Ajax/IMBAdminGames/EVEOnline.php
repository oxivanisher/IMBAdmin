<?php

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

require_once 'Ajax/IMBAdminGames/EVEOnline.Functions.php';

/*
include 'Libs/pheal/Pheal.php';
spl_autoload_register('Pheal::classload');
$pheal = new Pheal("7495716", "8D2AFBE8E0FE4122A62F976E1A2A0DBAF1D2935A0B994436BDD125D4ADC478AB");
*/

/*
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    /**
     * create a new smarty object
     */
    //$smarty = ImbaSharedFunctions::newSmarty();

    /**
     * Load the database
     */
    $managerUser = ImbaManagerUser::getInstance();


    switch ($_POST["request"]) {

        case "settings":
            echo "settings";
            break;
//----------------------------------------
        case "lintel":
            $test = IGBAcces();
            break;
//----------------------------------------
        default:
            echo "overview";
            
            
            //$result = $pheal->Characters();
            //foreach ($result->characters as $character)
             //   echo $character->name;
    }
    //$smarty->assign('test', true);
    //$smarty->display('IMBAdminGames/WelcomeIndex.tpl');
} else {
    echo "Not logged in";
}
?>