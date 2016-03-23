<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead("Request Details");

$id = $_GET["id"];
$res = mysql_query("SELECT * FROM requests WHERE id = $id") or sqlerr();
$num = mysql_fetch_array($res);
$s = $num["request"];

print("<h1>Details Of $s</h1>\n");

//print("<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
begin_frame("Details Of $s",true,5,600);
begin_table();

print("<tr><td class=\"row2\" align=\"left\">Request</td><td width=\"90%\" class=\"row1\" align=\"left\">$num[request]</td></tr>");
if ($num["descr"])
print("<tr><td class=\"row2\" align=\"left\">Info</td><td width=\"90%\" class=\"row1\" align=\"left\">$num[descr]</td></tr>");
print("<tr><td class=\"row2\" align=\"left\">Added</td><td width=\"90%\" class=\"row1\" align=\"left\">$num[added]</td></tr>");

$cres = mysql_query("SELECT username FROM users WHERE id=$num[userid]");
   if (mysql_num_rows($cres) == 1)
   {
     $carr = mysql_fetch_assoc($cres);
     $username = "$carr[username]";
   }
print("<tr><td class=\"row2\" align=\"left\">Requested&nbsp;By</td><td class=\"row1\" width=\"90%\" align=\"left\">$username</td></tr>");

if ($num["filled"] != NULL)
{
print("<tr><td class=\"row2\" align=\"left\"><b><b>Filled Date</b></b></td><td class=\"row1\" width=\"90%\" align=\"left\"><b>$num[filldate]</b></td></tr>");

$cres = mysql_query("SELECT username FROM users WHERE id=$num[filledby]");
   if (mysql_num_rows($cres) == 1)
   {
     $carr = mysql_fetch_assoc($cres);
     $username = "$carr[username]";
   }

print("<tr><td class=\"row2\" align=\"left\"><b>Filled By</b></td><td class=\"row1\" width=\"90%\" align=\"left\"><b>$username</b></td></tr>");
end_table();

}

if ($num["filled"] == NULL)
{
print("<tr><td class=\"row2\" align=\"left\">Vote for this request</td><td class=\"row1\" width=\"90%\" align=\"left\"><a href=\"addrequest.php?id=$id\"><b>Vote</b></a></td></tr>");
print("<tr><td class=\"row2\" align=\"left\">Filled Request</td><td class=\"row1\">Enter the <b>full</b> URL of the torrent i.e. " . $BASEURL. "/details.php?id=1 (just copy/paste from another window/tab) or modify the existing URL to have the correct ID number</td></tr>");
//print("</table>");
end_table();


if (get_user_class() >= UC_UPLOADER) {
print("<br/><form method=\"get\" action=\"reqfilled.php\">");
begin_table(true);
print("<tr><td class=\"row2\" align=\"left\"><input type=\"text\" size=\"80\" name=\"filledurl\" value=\"" . $BASEURL. "/details.php?id=\" />\n");
print("<input type=\"hidden\" value=\"$id\" name=\"requestid\" />");
print("<input type=\"submit\" value=\"Fill Request\" /></td></tr>\n");
end_table();
print("</form>");
}

}
end_frame();
stdfoot();
?>
