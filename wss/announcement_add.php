<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_anc_text'] ) ){
$tk_anc_text = "'',";
}else{
$tk_anc_text = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_anc_text']), "text"));
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_announcement (tk_anc_title, tk_anc_text, tk_anc_type, tk_anc_create) VALUES (%s, $tk_anc_text %s, %s)",
                       GetSQLValueString($_POST['tk_anc_title'], "text"),
					   GetSQLValueString($_POST['tk_anc_type'], "text"),
                       GetSQLValueString($_POST['tk_anc_create'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  $newID = mysql_insert_id();
  $insertGoTo = "announcement_view.php?recordID=$newID";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php require('head.php'); ?>
    <link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="srcipt/lhgcore.js"></script>
    <script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_anc_title', mid: 'anntitle', type: 'limit', requir: true, min: 2, max: 30, warn: '<?php echo $multilingual_announcement_titlerequired; ?>' }
	
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
                editor = K.create('#tk_anc_text', {
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

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
            <td ><br />
              <span class="font_big18 fontbold breakwordsfloat_left"><?php echo $multilingual_announcement_new_title; ?></span> </td>
          </tr>
          <tr>
            <td><input type="text" name="tk_anc_title" id="tk_anc_title" value="" size="50" class="width-p100-big" placeholder="<?php echo $multilingual_announcement_title; ?>" /> <span id="anntitle" class="red">*</span>  
			</td>
          </tr>
          <tr>
            <td><textarea name="tk_anc_text" id="tk_anc_text"></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      <td width="30%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td valign="top" width="20%">&nbsp;</td>
            <td valign="top" width="80%">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><?php echo $multilingual_announcement_status; ?></td>
            <td valign="top"><select name="tk_anc_type" id="tk_anc_type">
          <option value="2"><?php echo $multilingual_dd_announcement_settop; ?></option>
          <option value="1" selected="selected"><?php echo $multilingual_dd_announcement_online; ?></option>
          <option value="-1" ><?php echo $multilingual_dd_announcement_offline; ?></option>
        </select></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><span class="gray2"><?php echo $multilingual_announcement_tip; ?></span></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;			</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;			</td>
          </tr>
          <tr style="display:none">
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="tk_anc_create" type="text" class="gray" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" size="32" readonly="readonly" /></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2" class="input_task_bottom_bg" height="50px"><span class="input_task_submit">
        <div class="float_right">
         <input type="submit" value="<?php echo $multilingual_global_action_publish; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  /> <input name="button" type="button" id="button" onClick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_cancel; ?>" />
        </div>
        </span>
        <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
  </table>


  
  
</form>
<?php require('foot.php'); ?>
</body>
</html>