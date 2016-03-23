<?
require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

// The following line may need to be changed to UC_MODERATOR if you don't have Forum Moderators
if ($CURUSER['class'] < UC_MODERATOR) die(); // No acces to below this rank
if ($CURUSER['override_class'] != 255) die(); // No access to an overridden user class either - just in case

if ($_GET['action'] == 'editclass') //Process the querystring - No security checks are done as a temporary class higher
{                                   //than the actual class mean absoluetly nothing.
$newclass = 0+$_GET['class'];
   $returnto = $_GET['returnto'];

   mysql_query("UPDATE users SET override_class = ".sqlesc($newclass)." WHERE id = ".$CURUSER['id']); // Set temporary class

   header("Location: ".$BASEURL."/".$returnto);
   die();
}

// HTML Code to allow changes to current class
stdhead("Set override class for " . $CURUSER["username"]);
?>
<br/><font size="4"><b>Allows you to change your user class on the fly.</b></font><br/><br/>

<form method="get" action="setclass.php">
<input type="hidden" name="action" value="editclass"/>
<input type="hidden" name="returnto" value="userdetails.php?id=<?=$CURUSER['id']?>"/> <!-- Change to any page you want -->
<?begin_frame("Change your class on the fly",true,5,true);
begin_table();
?>
<tr><td class="row2">Class <select name="class">
<?
$maxclass = get_user_class() - 1;
for ($i = 0; $i <= $maxclass; ++$i)
{
	$currentclass = get_user_class_name($i);
	if ($currentclass)
		print("<option value=\"$i\"" .">" . get_user_class_name($i) . "</option>\n");
}
?>
</select></td></tr>
<tr><td align="center" class="row2"><input type="submit" value="Okay"/></td></tr>

<?end_table();
end_frame();?>
</form>
<br/>
<?
stdfoot();
?>