<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "3") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$to_user = "-1";
if (isset($_POST['csa_to_user'])) {
  $to_user= $_POST['csa_to_user'];
}

$title = "-1";
if (isset($_POST['csa_text'])) {
  $title= $_POST['csa_text'];
}

$project_id = "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}

$project_url = "-1";
if (isset($_GET['formproject'])) {
  $project_url= $_GET['formproject'];
}

$user_id = "-1";
if (isset($_GET['UID'])) {
  $user_id= $_GET['UID'];
}

$user_url = "-1";
if (isset($_GET['touser'])) {
  $user_url= $_GET['touser'];
}

if ( empty( $_POST['plan_hour'] ) )
		$_POST['plan_hour'] = '0.0';

if ( empty( $_POST['csa_remark1'] ) ){
$csa_remark1 = "'',";
}else{
$csa_remark1 = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ( empty( $_POST['csa_tag'] ) ){
$csa_tag = "'',";
}else{
$csa_tag = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_tag']), "text"));
}

//for wbs!
$wbs_id = "-1";
if (isset($_GET['wbsID'])) {
  $wbs_id = $_GET['wbsID'];
}

$task_id = "-1";
if (isset($_GET['taskID'])) {
  $task_id = $_GET['taskID'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_project.id as proid  
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($task_id, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

if ($wbs_id == "2"){
$wbs = $task_id.">".$wbs_id;
} else {
$wbs = $row_Recordset_task['csa_remark5'].">".$row_Recordset_task['TID'].">".$wbs_id; 
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$newID = add_task( $_POST['csa_from_dept'], $_POST['csa_from_user'], $_POST['csa_to_dept'], $_POST['csa_to_user'], $project_id, $_POST['csa_type'], $_POST['csa_text'], $_POST['csa_priority'], $_POST['csa_temp'], $_POST['plan_start'], $_POST['plan_end'], $_POST['plan_hour'], $_POST['csa_remark2'], $_POST['csa_create_user'], $_POST['csa_last_user'], $task_id, $wbs, $wbs_id, $_SESSION['MM_uid'], $csa_tag, $csa_remark1 );

if ($project_url == 1){
$insertGoTo = "project_view.php?recordID=$project_id";
} else if ($user_url == 1){
$insertGoTo = "user_view.php?recordID=$user_id";
}

else {
  $insertGoTo = "default_task_edit.php?editID=$newID";
}


  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }

$msg_to = $_POST['csa_to_user'];
$msg_from = $_POST['csa_create_user'];
$msg_type = "newtask";
$msg_id = $newID;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );
 

 
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_user = "SELECT * FROM tk_user WHERE tk_user_rank <>0  ORDER BY tk_display_name ASC";
$Recordset_user = mysql_query($query_Recordset_user, $tankdb) or die(mysql_error());
$row_Recordset_user = mysql_fetch_assoc($Recordset_user);
$totalRows_Recordset_user = mysql_num_rows($Recordset_user);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_type = "SELECT * FROM tk_task_tpye ORDER BY task_tpye_backup1 ASC";
$Recordset_type = mysql_query($query_Recordset_type, $tankdb) or die(mysql_error());
$row_Recordset_type = mysql_fetch_assoc($Recordset_type);
$totalRows_Recordset_type = mysql_num_rows($Recordset_type);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_project = sprintf("SELECT * FROM tk_project WHERE id = %s",
                       GetSQLValueString($project_id, "int"));  
$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
$totalRows_Recordset_project = mysql_num_rows($Recordset_project);

mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus = "SELECT * FROM tk_status WHERE task_status_backup2 <>1 ORDER BY task_status_backup1 ASC";
$tkstatus = mysql_query($query_tkstatus, $tankdb) or die(mysql_error());
$row_tkstatus = mysql_fetch_assoc($tkstatus);
$totalRows_tkstatus = mysql_num_rows($tkstatus);
?>
<?php require('head.php'); ?>
<link type="text/css" href="skin/themes/base/ui.all.css" rel="stylesheet" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript" src="skin/jquery-1.3.2.js"></script>
<script type="text/javascript" src="skin/ui/ui.core.js" charset="utf-8"></script>
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
J.check.rules = [
	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'select2', mid: 'csa_from_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'csa_type', mid: 'csa_type_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required3; ?>' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'csa_text', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'},
	{ name: 'plan_hour', mid: 'plan_hour_msg', type: 'rang', min: 0, warn: '<?php echo $multilingual_default_required5; ?>' }
   
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
		
function submitform()
{
    document.myform.cont.value='<?php echo $multilingual_global_wait; ?>';
	document.myform.cont.disabled=true;
	document.getElementById("btn5").click();
}	


</script>
<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="myform">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70%"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
            <td >
			<ul class="breadcrumb">
              <li><?php echo $multilingual_default_taskproject; ?>:
			  <a href="project_view.php?recordID=<?php echo $row_Recordset_project['id']; ?>" ><?php echo $row_Recordset_project['project_name']; ?></a><span class="divider">/</span></li>
			  <li><?php if ($task_id <> -1) { ?>
                <?php echo $multilingual_default_task_parent; ?>: <a href="default_task_edit.php?editID=<?php echo $row_Recordset_task['TID']; ?>" ><?php echo $row_Recordset_task['csa_text']; ?></a>
                <?php } else {
	 echo $multilingual_subtask_root;
	  } ?></li>
			</ul>
			  </td>
          </tr>
          <tr>
            <td >
              <span class="font_big18 fontbold"><?php echo $multilingual_taskadd_title; ?></span> </td>
          </tr>
          <tr>
            <td><input name="csa_text" id="csa_text" type="text" value="" size="50" class="width-p100-big" placeholder="<?php echo $multilingual_taskadd_title_plh;?>">
              <span id="csa_text_msg"></span></td>
          </tr>
          <tr>
            <td><textarea id="csa_remark1" name="csa_remark1" ></textarea></td>
          </tr>
          <tr>
            <td><input name="csa_tag" id="csa_tag" type="text" value="" class="width-p100-big" placeholder="<?php echo $multilingual_default_tasktag;?>"></td>
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
            <td valign="top"><?php echo $multilingual_default_task_type; ?></td>
            <td valign="top"><select name="csa_type" id="csa_type" onChange="option_gourl(this.value)" >
                <option value=""><?php echo $multilingual_global_select; ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset_type['id']?>"><?php echo $row_Recordset_type['task_tpye']?></option>
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
            <span id="csa_type_msg"></span> <b title="<?php echo $multilingual_default_task_catips2; ?>">[?]</b></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_to; ?></td>
            <td valign="top"><input type="text" id="select3" name="csa_to_dept" value="0001" style="display:none;" />
              <select id="select4" name="csa_to_user" onChange="option_gourl(this.value)">
                <option value="" ><?php echo $multilingual_global_select; ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset_user['uid']?>" 
		  <?php if (!(strcmp($row_Recordset_user['uid'], $user_id))) {echo "selected=\"selected\"";} ?>
		  ><?php echo $row_Recordset_user['tk_display_name']?></option>
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
            <span id="csa_to_user_msg"></span>
			<br /><span class="gray"><?php echo $multilingual_taskadd_totip; ?></span>
			</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_from; ?></td>
            <td valign="top"><input type="text" id="select1" name="csa_from_dept" value="0001" style="display:none;"  />
              <select id="select2" name="csa_from_user" onChange="option_gourl(this.value)">
                <option value=""  <?php if (!(strcmp("", "{$_SESSION['MM_uid']}"))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_global_select; ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset_user['uid']?>"<?php if (!(strcmp($row_Recordset_user['uid'], "{$_SESSION['MM_uid']}"))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_user['tk_display_name']?></option>
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
              <input name="csa_create_user" type="text"  id="csa_create_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>"  style="display:none">
            <input name="csa_last_user" type="text"  id="csa_last_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" style="display:none">
			<br /><span class="gray"><?php echo $multilingual_exam_tip; ?></span>
			</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_planstart; ?></td>
            <td valign="top"><input type="text" name="plan_start" id="datepicker2" value="<?php echo date('Y-m-d'); ?>" size="20"  />
            <span id="csa_plan_st_msg"></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_planend; ?></td>
            <td valign="top"><input type="text" name="plan_end" id="datepicker3" value="<?php echo date("Y-m-d",strtotime("+1 day")); ?>" size="20"  />
            <span id="csa_plan_et_msg"></span></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_planhour; ?></td>
            <td valign="top"><input type="text" name="plan_hour" id="plan_hour"  value="" size="20"  />
            <?php echo $multilingual_global_hour; ?><span id="plan_hour_msg"></span></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_task_priority; ?></td>
            <td valign="top"><select name="csa_priority">
                <option value="5" ><?php echo $multilingual_dd_priority_p5; ?></option>
                <option value="4" ><?php echo $multilingual_dd_priority_p4; ?></option>
                <option value="3" SELECTED=“SELECTED”><?php echo $multilingual_dd_priority_p3; ?></option>
                <option value="2" ><?php echo $multilingual_dd_priority_p2; ?></option>
                <option value="1" ><?php echo $multilingual_dd_priority_p1; ?></option>
            </select></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_tasklevel; ?></td>
            <td valign="top"><select name="csa_temp">
                <option value="5"><?php echo $multilingual_dd_level_l5; ?></option>
                <option value="4"><?php echo $multilingual_dd_level_l4; ?></option>
                <option value="3" SELECTED=“SELECTED”><?php echo $multilingual_dd_level_l3; ?></option>
                <option value="2"><?php echo $multilingual_dd_level_l2; ?></option>
                <option value="1"><?php echo $multilingual_dd_level_l1; ?></option>
            </select></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_default_taskstatus; ?></td>
            <td valign="top"><select name="csa_remark2" id="csa_remark2" >
                <?php
do {  
?>
                <option value="<?php echo $row_tkstatus['id']?>"><?php echo $row_tkstatus['task_status']?></option>
                <?php
} while ($row_tkstatus = mysql_fetch_assoc($tkstatus));
  $rows = mysql_num_rows($tkstatus);
  if($rows > 0) {
      mysql_data_seek($tkstatus, 0);
	  $row_tkstatus = mysql_fetch_assoc($tkstatus);
  }
?>
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
	   name="cont" 
	   <?php // if ( $mail == "on" ){echo "onClick='sendmail()'";} ?>
	   />
          <input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />
          &nbsp;&nbsp;
          <input name="button" type="button" id="button" onClick="javascript:history.go(-1);" value="<?php echo $multilingual_global_action_cancel; ?>"  class="button" />
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
mysql_free_result($Recordset_user);
mysql_free_result($Recordset_project);
mysql_free_result($Recordset_type);
?>
