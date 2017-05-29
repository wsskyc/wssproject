<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$taskid = $_GET['taskid'];
$nowuser = $_SESSION['MM_uid'];


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT csa_to_user, csa_text, csa_remark2, csa_from_user  
FROM tk_task 
WHERE TID = %s", GetSQLValueString($taskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);

$mailto = $row_Recordset_task['csa_to_user']; 
$title = $row_Recordset_task['csa_text'];



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

  if ((isset($_POST["task_update"])) && ($_POST["task_update"] == "form1")) {
  $updatetask = sprintf("UPDATE tk_task SET csa_remark2=%s, csa_remark8=%s, csa_last_user=%s WHERE TID=%s", 
                       GetSQLValueString($_POST['csa_to_user'], "text"),
                       GetSQLValueString($_POST['examtext'], "text"),
                       GetSQLValueString($nowuser, "text"),                      
                       GetSQLValueString($taskid, "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updatetask, $tankdb) or die(mysql_error());
 
 
 $statusid = "-1";
if (isset($_POST['csa_to_user'])) {
  $statusid = $_POST['csa_to_user'];
}

 $examtext = "-1";
if (isset($_POST['examtext'])) {
  $examtext = $_POST['examtext'];
}

if ($examtext <> null){
$examtitle = $multilingual_log_exam1.$examtext;
}

mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus1 = sprintf("SELECT * FROM tk_status WHERE id = %s ", GetSQLValueString($statusid, "text"));
$tkstatus1 = mysql_query($query_tkstatus1, $tankdb) or die(mysql_error());
$row_tkstatus1 = mysql_fetch_assoc($tkstatus1);
$totalRows_tkstatus1 = mysql_num_rows($tkstatus1);
 
 
  $newID = $taskid;
  $logstatus = $row_tkstatus1['task_status'];
  $newName = $_SESSION['MM_uid'];
  $action = $multilingual_log_exam.$multilingual_log_exam2."&nbsp;".$logstatus.$examtitle;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, '')",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($newID, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

 
    $updateGoTo = "log_finish.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
$title = $row_Recordset_task['csa_text'];



$msg_to = $mailto; 
$msg_from = $nowuser;
$msg_type = "examtask";
$msg_id = $taskid;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );
  
  header(sprintf("Location: %s", $updateGoTo));
  }

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_user = "SELECT * FROM tk_status WHERE task_status_backup2 = '1' ORDER BY task_status_backup1 ASC";
$Recordset_user = mysql_query($query_Recordset_user, $tankdb) or die(mysql_error());
$row_Recordset_user = mysql_fetch_assoc($Recordset_user);
$totalRows_Recordset_user = mysql_num_rows($Recordset_user);

$restrictGoTo = "user_error3.php";
if (($_SESSION['MM_rank'] < "2" || $row_Recordset_task['csa_from_user'] <> $_SESSION['MM_uid'])&&$_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
	<title>change onwer</title>
	<script type="text/javascript">
var P = window.parent, D = P.loadinndlg();   
function closreload(url)
{
    if(!url)
	    P.reload();    
}
function over()
{
    P.cancel();
}

function submitform()
{
    document.form1.cont.value='<?php echo $multilingual_global_wait; ?>';
	document.form1.cont.disabled=true;
	document.getElementById("btn5").click();
}
	</script>
</head>

<body>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="dialog_main">
    <tr valign="baseline">
      <td valign="top" nowrap="nowrap" width="15px">&nbsp;</td>
      <td valign="top" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td valign="top" nowrap="nowrap">&nbsp;</td>
      <td height="37px" valign="top" nowrap="nowrap"><?php echo $multilingual_exam_select; ?>:
	  
	    <select id="select4" name="csa_to_user">
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_user['id']?>"<?php if (!(strcmp($row_Recordset_user['id'], $row_Recordset_task['csa_remark2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_user['task_status']?></option>
          <?php
} while ($row_Recordset_user = mysql_fetch_assoc($Recordset_user));
  $rows = mysql_num_rows($Recordset_user);
  if($rows > 0) {
      mysql_data_seek($Recordset_user, 0);
	  $row_Recordset_user = mysql_fetch_assoc($Recordset_user);
  }
?>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td valign="top">&nbsp;</td>
      <td height="90px" valign="top" >
	  <?php echo $multilingual_exam_text; ?>:<br />
        <textarea name="examtext" id="examtext" class="exam_text"></textarea><br/>
		<span class="gray"><?php echo $multilingual_exam_tip2; ?></span>
      </td>
    </tr>
   
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">
	    <span class="dialog_submit">
	  <input name="cont" type="button" value="<?php echo $multilingual_global_action_ok; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  onClick="submitform()" 
	  />
	  
	  <input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_ok; ?>"  style="display:none" />
	  
      <input id="btn1" type="button" value="<?php echo $multilingual_global_action_cancel; ?>" onclick="over()"/>
	    </span>	  </td>
    </tr>
  </table>
  <input type="hidden" name="task_update" value="form1" />
</form>
</body>
</html>