<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Recordset1 = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset1 = $_GET['editID'];
}


if ( empty( $_POST['project_code'] ) ){
$project_code = "project_code='',";
}else{
$project_code = sprintf("project_code=%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_code']), "text"));
}

if ( empty( $_POST['project_text'] ) ){
$project_text = "project_text='',";
}else{
$project_text = sprintf("project_text=%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_text']), "text"));
}

if ( empty( $_POST['project_start'] ) ){
$project_start = "project_start='0000-00-00',";
}else{
$project_start = sprintf("project_start=%s,", GetSQLValueString($_POST['project_start'], "date"));
}

if ( empty( $_POST['project_end'] ) ){
$project_end = "project_end='0000-00-00',";
}else{
$project_end = sprintf("project_end=%s,", GetSQLValueString($_POST['project_end'], "date"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_project SET project_name=%s, $project_code $project_text $project_start $project_end project_to_user=%s, project_status=%s WHERE id=%s",
                       GetSQLValueString($_POST['project_name'], "text"),
                       GetSQLValueString($_POST['project_to_user'], "text"),
                       GetSQLValueString($_POST['project_status'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "project_view.php?recordID=$colname_Recordset1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_project WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset2 = "SELECT * FROM tk_status_project ORDER BY task_status_pbackup1 ASC";
$Recordset2 = mysql_query($query_Recordset2, $tankdb) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset4 = "SELECT * FROM tk_user WHERE tk_user_rank NOT LIKE '0' ORDER BY tk_display_name ASC";
$Recordset4 = mysql_query($query_Recordset4, $tankdb) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "4" && $row_Recordset1['project_to_user'] <> $_SESSION['MM_uid']) {   
  header("Location: ". $restrictGoTo); 
  exit;
}

?>
<?php require('head.php'); ?>
	<link type="text/css" href="skin/themes/base/pages.css" rel="stylesheet" />
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
              <span class="font_big18 fontbold breakwordsfloat_left"><?php echo $multilingual_projectlist_edit; ?></span> </td>
          </tr>
          <tr>
            <td><input type="text" name="project_name" id="project_name" value="<?php echo htmlentities($row_Recordset1['project_name'], ENT_COMPAT, 'utf-8'); ?>" size="50" class="width-p100-big" placeholder="<?php echo $multilingual_project_title; ?>" /><span id="projecttitle"></span></td>
          </tr>
          <tr>
            <td><textarea name="project_text"  id="project_text"><?php echo htmlentities($row_Recordset1['project_text'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
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
            <td valign="top"><?php echo $multilingual_project_id; ?></td>
            <td valign="top"><?php echo $row_Recordset1['id']; ?></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_status; ?></td>
            <td valign="top"><select name="project_status" onChange="option_gourl(this.value)">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset2['psid']?>"<?php if (!(strcmp($row_Recordset2['psid'], ($row_Recordset1['project_status'])))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset2['task_status']?></option>
        <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-2" class="gray" >+<?php echo $multilingual_projectstatus_new; ?></option>
<?php } ?>
      </select> <span id="user_pass" class="gray"><br /><?php echo $multilingual_project_tips; ?></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_code; ?></td>
            <td valign="top"><input type="text" name="project_code" value="<?php echo htmlentities($row_Recordset1['project_code'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;			</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_touser; ?></td>
            <td valign="top"><select name="project_to_user" onChange="option_gourl(this.value)">
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset4['uid']?>"<?php if (!(strcmp($row_Recordset4['uid'],$row_Recordset1['project_to_user']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset4['tk_display_name']?></option>
          <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-1" class="gray" >+<?php echo $multilingual_user_new; ?></option>
<?php } ?>
      </select><input type="text" name="project_to_dept" id="select" value="<?php echo $row_Recordset1['project_to_dept']?>" style="display:none" /><br /><span  class="gray"> <?php echo $multilingual_project_tips2; ?></span></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_start; ?></td>
            <td valign="top"><input type="text" name="project_start" value="<?php echo htmlentities($row_Recordset1['project_start'], ENT_COMPAT, 'utf-8'); ?>" size="32"  id="datepicker"  /><span id="datepicker_msg"></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_project_end; ?></td>
            <td valign="top"><input type="text" name="project_end" value="<?php echo htmlentities($row_Recordset1['project_end'], ENT_COMPAT, 'utf-8'); ?>" size="32"  id="datepicker2"  /><span id="datepicker2_msg"></span></td>
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
        <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_Recordset1['id']; ?>" /></td>
    </tr>
  </table>
</form>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
mysql_free_result($Recordset4);
?>