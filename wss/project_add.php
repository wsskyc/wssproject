<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "4") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

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

if ( empty( $_POST['project_code'] ) ){
$project_code = "'',";
}else{
$project_code = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_code']), "text"));
}

if ( empty( $_POST['project_text'] ) ){
$project_text = "'',";
}else{
$project_text = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_text']), "text"));
}

if ( empty( $_POST['project_from_contact'] ) ){
$project_from_contact = "'',";
}else{
$project_from_contact = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_from_contact']), "text"));
}

if ( empty( $_POST['project_start'] ) )
		$_POST['project_start'] = '0000-00-00';

if ( empty( $_POST['project_end'] ) )
		$_POST['project_end'] = '0000-00-00';

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_project (project_name, project_code, project_text, project_start, project_end, project_to_user, project_status, project_from, project_from_user, project_to_dept, project_remark, project_from_contact) VALUES (%s, $project_code $project_text %s, %s, %s, %s, '', '', '', '', '')",
                       GetSQLValueString($_POST['project_name'], "text"),
                       GetSQLValueString($_POST['project_start'], "date"),
                       GetSQLValueString($_POST['project_end'], "date"),
                       GetSQLValueString($_POST['project_to_user'], "text"),
                       GetSQLValueString($_POST['project_status'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  $newID = mysql_insert_id();
  $insertGoTo = "project_view.php?recordID=$newID";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset2 = "SELECT * FROM tk_user WHERE tk_user_rank NOT LIKE '0' ORDER BY tk_display_name ASC";
$Recordset2 = mysql_query($query_Recordset2, $tankdb) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset3 = "SELECT * FROM tk_status_project ORDER BY task_status_pbackup1 ASC";
$Recordset3 = mysql_query($query_Recordset3, $tankdb) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

?>
<?php require('head.php'); ?>
	<link type="text/css" href="skin/themes/base/ui.all.css" rel="stylesheet" />
    <link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="srcipt/lhgcore.js"></script>
    <script type="text/javascript" src="srcipt/lhgcheck.js"></script>
	<script type="text/javascript" src="skin/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="skin/ui/ui.core.js"></script>
	<script type="text/javascript" src="skin/ui/ui.datepicker_<?php echo $language; ?>.js" ></script>


	<script type="text/javascript">
	$(function() {
		$('#datepicker').datepicker({
			changeMonth: true,
			changeYear: true
		});
		$('#datepicker2').datepicker({
			changeMonth: true,
			changeYear: true
		});
	});
	</script>
<script type="text/javascript">
J.check.rules = [
    { name: 'project_name', mid: 'projecttitle', type: 'limit', requir: true, min: 2, max: 32, warn: '<?php echo $multilingual_projectstatus_titlerequired; ?>' },
	{ name: 'datepicker', mid: 'datepicker_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker2', mid: 'datepicker2_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
}

function option_gourl(str)
{
if(str == '-1')window.open('user_add.php');
if(str == '-2')window.open('project_status.php');
}
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#project_text', {
			width : '95%',
			height: '350px',
			items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'map', 'code', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });
</script>






<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
            <td ><br />
              <span class="font_big18 fontbold breakwordsfloat_left"><?php echo $multilingual_projectlist_new; ?></span> </td>
          </tr>
          <tr>
            <td>
			<input type="text" name="project_name" id="project_name" value="" size="50" class="width-p100-big" placeholder="<?php echo $multilingual_project_title; ?>" />
			<span id="projecttitle"></span>			</td>
          </tr>
          <tr>
            <td><textarea name="project_text" id="project_text"></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          
        </table></td>
      <td width="30%" class="input_task_right_bg" valign="top"><table width="96%" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
            <td valign="top" width="30%">&nbsp;</td>
            <td valign="top" width="70%">&nbsp;</td>
          </tr>

          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_status; ?></td>
            <td valign="top"><select name="project_status" onChange="option_gourl(this.value)">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset3['psid']?>"><?php echo $row_Recordset3['task_status']?></option>
        <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-2" class="gray" >+<?php echo $multilingual_projectstatus_new; ?></option>
<?php } ?>
      </select> <span id="user_pass"></span>
	  <br /><span class="gray"><?php echo $multilingual_project_tips; ?></span>
	  </td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_code; ?></td>
            <td valign="top"><input type="text" name="project_code" value="" size="32" /></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;
			</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_touser; ?></td>
            <td valign="top"><select id="select2" name="project_to_user" onChange="option_gourl(this.value)">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset2['uid']?>"><?php echo $row_Recordset2['tk_display_name']?></option>
        <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-1" class="gray" >+<?php echo $multilingual_user_new; ?></option>
<?php } ?>
      </select><br /><span  class="gray"> <?php echo $multilingual_project_tips2; ?></span>
			</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_start; ?></td>
            <td valign="top"><input type="text" name="project_start" id="datepicker" value="" size="32"  /><span id="datepicker_msg"></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_end; ?></td>
            <td valign="top"><input type="text" name="project_end" value="" size="32" id="datepicker2"  /><span id="datepicker2_msg"></span></td>
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
      
        <input name="button" type="button" id="button" onClick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_cancel; ?>" />
      

        </div>
        </span>
          <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
  </table>
</form>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset2);
mysql_free_result($Recordset3);
?>