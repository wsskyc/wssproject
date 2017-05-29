<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$logdate = $_GET['date'];
$taskid = $_GET['taskid'];

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


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["log_delete"])) && ($_POST["log_delete"] == "form1")) {
  $deleteSQL = sprintf("DELETE FROM tk_task_byday WHERE csa_tb_year= %s AND csa_tb_backup1= %s",    
                       GetSQLValueString($logdate, "text"),  
                       GetSQLValueString($taskid, "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());

  $deleteGoTo = "log_finish.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<title>log</title>
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

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table class="dialog_main">
<tr valign="baseline">
      <td nowrap="nowrap" valign="top" height="410px">
	  <?php
$logyear = str_split($logdate,4);
$logmonth = str_split($logyear[1],2);
$showdate = $logyear[0]."-".$logmonth[0]."-".$logmonth[1];
?>
<?php echo $multilingual_tasklog_delmsg1; ?><?php echo $showdate; ?><?php echo $multilingual_tasklog_delmsg2; ?>
	  </td>
    </tr>
 <tr valign="baseline">
      <td align="right" nowrap="nowrap">
	  <span class="dialog_submit">
	  <input type="submit" value="<?php echo $multilingual_global_action_ok; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  />
     <input type="button" id="btn12" onclick="MM_goToURL('self','<?php echo "log_view.php?date=".$logdate."&taskid=".$taskid; ?>');return document.MM_returnValue" value="<?php echo $multilingual_global_action_cancel; ?>"/>
	  </span>	  </td>
    </tr>
</table>
  <input type="hidden" name="log_delete" value="form1" />
</form>
</body>
</html>