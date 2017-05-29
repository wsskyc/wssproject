<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$project_id = "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}

$p_id = "-1";
if (isset($_GET['pid'])) {
  $p_id = $_GET['pid'];
}

$fd = null;
if (isset($_GET['folder'])) {
  $fd = $_GET['folder'];
}

$pfiles = "-1";
if (isset($_GET['pfile'])) {
  $pfiles = $_GET['pfile'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Recordset1 = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset1 = $_GET['editID'];
}

if ( $pfiles== "1") {
	  $pf = "&pfile=1";
	  } else {
	  $pf = "";
	  }
$pagetabs = "mcfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
$ptab = "&pagetab=".$pagetabs;	  

if ( empty( $_POST['tk_doc_description'] ) ){
$tk_doc_description = "tk_doc_description='',";
}else{
$tk_doc_description = sprintf("tk_doc_description=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_doc_description']), "text"));
}

if ( empty( $_POST['csa_remark1'] ) ){
$tk_doc_attachment = "tk_doc_attachment='',";
}else{
$tk_doc_attachment = sprintf("tk_doc_attachment=%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_document SET tk_doc_title=%s, $tk_doc_description $tk_doc_attachment tk_doc_class2=%s, tk_doc_edit=%s WHERE docid=%s",
                       GetSQLValueString($_POST['tk_doc_title'], "text"),
					   GetSQLValueString($_POST['tk_doc_class2'], "text"),
					   GetSQLValueString($_POST['tk_doc_edit'], "text"),
                       GetSQLValueString($_POST['docid'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $newID = $colname_Recordset1;
  $newName = $_SESSION['MM_uid'];

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 2, '')",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_editdoc, "text"),
                       GetSQLValueString($newID, "text"));  
$Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

$b01 ="-1";
if (isset($_POST["b01"])) {
  $b01 = $_POST["b01"];
}

if($b01 =="-1"){
  $updateGoTo = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
} else{
  $updateGoTo = "file_view.php?recordID=$colname_Recordset1&folder=$fd&projectID=$project_id".$pf.$ptab;
}
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php require('head.php'); ?>

    <link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="srcipt/lhgcore.js"></script>
    <script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_doc_title', mid: 'doctitle', type: 'limit', requir: true,  warn: '<?php echo $multilingual_announcement_titlerequired; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
}
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_doc_description', {
			width : '96%',
			height: '500px',
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
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
            <td ><br />
              <span class="font_big18 fontbold breakwordsfloat_left"><?php echo $multilingual_project_file_editfile; ?></span> </td>
          </tr>
          <tr>
            <td><input type="text" name="tk_doc_title" id="tk_doc_title" value="<?php echo $row_Recordset1['tk_doc_title']; ?>" size="50" class="file_input"  placeholder="<?php echo $multilingual_project_file_filetitle;?>" />
            <span id="doctitle"></span></td>
          </tr>
          <tr>
            <td><textarea name="tk_doc_description" id="tk_doc_description"><?php echo $row_Recordset1['tk_doc_description']; ?></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
         
        </table></td>
      <td width="30%" class="input_task_right_bg" valign="top"><table width="92%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td valign="top" width="20%">&nbsp;</td>
            <td valign="top" width="80%">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_upload_attachment; ?></td>
            <td valign="top"><input type="text"  name="csa_remark1" id="csa_remark1" value="<?php echo $row_Recordset1['tk_doc_attachment']; ?>" size="30" placeholder="<?php echo $multilingual_upload_attachment; ?>" />
            <a class="mouse_hover" onClick="openBrWindow('upload_file.php','<?php echo $multilingual_global_upload; ?>','width=450,height=235')" >
			<?php echo $multilingual_global_upload; ?>			</a></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><span class="gray" ><?php echo $multilingual_upload_tip3; ?></span></td>
          </tr>
          <tr style="display:none;">
            <td valign="top">&nbsp;</td>
            <td valign="top"><input type="text" name="tk_doc_class2" id="tk_doc_class2" value="<?php echo $row_Recordset1['tk_doc_class2']; ?>" size="32" />    <input type="text" name="docid" id="docid" value="<?php echo $row_Recordset1['docid']; ?>" size="32" /> </td>
          </tr>
          <tr style="display:none;">
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="tk_doc_edit" type="text" class="gray" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" size="32" readonly="readonly" />			</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2" class="input_task_bottom_bg" height="50px"><span class="input_task_submit">
        <div class="float_right">
          <input type="submit" value="<?php echo $multilingual_global_action_save;  ?>" id="b02" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  />
	  <input type="submit" value="<?php echo $multilingual_global_action_saveandgo;  ?>" name="b01" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  />
	   <input name="button" type="button" id="button" onClick="javascript:self.location='<?php echo "file_view.php?recordID=$colname_Recordset1&folder=$fd&projectID=$project_id".$pf.$ptab ;?>';"  value="<?php echo $multilingual_global_action_cancel; ?>" /><input type="hidden" name="MM_update" value="form1" />
        </div>
        </span>
        </td>
    </tr>
  </table>


</form>
<?php require('foot.php'); ?>
</body>
</html>