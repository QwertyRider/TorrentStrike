<?
///////////////////////////////////////////////////////////////////
//////                                                       //////
//////            New Control Panel By Madsurfer             //////
//////              For Torrent Strike Source                //////
//////          Based on the existant control panel,         //////
//////              And on the control panel for             //////
//////  Sys.Administrators, Administrators and Moderators.   //////
//////  made by Nazaret2005. All credits go to Nazaret2005   //////
//////                                                       //////
///////////////////////////////////////////////////////////////////
require "include/bittorrent.php";
dbconn();
loggedinorreturn();


///////////////////// Add new options \\\\\\\\\\\\\\\\\\\\\\\\\\\\
if (get_user_class() >= UC_SYSOP) {
$add = $_GET['add'];
if($add == 'true') {
$mod_name = $_GET['mod_name'];
$mod_url = $_GET['mod_url'];
$mod_info = $_GET['mod_info'];
$cppanel = $_GET['cppanel'];
$query = "INSERT INTO $cppanel SET
name = '$mod_name',
url = '$mod_url',
info = '$mod_info'";
$sql = mysql_query($query);
if($sql) {
$success = TRUE;
} else {
$success = FALSE;
}
}
if($success == TRUE) {
header("Location:  " . $_SERVER['PHP_SELF'] . "");
}
}

stdhead("Staff");


if (get_user_class() < UC_MODERATOR)
{
  stdmsg("Excuse...", "Access is not present!!!");
  stdfoot();
  exit;
}




// ----------------------- Quick Search ----------------

if (isset($_GET['act'])) $act=htmlspecialchars(stripslashes($_GET['act']));

// ------------------------------------------------

if ($act == "last") {
begin_frame("Latest users");

echo '<table width="640"  border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>User</td><td class=colhead>Ratio</td><td class=colhead>IP</td><td class=colhead>Date Joined</td><td class=colhead>Last Access</td><td class=colhead>Download</td><td class=colhead>Upload</td></tr>";

$result = mysql_query ("SELECT  * FROM users WHERE enabled =  'yes' AND status = 'confirmed' ORDER BY added DESC limit 100");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);

$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); 
stdfoot();
die();
}


// ------------------------------------------------

