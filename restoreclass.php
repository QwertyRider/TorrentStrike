<?
require_once("include/bittorrent.php");

dbconn(false);
loggedinorreturn();

mysql_query("UPDATE users SET override_class = 255 WHERE id = ".$CURUSER['id']);

header("Location: $BASEURL/index.php");
die();
?>