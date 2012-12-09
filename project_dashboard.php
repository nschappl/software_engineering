<?PHP
	require_once("./include/membersite_config.php");
	require_once("./include/gitclass_config.php");
	require_once("./php-github-api/vendor/autoload.php");
	
	if(!$fgmembersite->CheckLogin())
	{
		$fgmembersite->RedirectToURL("./login.php");
		exit;
	}



  require_once("./include/gitclass_config.php");
  
  	if(isset($_POST['submitted'])) {
		$fggitclass->addRepo();
    }



	if(isset($_GET['code'])){


		$data = 'client_id=' . 'd12c2803a9453ba44900' . '&' .
				'client_secret=' . '76a1c2f9c3d9229af028ee6b890e1c21de8cb926' . '&' .
				'code=' . urlencode($_GET['code']);

		$ch = curl_init('https://github.com/login/oauth/access_token');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);

		preg_match('/access_token=([0-9a-f]+)/', $response, $out);

		curl_close($ch);
	
		if($out[1])
		{
			$fgmembersite->insertToken($out[1]);
		}

		

	}
	$token = $fgmembersite->getusertoken();

	if($token != NULL)
	{
		$client = new Github\Client();
		$client->authenticate($token, $password=NULL, Github\Client::AUTH_HTTP_TOKEN);
		$general_info = $client->api('current_user')->show();

	}

	



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
			$("#github_login_module").hide();
			$("#github_success").hide();
			$("#track_project").hide();
			
			<?php
				$token = $fgmembersite->getusertoken();

				if ($token==NULL){ 
					echo "$(\"#github_login_module\").show();";
				}
				else {
					echo "$(\"#github_success\").show();";
				}
			?>
			
			$("#gh_logout").click(function() {
				window.location.href = "./disassociate_github.php";
			});
			
			$(".add_button").click(function() {
				$("#track_project").slideDown();
			});
		 });	
	</script>
	
	<style>
		#track_project
		{			
			-webkit-box-shadow:0 0 30px #3a87ad; 
			-moz-box-shadow: 0 0 30px #3a87ad; 
			box-shadow:0 0 30px #3a87ad;
		}
	</style>

</head>


<body>
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
						<img width="180px" src="./images/github-logo.png">
						<br />
						<h4 class="alert_error">You are currently not connected to GitHub.  Connect below!</h4>
						<br />
						<form action="https://github.com/login/oauth/authorize" method="GET">
							<input type="hidden" name="client_id" value="d12c2803a9453ba44900" >
							<input type="hidden" name="redirect_uri" value="http://127.0.0.1:8080/project_dashboard.php">
							<input type="hidden" name="state" value="hollaatyourboy">
							<input type="hidden" name="scope" value="repo, user">
							<input type="submit" value="Connect to GitHub" >
						</form>

                    </div>
                </article> 


                <article id="github_success" class="module width_half">
                    <header><h3>GitHub Authentication</h3></header>
                    <div class="module_content">
						<img width="180px" src="./images/github-logo.png">
						<br />
						<h4 class="alert_success">You are successfully logged into GitHub as <?php echo $general_info['login']; ?> <a style="float: right; color: #0099FF; cursor: pointer; margin-right:10px; font-weight: 400;" id="gh_logout">Logout</a></h4>
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
									echo '<td><button class="add_button">Add</button></td>';
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
							echo '<td>'.$repo['url'].'</td>';
							echo '</tr>';
						}
	 
						echo '</table>';
	
						?>
					</div>
			</article><!-- end of messages article -->

		<div class="clear"></div>

		<article id="track_project" class="module width_full">
			<header><h3>Track New Project</h3></header>
				<div class="module_content">
					<form action="" method="post">
						<input type="hidden" name="submitted" id="submitted" value="1"/>
						<input type="hidden" name="repo_url" id="repo_url" value='<?php echo $repo['html_url']; ?>' />
							<fieldset style="width:48%; float:left; margin-right: 3%;"> <!--     to make two field float next to one another, adjust values accordingly -->
									<label>Title</label>
									<input type="text" name="title" style="width:92%;">
							</fieldset>
	
							<fieldset style="width:48%; float:left;"> <!-- to make two field     float next to one another, adjust values accordingly -->
									<label>Client</label>
									<input type="text" name="client" style="width:92%;">
							</fieldset>
							<div class="clear">		
	
							<fieldset>
								<label>Project Summary</label>
								<textarea name="summary" rows="12"></textarea>
							</fieldset>
				</div>
						<footer>
							<div class="submit_link">
								<input type="submit" value="Add to EasyDoc" class="alt_btn">
							</div>
						</footer>
					</form>

		</article><!-- end of post new article -->

		<div class="spacer"></div>
	</section>


</body>

</html>
