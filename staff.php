<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead("Staff");
begin_main_frame();

$settings['cache']=true;
$settings['cache_staff']=120;		// the cache is updaded every 120 secondes

?>

<?
if (!$act) {

$cache_file = "cache/staff.txt";
$expire = $GLOBALS['settings']['cache_staff'];


if ($settings['cache'] && file_exists($cache_file) && filemtime($cache_file) > (time() - $expire)) {
  $staff_table = unserialize( file_get_contents($cache_file) );
}
else
{
$dt = gmtime();
$dt = sqlesc(get_date_time($dt));
$res = mysql_query("SELECT users.id,username,class,countries.name as country, countries.flagpic, UNIX_TIMESTAMP($dt)-UNIX_TIMESTAMP(last_access) as last_access2 FROM users LEFT JOIN countries ON users.country = countries.id WHERE class >= " .UC_MODERATOR. " AND status='confirmed' ORDER BY username" ) or sqlerr();
 
 while ($arr = mysql_fetch_assoc($res))
 {
   $staff_online = ($arr['last_access2'] <= 180) ? 'online' : 'offline';
   $country = (!empty($arr['flagpic'])) ? '<img src="pic/flag/'.$arr['flagpic'].'" width="22" height="15" alt="'.$arr['country'].'" title="'.$arr['country'].'"/>' : '';
   $nb_staff++;
  
   $staff_table[ $arr['class'] ] = $staff_table[ $arr['class'] ].
   '<td class="embedded"><a class="altlink" href="userdetails.php?id='.$arr['id'].'">'.$arr['username'].'</a></td>
    <td class="embedded"><img src="pic/'.$staff_online.'.gif" border="0" alt="'.$staff_online.'"/></td>
    <td class="embedded"><a href="sendmessage.php?receiver='.$arr['id'].'"> <img src="pic/buttons/button_pm.gif" alt="" border="0"/> </a></td>
    <td class="embedded">'.$country.'</td>';

   ++ $col[$arr['class']];
   
   if ($col[$arr['class']]<=2)
     $staff_table[$arr['class']]=$staff_table[$arr['class']]."";
   else
   {
     $staff_table[$arr['class']]=$staff_table[$arr['class']]."</tr><tr>";
     $col[$arr['class']]=0;
   }
 }
 
 
$fp = fopen($cache_file, 'w');
fwrite($fp, serialize($staff_table));
fclose($fp);
}
?>

<? begin_frame("Staff"); ?>
All software support questions and those already answered in the <a href="faq.php"><b>FAQ</b></a> will be ignored.<br/>
<br/>
<table class="main" width="725" cellspacing="0" align="center">
<tr>
   <td class="embedded" width="125">&nbsp;</td>
   <td class="embedded" width="25">&nbsp;</td>
   <td class="embedded" width="35">&nbsp;</td>
   <td class="embedded" width="85">&nbsp;</td>
   <td class="embedded" width="125">&nbsp;</td>
   <td class="embedded" width="25">&nbsp;</td>
   <td class="embedded" width="35">&nbsp;</td>
   <td class="embedded" width="85">&nbsp;</td>
   <td class="embedded" width="125">&nbsp;</td>
   <td class="embedded" width="25">&nbsp;</td>
   <td class="embedded" width="35">&nbsp;</td>
   <td class="embedded" width="85">&nbsp;</td>
 </tr>
 <tr valign="middle"><td class="embedded" colspan="12" ><?=($GLOBALS['settings']['mod_messagestaff'] ? '<a href="groupmessage.php?group='.UC_SYSOP.'"><img src="pic/button_pm.gif" border="0" alt=""></a>&nbsp;&nbsp;' : '') ?><b>SysOp</b></td></tr>
 <tr><td class="embedded" colspan="12"><hr size="1"></hr></td></tr>
 <tr>
<?if ($staff_table[UC_SYSOP]) echo $staff_table[UC_SYSOP]; else echo"<td></td>";?>
 </tr>
 <tr><td class="embedded" colspan="12">&nbsp;</td></tr>
 <tr><td class="embedded" colspan="12"><?=($GLOBALS['settings']['mod_messagestaff'] ? '<a href="groupmessage.php?group='.UC_ADMINISTRATOR.'"><img src="pic/button_pm.gif" border="0"></a>&nbsp;&nbsp;' : '') ?><b>Administrators</b></td></tr>
 <tr><td class="embedded" colspan="12"><hr size="1"></hr></td></tr>
 <tr>
<?if ($staff_table[UC_ADMINISTRATOR]) echo $staff_table[UC_ADMINISTRATOR]; else echo"<td></td>";?>
 </tr>
 <tr><td class="embedded" colspan="12">&nbsp;</td></tr>
 <tr><td class="embedded" colspan="12"><?=($GLOBALS['settings']['mod_messagestaff'] ? '<a href="groupmessage.php?group='.UC_MODERATOR.'"><img src="pic/button_pm.gif" border="0"></a>&nbsp;&nbsp;' : '') ?><b>Moderators</b></td></tr>
 <tr><td class="embedded" colspan="12"><hr size="1"></hr></td></tr>
 <tr>
<?if ($staff_table[UC_MODERATOR]) echo $staff_table[UC_MODERATOR]; else echo"<td></td>"?>
 </tr>
</table>
<? } end_frame(); ?>


