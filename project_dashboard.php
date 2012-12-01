<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("./login.php");
    exit;
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
			<article class="breadcrumbs"><a href="index.html">Projects</a></article>
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

                <article class="module width_full">
                    <header><h3>GitHub Integration</h3></header>
                    <div class="module_content">
                        <p>put in the github api authentication stuff</p>
                    </div>
                </article>

                <article class="module width_half">
		    <header><h3>GitHub Projects</h3></header>
                    <div class="module_content">
                        <p><br />show github projects<br />- ability to track these<br /><br /><br /></p>
                    </div>
                </article><!-- end of github projects article -->

		<article class="module width_half">
                    <header><h3>Tracked EasyDoc Projects</h3></header>
                    <div class="module_content">
                        <p><br />show tracked easydoc projects<br />- ability to untrack<br /><br /><br /></p>
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
