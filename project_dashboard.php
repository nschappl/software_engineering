<?PHP
	require_once("./include/membersite_config.php");
	require_once("./include/gitclass_config.php");
	require_once("./php-github-api/vendor/autoload.php");
    
    
	if(!$fgmembersite->CheckLogin()){
		$fgmembersite->RedirectToURL("./login.php");
		exit();
	}
    require_once("./include/gitclass_config.php");

  	if(isset($_POST['submitted'])) {
		$fggitclass->addRepo();
    }

    if(isset($_POST['remove_sub']))
    {
    	$fggitclass->removeRepo();
    }

    if(isset($_GET['login']))
    {
    	$fggitclass->cleanRepos();
    	$fggitclass->cloneRepos();
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
	
		if($out[1]){
			$fgmembersite->insertToken($out[1]);
		}
	}

	$token = $fgmembersite->getusertoken();

	if($token != NULL){
		$client = new Github\Client();
		$client->authenticate($token, $password=NULL, Github\Client::AUTH_HTTP_TOKEN);
		$general_info = $client->api('current_user')->show();

		$fggitclass->cleanRepos();
    	$fggitclass->cloneRepos();
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
			$(".hidden").hide();
			$("#github_login_module").hide();
			$("#github_success").hide();
			$("#track_project").hide();

			$("#gh_logout").click(function() {
				window.location.href = "./disassociate_github.php";
			});
			
			$(".add_button").click(function() {
				$(".proj_name").removeClass("redish");
				$(this).parent().parent().addClass("redish");
				$("#track_project").hide();
				$("#title").val("");
				$("#client").val("");
				$("#summary").val("");
				$("#track_project").slideDown();
				
				var url = $(this).parent().next().text();
				$("#repo_archive_url").val(url);
				
				var name = $(this).parent().prev().text();
				$("#repo_name").val(name);
				
				var login = $(this).parent().next().next().text();
				$("#repo_login").val(login);
				
				var name = $(this).parent().prev().text();
				$("#title").val(name);
				
				$("#github_login_module").removeClass("glowme");
				$("#github_success").removeClass("glowme");
				$("#github_projects").removeClass("glowme");


			});
			
			$("#x").click(function() {
				$("#track_project").hide();
				$(".proj_name").removeClass("redish");
			});
			
			
			$("#link_1").click(function() {
				$("#github_projects").removeClass("glowme");
				$("#github_login_module").addClass("glowme");
				$("#github_success").addClass("glowme");
			});
			
			$(".link_2").click(function() {
				$("#github_login_module").removeClass("glowme");
				$("#github_success").removeClass("glowme");
				$("#github_projects").addClass("glowme");
			});
			
			$(".submit_form").click(function(){
				$(this).parent().submit();
			 });
			
			$("#gh_connect").click(function(){
				$("#gh_connect_form").submit();
			 });
			
			
			
			<?php
				$token = $fgmembersite->getusertoken();

				if ($token==NULL){ 
					echo "$(\"#github_login_module\").show();";
					echo "$(\"#github_projects\").hide();";
				}
				else {
					echo "$(\"#github_success\").show();";
					echo "$(\"#github_projects\").show();";
				}
            ?>
			
		 });	
	</script>
	
	<style>
		.glowme{			
			-webkit-box-shadow:0 0 30px #00ADEE; 
			-moz-box-shadow: 0 0 30px #00ADEE; 
			box-shadow:0 0 30px #00ADEE;
		}
		
		.proj_name{
			font-size: 18px;
		}
		
		#x {
			padding: 3px;
		}
		
		.redish{
			color: #00ADEE;
		}
		
		.button, .button span {
			display: inline-block;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
		}
		.button {
			white-space: nowrap;
			line-height:1em;
			position:relative;
			outline: none;
			overflow: visible; /* removes extra side padding in IE */
			cursor: pointer;
			border: 1px solid #999;/* IE */
			border: rgba(0, 0, 0, .2) 1px solid;/* Saf4+, Chrome, FF3.6 */
			border-bottom:rgba(0, 0, 0, .4) 1px solid;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);
			box-shadow: 0 1px 2px rgba(0,0,0,.2);
			background: -moz-linear-gradient(
				center top,
				rgba(255, 255, 255, .1) 0%,
				rgba(0, 0, 0, .1) 100%
			);/* FF3.6 */
			background: -webkit-gradient(
				linear,
				center bottom,
				center top,
				from(rgba(0, 0, 0, .1)),
				to(rgba(255, 255, 255, .1))
			);/* Saf4+, Chrome */
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorStr='#19FFFFFF', EndColorStr='#19000000'); /* IE6,IE7 */
			-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorStr='#19FFFFFF', EndColorStr='#19000000')"; /* IE8 */
			-moz-user-select: none;
			-webkit-user-select:none;
			-khtml-user-select: none;
			user-select: none;
			margin-bottom:10px;
		}
		.button.full, .button.full span {
			display: block;
		}
		.button:hover, .button.hover {
			background: -moz-linear-gradient(
				center top,
				rgba(255, 255, 255, .2) 0%,
				rgba(255, 255, 255, .1) 100%
			);/* FF3.6 */
			background: -webkit-gradient(
				linear,
				center bottom,
				center top,
				from(rgba(255, 255, 255, .1)),
				to(rgba(255, 255, 255, .2))
			);/* Saf4+, Chrome */
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorStr='#33FFFFFF', EndColorStr='#19FFFFFF'); /* IE6,IE7 */
			-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorStr='#33FFFFFF', EndColorStr='#19FFFFFF')"; /* IE8 */
		}
		.button:active, .button.active {
			top:1px;
		}
		.button span {
			position: relative;
			color:#fff;
			text-shadow:0 1px 1px rgba(0, 0, 0, 0.25);
			border-top: rgba(255, 255, 255, .2) 1px solid;
			padding:0.6em 1.3em;
			line-height:1em;
			text-decoration:none;
			text-align:center;
			white-space: nowrap;
		}		

		.button.small span {
			font-size:12px;
		}
		
		.button.blue {
			background-color: #00ADEE;
			width: 70px;
			text-align: center;
		}
		
		.button.bluer {
			background-color: #00ADEE;
			width: 130px;
			text-align: center;
		}
		
		.button.red {
			background-color: #e62727;
			width: 70px;
			text-align: center;
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
                        <li class="icn_settings"><a id="link_1">Configure GitHub Connectivity</a></li>
                        <li class="icn_jump_back"><a target="_blank" href="http://www.github.com">GitHub Homepage</a></li>
		<h3>Projects</h3>
		<ul class="toggle">
                    <li class="icn_new_article"><a class="link_2">Add a GitHub Project
                    <li class="icn_categories"><a class="link_2">View EasyDoc Projects</a></li>
		</ul>
		<h3>Future Capabilities</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="#">Action 1</a></li>
			<li class="icn_view_users"><a href="#">Action 2</a></li>
			<li class="icn_profile"><a href="#">Action 3</a></li>
		</ul>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
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
						<form id="gh_connect_form" action="https://github.com/login/oauth/authorize" method="GET">
							<input type="hidden" name="client_id" value="d12c2803a9453ba44900" >
							<input type="hidden" name="redirect_uri" value="http://127.0.0.1:8080/project_dashboard.php">
							<input type="hidden" name="state" value="hollaatyourboy">
							<input type="hidden" name="scope" value="repo, user">
							<div style="width: 130px; margin: 0 auto;">
								<a class="button small bluer" id="gh_connect" href="#"><span>Connect to GitHub</span></a>
							</div>
						</form>

                    </div>
                </article> 


                <article id="github_success" class="module width_half">
                    <header><h3>GitHub Authentication</h3></header>
                    <div class="module_content">
						<img width="180px" src="./images/github-logo.png"><img style="float: right; border:1px solid #000000" width="90px" src="<?php echo $general_info['avatar_url'];?>">
						<br /><br />
						<h4 class="alert_success">You are successfully logged into GitHub as <?php echo $general_info['login'];?>
							<table style="font-size: 14px; padding-left: 10px;">
								<tr>
									<td>Location:</td>
									<td><?php echo $general_info['location'];?></td>
								</tr>
								<tr>
									<td>Private Repos:</td>
									<td><?php echo $general_info['total_private_repos'];?></td>
								</tr>
								<tr>
									<td>Public Repos:</td>
									<td><?php echo $general_info['public_repos'];?></td>
								</tr>
							</table>
						</h4>
						<br />
							<div style="width: 70px; margin: 0 auto;">
								<a class="button small blue" id="gh_logout" href="#"><span>Logout</span></a>
							</div>

                    </div>
                </article>

            <article id="github_projects" class="module width_half">
		    <header><h3>GitHub Projects</h3></header>
                    <div class="module_content">

                    	<?php
                                
								$repositories = $client->api('current_user')->repositories();
								echo '<table class="table table-bordered">';
								foreach($repositories as $repo) {
									echo '<tr>';
									if (is_dir('/var/server_files/tracked_projects/'.$repo['name'])){
										echo 	'<td class="proj_name"><b><a style="color:red; text-decoration: underline; cursor:pointer;" href="./doc_dashboard.php?proj_name='.$repo['name'].'">'.$repo['name'].'</a></b></td>';
										echo 	'<td style="padding-left: 200px;">
													<form action="./project_dashboard.php" method="post">
														<input style="display: none;" type="hidden" name="repo_name" value="'.$repo['name'].'">
														<input style="display: none;" type="hidden" name="remove_sub" value="1">
														<a style="display: block;" class="submit_form button small red" href="#"><span>Remove</span></a>
													</form>
												</td>';
									}
									else{
										echo '<td class="proj_name"><b>'.$repo['name'].'</b></td>';
										echo '<td style="padding-left: 200px;"><a class="add_button button small blue" href="#"><span>Add</span></a></td>';
									}
									echo '<td class="hidden">'.$repo['archive_url'].'</td>';
									echo '<td class="hidden">'.$general_info['login'].'</td>';
      								echo '</tr>';
								}
								echo '</table>';
                    	?>
                    </div>
            </article><!-- end of github projects article -->


		<div class="clear"></div>

		<article id="track_project" class="module width_full glowme">
			<header><h3>Track New Project</h3><img id="x" src="./images/xout.png" style="float: right;" width="25px" /></header>
				<div class="module_content">
					<form action="./project_dashboard.php" method="post">
						<input type="hidden" name="submitted" id="submitted" value="1"/>
						<input type="hidden" id="repo_name" name="repo_name"  value='' />
						<input type="hidden" id="repo_archive_url" name="repo_archive_url"  value='' />
						<input type="hidden" id="repo_login" name="repo_login"  value='' />
							<fieldset style="width:48%; float:left; margin-right: 3%;"> <!--     to make two field float next to one another, adjust values accordingly -->
									<label>Title</label>
									<input id="title" type="text" name="title" style="width:92%;">
							</fieldset>
	
							<fieldset style="width:48%; float:left;"> <!-- to make two field     float next to one another, adjust values accordingly -->
									<label>Client</label>
									<input id="client" type="text" name="client" style="width:92%;">
							</fieldset>
							<div class="clear">		
	
							<fieldset>
								<label>Project Summary</label>
								<textarea id="summary" name="summary" rows="4"></textarea>
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
