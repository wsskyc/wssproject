<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$colname_Recordset_task = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset_task = $_GET['editID'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['csa_remark1'] ) ){
$csa_remark1 = "csa_remark1='',";
}else{
$csa_remark1 = sprintf("csa_remark1=%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ( empty( $_POST['csa_tag'] ) ){
$test02 = "test02=''";
}else{
$test02 = sprintf("test02=%s", GetSQLValueString(str_replace("%","%%",$_POST['csa_tag']), "text"));
}

if ( empty( $_POST['plan_hour'] ) ){
$plan_hour = "csa_plan_hour='0.0',";
}else{
$plan_hour = sprintf("csa_plan_hour=%s,", GetSQLValueString($_POST['plan_hour'], "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_task SET csa_from_dept=%s, csa_from_user=%s, csa_to_dept=%s, csa_to_user=%s, csa_type=%s, csa_text=%s, csa_priority=%s, csa_temp=%s, csa_plan_st=%s, csa_plan_et=%s, $plan_hour $csa_remark1 csa_remark2=%s, csa_last_user=%s, $test02 WHERE TID=%s",
                       GetSQLValueString($_POST['csa_from_dept'], "text"),
                       GetSQLValueString($_POST['csa_from_user'], "text"),
                       GetSQLValueString($_POST['csa_to_dept'], "text"),
                       GetSQLValueString($_POST['csa_to_user'], "text"),
                       GetSQLValueString($_POST['csa_type'], "text"),
                       GetSQLValueString($_POST['csa_text'], "text"),
                       GetSQLValueString($_POST['csa_priority'], "text"),
                       GetSQLValueString($_POST['csa_temp'], "text"),
					   GetSQLValueString($_POST['plan_start'], "text"),
					   GetSQLValueString($_POST['plan_end'], "text"),
                       GetSQLValueString($_POST['csa_remark2'], "text"),
                       GetSQLValueString($_POST['csa_last_user'], "text"),
                       GetSQLValueString($_POST['TID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $newID = $colname_Recordset_task;
  $newName = $_SESSION['MM_uid'];

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_edittask, "text"),
                       GetSQLValueString($newID, "text"));  
$Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

  $updateGoTo = "default_task_edit.php?editID=$colname_Recordset_task";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, tk_user1.tk_display_name as tk_display_name1 
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
WHERE TID = %s", GetSQLValueString($colname_Recordset_task, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_ptask = sprintf("SELECT *  
FROM tk_task 
WHERE TID = %s", GetSQLValueString($row_Recordset_task['csa_remark4'], "int"));
$Recordset_ptask = mysql_query($query_Recordset_ptask, $tankdb) or die(mysql_error());
$row_Recordset_ptask = mysql_fetch_assoc($Recordset_ptask);
$totalRows_Recordset_ptask = mysql_num_rows($Recordset_ptask);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_user = "SELECT * FROM tk_user WHERE tk_user_rank <> 0 ORDER BY tk_display_name ASC";
$Recordset_user = mysql_query($query_Recordset_user, $tankdb) or die(mysql_error());
$row_Recordset_user = mysql_fetch_assoc($Recordset_user);
$totalRows_Recordset_user = mysql_num_rows($Recordset_user);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_type = "SELECT * FROM tk_task_tpye ORDER BY task_tpye_backup1 ASC";
$Recordset_type = mysql_query($query_Recordset_type, $tankdb) or die(mysql_error());
$row_Recordset_type = mysql_fetch_assoc($Recordset_type);
$totalRows_Recordset_type = mysql_num_rows($Recordset_type);

mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus = "SELECT * FROM tk_status WHERE task_status_backup2 <> 1 ORDER BY task_status_backup1 ASC";
$tkstatus = mysql_query($query_tkstatus, $tankdb) or die(mysql_error());
$row_tkstatus = mysql_fetch_assoc($tkstatus);
$totalRows_tkstatus = mysql_num_rows($tkstatus);

$prjid=$row_Recordset_task['csa_project'];
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_project = "SELECT * FROM tk_project WHERE id = $prjid";
$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
$totalRows_Recordset_project = mysql_num_rows($Recordset_project);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_countlog = sprintf("SELECT COUNT(*) as count_log FROM tk_task_byday WHERE csa_tb_backup1=%s", GetSQLValueString($colname_Recordset_task, "int"));
$Recordset_countlog = mysql_query($query_Recordset_countlog, $tankdb) or die(mysql_error());
$row_Recordset_countlog = mysql_fetch_assoc($Recordset_countlog);

$restrictGoTo = "user_error3.php";
if (($_SESSION['MM_rank'] < "5" && $row_Recordset_task['csa_create_user'] <> $_SESSION['MM_uid']) || $_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
?>
<?php require('head.php'); ?>
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link type="text/css" href="skin/themes/base/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="skin/jquery-1.3.2.js"></script>
<script type="text/javascript" src="skin/ui/ui.core.js"></script>
<script type="text/javascript" src="skin/ui/ui.datepicker_<?php echo $language; ?>.js" charset="utf-8"></script>
<script type="text/javascript">
	$(function() {
		$("#datepicker").datepicker({showOn: 'button', buttonImage: 'skin/themes/base/images/calendar.gif', buttonImageOnly: true});
	
	
    $('#datepicker2').datepicker({
			changeMonth: true,
			changeYear: true
		});
		$('#datepicker3').datepicker({
			changeMonth: true,
			changeYear: true
		});
		
		});
    </script>
<script type="text/javascript">
<!--
function openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#csa_remark1', {
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
<script type="text/javascript">
<!--
J.check.rules = [
	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'select2', mid: 'csa_from_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'csa_type', mid: 'csa_type_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required3; ?>' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'csa_text', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>' },
	{name: 'plan_hour', mid: 'plan_hour_msg', type: 'rang', min: -1, warn: '<?php echo $multilingual_default_required5; ?>' }
   
];

window.onload = function()
{
    J.check.regform('myform');
}

function option_gourl(str)
{
if(str == '-1')window.open('task_type_list.php');
if(str == '-2')window.open('user_add.php');
if(str == '-3')window.open('project_add.php');
}
//-->
</script>
<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="myform">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70%"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
            <td ><ul class="breadcrumb">
                <li><?php echo $multilingual_default_taskproject; ?>: <a href="project_view.php?recordID=<?php echo $row_Recordset_project['id']; ?>" ><?php echo $row_Recordset_project['project_name']; ?></a> <span class="divider">/</span></li>
                </li>
                <li>
                  <?php if ($row_Recordset_task['csa_remark4'] <> -1) { ?>
                  <?php echo $multilingual_default_task_parent; ?>: <a href="default_task_edit.php?editID=<?php echo $row_Recordset_ptask['TID']; ?>" ><?php echo $row_Recordset_ptask['csa_text']; ?></a>
                  <?php } else {
	 echo $multilingual_subtask_root;
	  } ?>
                </li>
              </ul></td>
          </tr>
          <tr>
            <td ><span class="font_big18 fontbold float_left"><?php echo $multilingual_taskedit_title; ?></span></td>
          </tr>
          <tr>
            <td >
        <input name="csa_text" id="csa_text" type="text" value="<?php echo htmlentities($row_Recordset_task['csa_text'], ENT_COMPAT, 'utf-8'); ?>" size="50"  class="width-p100-big" placeholder="<?php echo $multilingual_taskadd_title_plh;?>"><span id="csa_text_msg"></span></td>
          </tr>
          <tr>
            <td><textarea id="csa_remark1" name="csa_remark1" ><?php echo $row_Recordset_task['csa_remark1']; ?></textarea></td>
          </tr>
          <tr>
            <td><input name="csa_tag" id="csa_tag" type="text" value="<?php echo htmlentities($row_Recordset_task['test02'], ENT_COMPAT, 'utf-8'); ?>"  class="width-p100-big" placeholder="<?php echo $multilingual_default_tasktag;?>" ></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
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
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_taskid; ?></td>
            <td valign="top"><?php echo $row_Recordset_task['TID']; ?></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_type; ?></td>
            <td valign="top"><?php 
	if ($row_Recordset_countlog['count_log'] <> "0") { ?>
        <select name="dis_csa_type" id="dis_csa_type" disabled="disabled" >
          <option value="" <?php if (!(strcmp("", $row_Recordset_task['csa_type']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_global_select; ?></option>
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_type['id']?>"<?php if (!(strcmp($row_Recordset_type['id'], $row_Recordset_task['csa_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_type['task_tpye']?></option>
          <?php
} while ($row_Recordset_type = mysql_fetch_assoc($Recordset_type));
  $rows = mysql_num_rows($Recordset_type);
  if($rows > 0) {
      mysql_data_seek($Recordset_type, 0);
	  $row_Recordset_type = mysql_fetch_assoc($Recordset_type);
  }
?>
        </select>
        <b title="<?php echo $multilingual_tasktype_lock2; ?>"> [?]</b>
        <?php } ?>
        <select name="csa_type" id="csa_type"    onchange="option_gourl(this.value)" 
		 <?php 
	if ($row_Recordset_countlog['count_log'] <> 0) { 
	echo "style='display:none;'";
	 } ?> 
		>
          <option value="" <?php if (!(strcmp("", $row_Recordset_task['csa_type']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_global_select; ?></option>
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_type['id']?>"<?php if (!(strcmp($row_Recordset_type['id'], $row_Recordset_task['csa_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_type['task_tpye']?></option>
          <?php
} while ($row_Recordset_type = mysql_fetch_assoc($Recordset_type));
  $rows = mysql_num_rows($Recordset_type);
  if($rows > 0) {
      mysql_data_seek($Recordset_type, 0);
	  $row_Recordset_type = mysql_fetch_assoc($Recordset_type);
  }
?>
          <?php if ($_SESSION['MM_rank'] > "4") { ?>
          <option value="-1" class="gray" >+<?php echo $multilingual_tasktype_new; ?></option>
          <?php } ?>
        </select>
        <span id="csa_type_msg"></span></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_to; ?></td>
            <td valign="top"><input type="text" name="csa_to_dept" id="select3"  value="<?php echo $row_Recordset_task['csa_to_dept']; ?>"  style="display:none;"  />
        <?php 
	if ($row_Recordset_countlog['count_log'] <> "0") { ?>
        <input type="text" value="<?php echo $row_Recordset_task['tk_display_name1'] ?>" disabled="disabled">
        <select id="select4" name="csa_to_user" style=" display:none;">
          <option value="<?php echo $row_Recordset_task['csa_to_user'] ?>"><?php echo $row_Recordset_task['csa_to_user'] ?></option>
        </select>
        <b title="<?php echo $multilingual_tasktype_lock2; ?>"> [?]</b>
        <?php } else { ?>
        <select id="select4" name="csa_to_user" onChange="option_gourl(this.value)">
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
          <?php if ($_SESSION['MM_rank'] > "4") { ?>
          <option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
          <?php } ?>
        </select>
        <?php } ?>
        <span id="csa_to_user_msg"></span>
		<br /><span class="gray"><?php echo $multilingual_taskadd_totip; ?></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_from; ?></td>
            <td valign="top"><input type="text" id="select1" name="csa_from_dept" value="<?php echo $row_Recordset_task['csa_from_dept']; ?>"  style="display:none;"  />
        <select  id="select2" name="csa_from_user" onChange="option_gourl(this.value)">
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_user['uid']?>"<?php if (!(strcmp($row_Recordset_user['uid'], $row_Recordset_task['csa_from_user']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_user['tk_display_name']?></option>
          <?php
} while ($row_Recordset_user = mysql_fetch_assoc($Recordset_user));
  $rows = mysql_num_rows($Recordset_user);
  if($rows > 0) {
      mysql_data_seek($Recordset_user, 0);
	  $row_Recordset_user = mysql_fetch_assoc($Recordset_user);
  }
?>
          <?php if ($_SESSION['MM_rank'] > "4") { ?>
          <option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
          <?php } ?>
        </select>
        <span id="csa_from_user_msg"></span>
        <input name="csa_last_user" type="text"  id="csa_last_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" readonly="readonly" class="gray" style="display:none;">
		<br /><span class="gray"><?php echo $multilingual_exam_tip; ?></span>
		</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_planstart; ?></td>
            <td valign="top"><input type="text" name="plan_start" id="datepicker2" value="<?php echo $row_Recordset_task['csa_plan_st']; ?>" size="20"  />
        <span id="csa_plan_st_msg"></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_planend; ?></td>
            <td valign="top"><input type="text" name="plan_end" id="datepicker3" value="<?php echo $row_Recordset_task['csa_plan_et']; ?>" size="20"  />
        <span id="csa_plan_et_msg"></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_planhour; ?></td>
            <td valign="top"><input type="text" name="plan_hour" id="plan_hour" value="<?php echo $row_Recordset_task['csa_plan_hour']; ?>" size="20"  />
        <?php echo $multilingual_global_hour; ?><span id="plan_hour_msg"></span></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_priority; ?></td>
            <td valign="top"><select name="csa_priority">
          <option value="5" <?php if (!(strcmp(5, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p5; ?></option>
          <option value="4" <?php if (!(strcmp(4, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p4; ?></option>
          <option value="3" <?php if (!(strcmp(3, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p3; ?></option>
          <option value="2" <?php if (!(strcmp(2, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p2; ?></option>
          <option value="1" <?php if (!(strcmp(1, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p1; ?></option>
        </select></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_tasklevel; ?></td>
            <td valign="top"><select name="csa_temp">
          <option value="5" <?php if (!(strcmp(5, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l5; ?></option>
          <option value="4" <?php if (!(strcmp(4, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l4; ?></option>
          <option value="3" <?php if (!(strcmp(3, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l3; ?></option>
          <option value="2" <?php if (!(strcmp(2, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l2; ?></option>
          <option value="1" <?php if (!(strcmp(1, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l1; ?></option>
        </select></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_taskstatus; ?></td>
            <td valign="top"><select name="csa_remark2" id="csa_remark2" >
          <?php do {  ?>
          <option value="<?php echo $row_tkstatus['id'] ?>" <?php if (!(strcmp($row_tkstatus['id'], $row_Recordset_task['csa_remark2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tkstatus['task_status']?></option>
          <?php } while ($row_tkstatus = mysql_fetch_assoc($tkstatus));  $rows = mysql_num_rows($tkstatus);  if($rows > 0) {      mysql_data_seek($tkstatus, 0);	  $row_tkstatus = mysql_fetch_assoc($tkstatus);  } ?>
        </select></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2" class="input_task_bottom_bg" height="50px"><span class="input_task_submit">
        <div class="float_right">
          <input type="submit" value="<?php echo $multilingual_global_action_save; ?>" class="button"  
	  <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  />
          &nbsp;&nbsp;
          <input name="button2" type="button" id="button2" onClick="javascript:history.go(-1);" value="<?php echo $multilingual_global_action_cancel; ?>"  class="button" />
        </div>
        </span>
        <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="TID" value="<?php echo $row_Recordset_task['TID']; ?>" /></td>
    </tr>
  </table>
</form>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset_task);

mysql_free_result($Recordset_user);

mysql_free_result($Recordset_type);
mysql_free_result($Recordset_project);
?>
