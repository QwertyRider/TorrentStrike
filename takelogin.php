<?

require_once("include/bittorrent.php");
require_once("include/phpbb2Bridge.php");

if (!mkglobal("username:password"))
	die();



function bark($text = "Username or password incorrect")
{
  stderr("Login failed!", $text);
}

$res = mysql_query("SELECT id, passhash, secret, enabled, email FROM users WHERE username = " . sqlesc($username) . " AND status = 'confirmed'");
$row = mysql_fetch_array($res);

if (!$row)
	bark();

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
	bark();

if ($row["enabled"] == "no")
	bark("This account has been disabled.");

logincookie($row["id"], $row["passhash"]);
  insert_phpBB2user($username, md5($password), $row["email"]);
  if (login_phpBB2user($username, md5($password), TRUE)==false)
  {
    update_phpBB2userPassword($username,md5($password)); // login failed: try to update the password in case it was changed
    login_phpBB2user($username, md5($password), TRUE);
  }

if (!empty($_POST["returnto"]))
	header("Location: $_POST[returnto]");
else
	header("Location: index.php");

?>