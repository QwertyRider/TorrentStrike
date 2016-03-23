<?

require_once("include/bittorrent.php");

if ($CURUSER)
				stderr("Arff...", "You are connected, you don't need this...");

stdhead("Login");

unset($returnto);
if (!empty($_GET["returnto"])) {
	$returnto = $_GET["returnto"];
	if (!$_GET["nowarn"]) {
		print("<h1>Not logged in!</h1>\n");
		print("<p><b>Error:</b> The page you tried to view can only be used when you're logged in.</p>\n");
	}
}

?>
<form method="post" action="takelogin.php">
<p>Note: You need cookies enabled to log in.</p>
<? begin_frame("Login",false,10,false); 
	 begin_table(true);
?>
<tr><td class="rowhead">Username:</td><td class="row1" align="left"><input type="text" size="40" name="username" /></td></tr>
<tr><td class="rowhead">Password:</td><td class="row1" align="left"><input type="password" size="40" name="password" /></td></tr>
<!--<tr><td class=rowhead>Duration:</td><td align=left><input type=checkbox name=logout value='yes' checked>Log me out after 15 minutes inactivity</td></tr>-->
<tr><td class="row1" colspan="2" align="center"><input type="submit" value="Log in!" class="btn"/></td></tr>
<? end_table();
	end_frame();

if (isset($returnto))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($returnto) . "\" />\n");

?>
</form>
<p>Don't have an account? <a href="signup.php">Sign up</a> right now!</p>
<?

stdfoot();

?>