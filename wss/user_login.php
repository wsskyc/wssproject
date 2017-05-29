<?php require_once('config/tank_config.php'); ?>
<?php require "multilingual/language_$language".".php"; ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=$_POST['textfield2'];
  $tk_password = md5(crypt($password,substr($password,0,2)));
  $MM_fldUserAuthorization = "tk_user_status";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "user_error2.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_tankdb, $tankdb);
  	
  $LoginRS__query=sprintf("SELECT tk_user_login, tk_user_pass, tk_display_name, uid, tk_user_status, tk_user_rank, tk_user_message FROM tk_user WHERE binary tk_user_login=%s AND (tk_user_pass=%s OR tk_user_pass=%s)",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($tk_password, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  
 
  if ($loginFoundUser) {
  
	

    $loginStrGroup  = mysql_result($LoginRS,0,'tk_user_status');
	$loginStrDisplayname  = mysql_result($LoginRS,0,'tk_display_name');
	$loginStrpid  = mysql_result($LoginRS,0,'uid');
	$loginStrrank  = mysql_result($LoginRS,0,'tk_user_rank');
	$loginStrlogin  = mysql_result($LoginRS,0,'tk_user_login');
	$loginStrmsg  = mysql_result($LoginRS,0,'tk_user_message');

	//check_message( $loginStrpid );
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_Displayname'] = $loginStrDisplayname;	
	$_SESSION['MM_uid'] = $loginStrpid;	
	$_SESSION['MM_rank'] = $loginStrrank;	
	$_SESSION['MM_msg'] = $loginStrmsg;	
	
   //判断是否是老用户
  if ($loginStrGroup == $multilingual_dd_role_admin) {
  $userrank = "5";
  } else if ($loginStrGroup == $multilingual_dd_role_general){
  $userrank = "3";
  } else if ($loginStrGroup == $multilingual_dd_role_disabled){
  $userrank = "0";
  }
   
  if ($loginStrrank == null) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_user_rank=%s WHERE tk_user_login=%s", 
                       GetSQLValueString($userrank, "text"),                      
                       GetSQLValueString($loginStrlogin, "text"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
  $_SESSION['MM_rank'] = $userrank;
  }

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_userlogin_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php require('head_sub.php'); ?>
<br />
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65%" align="right">
<table width="70%" border="0" cellspacing="0" cellpadding="0" height="450px;" style="border-right: 1px solid #ccc">
    <tr>
      <td >
      <div class="ping_logo"></div>
      </td>
    </tr>
    <tr>
      
      <td ><form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
        <div class="login_table">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="2" align="left"><label>
                <?php echo $multilingual_userlogin_username; ?>
                <br />
                <input type="text" name="textfield" id="textfield" class="login_input" />
              </label></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" align="left"><label>
                <?php echo $multilingual_userlogin_password; ?>
                <br />
                <input type="password" name="textfield2" id="textfield2"  class="login_input" />
              </label></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td align="left"><label>
                <input type="submit" name="button" id="button" value="<?php echo $multilingual_userlogin_login; ?>"  />
                </label></td>
              <td align="right"><?php echo $multilingual_global_version; ?>: V<?php echo $version; ?></td>
            </tr>
          </table>
          
      </div></form></td>
    </tr>
  </table>
</td>
<td width="35%" align="left">
<table width="70%" >
<tr>
  <td width="8%" align="center">&nbsp;</td>
<td width="92%" >
<img src="skin/themes/base/images/getqrcode.jpg" width="130" height="130" /></td>
</tr>
<tr>
  <td>&nbsp;</td>
<td class="glink" style="line-height:150%;">
<?php echo $multilingual_getqrcode; ?></td>
</tr>
</table>
</td>
</tr>
</table>
<p>&nbsp;</p>
<iframe id="frame_content" name="main_frame" frameborder="0" height="1px" width="1px" src="http://www.wssys.net/analytics<?php if ($language == "en") { echo "_en";}?>.html" scrolling="no"></iframe>
<?php require('foot.php'); ?>
</body>
</html>