<?PHP
	require_once("./include/membersite_config.php");
	require_once("./include/gitclass_config.php");
	require_once("./php-github-api/vendor/autoload.php");
	
	echo "Starting...";
	if(!$fgmembersite->CheckLogin())
	{
		$fgmembersite->RedirectToURL("./login.php");
		exit;
	}
	if(!$_COOKIE){
		echo "NO COOKIE...";
	    setcookie("gh_authenticated","no",time()+60*60*24*30);
        setcookie("gh_username","-",time()+60*60*24*30);
        setcookie("gh_password","-",time()+60*60*24*30);
		$fgmembersite->RedirectToURL("./project_dashboard.php");
	}
	elseif($_COOKIE["gh_authenticated"]=="yes"){
		echo "AUTHENTICATED...";
		$client = new Github\Client();
		$client->authenticate($_COOKIE["gh_username"],$_COOKIE["gh_password"], Github\Client::AUTH_HTTP_PASSWORD);
	}
	if(isset($_POST['github_login'])){
		echo "CLICKED THE GITHUB LOGIN...";
		$username = $_POST['github_username'];
		$password = $_POST['github_password'];
		$client = new Github\Client();
		$client->authenticate($username, $password, Github\Client::AUTH_HTTP_PASSWORD);
		try{
			$repositories = $client->api('current_user')->repositories();
			setcookie("gh_authenticated","yes",time()+60*60*24*30);
			setcookie("gh_username",$username,time()+60*60*24*30);
			setcookie("gh_password",$password,time()+60*60*24*30);
			$fgmembersite->RedirectToURL("./project_dashboard.php");
		}
		catch (Exception $e){
			setcookie("gh_authenticated","failed",time()+600*60*24*30);
			setcookie("gh_username","-",time()+60*60*24*30);
			setcookie("gh_password","-",time()+60*60*24*30);
			$fgmembersite->RedirectToURL("./project_dashboard.php");
		}
	}
	echo "ENDING...";

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>EasyDoc</title>

	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>
	<script src="js/hideshow.js" type="text/javascript"></script>
	<script src="js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery.equalHeight.js"></script>
        <script type="text/javascript">
            $(function(){
                $('.column').equalHeight();
            });
        </script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			$("#github_success").hide();

							<?php
								if ($_COOKIE["gh_authenticated"]=="yes"){ 
									echo "$(\"#github_login_module\").hide();";
									echo "$(\"#github_success\").show();";
								}
							?>
			
			//if ($.cookie("gh_authenticated") == "yes"){ //NEED JQUERY COOKIE PLUGIN
			//if (<?php echo $_COOKIE["gh_authenticated"]; ?>.match(/^yes$/)){
			//	$("#github_login_module").hide();
			//	$("#github_success").show();
			//}
			
			$("a#gh_logout").click(function(event){  
				alert("Thanks for visiting!");
				<?php
					setcookie("gh_authenticated","no");
					setcookie("gh_username","-");
					setcookie("gh_password","-");
					//$fgmembersite->RedirectToURL("./project_dashboard.php");
				?>
			});
		 });	
	</script>

</head>


