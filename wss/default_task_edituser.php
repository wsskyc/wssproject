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

$to_user = "-1";
if (isset($_POST['csa_to_user'])) {
  $to_user= $_POST['csa_to_user'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_touser = "SELECT * FROM tk_user WHERE uid = '$to_user'";
$touser = mysql_query($query_touser, $tankdb) or die(mysql_error());
$row_touser = mysql_fetch_assoc($touser);
$totalRows_touser = mysql_num_rows($touser);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_user1.tk_user_email as tk_user_email1, 
tk_user1.tk_display_name as tk_display_name1 
FROM tk_task 
inner join tk_user as tk_user1 on tk_task.csa_from_user=tk_user1.uid 
WHERE TID = %s", GetSQLValueString($taskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

$mailto = $row_touser['tk_user_email']; 
$mailto2 = $row_Recordset_task['tk_user_email1']; 
$title = $row_Recordset_task['csa_text'];
$user = $row_Recordset_task['tk_display_name1'];  


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

  if ((isset($_POST["task_update"])) && ($_POST["task_update"] == "form1")) {
  $updatetask = sprintf("UPDATE tk_task SET csa_to_user=%s, csa_last_user=%s WHERE TID=%s", 
                       GetSQLValueString($_POST['csa_to_user'], "text"),
                       GetSQLValueString($nowuser, "text"),                      
                       GetSQLValueString($taskid, "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updatetask, $tankdb) or die(mysql_error());
 
  $newID = $taskid;
  $to_user_display = $row_touser['tk_display_name']; 
  $newName = $_SESSION['MM_uid'];
  $action = $multilingual_log_edittaskuser."&nbsp;".$to_user_display;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, ''  )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($newID, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

 
    $updateGoTo = "log_finish.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
 

$msg_to = $to_user; 
$msg_from = $nowuser;
$msg_type = "edituser";
$msg_id = $taskid;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );


  
  header(sprintf("Location: %s", $updateGoTo));
  }

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT * FROM tk_task WHERE TID = %s", GetSQLValueString($taskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_user = "SELECT * FROM tk_user WHERE tk_user_rank NOT LIKE '0' ORDER BY tk_display_name ASC";
$Recordset_user = mysql_query($query_Recordset_user, $tankdb) or die(mysql_error());
$row_Recordset_user = mysql_fetch_assoc($Recordset_user);
$totalRows_Recordset_user = mysql_num_rows($Recordset_user);
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
      <td height="37px" valign="top" nowrap="nowrap"><?php echo $multilingual_tasklog_changeto; ?>:
	  
	    <select id="select4" name="csa_to_user">
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_user['uid']?>"<?php if (!(strcmp($row_Recordset_user['uid'], $row_Recordset_task['csa_to_user']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_user['tk_display_name']?></option>
          <?php
} while ($row_Recordset_user = mysql_fetch_assoc($Recordset_user));
  $rows = mysql_num_rows($Recordset_user);
  if($rows > 0) {
      mysql_data_seek($Recordset_user, 0);
	  $row_Recordset_user = mysql_fetch_assoc($Recordset_user);
  }
?>
        </select>	  </td>
    </tr>
   
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">
	    <span class="dialog_submit">
	  <input type="submit" value="<?php echo $multilingual_global_action_ok; ?>" 
	  <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?>
	  />
      <input id="btn1" type="button" value="<?php echo $multilingual_global_action_cancel; ?>" onclick="over()"/>
	    </span>	  </td>
    </tr>
  </table>
  <input type="hidden" name="task_update" value="form1" />
</form>

</body>
</html>