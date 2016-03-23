<?php

require "include/bittorrent.php";
dbconn(false);

loggedinorreturn();
if (get_user_class() < UC_SYSOP)
stderr("Sorry", "Access denied!");


stdhead("");
begin_frame("Statistics");
?>

<table cellpadding="4" cellspacing="1" border="0" style="width:100%" class="tableinborder">
<td class="tabletitle" valign="top">Time</td>
<td class="tabletitle" valign="top">IP</td>
<td class="tabletitle" valign="top">Browser</td>
<td class="tabletitle" valign="top">Whence</td>
<td class="tabletitle" valign="top">Page</td>
</tr>
<? $fop = fopen ("info/stat.txt", "r+");
while (!feof($fop))
{
$read = fgets($fop, 1000);
list($date,$time,$ip,$hua,$from,$host) = split('#',$read);
echo "<td class=tablea >$date</a></b><br>$time</td>";
echo "<td class=tablea >$ip</td>";
echo "<td class=tablea >$hua</td>";
echo "<td class=tablea ><a href=$from>$from</td>";
echo "<td class=tablea ><a href=$host>$host</td>";
echo " </tr>";
}
fclose($fop);

?>
<table width="100%" border="0">
<tr>
<p>
<form method="post" action="del.php" name="add">
<br>
<input type="submit" name="Submit" value="Clear Statistics">
<?