if ($act == "forum") {

// SHOW FORUMS WITH FORUM MANAGMENT TOOLS
begin_frame("Forums");
?>
<script language="JavaScript">
<!--
function confirm_delete(id)
{
   if(confirm('Are you sure you want to delete this forum?'))
   {
      self.location.href='<? $PHP_SELF; ?>?action=del&id='+id;
   }
}
//-->
</script>
<?
echo '<table width="700"  border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>Name</td><td class=colhead>Topics</td><td class=colhead>Posts</td><td class=colhead>Read</td><td class=colhead>Write</td><td class=colhead>Create topic</td><td class=colhead>Modify</td></tr>";
$result = mysql_query ("SELECT  * FROM forums ORDER BY sort ASC");
if ($row = mysql_fetch_array($result)) {
do {
//if ($row["uploaded"] == "0" || $row["downloaded"] == "0") { $ratio = "inf"; } else {
//$ratio = $row["uploaded"] / $row["downloaded"];
//$ratio = number_format($ratio, 2);
//}
echo "<tr><td><a href=forums.php?action=viewforum&amp;forumid=".$row["id"]."><b>".$row["name"]."</b></a><br/>".$row["description"]."</td>";
echo "<td>".$row["topiccount"]."</td><td>".$row["postcount"]."</td><td>minimal " . get_user_class_name($row["minclassread"]) . "</td><td>minimal " . get_user_class_name($row["minclasswrite"]) . "</td><td>minimal " . get_user_class_name($row["minclasscreate"]) . "</td><td align=center nowrap><b><a href=\"".$PHP_SELF."?act=editforum&amp;id=".$row["id"]."\">EDIT</a>&nbsp;|&nbsp;<a href=\"javascript:confirm_delete('".$row["id"]."');\"><font color=red>DELETE</font></a></b></td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
?>
<br/><br/>
<form method=post action="<?=$PHP_SELF;?>">
<table width="600"  border="0" cellspacing="0" cellpadding="3" align="center">
<tr align="center">
    <td colspan="2" class=colhead>Make new forum</td>
  </tr>
  <tr>
    <td><b>Forum name</td>
    <td><input name="name" type="text" size="20" maxlength="60"/></td>
  </tr>
  <tr>
    <td><b>Forum description  </td>
    <td><input name="desc" type="text" size="30" maxlength="200"/></td>
  </tr>
    <tr>
    <td><b>Minimun read permission </td>
    <td>
    <select name=readclass>\n
    <?
             $maxclass = get_user_class();
          for ($i = 0; $i <= $maxclass; ++$i)
          {
          	$currentclass = get_user_class_name($i);
          	if ($currentclass)
            	print("<option value=$i" . ($user["class"] == $i ? " selected" : "") . ">$prefix" . $currentclass . "\n");
          }
?>
        </select>
    </td>
  </tr>
  <tr>
    <td><b>Minimun write permission </td>
    <td><select name=writeclass>\n
    <?
          $maxclass = get_user_class();
          for ($i = 0; $i <= $maxclass; ++$i)
          {
						$currentclass = get_user_class_name($i);
						if ($currentclass)
            	print("<option value=$i" . ($user["class"] == $i ? " selected" : "") . ">$prefix" . $currentclass . "\n");
          }
?>
        </select></td>
  </tr>
  <tr>
    <td><b>Minimun create topic permission </td>
    <td><select name=createclass>\n
    <?
            $maxclass = get_user_class();
          for ($i = 0; $i <= $maxclass; ++$i)
          {
						$currentclass = get_user_class_name($i);
						if ($currentclass)
            	print("<option value=$i" . ($user["class"] == $i ? " selected" : "") . ">$prefix" . $currentclass . "\n");
          }
?>
        </select></td>
  </tr>
    <tr>
    <td><b>Forum rank </td>
    <td>
    <select name=sort>\n
    <?
$res = mysql_query ("SELECT sort FROM forums");
$nr = mysql_num_rows($res);
            $maxclass = $nr + 1;
          for ($i = 0; $i <= $maxclass; ++$i)
            print("<option value=$i>$i \n");
?>
        </select>


    </td>
  </tr>

  <tr align="center">
    <td colspan="2"><input type="hidden" name="action" value="addforum"/><input type="submit" name="Submit" value="Make forum"/></td>
  </tr>
</table>

<?end_frame(); 
stdfoot();
die();
}?>



<? // ------------------------------------------------

if ($act == "editforum") {

//EDIT PAGE FOR THE FORUMS
$id = $_GET["id"];
begin_frame("Edit Forum");
$result = mysql_query ("SELECT * FROM forums where id = '$id'");
if ($row = mysql_fetch_array($result)) {
do {
?>

<form method=post action="<?=$PHP_SELF;?>">
<table width="600"  border="0" cellspacing="0" cellpadding="3" align="center">
<tr align="center">
    <td colspan="2" class=colhead>edit forum: <?=$row["name"];?></td>
  </tr>
  <tr>
    <td><b>Forum name</td>
    <td><input name="name" type="text" size="20" maxlength="60" value="<?=$row["name"];?>"/></td>
  </tr>
  <tr>
    <td><b>Forum description  </td>
    <td><input name="desc" type="text" size="30" maxlength="200" value="<?=$row["description"];?>"/></td>
  </tr>
    <tr>
    <td><b>Minimun read permission </td>
    <td>
    <select name=readclass>\n
    <?
          $maxclass = get_user_class();
          for ($i = 0; $i <= $maxclass; ++$i)
          {
						$currentclass = get_user_class_name($i);
						if ($currentclass)
            	print("<option value=$i" . ($row["minclassread"] == $i ? " selected" : "") . ">$prefix" . $currentclass . "\n");
          }
?>
        </select>
    </td>
  </tr>
  <tr>
    <td><b>Minimun write permission </td>
    <td><select name=writeclass>\n
    <?
          $maxclass = get_user_class();
          for ($i = 0; $i <= $maxclass; ++$i)
          {
						$currentclass = get_user_class_name($i);
						if ($currentclass)
            	print("<option value=$i" . ($row["minclasswrite"] == $i ? " selected" : "") . ">$prefix" . $currentclass . "\n");
          }
?>
        </select></td>
  </tr>
  <tr>
    <td><b>Minimun create topic permission </td>
    <td><select name=createclass>\n
    <?
            $maxclass = get_user_class();
          for ($i = 0; $i <= $maxclass; ++$i)
          {
						$currentclass = get_user_class_name($i);
						if ($currentclass)
            	print("<option value=$i" . ($row["minclasscreate"] == $i ? " selected" : "") . ">$prefix" . $currentclass . "\n");
          }
?>
        </select></td>
  </tr>
    <tr>
    <td><b>Forum rank </td>
    <td>
    <select name=sort>\n
    <?
$res = mysql_query ("SELECT sort FROM forums");
$nr = mysql_num_rows($res);
            $maxclass = $nr + 1;
          for ($i = 0; $i <= $maxclass; ++$i)
            print("<option value=$i" . ($row["sort"] == $i ? " selected" : "") . ">$i \n");
?>
        </select>


    </td>
  </tr>

  <tr align="center">
    <td colspan="2"><input type="hidden" name="action" value="editforum"/><input type="hidden" name="id" value="<?=$id;?>"/><input type="submit" name="Submit" value="Edit forum"/></td>
  </tr>
</table>

<?
} while($row = mysql_fetch_array($result));
} else {print "Sorry, no records were found!";}
end_frame(); 
stdfoot();
die();
}?>


<?//---------------------------


// LIST OF THE MOD TOOLS (ONLY VISIBLE WHEN YOU ARE MOD, ELSE YOU ONLY SEE LIST OF MODS
begin_frame("..:: Administration ::..",true,10,false);

print("<br/>");

///////////////////// Remove And Edit Options Sys.Admin \\\\\\\\\\\\\\\\\\\\\\\\\\\\

$sure = $_GET['sure'];
if($sure == "yes") {
$delsosadminid = $_GET['delsosadminid'];
$query = "DELETE FROM sysoppanel WHERE id=" .sqlesc($delsosadminid) . " LIMIT 1";
$sql = mysql_query($query);
echo("The option is successfully removed!<br/>[ <a href='". $_SERVER['PHP_SELF'] . "'>Back</a> ]");
end_frame();
stdfoot();
die();
}
$delsosadminid = $_GET['delsosadminid'];
$name = $_GET['mod'];
if($delsosadminid > 0) {
echo("Only Sys.Admin is able to do it");
if (get_user_class() >= UC_SYSOP) {
echo("<p>You and in the truth wish to remove an option? ($name)</p> ( <strong><a href='". $_SERVER['PHP_SELF'] . "?delsosadminid=$delsosadminid&amp;mod=$name&amp;sure=yes'>Yes!</a></strong> / <strong><a href='". $_SERVER['PHP_SELF'] . "'>No!</a></strong> )");
}
end_frame();
stdfoot();
die();
}

$editsosadmin = $_GET['editsosadmin'];
if($editsosadmin == 1) {
$id = $_GET['id'];
$mod_name = $_GET['mod_name'];
$mod_url = $_GET['mod_url'];
$mod_info = $_GET['mod_info'];
$query = "UPDATE sysoppanel SET
name = '$mod_name',
url = '$mod_url',
info = '$mod_info'
WHERE id=".sqlesc($id);
$sql = mysql_query($query);
if($sql) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("<table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<tr><td><div align='center'><strong>It is successfully changed</strong><br/>[ <a href='". $_SERVER['PHP_SELF'] . "'>Back</a> ]</div></tr>");
echo("</table>");
}
end_frame();
stdfoot();
die();
}
}
$editsosadminid = $_GET['editsosadminid'];
$name = $_GET['name'];
$url = $_GET['url'];
$info = $_GET['info'];
if($editsosadminid > 0) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<div align='center'><input type='hidden' name='editsosadmin' value='1'/>At present you change an option <strong>&quot;$name&quot;</strong></div>");
echo("<br/>");
echo("<input type='hidden' name='id' value='$editsosadminid'/><table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<tr><td>Option: </td><td align='right'><input type='text' size='50' name='mod_name' value='$name'/></td></tr>");
echo("<tr><td>URL-file: </td><td align='right'><input type='text' size='50' name='mod_url' value='$url'/></td></tr>");
echo("<tr><td>Info: </td><td align='right'><input type='text' size='50' name='mod_info' value='$info'/></td></tr>");
echo("<tr><td></td><td><div align='right'><input type='submit' value='Change'/></div></td></tr>");
echo("</table></form>");
}
end_frame();
stdfoot();
die();
}
///////////////////// Remove And Edit Options Admin \\\\\\\\\\\\\\\\\\\\\\\\\\\\

