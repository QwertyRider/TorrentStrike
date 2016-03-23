<?php
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

stdhead("Fill Request");

begin_main_frame();

$filledurl = $_GET["filledurl"];
$requestid = $_GET["requestid"];


$res = mysql_query("SELECT users.username, requests.userid, requests.request FROM requests inner join users on requests.userid = users.id where requests.id = $requestid") or sqlerr();
 $arr = mysql_fetch_assoc($res);

$res2 = mysql_query("SELECT username FROM users where id =" . $CURUSER[id]) or sqlerr();
 $arr2 = mysql_fetch_assoc($res2);


$msg = "Your request, [url=reqdetails.php?id=" . $requestid . "][b]" . $arr[request] . "[/b][/url] has been filled by [url=userdetails.php?id=" . $CURUSER[id] . "][b]" . $arr2[username] . "[/b][/url]. You can download your request from [url=" . $filledurl. "][b]" . $filledurl. "[/b][/url].  Please do not forget to leave thanks where due.  If for some reason this is not what you requested, please reset your request so someone else can fill it by following [URL=reqreset.php?requestid=" . $requestid . "]this[/url] link.  Do [b]NOT[/b] follow this link unless you are sure that this does not match your request.";

       mysql_query ("UPDATE requests SET filled = '$filledurl', filledby = $CURUSER[id], filldate = '" . get_date_time() . "' WHERE id = $requestid") or sqlerr();
mysql_query("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES(0, 0, $arr[userid], '" . get_date_time() . "', " . sqlesc($msg) . ")") or sqlerr(__FILE__, __LINE__);


print("Request $requestid successfully filled with <a href=\"$filledurl\">$filledurl</a>.<br/>  User <a href=\"userdetails.php?id=$arr[userid]\"><b>$arr[username]</b></a> automatically PMd.<br/>  If you have made a mistake in filling in the URL or have realised that your torrent does not actually satisfy this request, please reset the request so someone else can fill it by clicking <a href=\"reqreset.php?requestid=$requestid\">here</a><br/>  Do <b>NOT</b> follow this link unless you are sure there is a problem.");

end_main_frame();
stdfoot();
?>
