<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_prj   
							FROM tk_project 	
							WHERE project_to_user = %s", 
								GetSQLValueString($_SESSION['MM_uid'], "int")
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$my_totalprj=$row_Recordset_sumtotal['count_prj'];

if($my_totalprj > 0) {
$pagetabs = "mprj";
}else {
$pagetabs = "jprj";
}
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_default_title; ?></title>

<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/jqueryd.js"></script>
</head>
<body>
<?php require('head.php'); ?>

<div class="subnav">
  <ul class="nav nav-tabs">
<?php if($my_totalprj > 0) { ?>
	  <li class="
	  <?php if($pagetabs == "mprj") {
	  echo "active";} ?>
	  "><a href="<?php echo $pagename; ?>"><?php echo $multilingual_project_myprj;?> </a></li>
<?php } ?>	

	  <li class="
	  <?php if($pagetabs == "jprj") {
	  echo "active";} ?>
	  "><a href="<?php echo $pagename; ?>?pagetab=jprj"><?php echo $multilingual_project_jprj;?> </a></li>
  
	  <li class="
	  <?php if($pagetabs == "allprj") {
	  echo "active";} ?>
	  " ><a href="<?php echo $pagename; ?>?pagetab=allprj"><?php echo $multilingual_project_allprj;?></a></li>
    </ul>
<div class="clearboth"></div>
</div>

<div class="pagemargin">

<?php require('control_project.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>