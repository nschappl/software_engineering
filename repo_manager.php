<?php
  require_once("./include/membersite_config.php");

  if(!$fgmembersite->CheckLogin())
  {
      $fgmembersite->RedirectToURL("login.php");
      exit;
  }
 
  require_once("./include/gitclass_config.php");
  
  if(isset($_POST['submitted'])) {

       if($fggitclass->addRepo())
       {
	  $fgmembersite->RedirectToURL("index.php");
       }
  
  }


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Repo Manager</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
   </head>
  <body>
  <h2>Project Manager</h2>
  <h3>Add a Repo</h3>
    <form class="form-inline" action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' />
      <input type='hidden' name='submitted' id='submitted' value='1'/>
      <input type="text" class="input-large" name='repo_url' id='repo_url' placeholder="HTTP Repo URL" />
      <button type="submit" class="btn">Add</button>
    </form>
    

    
    <?php $repos = $fggitclass->getRepos();
    
    echo     '<h3>Tracked Repos</h3>';
       
     echo '<table class="table table-bordered">';
     echo '<tr><td>Repo ID</td><td>Repo URL</td></tr>';
     foreach ($repos as $repo) {
      echo '<tr>';
	echo '<td>'.$repo['id_repo'].'</td>';
	echo '<td>'.$repo['name'].'</td>';
      echo '</tr>';
     }
     
     echo '</table>';
    
    ?>
    
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>