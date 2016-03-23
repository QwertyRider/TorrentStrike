<? 
require_once("include/bittorrent.php"); 
function bark($msg) { 
 stdhead(); 
   stdmsg("Failed", $msg); 
 stdfoot(); 
 exit; 
} 
dbconn(); 
loggedinorreturn(); 
if (get_user_class() > 3) { 

if (empty($_POST["delreq"])) 
   bark("Don't leave any fields blank."); 

$do="DELETE FROM requests WHERE id IN (" . implode(", ", $_POST[delreq]) . ")"; 
$do2="DELETE FROM addedrequests WHERE requestid IN (" . implode(", ", $_POST[delreq]) . ")";
$res2=mysql_query($do2);
$res=mysql_query($do); 
} 
else
{ bark("You're not staff.  Please contact <a href=staff.php>Staff</a> to delete the request");}
header("Refresh: 0; url=viewrequests.php"); 
?>