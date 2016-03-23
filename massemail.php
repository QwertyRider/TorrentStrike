<?

require "include/bittorrent.php";
dbconn();

loggedinorreturn();

if (get_user_class() < UC_ADMINISTRATOR)
stderr("Error", "Permission denied.");
$class = 0 + $_POST["class"];
$or = $_POST["or"];

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
$res = mysql_query("SELECT id, username, email FROM users WHERE class $or $class") or sqlerr(__FILE__, __LINE__);

$subject = substr(trim($HTTP_POST_VARS["subject"]), 0, 80);
if ($subject == "") $subject = "(no subject)";
$subject = "$subject";

$message1 = trim($HTTP_POST_VARS["message"]);
if ($message1 == "") stderr("Error", "Empty message!");


while($arr=mysql_fetch_array($res)){

$to = $arr["email"];


$message = "Message received from $SITENAME on " . gmdate("Y-m-d H:i:s") . " GMT.\n" .
"---------------------------------------------------------------------\n\n" .
$message1 . "\n\n" .
"---------------------------------------------------------------------\n$SITENAME\n";

$success = mail($to, $subject, $message, "Od: $SITEEMAIL", "-f$SITEEMAIL");

}


if ($success)
stderr("Success", "Messages sent.");
else
stderr("Error", "Try again.");

}

stdhead("Mass E-mail Gateway");
?>

<table border="0" class="main" cellspacing="0" cellpadding="0"><tr>
<td class="embedded"><img src="pic/email.gif" alt=""/></td>
<td class="embedded" style="padding-left: 10px"><font size="3"><b>Send mass e-mail to all members</b></font></td>
</tr></table><br/>


<?begin_frame("Send mass e-mail",false,5,false);?>
<form method="post" action="massemail.php">
<?begin_table();

if (get_user_class() == UC_MODERATOR && $CURUSER["class"] > UC_POWER_USER)
printf("<input type=hidden name=class value=$CURUSER[class]\n");
else
{
print("<tr><td class=\"rowhead\">Classe</td><td class=\"row2\" colspan=\"2\" align=\"left\"><select name=\"or\"><option value='<'><</option><option value='>'>></option><option value='='>=</option><option value='<='><=</option><option value='>='>>=</option></select>&nbsp;<select name=\"class\">\n");
if (get_user_class() == UC_MODERATOR)
$maxclass = UC_POWER_USER;
else
$maxclass = get_user_class() - 1;
for ($i = 0; $i <= $maxclass; ++$i)
{
	$currentclass = get_user_class_name($i);
	if ($currentclass)
		print("<option value=\"$i\"" . ($CURUSER["class"] == $i ? " selected=\"selected\"" : "") . ">$prefix" . $currentclass . "</option>\n");
}
print("</select></td></tr>\n");
}
?>

<tr><td class="rowhead">Subject</td><td class="row2"><input type="text" name="subject" size="80"/></td></tr>
<tr><td class="rowhead">Body</td><td class="row2"><textarea name="message" cols="80" rows="20"></textarea></td></tr>
<tr><td colspan="2" class="row2" align="center"><input type="submit" value="Send" class="btn"/></td></tr>
<?end_table();?>
</form>
<?end_frame();?>

<?
stdfoot();
?>