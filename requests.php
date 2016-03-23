<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();

stdhead("Requests Page");

$where = "WHERE userid = " . $CURUSER["id"] . "";
$res2 = mysql_query("SELECT * FROM requests $where") or sqlerr();
$num2 = mysql_num_rows($res2);

print("<br/>");
begin_frame("Search Torrents (i.e. for the torrent before adding a request)",true,5,750);
?>

<form method="get" action="browse.php">
<input type="text" name="search" size="40" value="<?= htmlspecialchars($searchstr) ?>" />
in
<select name="cat">
<option value="0">(all types)</option>
<?
$cats = genrelist();
$catdropdown = "";
foreach ($cats as $cat) {
   $catdropdown .= "<option value=\"" . $cat["id"] . "\"";
   $catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
}
$deadchkbox = "<input type=\"checkbox\" name=\"incldead\" value=\"1\"";
if ($_GET["incldead"])
   $deadchkbox .= " checked=\"checked\"";
$deadchkbox .= " /> including dead torrents\n";
?>
<?= $catdropdown ?>
</select>
<?= $deadchkbox ?>
<input type="submit" value="Search!" style='height: 18px' />
</form>
<?
end_frame();
print("<form method=\"post\" action=\"takerequest.php\"><a name=\"add\" id=\"add\"></a>\n");
//print("<table align=center border=1 width=750 cellspacing=0 cellpadding=5>\n");
begin_frame("Adding Request",true,5,750);
begin_table(true);
print("<tr><td class=\"row2\" align=\"center\"><br/><b>Title: </b> <input type=\"text\" size=\"40\" name=\"requesttitle\" /> ");
?>
<select name="category">
<option value="0">Please Select A Category</option>
<?= $catdropdown; ?>
</select></td></tr>
<?
print("<tr><td class=\"row2\" align=\"center\"><br/>Additional Information (Optional)<br/><textarea name=\"descr\" rows=\"5\" cols=\"100\"></textarea></td></tr>\n");
print("<tr><td class=\"row2\" align=\"center\"><br/><input type=\"submit\" value=\"Submit!\" /></td></tr>\n");
end_table();
end_frame();
print("</form>\n");
stdfoot();
?>
