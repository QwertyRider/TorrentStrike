<?
require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied.");

$action = $HTTP_GET_VARS["action"];

///////////////////////////
// SHOW PM'S //
/////////////////////////

if (!$action) {

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied.");

stdhead("Staff PM's");

$res = mysql_query("SELECT count(id) FROM staffmessages") or die(mysql_error());
$row = mysql_fetch_array($res);

$url = " .$_SERVER[PHP_SELF]?";
$count = $row[0];
$perpage = 20;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $url);

print("<h1 align=\"center\">Staff PM's</h1>");

if ($count == 0) {
print("<h2>No messages yet!</h2>");
}
else {

echo $pagertop;


begin_main_frame();

begin_frame("Staff PM's",true,5,765);
print("<form method=\"post\" action=\"?action=takecontactanswered\">");
begin_table(true);

print("
<tr>
<td class=\"colhead\" align=\"left\">Subject</td>
<td class=\"colhead\" align=\"left\">Sender</td>
<td class=\"colhead\" align=\"left\">Added</td>
<td class=\"colhead\" align=\"left\">Answered</td>
<td class=\"colhead\" align=\"center\">Set Answered</td>
<td class=\"colhead\" align=\"left\">Del</td>
</tr>
");
$res = mysql_query("SELECT staffmessages.id, staffmessages.added, staffmessages.subject, staffmessages.answered, staffmessages.answeredby, staffmessages.sender, staffmessages.answer, users.username FROM staffmessages INNER JOIN users on staffmessages.sender = users.id ORDER BY id desc $limit");

while ($arr = mysql_fetch_assoc($res))
{
if ($arr[answered])
{
$res3 = mysql_query("SELECT username FROM users WHERE id=$arr[answeredby]");
$arr3 = mysql_fetch_assoc($res3);
$answered = "<font color=\"green\"><b>Yes - <a href=\"userdetails.php?id=$arr[answeredby]\"><b>$arr3[username]</b></a> (<a href=\"staffbox.php?action=viewanswer&amp;pmid=$arr[id]\">View Answer</a>)</b></font>";
}
else
$answered = "<font color=\"red\"><b>No</b></font>";

$pmid = $arr["id"];

print("<tr>
<td class=\"row1\"><a href=\"staffbox.php?action=viewpm&amp;pmid=$pmid\"><b>$arr[subject]</b></a></td>
<td class=\"row1\"><a href=\"userdetails.php?id=$arr[sender]\"><b>$arr[username]</b></a></td>
<td class=\"row1\">$arr[added]</td>
<td class=\"row1\"align=\"left\">$answered</td>
<td class=\"row1\"><input type=\"checkbox\" name=\"setanswered[]\" value=\"" . $arr[id] . "\" /></td>
<td class=\"row1\"><a href=\"staffbox.php?action=deletestaffmessage&amp;id=$arr[id]\">Del</a></td>
</tr>\n
");
}

end_table();
print("<p align=\"right\"><input type=\"submit\" value=\"Confirm\"/></p>");
print("</form>");

end_frame();


echo $pagerbottom;

end_main_frame();
}
stdfoot();
}

//////////////////////////
// VIEW PM'S //
//////////////////////////

if ($action == "viewpm")
{

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied.");

$pmid = 0 + $_GET["pmid"];

$ress4 = mysql_query("SELECT id, subject, sender, added, msg, answeredby, answered FROM staffmessages WHERE id=$pmid");
$arr4 = mysql_fetch_assoc($ress4);

$answeredby = $arr4["answeredby"];

$rast = mysql_query("SELECT username FROM users WHERE id=$answeredby");
$arr5 = mysql_fetch_assoc($rast);

$senderr = "" . $arr4["sender"] . "";

if (is_valid_id($arr4["sender"]))
{
$res2 = mysql_query("SELECT username FROM users WHERE id=" . $arr4["sender"]) or sqlerr();
$arr2 = mysql_fetch_assoc($res2);
$sender = "<a href='userdetails.php?id=$senderr'>" . ($arr2["username"] ? $arr2["username"]:"[Deleted]") . "</a>";
}
else
$sender = "System";

$subject = $arr4["subject"];

if ($arr4["answered"] == '0') {
$answered = "<font color=\"red\"><b>No</b></font>";
}
else {
$answered = "<font color=\"blue\"><b>Yes</b></font> by <a href=\"userdetails.php?id=$answeredby\">$arr5[username]</a> (<a href=\"staffbox.php?action=viewanswer&amp;pmid=$pmid\">Show Answer</a>)";
}

if ($arr4["answered"] == '0') {
$setanswered = "[<a href=\"staffbox.php?action=setanswered&amp;id=$arr4[id]\">Mark Answered</a>]";
}
else {
$setanswered = "";
}

$iidee = $arr4["id"];


stdhead("Staff PM's");
print("<h1 align=\"center\">Messages to staff</h1>\n");
//print("<table class=bottom width=730 border=0 cellspacing=0 cellpadding=10><tr><td class=embedded width=700>\n");

begin_frame("Messages to staff",true,5,730);

$elapsed = get_elapsed_time(sql_timestamp_to_unix_timestamp($arr4["added"]));

//print("<table width=750 border=1 cellspacing=0 cellpadding=10 style='margin-bottom: 10px'>");
begin_table(true);

print("<tr><td class=\"row2\">\n");
print("From <b>$sender</b> at\n" . $arr4["added"] . " ($elapsed ago) GMT\n");
print("<br/><br style='margin-bottom: -10px'/><div align=\"left\"><b>Subject: <font color=\"darkred\">$subject</font></b>
&nbsp;&nbsp;<br/><b>Answered:</b> $answered&nbsp;&nbsp;$setanswered</div>
<br/><table class=\"main\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td>\n");
print(format_comment($arr4["msg"]));
print("</td></tr></table>\n");
print("<table width=\"100%\" border=\"0\"><tr><td >\n");
print(($arr4["sender"] ? "<a href=\"staffbox.php?action=answermessage&amp;receiver=" . $arr4["sender"] . "&amp;answeringto=$iidee\"><b>Reply</b></a>" : "<font class=\"gray\"><b>Reply</b></font>") .
" | <a href=\"staffbox.php?action=deletestaffmessage&amp;id=" . $arr4["id"] . "\"><b>Delete</b></a></td></tr>");
print("</table></td></tr>\n");
	end_table();
end_frame();
stdfoot();
}

//////////////////////////
// VIEW ANSWERS //
//////////////////////////

if ($action == "viewanswer")
{

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied.");

$pmid = 0 + $_GET["pmid"];

$ress4 = mysql_query("SELECT id, subject, sender, added, msg, answeredby, answered, answer FROM staffmessages WHERE id=$pmid");
$arr4 = mysql_fetch_assoc($ress4);

$answeredby = $arr4["answeredby"];

$rast = mysql_query("SELECT username FROM users WHERE id=$answeredby");
$arr5 = mysql_fetch_assoc($rast);

if (is_valid_id($arr4["sender"]))
{
$res2 = mysql_query("SELECT username FROM users WHERE id=" . $arr4["sender"]) or sqlerr();
$arr2 = mysql_fetch_assoc($res2);
$sender = "<a href=\"userdetails.php?id=" . $arr4["sender"] . "\">" . ($arr2["username"]?$arr2["username"]:"[Deleted]") . "</a>";
}
else
$sender = "System";

if ($arr4['subject'] == "") {
$subject = "No subject";
}

else {
$subject = "<a style='color: darkred' href=\"staffbox.php?action=viewpm&amp;pmid=$pmid\">$arr4[subject]</a>";
}


$iidee = $arr4["id"];

if ($arr4[answer] == "") {

$answer = "This message has not been answered yet!";
}

else {

$answer = $arr4["answer"];
}

stdhead("Staff PM's");

print("<h1 align=\"center\">Viewing Answer</h1>\n");
//print("<table class=bottom width=730 border=0 cellspacing=0 cellpadding=10><tr><td class=embedded width=700>\n");
begin_frame("Viewing Answer",true,5,730);

$elapsed = get_elapsed_time(sql_timestamp_to_unix_timestamp($arr4["added"]));

//print("<table width=750 border=1 cellspacing=0 cellpadding=10 style='margin-bottom: 10px'>");
begin_table(true);
print("<tr><td class=\"row2\">\n");
print("<b><a href=\"userdetails.php?id=$answeredby\">$arr5[username]</a></b> answered this message sent by $sender");
print("<br/><br style='margin-bottom: -10px'/><div align=\"left\"><b>Subject: $subject</b>
&nbsp;&nbsp;<br/><b>Answer:</b></div>
<br/><table class=\"main\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\">");
print("<tr><td>\n");
print(format_comment($answer));

print("</td></tr>");
print("</table></td></tr>\n");
//print("</table>\n");
//print("</table>\n");
end_table();
end_frame();
stdfoot();
}


//////////////////////////
// ANSWER MESSAGE //
//////////////////////////

if ($action == "answermessage") {

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied");

$answeringto = $_GET["answeringto"];
$receiver = 0 + $_GET["receiver"];

if (!is_valid_id($receiver))
die;

$res = mysql_query("SELECT * FROM users WHERE id=$receiver") or die(mysql_error());
$user = mysql_fetch_assoc($res);

if (!$user)
stderr("Error", "No user with that ID.");

$res2 = mysql_query("SELECT * FROM staffmessages WHERE id=$answeringto") or die(mysql_error());
$array = mysql_fetch_assoc($res2);

stdhead("Answer to Staff PM", false);
?>
<h2>Answering to <a href="staffbox.php?action=viewpm&amp;pmid=<?=$array['id']?>"><i><?=$array["subject"]?></i></a> sent by <i><?=$user["username"]?></i></h2>

<?begin_frame("Answer to Staff PM",true,5,450);?>


<form method="post" name="message" action="?action=takeanswer">
<? if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"]) { ?>
<? } ?>
<table cellspacing="0" cellpadding="5">
<tr><td colspan="2">
<textarea name="msg" cols="80" rows="15"><?=htmlspecialchars($body)?></textarea></td></tr>
<tr><td<?=$replyto?" colspan=\"2\"":""?> align="center"><input type="submit" value="Send it!" class="btn"/></td></tr>
</table>
<input type="hidden" name="receiver" value="<?=$receiver?>"/>
<input type="hidden" name="answeringto" value="<?=$answeringto?>"/>
</form>


<?
end_frame();
stdfoot();
}

