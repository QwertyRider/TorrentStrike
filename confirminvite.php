<?

require_once("include/bittorrent.php");
require_once('include/config.php');



$id = 0 + $HTTP_GET_VARS["id"];
$md5 = $HTTP_GET_VARS["secret"];

if (!$id)
httperr();

dbconn();



$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_row($res);
if ($arr[0] >= $invites)
stderr("Sorry", "The current user account limit (" . number_format($invites) . ") has been reached. Inactive accounts are pruned all the time, please check back again later...");

$res = mysql_query("SELECT passhash, editsecret, secret, status FROM users WHERE id = $id");
$row = mysql_fetch_array($res);

if (!$row)
httperr();

if ($row["status"] != "pending") {
header("Refresh: 0; url=ok.php?type=confirmed");
exit();
}

$sec = hash_pad($row["editsecret"]);
if ($md5 != md5($sec))
httperr();

$secret = $row["secret"];
$psecret = md5($row["editsecret"]);
stdhead("Confirm Invite");

$countries = "<option value=\"0\">---- None selected ----</option>\n";
$ct_r = mysql_query("SELECT id,name FROM countries ORDER BY name") or die;
while ($ct_a = mysql_fetch_array($ct_r))
  $countries .= "<option value=\"$ct_a[id]\"" . ($CURUSER["country"] == $ct_a['id'] ? " selected=\"selected\"" : "") . ">$ct_a[name]</option>\n";

?>

Note: You need cookies enabled to sign up or log in.<br/><br/>
<? begin_frame("Confirm your invite",false,10,false);
echo "<form method=\"post\" action=\"takeconfirminvite.php?id=".$id."&amp;secret=".$psecret."\">";
begin_table();
?>
<tr><td align="right" class="row2">Desired username:</td><td class="row1" align="left"><input type="text" size="40" name="wantusername" /></td></tr>
<tr><td align="right" class="row2">Pick a password:</td><td class="row1" align="left"><input type="password" size="40" name="wantpassword" /></td></tr>
<tr><td align="right" class="row2">Enter password again:</td><td class="row1" align="left"><input type="password" size="40" name="passagain" /></td></tr>
<tr><td align="right" class="row2">Country:</td><td align="left" class="row1"><select name="country"><? echo $countries;?></select></td></tr>
<tr><td align="right" class="row2"></td><td class="row1" align="left">
<input type="checkbox" name="rulesverify" value="yes"/> I have read the site <a href="rules.php" target="_blank">rules</a> page.<br/>
<input type="checkbox" name="faqverify" value="yes"/> I agree to read the <a href="faq.php" target="_blank">FAQ</a> before asking questions.<br/>
<input type="checkbox" name="ageverify" value="yes"/> I am at least 13 years old.</td></tr>
<tr><td class="row1" colspan="2" align="center"><input type="submit" value="Sign up! (PRESS ONLY ONCE)"/></td></tr>
<?end_table();?>
</form>
<?end_frame();?>
<?

stdfoot();

?> 