$suree = $_GET['suree'];
if($suree == "yes") {
$deladminid = $_GET['deladminid'];
$query = "DELETE FROM adminpanel WHERE id=" .sqlesc($deladminid) . " LIMIT 1";
$sql = mysql_query($query);
echo("The option is successfully removed!<br/>[ <a href='". $_SERVER['PHP_SELF'] . "'>Back</a> ]");
end_frame();
stdfoot();
die();
}
$deladminid = $_GET['deladminid'];
$nameadmin = $_GET['admin'];
if($deladminid > 0) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("You and in the truth wish to remove an option? ($nameadmin) ( <strong><a href='". $_SERVER['PHP_SELF'] . "?deladminid=$deladminid&amp;admin=$nameadmin&amp;suree=yes'>Yes!</a></strong> / <strong><a href='". $_SERVER['PHP_SELF'] . "'>No!</a></strong> )");
}
end_frame();
stdfoot();
die();
}

$editadmin = $_GET['editadmin'];
if($editadmin == 1) {
$id = $_GET['id'];
$mod_name = $_GET['mod_name'];
$mod_url = $_GET['mod_url'];
$mod_info = $_GET['mod_info'];
$query = "UPDATE adminpanel SET
name = '$mod_name',
url = '$mod_url',
info = '$mod_info'
WHERE id=".sqlesc($id);
$sql = mysql_query($query);
if($sql) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("<table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<tr><td><div align='center'><strong>It is successfully changed</strong><br/>[ <a href='". $_SERVER['PHP_SELF'] . "'>Back</a> ]</div></tr>");
echo("</table>");
}
end_frame();
stdfoot();
die();
}
}

