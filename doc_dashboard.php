<?PHP
require_once("./include/membersite_config.php");
require_once("./include/gitclass_config.php");


if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("./login.php");
    exit;
}

$repo = $fggitclass->getRepo($_GET['proj_name']);


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
			<article class="breadcrumbs"><a href="index.php">Projects</a> <div class="breadcrumb_divider"></div> <a class="current"><?php echo $_GET['proj_name']; ?></a></article>
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
			<li class="icn_add_user"><a href="./project_dashboard.php">Track Project Branch</a></li>
			<li class="icn_view_users"><a href="./project_dashboard.php">Manager Overview</a></li>
			<li class="icn_profile"><a href="./project_dashboard.php">Export Docs to PDF</a></li>
		</ul>

		<footer>
			<hr />
			<p><strong>Copyright &copy; Booz Allen Hamilton 2012</strong></p>
			<p>Team Unbillable Represent</a></p>
		</footer>
	</aside><!-- end of sidebar -->

	<section id="main" class="column">

                <article class="module width_half">
                    <header><h3>Project Information</h3></header>
                    <div class="module_content">
						<table>
							<tr>
								<td><b>Name:</b></td>
								<td style="padding-left:30px;"><?php echo $_GET['proj_name']; ?></td>
							</tr>
							<tr>
								<td><b>GitHub Name:</b></td>
								<td style="padding-left:30px;"><?php echo $repo[0]['proj_title']; ?></td>
							</tr>
							<tr>
								<td><b>Client:</b></td>
								<td style="padding-left:30px;"><?php echo $repo[0]['client']; ?></td>
							</tr>
							<tr>
								<td><b>Project Summary:</b></td>
								<td style="padding-left:30px;"><?php echo $repo[0]['exec_summ']; ?></td>
							</tr>
						</table>
                    </div>
                </article>
	
                <article class="module width_half">
                    <header><h3>Documentation Status</h3></header>
                    <div class="module_content">
                        <?php
                            if(isset($_GET['proj_name'])){
                                system('sudo rm -r /var/server_files/tracked_files/*');
                                system('sudo rm -r /var/www/tracked/*');
                                system('sudo mkdir /var/server_files/tracked_files/'.$_GET['proj_name'].'-html');
                                system('sudo mkdir /var/server_files/tracked_files/'.$_GET['proj_name'].'-data');
                                system('sudo perl /var/www/NaturalDocs-1.52/NaturalDocs -i /var/server_files/tracked_projects/'.$_GET['proj_name'].' -o FramedHTML /var/server_files/tracked_files/'.$_GET['proj_name'].'-html -p /var/server_files/tracked_files/'.$_GET['proj_name'].'-data > /dev/null');
                                system('sudo cp -R /var/server_files/tracked_files/'.$_GET['proj_name'].'-html /var/www/tracked');
								$path = '/var/server_files/tracked_files/'.$_GET['proj_name'].'-html/files/';
								$is_bad = false;
								$it = new RecursiveDirectoryIterator($path);
								foreach(new RecursiveIteratorIterator($it) as $file)
								{
									$file_contents = file_get_contents($file);
									$pos = strpos($file_contents, "DOCUMENTATION MISSING");
									if ($pos != false){
										$is_bad = true;
										break;
									}
								}

                                
                                if($is_bad == true){
                                	echo '<h4 class="alert_error">Documentation Missing.  Check Below for Details.</h4>';
                                }
                                else{
                                    echo '<h4 class="alert_success">All Code is Documented.  See Resulting Documentation Below.</h4>';
                                }
                            }
                        ?>
                    </div>
                </article>
				
				<div class="clear"></div>


		<article class="module width_full">
			<header><h3>Project Documentation</h3></header>
				<div class="module_content">
                    <?php
                    if(isset($_GET['proj_name'])){
                        //if ((!is_dir('/var/server_files/tracked_files/'.$_GET['proj_name'].'-html') or !is_dir('/var/server_files/tracked_files/'.$_GET['proj_name'].'-data'))){
                            /*mkdir('/var/server_files/tracked_files/'.$_GET['proj_name'].'-html', 0777);
                            mkdir('/var/server_files/tracked_files/'.$_GET['proj_name'].'-data', 0777);
                            system('sudo perl /var/www/NaturalDocs-1.52/NaturalDocs -i /var/server_files/tracked_projects/'.$_GET['proj_name'].' -o FramedHTML /var/server_files/tracked_files/'.$_GET['proj_name'].'-html -p /var/server_files/tracked_files/'.$_GET['proj_name'].'-data > /dev/null');
                        
                        system('sudo cp -R /var/server_files/tracked_files/'.$_GET['proj_name'].'-html /var/www/tracked');
                        */
                        echo "<iframe src='tracked/".$_GET['proj_name']."-html/index.html' style='width:100%;height:100%;''>";
                        echo "</iframe>";
                    }
                    
                    ?>
				</div>
			<footer>
			</footer>
		</article><!-- end of post new article -->

		<div class="spacer"></div>
	</section>


</body>

</html>
