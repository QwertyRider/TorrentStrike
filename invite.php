<?

require_once("include/bittorrent.php");
require_once('include/config.php');
dbconn();
loggedinorreturn();
if (get_user_class() < UC_USER)
stderr("Error", "Access denied.");
$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_row($res);
if ($arr[0] >= $invites)
stderr("Sorry", "The current user account limit (" . number_format($invites) . ") has been reached. Inactive accounts are pruned all the time, please check back again later...");
if($CURUSER["invites"] == 0)
stderr("Sorry","No invites!");
stdhead("Invite");

?>

<? begin_frame("Send an invite",false,10,false);
echo "<form method=\"post\" action=\"takeinvite.php\">";
	 begin_table(true);
?>
<tr valign="top"><td align="right" class="row2">Email address:</td><td align="left" class="row1"><input type="text" size="40" name="email" />
<font class="small">
<br/>The email address must be valid.<br/>
They will receive a confirmation email which they need to respond to.
</font>
</td></tr>
<tr><td align="right" class="row2">Message:</td><td class="row1" align="left"><textarea name="mess" rows="10" cols="80"></textarea>
</td></tr>
<tr><td colspan="2" class="row1" align="center"><input type="submit" value="Invite! (PRESS ONLY ONCE)"/></td></tr>
<? 
end_table();
echo "</form>";
end_frame();
stdfoot();

?>