<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$url_project = $_SERVER["QUERY_STRING"] ;
$current_url = current(explode("&sort",$url_project));

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = get_item( 'maxrows_user' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$sortlist = "tk_user_registered";
if (isset($_GET['sort'])) {
  $sortlist = $_GET['sort'];
}

$orderlist = "DESC";
if (isset($_GET['order'])) {
  $orderlist= $_GET['order'];
}

$colrole_Recordset1 = "";
if (isset($_GET['select3'])) {
  $colrole_Recordset1 = $_GET['select3'];
}

$colrole_dis = "0";
if (isset($_GET['select1'])) {
  $colrole_dis = $_GET['select1'];
}

$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_user WHERE tk_user_rank LIKE %s AND tk_user_rank NOT LIKE %s AND tk_display_name LIKE %s ORDER BY %s %s", GetSQLValueString("%" . $colrole_Recordset1 . "%", "text"),
GetSQLValueString("%" . $colrole_dis . "%", "text"), 
GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
							GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>
<?php require('head.php'); ?>
<script type="text/JavaScript">
<!--
function GP_popupConfirmMsg(msg) { //v1.0
  document.MM_returnValue = confirm(msg);
}
//-->
</script>
<br />
<div class="pagemargin">
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
<table class="rowcon" border="0" align="center">
  <tr>
    <td>
	<div class="search_div">
      <table width="100%">
        <tr>
         
          <td>

		   
		  <form id="form1" name="form1" method="get" action="default_user.php"  class="saerch_form">
              <?php if ($_SESSION['MM_rank'] > "4") {  ?>
			  <select name="select3" id="select3">
                <option value="" selected="selected"><?php echo $multilingual_user_selectrole; ?></option>
                <option value="0" ><?php echo $multilingual_dd_role_disabled; ?></option>
                <option value="1" ><?php echo $multilingual_dd_role_readonly; ?></option>
                <option value="2" ><?php echo $multilingual_dd_role_guest; ?></option>
                <option value="3" ><?php echo $multilingual_dd_role_general; ?></option>
                <option value="4" ><?php echo $multilingual_dd_role_pm; ?></option>
                <option value="5" ><?php echo $multilingual_dd_role_admin; ?></option>	
              </select> &nbsp;&nbsp;&nbsp;&nbsp;
              <label style=" display:inline; margin-bottom:0px; ">
              <input type="checkbox" name="select1" value="+" />
              <?php echo $multilingual_user_list_showdis; ?></label> &nbsp;&nbsp;&nbsp;&nbsp;
			  <?php }  ?>
			   <?php echo $multilingual_user_list_search; ?>:<input type="text" name="inputtitle" id="inputtitle">
              <input type="submit" name="button11" id="button11" value="<?php echo $multilingual_global_action_ok; ?>" class="button" />
            </form>
			
			
			</td>
			<?php if ($_SESSION['MM_rank'] > "4") {  ?> 
	<td align="right"><input name="" type="button" onClick="javascript:self.location='user_add.php';" value="<?php echo $multilingual_user_new; ?>" class="button addbutton" />          
	</td>
    <?php }  ?>
        </tr>

      </table>
	  </div>
      
    </td>
    
  </tr>
</table>

  <table class="table table-striped table-hover glink" width="98%" >
    <thead>
      <tr>
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_display_name&order=<?php 
	  if ( $sortlist <> "tk_display_name"){
	  echo "DESC";
	  }else if( $sortlist == "tk_display_name" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_display_name" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_display_name" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_title; ?></a></th>
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_rank&order=<?php 
	  if ( $sortlist <> "tk_user_rank"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_rank" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_rank" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_rank" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_role; ?></a></th>
		
		<th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_contact&order=<?php 
	  if ( $sortlist <> "tk_user_contact"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_contact" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_contact" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_contact" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_contact; ?></a></th>
		
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_email&order=<?php 
	  if ( $sortlist <> "tk_user_email"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_email" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_email" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_email" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_email; ?></a></th>
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_registered&order=<?php 
	  if ( $sortlist <> "tk_user_registered"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_registered" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_registered" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_registered" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_global_lastupdate; ?></a></th>
      </tr>
    </thead>
    <?php do { ?>
      <tr>
        <td><a href="user_view.php?recordID=<?php echo $row_Recordset1['uid']; ?>"><?php echo $row_Recordset1['tk_display_name']; ?></a></td>
        <td>
		<?php
switch ($row_Recordset1['tk_user_rank'])
{
case 0:
  echo $multilingual_dd_role_disabled;
  break;
case 1:
  echo $multilingual_dd_role_readonly;
  break;
case 2:
  echo $multilingual_dd_role_guest;
  break;
case 3:
  echo $multilingual_dd_role_general;
  break;
case 4:
  echo $multilingual_dd_role_pm;
  break;
case 5:
  echo $multilingual_dd_role_admin;
  break;
}
?>
		</td>
		<td><?php echo $row_Recordset1['tk_user_contact']; ?>&nbsp;</td>
        <td><a href="mailto:<?php echo $row_Recordset1['tk_user_email']; ?>"><?php echo $row_Recordset1['tk_user_email']; ?></a>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_registered']; ?></td>
      </tr>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
  </table>

<table class="rowcon" border="0" align="center">
  <tr>
    <td><table border="0">
        <tr>
          <td>&nbsp;</td>
          <td><table border="0">
              <tr>
                <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><?php echo $multilingual_global_first; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_previous; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_next; ?></a>
                    <?php } // Show if not last page ?></td>
                <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><?php echo $multilingual_global_last; ?></a>
                    <?php } // Show if not last page ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td align="right"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
  </tr>
</table>
<?php } else { // Show if recordset empty ?>  
  <div class="ui-widget"  style="margin-left:5px;">
    <div class="ui-state-highlight fontsize-s" style=" padding: 5px; width:340px;"> 
      <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
    <table>
	<tr>
	<td valign="top">
	<?php echo $multilingual_user_sorrytip; ?>
	</td>
	
	<?php if ($_SESSION['MM_rank'] > "4") { ?>
      <td valign="top">
	  <input name="" type="button" onClick="javascript:self.location='user_add.php';" value="<?php echo $multilingual_user_new; ?>" class="button" /> 
	  </td>
	  <td valign="top">
	  <form id="form1" name="form1" method="get" action="default_user.php">
              
              <input name="select1" type="checkbox" value="+" checked="checked" style="display:none;" />
              
              <input type="submit" name="button11" class="button" id="button11" value="<?php echo $multilingual_user_list_showdis; ?>" />
      </form>
	  </td>
      <?php }  ?>
	</tr>
	</table>
	</div>
  </div>
  </div>
<?php } // Show if recordset empty ?>  
<p>&nbsp;</p>
</div><!--pagemargin结束 -->
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
