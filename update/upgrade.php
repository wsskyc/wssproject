<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session_admin.php'); ?>
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
 
$update = 2;
if (isset($_POST['agreecheck'])) {
$update = $_POST['agreecheck'];
}

if($update == "start"){

set_time_limit(3600);

mysql_select_db($database_tankdb, $tankdb);

mysql_query("ALTER TABLE  `tk_user` ADD  `tk_user_token` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0' AFTER  `tk_user_pass`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` ADD  `tk_user_message` BIGINT( 20 ) NOT NULL DEFAULT  '0' AFTER  `tk_user_email`", $tankdb) or die(mysql_error());

mysql_query("
CREATE TABLE `tk_message` (
`meid` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tk_mess_touser` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0',
`tk_mess_fromuser` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0',
`tk_mess_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`tk_mess_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`tk_mess_status` TINYINT( 2 ) NOT NULL DEFAULT  '1',
`tk_mess_type` TINYINT( 2 ) NOT NULL DEFAULT  '0',
`tk_mess_time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = INNODB;
", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_message` ADD INDEX (  `tk_mess_touser` )", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_message` ADD INDEX (  `tk_mess_fromuser` )", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark8`  `csa_remark8` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL", $tankdb) or die(mysql_error());

$update_rs =1;

} else {
$update_rs =0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS1.3.2 升级说明</title>

<style type="text/css">
<!--
img {border: none;}
li {list-style: none;}
body,html{  font-family:Arial; width:100%;   margin: 0; padding: 0; min-width:960px; background:#E0E0E0; }
body {
	font-size: 12px; line-height:150%;
}
table {
	font-size: 12px;
}
p{ margin:5px;}
.main_body{ width:947px; margin:10px auto auto auto;}
.content_bg{ width:945px; border:1px solid #CCCCCC;  background:#FFF; }
.rowcon{ width:95%;  margin:auto; }
.rowcon td a{ color:#395a90;text-decoration:underline;  padding-right:10px; }
.rowcon td a:visited{ color:#395a90;text-decoration:underline; }
.rowcon td a:hover{ color:#395a90;text-decoration:none; }
.rowcon a{ color:#395a90;text-decoration:underline;  padding-right:10px; }
.rowcon a:visited{ color:#395a90;text-decoration:underline; }
.rowcon a:hover{ color:#395a90;text-decoration:none; }
.big_text{ font-size:36px; font-weight:bold; line-height:150%;}
.font_big18{ font-size:18px; font-weight:bold;}
.ping_logo{ background:url(wss/skin/themes/base/images/wsslogo.png) no-repeat; width:158px; height:158px; margin:20px auto;}
}
-->
</style>
<script>
var checkobj
function agreesubmit(el){
checkobj=el
if (document.all||document.getElementById){
for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
var tempobj=checkobj.form.elements[i]
if(tempobj.type.toLowerCase()=="submit")
tempobj.disabled=!checkobj.checked
}
}
}

function defaultagree(el){
if (!document.all && !document.getElementById){
if (window.checkobj && checkobj.checked)
return true
else{
alert("Please read/accept terms to submit form")
return false
}
}
}
</script>
</head>

<body>
<div class="main_body">
<div class="content_bg">
<div class="rowcon">


  <?php  if($update_rs == 0){ ?>  
  <p>&nbsp;</p>
  <p><span class="font_big18">WSS 1.3.2 升级说明</span><br/><br/>
  <span class="font_big18">注意，本升级脚本在Wamp环境的默认配置下，经过数十次测试，均可正常运行。由于不同的amp环境对数据库的操作权限不同，我们不保证在其他amp环境中可正常升级，如果您的环境中无法正常运行本升级脚本，我们建议您先将您正在使用的WSS中的数据库导出，然后使用一台windows环境的pc，参考 <a href="http://www.wssys.net/zh-CN/file.php?recordID=16&projectID=-1" target="_blank">5分钟安装说明</a> 部署一套Wamp环境的WSS1.3.0版并导入您刚导出的数据，在Wamp环境中运行升级脚本，对数据库进行升级后，再倒入至您其他amp环境中的WSS。</span><br/><br/>
  </p>
  <p><span class="font_big18">使用这种方式升级所需时间不超过10分钟，WSS官方不再受理任何升级脚本相关的问题。</span><br/><br/></p>
  <p><span class="font_big18">另外，二次开发导致的无法升级也不在官方支持的范围内。</span><br/><br/></p>
  <p>&nbsp;</p>
<p>请仔细阅读以下步骤再进行升级：</p>
  <p>&nbsp;</p>
  <p>1) <b>备份数据库</b>：本升级脚本会对您的数据库进行升级，理论上不会造成您的数据丢失的情况，但为了安全，我们仍然建议您先备份数据库再进行升级，备份方式：使用phpmyadmin完整的导出tankdb表；<br />
	  2) 确认本文件已经正确拷贝至您要升级的WSS目录下；<br />
	  3) 确认已经使用管理员权限登录WSS（此时升级操作还未进行，登录的还是您老版本的WSS）；<br />
	  4) 确认以上准备工作后，请点击“开始升级”按钮，进行升级，如果您系统中的数据很多，升级操作将需要几分钟甚至更多时间，请耐心等待。升级过程中，请不要关闭本页面，或关闭服务器电源，否则将导致升级失败；<br />
	  5) 如升级失败，请使用phpmyadmin恢复您所备份的数据库，并重新执行本升级脚本。<br />

	<p>&nbsp;</p>
	<p>    
	<p>
    <form name="agreeform" onSubmit="return defaultagree(this)" method="POST" action="upgrade.php">
	
	
<label><input name="agreecheck" type="checkbox" onClick="agreesubmit(this)" value="start"><b>我已备份数据库，并且了解升级操作不可逆</b></label><br>
<input type="Submit" value="开始升级" disabled>
</form>

<script>
document.forms.agreeform.agreecheck.checked=false
</script>
<p></p>
	<p>&nbsp;</p>
	<p><br/>
	  <span class="font_big18">免责声明</span><br/>
	  </p>
	<p>WSS为使用者根据需要自愿下载使用，White Shark System以及WSS的作者，对WSS使用过程中造成的任何数据丢失及其他风险不承担任何责任。</p>
  <br/>

  <?php }else if ($update_rs ==1){ ?>  
<p>&nbsp;</p>
<p><span class="font_big18">数据库升级成功</span><br/>
<p>&nbsp;</p>
<p>数据库升级已经完成，请继续执行以下操作完成升级：</p>
	<p>&nbsp;</p>
	<p>1) 返回 <a href="index.php" target="_blank">首页，</a>并退出登录（重要）；</p>
	<p>2) 使用 WSS 1.3.2 压缩包中的 WSS目录覆盖你服务器上的WSS目录（如修改过数据库连接文件config/tank_config.php，则升级后需要重新配置）；</p>
	<p>3) 删除服务器上本升级脚本文件（upgrade.php）；</p>
	<p>4) 重新登录后，可开始使用 WSS1.3.2，如升级后样式错乱，使用ctrl+F5 强制刷新。</p>
	<br /><br />

   <?php } ?>  
</div>
</div>
</div> <br/>
</body>
</html>