//////////////////////////
// TAKE ANSWER //
//////////////////////////


if ($action == "takeanswer") {


if ($HTTP_SERVER_VARS["REQUEST_METHOD"] != "POST")
stderr("Error", "Method");

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied");

$receiver = 0 + $_POST["receiver"];
$answeringto = $_POST["answeringto"];

if (!is_valid_id($receiver))
stderr("Error","Invalid ID");

$userid = $CURUSER["id"];

$msg = trim($_POST["msg"]);

$message = sqlesc($msg);

$added = "'" . get_date_time() . "'";

if (!$msg)
stderr("Error","Please enter something!");

mysql_query("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES($userid, $userid, $receiver, $added, $message)") or sqlerr(__FILE__, __LINE__);

mysql_query("UPDATE staffmessages SET answer=$message WHERE id=$answeringto") or sqlerr(__FILE__, __LINE__);

mysql_query("UPDATE staffmessages SET answered='1', answeredby='$userid' WHERE id=$answeringto") or sqlerr(__FILE__, __LINE__);

header("Location: staffbox.php?action=viewpm&pmid=$answeringto");
die;
}
//////////////////////////
// DELETE STAFF MESSAGE //
//////////////////////////

if ($action == "deletestaffmessage") {

$id = 0 + $_GET["id"];

if (!is_numeric($id) || $id < 1 || floor($id) != $id)
die;

if (get_user_class() < UC_MODERATOR)
stderr("Error", "Permission denied.");

mysql_query("DELETE FROM staffmessages WHERE id=" . sqlesc($id)) or die();

header("Location: $BASEURL/staffbox.php");
}

//////////////////////////
// MARK AS ANSWERED //
//////////////////////////

if ($action == "setanswered") {

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied.");

$id = 0 + $_GET["id"];

mysql_query ("UPDATE staffmessages SET answered=1, answeredby = $CURUSER[id] WHERE id = $id") or sqlerr();

header("Refresh: 0; url=staffbox.php?action=viewpm&pmid=$id");
}

//////////////////////////
// MARK AS ANSWERED #2 //
//////////////////////////

if ($action == "takecontactanswered") {

if ((get_user_class() < UC_MODERATOR)&&($CURUSER[support]!='yes'))
stderr("Error", "Permission denied.");

$res = mysql_query ("SELECT id FROM staffmessages WHERE answered=0 AND id IN (" . implode(", ", $_POST[setanswered]) . ")");

while ($arr = mysql_fetch_assoc($res))
mysql_query ("UPDATE staffmessages SET answered=1, answeredby = $CURUSER[id] WHERE id = $arr[id]") or sqlerr();

header("Refresh: 0; url=staffbox.php");
}

?>
