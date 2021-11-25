<?php

session_start();

DEFINE("FRAMEWORK_PATH", dirname(__FILE__) . "/");
DEFINE("BASEURL", "https://www.mra.mw/sandbox/programming/challenge/webservice");

require('Registry/registry.class.php');
$registry = new Registry();
// setup our core registry objects
$registry->createAndStoreObject('template', 'template');
$registry->createAndStoreObject('urlprocessor', 'url');
$registry->createAndStoreObject('Api', 'api');
$registry->createAndStoreObject('validator', 'validator');

$loggedin = "loggedout";

$registry->getObject('url')->getURLData();

$controller = $registry->getObject('url')->getURLBit(0);


$registry->getObject('template')->getPage()->addTag('siteurl', $registry->getSetting('siteurl'));

$registry->getObject('template')->buildFromTemplates('header.tpl.php', 'main.tpl.php', 'footer.tpl.php');
$registry->getObject('template')->addTemplateBit('topbar', 'topbar.tpl.php');

$controllers = array(
    "Taxpayers",
    "Auth"
    );

// Check if countroller exists
if (in_array($controller, $controllers)) {
    require_once( FRAMEWORK_PATH . 'Controllers/' . $controller . '/controller.php');
    $controllerInc = $controller . 'controller';
    $controller = new $controllerInc($registry, true);
} else {
    if (empty($controller)) {
        if (isset($_SESSION['loggedin'])) {
            $registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Home/loggedin.tpl.php', 'footer.tpl.php');
            $registry->getObject('template')->addTemplateBit('topbar', 'topbar.tpl.php');
            $url = BASEURL . "/Taxpayers/getAll";
            $result = $registry->getObject('api')->processRequest($url, null, 'GET');

            $taxpayers = $result;

            $cache = $registry->getObject('template')->cacheData($result);
            $registry->getObject('template')->getPage()->addTag('tplist', array('DATA', $cache));
        } else {
            $registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Home/loggedout.tpl.php', 'footer.tpl.php');
            $registry->getObject('template')->addTemplateBit('topbar', 'topbar.tpl.php');
        }
    }
}
/* * *****************************************************************************
 *
 * 
 */


/* * *********************************************************************
  Code tobe be executed either way
 * ********************************************************************* */
if(isset($_SESSION['loggedin']))
{
    $registry->getObject('template')->addTemplateBit('userbar', 'userbar_loggedin.tpl.php');
    $registry->getObject('template')->addTemplateBit('rightbit', 'rightbit.tpl.php');
    $registry->getObject('template')->getPage()->addTag('myemail', $_SESSION['myemail']);
}
else
{
    $registry->getObject('template')->addTemplateBit('userbar', 'userbar_loggedout.tpl.php');
}

    




/* * *****************************************************************************
 * ************          WORK ON BITS            ********************************
 * **************************************************************************** */
$urlBits = $registry->getObject('url')->getURLBits();
$bits = count($urlBits);
$redirectbits = "";
$i = 1;
while ($i <= $bits) {

    $redirectbits = $redirectbits . ".";
    $i++;
}
$redirectbits = $redirectbits . "/";


$registry->getObject('template')->addTemplateBit('footer', 'blank.tpl.php');
$registry->getObject('template')->addTemplateBit('scripts', 'scripts.tpl.php');
$registry->getObject('template')->getPage()->addTag('bits', $redirectbits);
$registry->getObject('template')->getPage()->addTag('loggedin', $loggedin);

$registry->getObject('template')->parseOutput();
print $registry->getObject('template')->getPage()->getContentToPrint();
?>