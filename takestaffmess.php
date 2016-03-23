<?
require "include/bittorrent.php";

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] != "POST")
 stderr("Error", "damn cheater");



dbconn();
loggedinorreturn();                                                    

if (get_user_class() < UC_ADMINISTRATOR)
stderr("Sorry", "but your just a little bitch on this tracker");

$sender_id = ($_POST['sender'] == 'system' ? 0 : $CURUSER['id']);
$dt = sqlesc(get_date_time());
$msg = $_POST['msg'];
if (!$msg)
stderr("Error","Please, enter a message!");

$subject = $_POST['subject'];
if (!$subject)
stderr("Error","Please, enter a subject!");

$updateset = $_POST['clases'];
if(!$_POST['clases'])
stderr("Error","please check 1 or more classes");
$query = mysql_query("SELECT id FROM users WHERE class IN (".implode(",", $updateset).")");

while($dat=mysql_fetch_assoc($query))
{
mysql_query("INSERT INTO messages (sender, receiver, added, msg,subject) VALUES ($sender_id, $dat[id], '" . get_date_time() . "', " . sqlesc($msg) .", " . sqlesc($subject) .")") or sqlerr(__FILE__,__LINE__);
}

header("Refresh: 4; url=staffmess.php");


?>