$editadminid = $_GET['editadminid'];
$name = $_GET['name'];
$url = $_GET['url'];
$info = $_GET['info'];
if($editadminid > 0) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<div align='center'><input type='hidden' name='editadmin' value='1'/>At present you change an option <strong>&quot;$name&quot;</strong></div>");
echo("<br/>");
echo("<input type='hidden' name='id' value='$editadminid'/><table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<tr><td>Option: </td><td align='right'><input type='text' size='50' name='mod_name' value='$name'/></td></tr>");
echo("<tr><td>URL-file: </td><td align='right'><input type='text' size='50' name='mod_url' value='$url'/></td></tr>");
echo("<tr><td>Info: </td><td align='right'><input type='text' size='50' name='mod_info' value='$info'/></td></tr>");
echo("<tr><td></td><td><div align='right'><input type='submit' value='Change'/></div></td></tr>");
echo("</table></form>");
}
end_frame();
stdfoot();
die();
}

///////////////////// Remove And Edit Options Moderator \\\\\\\\\\\\\\\\\\\\\\\\\\\\

$sureee = $_GET['sureee'];
if($sureee == "yes") {
$delmodid = $_GET['delmodid'];
$query = "DELETE FROM modpanel WHERE id=" .sqlesc($delmodid) . " LIMIT 1";
$sql = mysql_query($query);
echo("The option is successfully removed!<br/>[ <a href='". $_SERVER['PHP_SELF'] . "'>Back</a> ]");
end_frame();
stdfoot();
die();
}
$delmodid = $_GET['delmodid'];
$namemod = $_GET['mod'];
if($delmodid > 0) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("You and in the truth wish to remove an option? ($namemod) ( <strong><a href='". $_SERVER['PHP_SELF'] . "?delmodid=$delmodid&mod=$namemod&sureee=yes'>Yes!</a></strong> / <strong><a href='". $_SERVER['PHP_SELF'] . "'>No!</a></strong> )");
}
end_frame();
stdfoot();
die();
}

