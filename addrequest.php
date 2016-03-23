<?

require_once("include/bittorrent.php");

dbconn();

stdhead("Vote");

$requestid = $_GET["id"];
$userid = $CURUSER["id"];
$res = mysql_query("SELECT * FROM addedrequests WHERE requestid=$requestid and userid = $userid") or sqlerr();
$arr = mysql_fetch_assoc($res);
$voted = $arr;

if ($voted) {
?>
<h1>You've Already Voted</h1>
<p>You've already voted for this request, only 1 vote for each request is allowed</p><p>Back to <a href=viewrequests.php><b>requests</b></a></p>
<?
}
else {

mysql_query("UPDATE requests SET hits = hits + 1 WHERE id=$requestid") or sqlerr();
@mysql_query("INSERT INTO addedrequests VALUES(0, $requestid, $userid)") or sqlerr();

print("<h1>Vote accepted</h1>");
print("<p>Successfully voted for request $requestid</p><p>Back to <a href=viewrequests.php><b>requests</b></a></p>");

}

?>
