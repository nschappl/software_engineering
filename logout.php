<?PHP
require_once("./include/membersite_config.php");

    $fgmembersite->LogOut();

    $fgmembersite->RedirectToURL("login.php");
    exit;
?>

