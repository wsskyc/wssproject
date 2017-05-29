<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = get_item( 'maxrows_announcement' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


$colname_Recordset1 = "-1";
if (isset($_GET['select1'])) {
  $colname_Recordset1 = $_GET['select1'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_announcement inner join tk_user on tk_announcement.tk_anc_create=tk_user.uid WHERE tk_anc_type <> %s ORDER BY tk_anc_lastupdate DESC", GetSQLValueString($colname_Recordset1, "text"));
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
  <br />
  <div class="pagemargin">
  <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
    <?php if ($_SESSION['MM_rank'] > "4") {  ?>
	<table class="rowcon" border="0" align="center">
      <tr>
        <td>
		<div class="search_div">
      <table width="100%">
        <tr>
          <td valign="top"><form id="form1" name="form1" method="get" action="default_announcement.php" class="saerch_form">
              <label style=" display:inline; margin-bottom:0px; ">
              <input type="checkbox" name="select1" value="+" />
              <?php echo $multilingual_announcement_list_showoff; ?></label>
              <input type="submit" name="button11" id="button11" class="button" value="<?php echo $multilingual_global_action_ok; ?>" />
            </form></td>

			<td align="right" valign="bottom"><input name="" type="button" class="button  addbutton" onclick="javascript:self.location='announcement_add.php';" value="<?php echo $multilingual_announcement_new_title; ?>" /> </td>
        </tr>
      </table>
      </div>
		</td>
      </tr>
    </table>
    <?php }  ?>
    
      <table  class="table table-striped table-hover glink" width="98%" >
        <thead>
          <tr>
            <th><?php echo $multilingual_announcement_title; ?></th>
            <th><?php echo $multilingual_announcement_publisher; ?></th>
            <th><?php echo $multilingual_announcement_status; ?></th>
            <th><?php echo $multilingual_global_lastupdate; ?></th>
          </tr>
        </thead>
		<tbody>
        <?php do { ?>
          <tr>
            <td class="task_title5"><div  class="text_overflow_450  "><a href="announcement_view.php?recordID=<?php echo $row_Recordset1['AID']; ?>" ><?php echo $row_Recordset1['tk_anc_title']; ?></a></div></td>
            <td>
			<a href="user_view.php?recordID=<?php echo $row_Recordset1['tk_anc_create']; ?>">
			<?php echo $row_Recordset1['tk_display_name']; ?></a>
			&nbsp; </td>
            <td>
			<?php
switch ($row_Recordset1['tk_anc_type'])
{

case 1:
  echo $multilingual_dd_announcement_online;
  break;
case 2:
  echo $multilingual_dd_announcement_settop;
  break;
case -1:
  echo $multilingual_dd_announcement_offline;
  break;
}

?>
			
			</td>
            <td><?php echo $row_Recordset1['tk_anc_lastupdate']; ?>&nbsp; </td>
          </tr>
          <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
		  </tbody>
      </table>

    <table class="rowcon" border="0" align="center">
      <tr>
        <td>  <table border="0">
          <tr>
            
            <td valign="bottom">
              <table border="0">
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
              </table>
            </td>
            
            
          </tr>
        </table></td>
        <td align="right" valign="bottom"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
      </tr>
    </table>
    <?php } else { // Show if recordset empty ?>  
  <div class="alert" style="margin:6px;">
    <table>
	<tr>
	<td valign="top">
	<?php echo $multilingual_announcement_none; ?>
	</td>
	
	<?php if ($_SESSION['MM_rank'] > "4") { ?>
      <td valign="top">
	  <input name="" type="button" class="button" onclick="javascript:self.location='announcement_add.php';" value="<?php echo $multilingual_announcement_new_title; ?>" />
	  </td>
	  <td valign="top">
	  <form id="form1" name="form1" method="get" action="default_announcement.php" style="margin:0px;">
              
              <input name="select1" type="checkbox" value="+" checked="checked" style="display:none;" />
              
              <input type="submit" name="button11" class="button" id="button11" value="<?php echo $multilingual_announcement_list_showoff; ?>" />
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
