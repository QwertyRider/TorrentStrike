<?
ob_start("ob_gzhandler");

require "include/bittorrent.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $choice = isset($_POST["choice"]) ? $_POST["choice"] : 257;
  if ($CURUSER && $choice != "" && $choice < 256 && $choice == floor($choice))
  {
    $res = mysql_query("SELECT * FROM polls ORDER BY added DESC LIMIT 1") or sqlerr();
    $arr = mysql_fetch_assoc($res) or die("No poll");
    $pollid = $arr["id"];
    $userid = $CURUSER["id"];
    $res = mysql_query("SELECT * FROM pollanswers WHERE pollid=$pollid && userid=$userid") or sqlerr();
    $arr = mysql_fetch_assoc($res);
    if ($arr) die("Dupe vote");
    mysql_query("INSERT INTO pollanswers VALUES(0, $pollid, $userid, $choice)") or sqlerr();
    if (mysql_affected_rows() != 1)
      stderr("Error", "An error occured. Your vote has not been counted.");
    header("Location: $BASEURL/");
    die;
  }
  else
    stderr("Error", "Please select an option.");
}

$a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1"));
if ($CURUSER)
  $latestuser = "<a href=\"userdetails.php?id=" . $a["id"] . "\">" . $a["username"] . "</a>";
else
  $latestuser = $a['username'];

$registered = number_format(get_row_count("users"));
$torrents = number_format(get_row_count("torrents"));
$seeders = get_row_count("peers", "WHERE seeder='yes'");
$leechers = get_row_count("peers", "WHERE seeder='no'");
$result = mysql_query("SELECT SUM(downloaded) AS totaldl, SUM(uploaded) AS totalul FROM users") or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_assoc($result);
$totaldownloaded = mksize($row["totaldl"]);
$totaluploaded = mksize($row["totalul"]);
$seeders = $seeders;
$leechers = $leechers;
$seeders = number_format($seeders);
$leechers = number_format($leechers);
$peers = number_format($leechers + $seeders);
if ($seeders == "0") { $ratio = "0"; }
elseif ($leechers == "0") { $ratio = "0"; }
else {
$ratio = round($seeders / $leechers * 100);
}

stdhead();

$news_title = "Recent news";

print("<table width=\"737\" class=\"main\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"embedded\">");
if (get_user_class() >= UC_ADMINISTRATOR)
	$news_title.=" - <font class=\"small\">[<a class=\"altlink\" href=\"news.php\"><b>News page</b></a>]</font>";
