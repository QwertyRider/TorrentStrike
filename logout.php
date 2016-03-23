<?

require_once("include/bittorrent.php");
require_once("include/phpbb2Bridge.php");



logoutcookie();
logout_phpBB2user();

//header("Refresh: 0; url=./");
Header("Location: $BASEURL/");

?>