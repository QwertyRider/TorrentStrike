<?

require_once("include/bittorrent.php");

dbconn();

function bark($msg) {
 stdhead();
stdmsg("Request failed!", $msg);
 stdfoot();
 exit;
}

$userid = $_POST["userid"];
$requestartist = $_POST["requestartist"];
$requesttitle = $_POST["requesttitle"];
$request = $requesttitle;
$descr = $_POST["descr"];
if(isset($_POST["category"]))
$cat =   $_POST["category"] > 0 ? 0 + $_POST["category"] : bark("Please go back and select a category!");
$userid = sqlesc($userid);
$request = sqlesc($request);
$descr = sqlesc($descr);
$cat = sqlesc($cat);
mysql_query("INSERT INTO requests (hits,userid, cat, request, descr, added) VALUES(1,$CURUSER[id], $cat, $request, $descr, '" . get_date_time() . "')") or sqlerr(__FILE__,__LINE__);
$id = mysql_insert_id();
@mysql_query("INSERT INTO addedrequests VALUES(0, $id, $CURUSER[id])") or sqlerr();
write_log("$request was added to the Request section");
header("Refresh: 0; url=viewrequests.php");
?>
