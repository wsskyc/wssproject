<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$project_id = "-1";
if (isset($_GET['projectid'])) {
  $project_id = $_GET['projectid'];
}

$p_id = "-1";
if (isset($_GET['pid'])) {
  $p_id = $_GET['pid'];
}

$fd = "0";
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

if ( empty( $_POST['tk_doc_description'] ) ){
$tk_doc_description = "'',";
}else{
$tk_doc_description = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_doc_description']), "text"));
}

if ( empty( $_POST['csa_remark1'] ) ){
$csa_remark1 = "'',";
}else{
$csa_remark1 = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_document (tk_doc_title, tk_doc_description, tk_doc_attachment, tk_doc_class1, tk_doc_class2, tk_doc_create, tk_doc_createtime, tk_doc_edit, tk_doc_backup1, tk_doc_type, tk_doc_backup2) VALUES (%s, $tk_doc_description $csa_remark1 %s, %s, %s, %s, %s, %s, '', '')",
                       GetSQLValueString($_POST['tk_doc_title'], "text"),
                       GetSQLValueString($_POST['tk_doc_class1'], "text"),
                       GetSQLValueString($_POST['tk_doc_class2'], "text"),
                       GetSQLValueString($_POST['tk_doc_create'], "text"),
                       GetSQLValueString($_POST['tk_doc_createtime'], "text"),
                       GetSQLValueString($_POST['tk_doc_edit'], "text"),
                       GetSQLValueString($_POST['tk_doc_backup1'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $newID = mysql_insert_id();
  $docID = $newID;
  $newName = $_SESSION['MM_uid'];

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 2, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_adddoc, "text"),
                       GetSQLValueString($docID, "text"));  
  $Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

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
 $insertGoTo = "log_finish.php";
//$insertGoTo = "file.php?recordID=$newID&folder=$fd&projectID=$project_id".$pf.$ptab;

  
  if (isset($_SERVER['QUERY_STRING'])) {
   // $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
  //  $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_project_file_addfolder; ?></title>
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
    <link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
	<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="srcipt/lhgcore.js"></script>
    <script type="text/javascript" src="srcipt/lhgcheck.js"></script>
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
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_doc_title', mid: 'doctitle', type: 'limit', requir: true, min: 1, max: 30, warn: '<?php echo $multilingual_announcement_titlerequired; ?>' }
	
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
			width : '92%',
			height: '246px',
			items:[
        'source', '|', 'undo', 'redo', '|',  'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', '|', 'fullscreen', 
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
  
  <table align="center" width="100%">
    <tr>
	<td width="15%" align="right">
	<?php echo $multilingual_project_file_foldertitle; ?>
	</td>
      <td>
	  <input type="text" name="tk_doc_title" id="tk_doc_title" value="" class="file_input" style="width:92%"/><span id="doctitle"></span></td>
    </tr>
   
    <tr>
	<td valign="top" align="right">
	<?php echo $multilingual_project_file_description; ?>
	</td>
      <td  class="glink">
      <textarea name="tk_doc_description" id="tk_doc_description" >

</textarea></td>
    </tr>
	
   <tr valign="baseline" style="display:none;">
      <td  class="glink">
      <input type="text" name="tk_doc_class1" id="tk_doc_class1" value="<?php echo $project_id; ?>" size="32" />      </td>
    </tr>
   
   <tr valign="baseline" style="display:none;">
      <td  class="glink">
      <input type="text" name="tk_doc_class2" id="tk_doc_class2" value="<?php echo $p_id; ?>" size="32" />      </td>
    </tr>

   
    <tr valign="baseline" style="display:none;">
      <td><input name="tk_doc_create" type="text" class="gray" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" size="32" readonly="readonly" /></td>
    </tr>
	
	<tr valign="baseline" style="display:none;">
      <td><input name="tk_doc_createtime" type="text" class="gray" value="<?php echo date("Y-m-d H:i:s"); ?>" size="32" readonly="readonly" /></td>
    </tr>
	
	<tr valign="baseline" style="display:none;">
      <td><input name="tk_doc_edit" type="text" class="gray" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" size="32" readonly="readonly" /></td>
    </tr>
	<tr valign="baseline" style="display:none;">
	  <td><input type="text" name="tk_doc_backup1" id="tk_doc_backup1" value="<?php echo $fd; ?>" size="32" /></td>
    </tr>
	
    <tr valign="baseline">
      <td colspan="2">
	  <span class="input_task_submit">
	 
<div class="float_right">	  
	  
	  <input type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  /> <input name="button" type="button" id="button" onclick="over()" value="<?php echo $multilingual_global_action_cancel; ?>" />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  </div>
	  </span>
	  </td>
    </tr>
  </table>

  <input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>