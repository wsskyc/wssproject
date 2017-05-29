<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
$pagetabs = "allfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$currentPage = $_SERVER["PHP_SELF"];
?>
<?php require('head.php'); ?>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/jqueryd.js"></script>
<div class="subnav">
  <ul class="nav nav-tabs">
      <li class="
	  <?php if($pagetabs == "allfile") {
	  echo "active";} ?>
	  " ><a href="<?php echo $pagename; ?>?pagetab=allfile"><?php echo $multilingual_project_file_allfile;?></a></li>
  
  
      <li class="
	  <?php if($pagetabs == "mcfile") {
	  echo "active";} ?>
	  "><a href="<?php echo $pagename; ?>?pagetab=mcfile"><?php echo $multilingual_project_file_myfile;?> </a></li>

      <li class="
	  <?php if($pagetabs == "mefile") {
	  echo "active";} ?>
	  "><a href="<?php echo $pagename; ?>?pagetab=mefile"><?php echo $multilingual_project_file_myeditfile;?> </a></li>
	  
	  
	  
    </ul>


<div class="clearboth"></div>
</div>

<div class="pagemargin">

<?php require('control_file.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>