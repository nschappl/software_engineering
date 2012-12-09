<?php
    require_once("./include/membersite_config.php");
    require_once("./include/gitclass_config.php");
    require_once("./php-github-api/vendor/autoload.php");
    
    if(!$fgmembersite->CheckLogin())
    {
        $fgmembersite->RedirectToURL("./login.php");
        exit;
    }
    $fgmembersite->insertToken(NULL);
    $fgmembersite->RedirectToURL("./project_dashboard.php");

?>
