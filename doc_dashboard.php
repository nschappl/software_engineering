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
			<h2 class="section_title">Documentation</h2>
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
			<article class="breadcrumbs"><a href="index.html">Projects</a> <div class="breadcrumb_divider"></div> <a class="current">Project_Name</a></article>
		</div>
	</section><!-- end of secondary bar -->

	<aside id="sidebar" class="column">
		<form class="quick_search">
			<input type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
                <hr/>
                <h3>GitHub</h3>
                <ul class="toggle">
                        <li class="icn_settings"><a href="./project_dashboard.php">Configure GitHub Connectivity</a></li>
                        <li class="icn_jump_back"><a href="http://www.github.com">GitHub Homepage</a></li>
		<h3>Projects</h3>
		<ul class="toggle">
                    <li class="icn_new_article"><a href="./project_dashboard.php">Add a GitHub Project
                    <li class="icn_categories"><a href="./project_dashboard.php">View EasyDoc Projects</a></li>
		</ul>
		<h3>Future Capabilities</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="#">Action 1</a></li>
			<li class="icn_view_users"><a href="#">Action 2</a></li>
			<li class="icn_profile"><a href="#">Action 3</a></li>
		</ul>

		<footer>
			<hr />
			<p><strong>Copyright &copy; Booz Allen Hamilton 2012</strong></p>
			<p>Team Unbillable Represent</a></p>
		</footer>
	</aside><!-- end of sidebar -->

	<section id="main" class="column">

                <article class="module width_full">
                    <header><h3>Documentation Status</h3></header>
                    <div class="module_content">
                        <h4 class="alert_info">This could be an informative message.</h4>
                        <h4 class="alert_warning">A Warning Alert</h4>
                        <h4 class="alert_error">An Error Message</h4>
                        <h4 class="alert_success">A Success Message</h4>
                    </div>
                </article>

		<article class="module width_full">
			<header><h3>Project Documentation</h3></header>
				<div class="module_content">
                                    <p>This is where all of the Natural Doc Documentation will go <br /><br />Boom bitches <br /><br /></p>
				</div>
			<footer>
			</footer>
		</article><!-- end of post new article -->

		<div class="spacer"></div>
	</section>


</body>

</html>
