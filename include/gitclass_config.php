<?php

require_once("./include/gitclass.php");

$fggitclass = new gitclass ();



//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fggitclass->InitDB(/*hostname*/'dev.localhost.com',
                      /*username*/'root',
                      /*password*/'root',
                      /*database name*/'easy_doc',
                      /*table name*/'repo_library');



?>