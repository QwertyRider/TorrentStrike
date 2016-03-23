<?

require "include/bittorrent.php";



loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
  die;

$remove = $_GET['remove'];
if (is_valid_id($remove))
{
  mysql_query("DELETE FROM bans WHERE id=$remove") or sqlerr();
  write_log("Ban $remove was removed by $CURUSER[id] ($CURUSER[username])");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && get_user_class() >= UC_ADMINISTRATOR)
{
	$first = trim($_POST["first"]);
	$last = trim($_POST["last"]);
	$comment = trim($_POST["comment"]);
	if (!$first || !$last || !$comment)
		stderr("Error", "Missing form data.");
	$first = ip2long($first);
	$last = ip2long($last);
	if ($first == -1 || $last == -1)
		stderr("Error", "Bad IP address.");
	$comment = sqlesc($comment);
	$added = sqlesc(get_date_time());
	mysql_query("INSERT INTO bans (added, addedby, first, last, comment) VALUES($added, $CURUSER[id], $first, $last, $comment)") or sqlerr(__FILE__, __LINE__);
	header("Location: $BASEURL$_SERVER[REQUEST_URI]");
	die;
}

ob_start("ob_gzhandler");

$res = mysql_query("SELECT * FROM bans ORDER BY added DESC") or sqlerr();

stdhead("Bans");

print("<h1>Current Bans</h1>\n");

if (mysql_num_rows($res) == 0)
  print("<p align=\"center\"><b>Nothing found</b></p>\n");
else
{
  //print("<table border=1 cellspacing=0 cellpadding=5>\n");
  begin_frame("Bans IP Range",true,5,false);
  begin_table();
  print("<tr><td class=\"colhead\">Added</td><td class=\"colhead\" align=\"left\">First IP</td><td class=\"colhead\" align=\"left\">Last IP</td>".
    "<td class=\"colhead\" align=\"left\">By</td><td class=\"colhead\" align=\"left\">Comment</td><td class=\"colhead\">Remove</td></tr>\n");

  while ($arr = mysql_fetch_assoc($res))
  {
  	$r2 = mysql_query("SELECT username FROM users WHERE id=$arr[addedby]") or sqlerr();
  	$a2 = mysql_fetch_assoc($r2);
	$arr["first"] = long2ip($arr["first"]);
	$arr["last"] = long2ip($arr["last"]);
 	  print("<tr><td class=\"row1\">$arr[added]</td><td class=\"row1\" align=\"left\">$arr[first]</td><td  class=\"row1\" align=\"left\">$arr[last]</td><td  class=\"row1\" align=\"left\"><a href=\"userdetails.php?id=$arr[addedby]\">$a2[username]".
 	    "</a></td><td  class=\"row1\" align=\"left\">$arr[comment]</td><td  class=\"row1\"><a href=\"bans.php?remove=$arr[id]\">Remove</a></td></tr>\n");
  }
  end_table();
  end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR)
{
	print("<h2>Add ban</h2>\n");
	//print("<table border=1 cellspacing=0 cellpadding=5>\n");
  begin_frame("Bans IP Range",true,5,false);
  print("<form method=\"post\" action=\"bans.php\">\n");
  begin_table();
	print("<tr><td class=\"rowhead\">First IP</td><td class=\"row1\"><input type=\"text\" name=\"first\" size=\"40\"/></td></tr>\n");
	print("<tr><td class=\"rowhead\">Last IP</td><td class=\"row1\"><input type=\"text\" name=\"last\" size=\"40\"/></td></tr>\n");
	print("<tr><td class=\"rowhead\">Comment</td><td class=\"row1\"><input type=\"text\" name=\"comment\" size=\"40\"/></td></tr>\n");
	print("<tr><td class=\"row1\" align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"Okay\" class=\"btn\"/></td></tr>\n");
	end_table();
	print("</form>\n");
	end_frame();
}

stdfoot();

?>