<?
if (!$act) {
$dt = gmtime() - 180;
$dt = sqlesc(get_date_time($dt));
// LIST ALL FIRSTLINE SUPPORTERS
// Search User Database for Firstline Support and display in alphabetical order
$res = mysql_query("SELECT * FROM users WHERE support='yes' AND status='confirmed' ORDER BY username LIMIT 10") or sqlerr();
while ($arr = mysql_fetch_assoc($res))
{
 $land = mysql_query("SELECT name,flagpic FROM countries WHERE id=$arr[country]") or sqlerr();
 $arr2 = mysql_fetch_assoc($land);
 $firstline .= "<tr><td class=\"embedded\"><a class=\"altlink\" href=\"userdetails.php?id=".$arr['id']."\">".$arr['username']."</a></td>
 <td class=\"embedded\"> ".("'".$arr['last_access']."'">$dt?"<img src=\"pic/buttons/button_online.gif\" border=\"0\" alt=\"online\" />":"<img src=\"pic/buttons/button_offline.gif\" border=\"0\" alt=\"offline\" />" )."</td>".
 "<td class=\"embedded\"><a href=\"sendmessage.php?receiver=".$arr['id']."\" >"."<img src=\"pic/buttons/button_pm.gif\" border=\"0\" alt=\"\"/></a></td>".
 "<td class=\"embedded\"><img src=\"pic/flag/$arr2[flagpic]\" border=\"0\" width=\"19\" height=\"12\" alt=\"\"/></td>".
 "<td class=\"embedded\">".$arr['supportfor']."</td></tr>\n";
}

begin_frame("Firstline Support");
?>

<table class="main" width="725" cellspacing="0">
<tr>
<td class="embedded" colspan="12">General support questions should preferably be directed to these users.<br/>Note that they are volunteers, giving away their time and effort to help you.
Treat them accordingly. (Languages listed are those besides English.)<br/><br/></td></tr>
<!-- Define table column widths -->
<tr>
 <td class="embedded" width="30"><b>Username</b></td>
 <td class="embedded" width="5"><b>Active</b></td>
 <td class="embedded" width="5"><b>Contact</b></td>
 <td class="embedded" width="85"><b>Language</b></td>
 <td class="embedded" width="200"><b>Support for:</b></td>
</tr>


<tr><td class="embedded" colspan="12"><hr size="1"></hr></td></tr>

<?=$firstline?>

</table>
<?
end_frame();
}

// -------- Contact staff box

begin_frame("Staff Box");
?>
<form method="post" action="contactstaff.php">
<table class="main" width="725" cellspacing="0">
<tr>
<td class="embedded">You can also send us a general message using our staff box.<br/> 
Every people listed in this page can consult and respond to those messages when they feel the need.<br/><br/></td></tr>
<tr><td class="embedded" colspan="12"><hr size="1"></hr></td></tr>
<tr><td align="center"><input name="submit" type="submit" class="btn" value="Staff Box" /></td></tr>
</table>
</form>
<?
end_frame();


?> 

<?
end_main_frame();
stdfoot();
?>