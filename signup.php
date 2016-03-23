<?

require_once("include/bittorrent.php");


$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_row($res);
if ($arr[0] >= $maxusers)
        stderr("Sorry", "The current user account limit (" . number_format($maxusers) . ") has been reached. Inactive accounts are pruned all the time, please check back again later...");

if ($CURUSER)
				stderr("Arff...", "You are connected, you don't need this...");

stdhead("Signup");

$countries = "<option value=\"0\">---- None selected ----</option>\n";
$ct_r = mysql_query("SELECT id,name FROM countries ORDER BY name") or die;
while ($ct_a = mysql_fetch_array($ct_r))
  $countries .= "<option value=\"$ct_a[id]\"" . ($CURUSER["country"] == $ct_a['id'] ? " selected=\"selected\"" : "") . ">$ct_a[name]</option>\n";

?>
<!--
<table width=500 border=1 cellspacing=0 cellpadding=10><tr><td align=left>
<h2 align=center>Proxy check</h2>
<b><font color=red>Important - please read:</font></b> We do not accept users connecting through public proxies. When you
submit the form below we will check whether any commonly used proxy ports on your computer is open. If you have a firewall it may alert of you of port
scanning activity originating from <b>69.10.142.42</b> (torrentbits.org). This is only our proxy-detector in action.
<b>The check takes up to 30 seconds to complete, please be patient.</b> The IP address we will test is <b><?= $_SERVER["REMOTE_ADDR"]; ?></b>.
By proceeding with submitting the form below you grant us permission to scan certain ports on this computer.
</td></tr></table>
<p>
-->
Note: You need cookies enabled to sign up or log in.
<form method="post" action="takesignup.php">

<? begin_frame("Signup",false,10,false);
	 begin_table(true);
?>

<tr><td align="right" class="row2">Desired username:</td><td align="left" class="row1"><input type="text" size="40" name="wantusername" /></td></tr>
<tr><td align="right" class="row2">Pick a password:</td><td align="left" class="row1"><input type="password" size="40" name="wantpassword" /></td></tr>
<tr><td align="right" class="row2">Enter password again:</td><td align="left" class="row1"><input type="password" size="40" name="passagain" /></td></tr>
<tr valign="top"><td align="right" class="row2">Email address:</td><td align="left" class="row1"><input type="text" size="40" name="email" /><br/>The email address must be valid.<br/>
<?if (ENA_EMAIL_CONFIRM) echo "You will receive a confirmation email which you need to respond to.<br/>";?>
The email address won't be publicly shown anywhere.</td></tr>
<tr><td align="right" class="row2">Country:</td><td align="left" class="row1"><select name="country"><? echo $countries;?></select></td></tr>
<tr><td colspan="2" align="left" class="row1"><input type="checkbox" name="rulesverify" value="yes" /> I have read the site rules page.<br/>
<input type="checkbox" name="faqverify" value="yes" /> I agree to read the FAQ before asking questions.<br/>
<input type="checkbox" name="ageverify" value="yes" /> I am at least 13 years old.</td></tr>
<tr><td colspan="2" align="center" class="row2"><input type="submit" value="Sign up! (PRESS ONLY ONCE)" style="height: 25px" /></td></tr>
<? end_table();
end_frame();
?>

</form>
<?

stdfoot();

?>