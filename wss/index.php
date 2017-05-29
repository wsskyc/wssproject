<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
$url_this = $_SERVER["QUERY_STRING"] ;

$current_url = current(explode("&sort",$url_this));

$pagetabs = "mtask";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$currentPage = $_SERVER["PHP_SELF"];

$taskpage=2;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_task   
							FROM tk_task 	
							inner join tk_status on tk_task.csa_remark2=tk_status.id 							
							WHERE csa_from_user = %s AND task_status LIKE %s", 
								GetSQLValueString($_SESSION['MM_uid'], "int"),
								GetSQLValueString("%" . $multilingual_dd_status_exam . "%", "text")
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$exam_totaltask=$row_Recordset_sumtotal['count_task'];

?>

<?php require('head.php'); ?>


<div class="subnav">
<div class="float_left" style="width:85%">

<ul class="nav nav-tabs">
  <li class="
	  <?php if($pagetabs == "mtask") {
	  echo "active";} ?>
	  ">
    <a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag="><?php echo $multilingual_user_mytask;?></a>
  </li>
  <li class="
	  <?php if($pagetabs == "ftask") {
	  echo "active";} ?>
	  " ><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=ftask"><?php echo $multilingual_default_fromme;?></a></li>
  <li class="
	  <?php if($pagetabs == "ctask") {
	  echo "active";} ?>
	  " ><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=ctask"><?php echo $multilingual_default_createme;?></a></li>
  
  <?php if($exam_totaltask > 0) { ?>
  <li class="
	  <?php if($pagetabs == "etask") {
	  echo "active";} ?>
	  " ><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_prt=&select_temp=&select_exam=<?php echo $multilingual_dd_status_exam; ?>&inputtitle=&select1=-1&select2=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=etask"><?php echo $multilingual_exam_wait."(".$exam_totaltask.")"; ?></a></li>
  <?php } ?>

  <li class="
	  <?php if($pagetabs == "alltask") {
	  echo "active";} ?>
	  "><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=&pagetab=alltask"><?php echo $multilingual_default_alltask;?></a></li>
</ul>
</div>
<?php if($_SESSION['MM_rank'] > "2" ) { ?>
<div class="float_right newtaskdiv">
        <input name="button2" type="submit" id="button2" onclick="addtask();" value="<?php echo $multilingual_default_newtask; ?>"  class="button"  >
</div>
<?php }  ?> 

<div class="clearboth"></div>
</div>

<div class="pagemargin">
<?php require('control_task.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>