<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$password = "-1";
if (isset($_POST['tk_user_pass'])) {
  $password = $_POST['tk_user_pass'];
}

$tk_password = md5(crypt($password,substr($password,0,2)));

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_user_pass=%s WHERE uid=%s",
                       GetSQLValueString($tk_password, "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "success.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['UID'])) {
  $colname_Recordset1 = $_GET['UID'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_user WHERE uid = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$restrictGoTo = "user_error3.php";
if ($row_Recordset1['uid'] <> $_SESSION['MM_uid'] && $_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_user_edit_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
	{ name: 'tk_user_pass', mid: 'user_pass', type: 'limit', requir: true, min: 2, max: 8, warn: '<?php echo $multilingual_user_namequired8; ?>' },
	{ name: 'tk_user_pass', mid: 'user_pass2', requir: true, type: 'match', to: 'tk_user_pass2', warn: '<?php echo $multilingual_user_tip_match; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
}
</script>

</head>

<body>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100%"><table width="98%" border="0" cellspacing="0" cellpadding="8" align="right">
          
          <tr>
            <td width="8%" valign="top" class="input_td_padding"><?php echo $multilingual_user_newpassword; ?></td>
            <td width="92%" valign="top">
			<input type="password" name="tk_user_pass" id="tk_user_pass" value="" size="32"  class="input_user" /><span id="user_pass"></span>
        <br /><span class="gray"> <?php echo $multilingual_user_tip_password; ?></span></td>
          </tr>

		   <tr>
		     <td class="input_td_padding" valign="top"><?php echo $multilingual_user_newpassword2; ?></td>
		     <td><input type="password" name="tk_user_pass2" id="tk_user_pass2" value="" size="32" class="input_user" /><span id="user_pass2" ></span>
	  <br /><span class="gray"> <?php echo $multilingual_user_tip_password2; ?></span></td>
	      </tr>
		   <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      
    </tr>
    <tr>
      <td colspan="2" class="input_task_bottom_bg" height="50px"><span class="input_task_submit">
        <div class="float_right">
          <input type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
		<?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?>
		/>
		<input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="ID" value="<?php echo $row_Recordset1['uid']; ?>" />
        </div>
        </span>
       </td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>