$editmod = $_GET['editmod'];
if($editmod == 1) {
$id = $_GET['id'];
$mod_name = $_GET['mod_name'];
$mod_url = $_GET['mod_url'];
$mod_info = $_GET['mod_info'];
$query = "UPDATE modpanel SET
name = '$mod_name',
url = '$mod_url',
info = '$mod_info'
WHERE id=".sqlesc($id);
$sql = mysql_query($query);
if($sql) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("<table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<tr><td><div align='center'><strong>It is successfully changed</strong><br/>[ <a href='". $_SERVER['PHP_SELF'] . "'>Back</a> ]</div></tr>");
echo("</table>");
}
end_frame();
stdfoot();
die();
}
}

$editmodid = $_GET['editmodid'];
$name = $_GET['name'];
$url = $_GET['url'];
$info = $_GET['info'];
if($editmodid > 0) {
echo("Only Sys.Admin is able to do it<p>");
if (get_user_class() >= UC_SYSOP) {
echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<div align='center'><input type='hidden' name='editmod' value='1'/>At present you change an option <strong>&quot;$name&quot;</strong></div>");
echo("<br/>");
echo("<input type='hidden' name='id' value='$editmodid'/><table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<tr><td>Option: </td><td align='right'><input type='text' size='50' name='mod_name' value='$name'/></td></tr>");
echo("<tr><td>URL-file: </td><td align='right'><input type='text' size='50' name='mod_url' value='$url'/></td></tr>");
echo("<tr><td>Info: </td><td align='right'><input type='text' size='50' name='mod_info' value='$info'/></td></tr>");
echo("<tr><td></td><td><div align='right'><input type='submit' value='Change'/></div></td></tr>");
echo("</table></form>");
}
end_frame();
stdfoot();
die();
}

///////////////////// Add new options \\\\\\\\\\\\\\\\\\\\\\\\\\\\
if (get_user_class() >= UC_SYSOP) {
print("<strong>Add new option:</strong>");
print("<br />");
print("<br />");

echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=\"main\" cellspacing=\"0\" cellpadding=\"5\" width=\"50%\">");
echo("<tr><td>Name: </td><td align='left'><input type='text' size='50' name='mod_name'/></td></tr>");
echo("<tr><td>URL-file: </td><td align='left'><input type='text' size='50' name='mod_url'/></td></tr>");
echo("<tr><td>Info: </td><td align='left'><input type='text' size='50' name='mod_info'/></td></tr>");
echo("<tr><td>Option for: </td><td align='left'><select name='cppanel'><option value='sysoppanel'>Sys.Admins</option><option value='adminpanel'>Admins</option><option value='modpanel'>Moderators</option></select><input type='hidden' name='add' value='true'/></td></tr>");
echo("<tr><td></td><td><div align='left'><input value='Add' type='submit'/></div></td></tr>");
echo("</table>");
echo("</form>");
}



