<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
if (get_user_class() < UC_ADMINISTRATOR)
stderr("Error", "Access denied.");
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
	if ($_POST['doit'] == 'yes') {
	mysql_query("UPDATE users SET invites = invites + 1 WHERE status='confirmed'");
	stderr("Invit", "an invit had been sent to everyone...");
	die;
	}
		
	if ($HTTP_POST_VARS["username"] == "" || $HTTP_POST_VARS["invites"] == "" || $HTTP_POST_VARS["invites"] == "")
	stderr("Error", "Missing form data.");
	$username = sqlesc($HTTP_POST_VARS["username"]);
	$invites = sqlesc($HTTP_POST_VARS["invites"]);
	
	mysql_query("UPDATE users SET invites=$invites WHERE username=$username") or sqlerr(__FILE__, __LINE__);
	$res = mysql_query("SELECT id FROM users WHERE username=$username");
	$arr = mysql_fetch_row($res);
	if (!$arr)
	stderr("Error", "Unable to update account.");
	header("Location: $BASEURL/userdetails.php?id=$arr[0]");
	die;
}
stdhead("Update Users Invite Amounts");
?>
<h1>Update Users Invite Amounts</h1>

<?begin_frame("Update Users Invite Amounts",false,10,false);
echo "<form method=\"post\" action=\"inviteadd.php\">";
begin_table(true);
?>
<tr><td class="rowhead">User name</td><td class="row1"><input type="text" name="username" size="40"/></td></tr>
<tr><td class="rowhead">Invites</td><td class="row1"><input type="text" name="invites" size="5"/></td></tr>
<tr><td colspan="2" class="row1" align="center"><input type="submit" value="Okay" class="btn"/></td></tr>
<?end_table();?>
</form>
<?end_frame();

begin_frame("Send an invite to everyone",false,10,false);?>
<form action="inviteadd.php" method="post">
<?begin_table(true);?>
<tr><td class="row1" align="center">
Are you sure you want to give<br/> all confirmed users an extra invite?<br /><br />
<input type = "hidden" name = "doit" value = "yes" />
<input type="submit" value="Yes" />
</td></tr>
<? end_table();?>
</form>
<?end_frame();

stdfoot(); ?>