$res = mysql_query("SELECT * FROM news WHERE ADDDATE(added, INTERVAL 45 DAY) > NOW() ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) > 0)
{
	begin_frame($news_title,false,5);
	print("<ul>");
	
	while($array = mysql_fetch_array($res))
	{
	  print("<li>" . gmdate("Y-m-d",strtotime($array['added'])) . " - " . $array['body']);
    if (get_user_class() >= UC_ADMINISTRATOR)
    {
    	print(" <font size=\"-2\">[<a class=\"altlink\" href=\"news.php?action=edit&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>E</b></a>]</font>");
    	print(" <font size=\"-2\">[<a class=\"altlink\" href=\"news.php?action=delete&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>D</b></a>]</font>");
    }
    print("</li>");
  }
  print("</ul>");
  end_frame();
}

 if ($CURUSER)
{
  // Get current poll
  $res = mysql_query("SELECT * FROM polls ORDER BY added DESC LIMIT 1") or sqlerr();
  if($pollok=(mysql_num_rows($res)))
  {
  	$arr = mysql_fetch_assoc($res);
  	$pollid = $arr["id"];
  	$userid = $CURUSER["id"];
  	$question = $arr["question"];
  	$o = array($arr["option0"], $arr["option1"], $arr["option2"], $arr["option3"], $arr["option4"],
    	$arr["option5"], $arr["option6"], $arr["option7"], $arr["option8"], $arr["option9"],
    	$arr["option10"], $arr["option11"], $arr["option12"], $arr["option13"], $arr["option14"],
    	$arr["option15"], $arr["option16"], $arr["option17"], $arr["option18"], $arr["option19"]);

  // Check if user has already voted
  	$res = mysql_query("SELECT * FROM pollanswers WHERE pollid=$pollid AND userid=$userid") or sqlerr();
  	$arr2 = mysql_fetch_assoc($res);
  }

  //print("<h2>Poll");
  
  $poll_title = "Poll";

  if (get_user_class() >= UC_MODERATOR)
  {
  	$poll_title.="<font class=\"small\">";
		$poll_title.=" - [<a class=\"altlink\" href=\"makepoll.php?returnto=main\"><b>New Poll</b></a>]\n";
		if($pollok) {
  		$poll_title.=" - [<a class=\"altlink\" href=\"makepoll.php?action=edit&amp;pollid=$arr[id]&amp;returnto=main\"><b>Edit</b></a>]\n";
			$poll_title.=" - [<a class=\"altlink\" href=\"polls.php?action=delete&amp;pollid=$arr[id]&amp;returnto=main\"><b>Delete</b></a>]";
		}
		$poll_title.="</font>";
	}
	
	if($pollok) {
	begin_frame($poll_title,true,5);
  	print("<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">");
  	print("<p align=\"center\"><b>$question</b></p>\n");
  	$voted = $arr2;
  	if ($voted)
  	{
    	// display results
    	if ($arr["selection"])
      	$uservote = $arr["selection"];
    	else
      	$uservote = -1;
		// we reserve 255 for blank vote.
    	$res = mysql_query("SELECT selection FROM pollanswers WHERE pollid=$pollid AND selection < 20") or sqlerr();

    	$tvotes = mysql_num_rows($res);

    	$vs = array(); // array of
    	$os = array();

    	// Count votes
    	while ($arr2 = mysql_fetch_row($res))
      	$vs[$arr2[0]] += 1;

    	reset($o);
    	for ($i = 0; $i < count($o); ++$i)
      	if ($o[$i])
        	$os[$i] = array($vs[$i], $o[$i]);

    	function srt($a,$b)
    	{
      	if ($a[0] > $b[0]) return -1;
      	if ($a[0] < $b[0]) return 1;
      	return 0;
    	}

    	// now os is an array like this: array(array(123, "Option 1"), array(45, "Option 2"))
    	if ($arr["sort"] == "yes")
    		usort($os, srt);

    	print("<table class=\"main\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n");
    	$i = 0;
    	while ($a = $os[$i])
    	{
      	if ($i == $uservote)
        	$a[1] .= "&nbsp;*";
      	if ($tvotes == 0)
      		$p = 0;
      	else
      		$p = round($a[0] / $tvotes * 100);
      	if ($i % 2)
        	$c = "";
      	else
        	$c = "";
      	print("<tr><td width=\"1%\" class=\"embedded\" $c>" . $a[1] . "&nbsp;&nbsp;</td><td width=\"99%\" class=\"embedded\" $c>" .
        	"<img src=\"pic/bar_left.gif\" alt=\"\" /><img src=\"pic/bar.gif\" height=\"9\" width=\"" . ($p * 3) .
        	"\" alt=\"\" /><img src=\"pic/bar_right.gif\" alt=\"\" /> $p%</td></tr>\n");
      	++$i;
    	}
    	print("</table>\n");
			$tvotes = number_format($tvotes);
    	print("<p align=\"center\">Votes: $tvotes</p>\n");
  	}
  	else
  	{
    	print("<form method=\"post\" action=\"index.php\">\n");
    	$i = 0;
    	while ($a = $o[$i])
    	{
      	print("<input type=\"radio\" name=\"choice\" value=\"$i\" />$a<br />\n");
      	++$i;
    	}
    	print("<br />");
    	print("<input type=\"radio\" name=\"choice\" value=\"255\" />Blank vote (a.k.a. \"I just want to see the results!\")<br />\n");
    	print("<p align=\"center\"><input type=\"submit\" value=\"Vote!\" class=\"btn\" /></p></form>");
  	}
?>
</td></tr></table>
<?
if ($voted)
  print("<p align=\"center\"><a href=\"polls.php\">Previous polls</a></p>\n");
end_frame();
?>

<?
	} else {
		echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td align=\"center\">\n";
  	echo "<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">";
  	echo"<h3>No Active Polls</h3>\n";
  	echo "</td></tr></table></td></tr></table>";
	}
}
?>

<?begin_frame("Stats",true,5);?>
<?begin_table();?>
<tr><td class="rowhead">Registered users</td><td class="rowhead" align="right"><?=$registered?></td></tr>
<tr><td class="rowhead2">Torrents</td><td class="rowhead2" align="right"><?=$torrents?></td></tr>
<? if (isset($peers)) { ?>
<tr><td class="rowhead">Peers</td><td class="rowhead" align="right"><?=$peers?></td></tr>
<tr><td class="rowhead2">Seeders</td><td class="rowhead2" align="right"><?=$seeders?></td></tr>
<tr><td class="rowhead">Leechers</td><td class="rowhead" align="right"><?=$leechers?></td></tr>
<tr><td class="rowhead2">Seeder/leecher ratio (%)</td><td class="rowhead2" align="right"><?=$ratio?></td></tr>
<? } ?>
<?end_table();?>
<?end_frame();?>

<?begin_frame("Server load",true,5);?>
<table class="main" border="0" width="402"><tr><td style='padding: 0px; background-image: url(pic/loadbarbg.gif); background-repeat: repeat-x'>
<? $percent = min(100, round(exec('ps ax | grep -c apache') / 256 * 100));
if ($percent <= 70) $pic = "loadbargreen.gif";
elseif ($percent <= 90) $pic = "loadbaryellow.gif";
else $pic = "loadbarred.gif";
$width = $percent * 4;
print("<img height=\"15\" width=\"$width\" src=\"pic/$pic\" alt='$percent%' />"); ?>
</td></tr></table>
<?end_frame();?>

<?

// ---- online users

$dt = gmtime() - 300;
$dt = sqlesc(get_date_time($dt));
$numberactive = number_format(get_row_count("users", "WHERE last_access >=$dt"));
$res = mysql_query("SELECT id, username, class, warned, donor FROM users WHERE last_access >=$dt ORDER BY class DESC") or print(mysql_error());
while ($arr = mysql_fetch_assoc($res))
{
  if ($activeusers) $activeusers .= ",\n";
  switch ($arr["class"])
  {
    case UC_SYSOP:
      $arr["username"] = "<font color=\"#0F6CEE\">" . $arr["username"] . "</font>";
      break;
    case UC_ADMINISTRATOR:
      $arr["username"] = "<font color=\"#30EE0F\">" . $arr["username"] . "</font>";
      break;
    case UC_MODERATOR:
      $arr["username"] = "<font color=\"#EE950F\">" . $arr["username"] . "</font>";
      break;
     case UC_UPLOADER:
      $arr["username"] = "<font color=\"#EAEE0F\">" . $arr["username"] . "</font>";
      break;
     case UC_VIP:
      $arr["username"] = "<font color=\"#9C2FE0\">" . $arr["username"] . "</font>";
      break;
     case UC_POWER_USER:
      $arr["username"] = "<font color=\"#D21E36\">" . $arr["username"] . "</font>";
      break;
    case UC_USER:
      $arr["username"] = "" . $arr["username"] . "";
      break;
  }

if ($arr["donor"] == "yes")  $star = "<img src=\"pic/star.gif\" alt=\"Donated\"/>";
else $star = "";

if ($arr["warned"] == "yes")  $warn = "<img src=\"pic/warned.gif\" alt=\"Warned\"/>";
else $warn = "";

$donator = $arr["donated"] > 0;

  if ($donator)
    $activeusers .= "";
    $activeusers .= "" . $arr["username"] . $star . $warn . "";
  if ($donator)
    $activeusers .= "";

}
if (!$activeusers)
  $activeusers = "There have been no active users in the last 15 minutes.";

?>
<? begin_frame("Users Online (".$numberactive.")",false,5) ?>

<?
begin_table(true);
echo "<tr><td class=\"colhead\">";
echo $activeusers;
echo "</td></tr>";
end_table();
?>

<center>
<font class="small" color="#0F6CEE">Sysop</font> | 
<font class="small" color="#30EE0F">Administator</font> | 
<font class="small" color="#EE950F">Moderator</font> | 
<font class="small" color="#EAEE0F">Uploader</font> | 
<font class="small" color="#9C2FE0">VIP</font> | 
<font class="small" color="#D21E36">PowerUser</font> | User

<?
echo "<br/><font class=\"small\">Welcome to our newest member <b>$latestuser</b>!</font></center>\n";
end_frame ();
// -------------------------------
?> 

<p><font class="small">Disclaimer: None of the files shown here are actually hosted on this server. The links are provided solely by this site's users.
The administrator of this site (yoursite) cannot be held responsible for what its users post, or any other actions of its users.
You may not use this site to distribute or download any material when you do not have the legal rights to do so.
It is your own responsibility to adhere to these terms.</font></p>
</td></tr></table>

<?
stdfoot();
?>