<?
require_once("include/bittorrent.php");
dbconn();

loggedinorreturn();
if (get_user_class() < UC_SYSOP)
stderr("Sorry", "Access denied!");
stdhead("");
$sf = "info/stat.txt";
$fpsf = fopen ("$sf", "w");
$text1 = "";
fwrite($fpsf,$text1);
fclose($fpsf);


begin_frame("The statistics is cleaned!");


?>
To pass  <a href="info.php">back</a>