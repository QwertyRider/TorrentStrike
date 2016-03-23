<?
require_once("include/bittorrent.php");

loggedinorreturn();

stdhead("Perform a Cleanup");

if (get_user_class() < UC_MODERATOR)
{
  stdmsg("Sorry...", "You are not authorized.");
  stdfoot();
  exit;
}

$now = time();

$res = mysql_query("SELECT value_u FROM avps WHERE arg = 'lastcleantime'");
$row = mysql_fetch_array($res);
if (!$row) {
    mysql_query("INSERT INTO avps (arg, value_u) VALUES ('lastcleantime',$now)");
    return;
}
$ts = $row[0];
/*if ($ts + $autoclean_interval > $now)
{
	print("Cleanup not needed");
	stdfoot();
  return;
}*/
mysql_query("UPDATE avps SET value_u=$now WHERE arg='lastcleantime' AND value_u = $ts");
if (!mysql_affected_rows())
    return;

docleanup(true);
print("Full Cleanup Done!<br/> Unwanted accounts (including phpbb accounts) are now purged...");

stdfoot();
?>
