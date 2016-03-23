<?
 require "include/bittorrent.php";
 dbconn(false);

 loggedinorreturn();
if (get_user_class() < UC_MODERATOR)
       stderr("Error", "Permission denied.can only be seen by a mod or higher.");

$res2 = mysql_query("SELECT agent FROM peers  GROUP BY agent ") or sqlerr();
stdhead("All Clients");


print("<table border=3 cellspacing=0 cellpadding=2>\n");
print("<tr><td class=colhead>Client</td></tr>\n");
while($arr2 = mysql_fetch_assoc($res2))
{
print("</a></td><td align=left>$arr2[agent]</td></tr>\n");
}
print("</table>\n");

 stdfoot();

?>
