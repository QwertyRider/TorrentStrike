<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead("Unconfirmed Users");
begin_main_frame();
begin_frame("Unconfirmed Users");


if (get_user_class() < UC_ADMINISTRATOR)
die;
$res = mysql_query("SELECT * FROM users WHERE status='pending' ORDER BY username" ) or sqlerr();
if( mysql_num_rows($res) != 0 )
{
print'<br><table width=100% border=1 cellspacing=0 cellpadding=5>';
print'<tr>';
print'<td class=rowhead><center>Name</center></td>';
print'<td class=rowhead><center>eMail</center></td>';
print'<td class=rowhead><center>Added</center></td>';
print'<td class=rowhead><center>Set Status</center></td>';
print'<td class=rowhead><center>Confirm</center></td>';
print'</tr>';
while( $row = mysql_fetch_assoc($res) )
{
$id = $row['id'];
print'<tr><form method=post action=modtask.php>';
print'<input type=hidden name=\'action\' value=\'confirmuser\'>';
print("<input type=hidden name='userid' value='$id'>");
print'<a href="userdetails.php?id=' . $row['id'] . '"><td><center>' . $row['username'] . '</center></td></a>';
print'<td align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['email'] . '</td>';
print'<td align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['added'] . '</td>';
print'<td align=center><select name=confirm><option value=pending>pending</option><option value=confirmed>confirmed</option></select></td>';
print'<td align=center><input type=submit value="-Go-" style=\'height: 20px; width: 40px\'>';
print'</form></tr>';
}
print '</table>';
}
else
{
	print 'No pending users...';
}

end_frame();
end_main_frame();
stdfoot();
?>