<body>
<?php print_r($_COOKIE); ?>
	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.php">Easy Doc</a></h1>
			<h2 class="section_title">Project Dashboard</h2>
			<div class="btn_view_site">
				<a href="http://www.boozallen.com">BAH Home</a>
			</div>
		</hgroup>
	</header> <!-- end of header bar -->

	<section id="secondary_bar">
		<div class="user">
			<p><?= $fgmembersite->UserFullName() ?></p>
			<a class="logout_user" href="logout.php" title="Logout">Logout</a>
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="index.php">Projects</a></article>
		</div>
	</section><!-- end of secondary bar -->

	<aside id="sidebar" class="column">
		<form class="quick_search">
			<input type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
                <hr/>
                <h3>GitHub</h3>
                <ul class="toggle">
                        <li class="icn_settings"><a href="#">Configure GitHub Connectivity</a></li>
                        <li class="icn_jump_back"><a href="#">GitHub Homepage</a></li>
		<h3>Projects</h3>
		<ul class="toggle">
                    <li class="icn_new_article"><a href="#">Add a GitHub Project
                    <li class="icn_categories"><a href="repo_manager.php">View EasyDoc Projects</a></li>
		</ul>
		<h3>Anything Else</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="#">Blah</a></li>
			<li class="icn_view_users"><a href="#">View Users</a></li>
			<li class="icn_profile"><a href="#">Your Profile</a></li>
		</ul>

		<footer>
			<hr />
			<p><strong>Copyright &copy; Booz Allen Hamilton 2012</strong></p>
			<p>Team Unbillable Represent</a></p>
		</footer>
	</aside><!-- end of sidebar -->

	<section id="main" class="column">

                <article id="github_login_module" class="module width_half">
                    <header><h3>GitHub Authentication</h3></header>
                    <div class="module_content">
                        <p>Enter your GitHub Credentials to connect with your repositories:</p>
						<img width="180px" src="./images/github-logo.png">
						<br /><br />

						<form action="project_dashboard.php" method="post">
							<?php
								if ($_COOKIE["gh_authenticated"]=="failed"){ 
									echo "<div style=\"color:red;\">Invalid Github Username/Password</div>";
								}
							?>
							<div>Username:<input style="margin-left: 10px;" type="text" name="github_username"></div>
							<div>Password:<input style="margin-left: 11px;" type="password" name="github_password"></div>
							<input type='hidden' name='github_login' />
							<br />
							<input type="submit" value="Connect to GitHub" name='github_login'>
						</form>
                    </div>
                </article>
				
                <article id="github_success" class="module width_half">
                    <header><h3>GitHub Authentication</h3></header>
                    <div class="module_content">
						<img width="180px" src="./images/github-logo.png">
						<br />
                        <p>You are successfully logged into Github as <b>
						<?php echo $_COOKIE["gh_username"];?></b> <a style="float: right;" id="gh_logout" href="#">Logout</a></p>
						
                    </div>
                </article>

            <article class="module width_half">
		    <header><h3>GitHub Projects</h3></header>
                    <div class="module_content">

                    	<?php
								$repositories = $client->api('current_user')->repositories();
								echo '<table class="table table-bordered">';
								foreach($repositories as $repo) {
									echo '<tr>';
									echo '<td>'.$repo['name'].'</td>';
									echo '<td><form class="form-inline" action="/repo_manager.php" method="post"/><input type="hidden" name="submitted" id="submitted" value="1"/><input type="hidden" name="repo_url" id="repo_url" value="'.$repo['html_url'].'"/><button type="submit" class="btn">Add</button></form></td>';
      								echo '</tr>';
								}
								
								echo '</table>';
                    	?>
                    </div>
            </article><!-- end of github projects article -->

		<article class="module width_half">
                    <header><h3>Tracked EasyDoc Projects</h3></header>
                    <div class="module_content">
    					<?php $repos = $fggitclass->getRepos();

     					echo '<table class="table table-bordered">';
     					foreach ($repos as $repo) {
      						echo '<tr>';
							echo '<td>'.$repo['id_repo'].'</td>';
							echo '<td>'.$repo['name'].'</td>';
      						echo '</tr>';
     					}
     
    	 				echo '</table>';
    
    					?>
                    </div>
                </article><!-- end of messages article -->

		<div class="clear"></div>

		<article class="module width_full">
			<header><h3>Track New Project</h3></header>
				<div class="module_content">

                                                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!--     to make two field float next to one another, adjust values accordingly -->
                                                        <label>Title</label>
                                                        <input type="text" style="width:92%;">
                                                </fieldset>

                                                <fieldset style="width:48%; float:left;"> <!-- to make two field     float next to one another, adjust values accordingly -->
                                                        <label>Client</label>
                                                        <input type="text" style="width:92%;">
                                                </fieldset>
                                                <div class="clear"></div>


						<fieldset>
							<label>Executive Summary</label>
							<textarea rows="12"></textarea>
						</fieldset>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" value="Publish" class="alt_btn">
					<input type="submit" value="Reset">
				</div>
			</footer>
		</article><!-- end of post new article -->

                <h4 class="alert_info">This could be an informative message.</h4>
                <h4 class="alert_warning">A Warning Alert</h4>
                <h4 class="alert_error">An Error Message</h4>
                <h4 class="alert_success">A Success Message</h4>

		<div class="spacer"></div>
	</section>


</body>

</html>