///////////////////// Sys.Admin Only \\\\\\\\\\\\\\\\\\\\\\\\\\\\
if (get_user_class() >= UC_SYSOP) {
print("<br />");
//print("<table border=\"1\" class=\"row2\" cellspacing=\"0\" cellpadding=\"5\">");
begin_frame("..:: For Sys.Admin Only  ::..",true,10,false);
begin_table();
echo("<tr><td class=\"row2\">The name an option and URL:</td><td class=\"row2\">Info:</td><td class=\"row2\">Change:</td><td class=\"row2\">Remove:</td></tr>");
$query = "SELECT * FROM sysoppanel WHERE 1=1";
$sql = mysql_query($query);
while ($row = mysql_fetch_array($sql)) {
$id = $row['id'];
$name = $row['name'];
$url = $row['url'];
$info = $row['info'];

echo("<tr><td class=\"row2\"><strong><a class=\"altlink\" href=\"$url\">$name</a></strong></td> <td class=\"row2\">$info</td> <td class=\"row2\"><div align='center'><a href='". $_SERVER['PHP_SELF'] . "?editsosadminid=$id&amp;name=$name&amp;url=$url&amp;info=$info'><img src='$BASEURL/pic/multipage.gif' alt='Change' border='0' align='middle' /></a></div></td> <td class=\"row2\"><div align='center'><a href='". $_SERVER['PHP_SELF'] . "?delsosadminid=$id&amp;mod=$name'><img src='$BASEURL/pic/warned2.gif' alt='Remove' border='0' align='middle' /></a></div></td></tr>");
}
end_table();
end_frame();
}

///////////////////// Admin Only \\\\\\\\\\\\\\\\\\\\\\\\\\\\
if (get_user_class() >= UC_ADMINISTRATOR) {
print("<br />");
//print("<table border=\"1\" class=\"row2\" cellspacing=\"0\" cellpadding=\"5\">");
begin_frame("..:: For Admin Only :..",true,10,false);
begin_table();
echo("<tr><td class=\"row2\">The name an option and URL:</td><td class=\"row2\">Info:</td><td class=\"row2\">Change:</td><td class=\"row2\">Remove:</td></tr>");
$query = "SELECT * FROM adminpanel WHERE 1=1";
$sql = mysql_query($query);
while ($row = mysql_fetch_array($sql)) {
$id = $row['id'];
$name = $row['name'];
$url = $row['url'];
$info = $row['info'];

echo("<tr><td class=\"row2\"><strong><a class=\"altlink\" href=\"$url\">$name</a></strong></td> <td class=\"row2\">$info</td> <td class=\"row2\"><div align='center'><a href='". $_SERVER['PHP_SELF'] . "?editadminid=$id&amp;name=$name&amp;url=$url&amp;info=$info'><img src='$BASEURL/pic/multipage.gif' alt='Change' border='0' align='middle' /></a></div></td> <td class=\"row2\"><div align='center'><a href='". $_SERVER['PHP_SELF'] . "?deladminid=$id&amp;admin=$name'><img src='$BASEURL/pic/warned2.gif' alt='Remove' border='0' align='middle' /></a></div></td></tr>");

}
end_table();
end_frame();
}

///////////////////// Moderator Only \\\\\\\\\\\\\\\\\\\\\\\\\\\\

print("<br />");
//print("<table border=\"1\" class=\"row2\" cellspacing=\"0\" cellpadding=\"5\">");
begin_frame("..:: For Moderator Only  ::..",true,10,false);
begin_table();
echo("<tr><td class=\"row2\">The name an option and URL:</td><td class=\"row2\">Info:</td><td class=\"row2\">Change:</td><td class=\"row2\">Remove:</td></tr>");
$query = "SELECT * FROM modpanel WHERE 1=1";
$sql = mysql_query($query);
while ($row = mysql_fetch_array($sql)) {
$id = $row['id'];
$name = $row['name'];
$url = $row['url'];
$info = $row['info'];

echo("<tr><td class=\"row2\"><a class=\"altlink\" href=\"$url\">$name</a></td> <td class=\"row2\">$info</td> <td class=\"row2\"><div align='center'><a href='". $_SERVER['PHP_SELF'] . "?editmodid=$id&amp;name=$name&amp;url=$url&amp;info=$info'><img src='$BASEURL/pic/multipage.gif' alt='Change' border='0' align='middle'/></a></div></td> <td class=\"row2\"><div align='center'><a href='". $_SERVER['PHP_SELF'] . "?delmodid=$id&amp;mod=$name'><img src='$BASEURL/pic/warned2.gif' alt='Remove' border='0' align='middle' /></a></div></td></tr>");
}
end_table();
end_frame();
print("<br />");


end_frame();
echo "<br/>";


// --------------
stdfoot();
?>