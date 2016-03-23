<?

require_once("include/bittorrent.php");
require_once("include/phpbb2Bridge.php");


$id = 0 + $_GET["id"];
$md5 = $_GET["secret"];

if (!$id)
	httperr();




$res = mysql_query("SELECT passhash, editsecret, email, username,  status FROM users WHERE id = $id");
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

mysql_query("UPDATE users SET status='confirmed', editsecret='' WHERE id=$id AND status='pending'");

if (!mysql_affected_rows())
	httperr();

logincookie($id, $row["passhash"]);
activate_and_login_phpBB2user($row["username"]);

header("Refresh: 0; url=ok.php?type=confirm");


?>