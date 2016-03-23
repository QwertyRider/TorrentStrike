<?

function local_user()
{
  return $_SERVER["SERVER_ADDR"] == $_SERVER["REMOTE_ADDR"];
}

if(!file_exists('include/config.php'))
            die("Site is down for maintenance, please check back again later... thanks<br/>");
require_once('include/config.php');
if(empty($mysql_user) && empty($mysql_pass))
            die("Site is down for maintenance, please check back again later... thanks<br/>");

require_once('cleanup.php');
require_once('global.php');


/**** validip/getip courtesy of manolete <manolete@myway.com> ****/

// IP Validation
function validip($ip)
{
        if (!empty($ip) && $ip == long2ip(ip2long($ip)))
        {
                // reserved IANA IPv4 addresses
                // http://www.iana.org/assignments/ipv4-address-space
                $reserved_ips = array (
                                array('0.0.0.0','2.255.255.255'),
                                array('10.0.0.0','10.255.255.255'),
                                array('127.0.0.0','127.255.255.255'),
                                array('169.254.0.0','169.254.255.255'),
                                array('172.16.0.0','172.31.255.255'),
                                array('192.0.2.0','192.0.2.255'),
                                array('192.168.0.0','192.168.255.255'),
                                array('255.255.255.0','255.255.255.255')
                );

                foreach ($reserved_ips as $r)
                {
                                $min = ip2long($r[0]);
                                $max = ip2long($r[1]);
                                if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
                }
                return true;
        }
        else return false;
}

// Patched function to detect REAL IP address if it's valid

function getip() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

    return $ip;
  }

function info()
{
	$sf ="info/stat.txt";
	$fpsf=fopen($sf,"a+");
	$ip=getenv("REMOTE_ADDR");
	$ag=getenv("HTTP_USER_AGENT");
	$from=getenv("HTTP_REFERER");
	$host=getenv("REQUEST_URI");
	$date = date("d.m.y");
	$time= date("H:i:s");
	fputs($fpsf,"$date#$time#$ip#$ag#$from#$host\n");
	fclose($fpsf);
}

function userlogin() {
    global $SITE_ONLINE;
    unset($GLOBALS["CURUSER"]);

    $ip = getip();
        $nip = ip2long($ip);
    $res = mysql_query("SELECT * FROM bans WHERE $nip >= first AND $nip <= last") or sqlerr(__FILE__, __LINE__);
    if (mysql_num_rows($res) > 0)
    {
      header("HTTP/1.0 403 Forbidden");
      print("<html><body><h1>403 Forbidden</h1>Unauthorized IP address.</body></html>\n");
      die;
    }

    if (!$SITE_ONLINE || empty($_COOKIE["uid"]) || empty($_COOKIE["pass"]))
        return;
    $id = 0 + $_COOKIE["uid"];
    if (!$id || strlen($_COOKIE["pass"]) != 32)
        return;
    $res = mysql_query("SELECT * FROM users WHERE id = $id AND enabled='yes' AND status = 'confirmed'");// or die(mysql_error());
    $row = mysql_fetch_array($res);
    if (!$row)
        return;
    $sec = hash_pad($row["secret"]);
    if ($_COOKIE["pass"] !== $row["passhash"])
        return;
    mysql_query("UPDATE users SET last_access='" . get_date_time() . "', ip=".sqlesc($ip)." WHERE id=" . $row["id"]);// or die(mysql_error());
   $row['ip'] = $ip;
////FOR TEMPORARY DEMOTION
   if ($row['override_class'] < $row['class']) $row['class'] = $row['override_class']; // Override class and save in GLOBAL array below.

   $GLOBALS["CURUSER"] = $row;
}

function autoclean() {
    global $autoclean_interval;

    $now = time();
    $docleanup = 0;

    $res = mysql_query("SELECT value_u FROM avps WHERE arg = 'lastcleantime'");
    $row = mysql_fetch_array($res);
    if (!$row) {
        mysql_query("INSERT INTO avps (arg, value_u) VALUES ('lastcleantime',$now)");
        return;
    }
    $ts = $row[0];
    if ($ts + $autoclean_interval > $now)
        return;
    mysql_query("UPDATE avps SET value_u=$now WHERE arg='lastcleantime' AND value_u = $ts");
    if (!mysql_affected_rows())
        return;

    docleanup();
}

function unesc($x) {
    if (get_magic_quotes_gpc())
        return stripslashes($x);
    return $x;
}

function mksize($bytes)
{
        if ($bytes < 1000 * 1024)
                return number_format($bytes / 1024, 2) . " kB";
        elseif ($bytes < 1000 * 1048576)
                return number_format($bytes / 1048576, 2) . " MB";
        elseif ($bytes < 1000 * 1073741824)
                return number_format($bytes / 1073741824, 2) . " GB";
        else
                return number_format($bytes / 1099511627776, 2) . " TB";
}

function mksizeint($bytes)
{
        $bytes = max(0, $bytes);
        if ($bytes < 1000)
                return floor($bytes) . " B";
        elseif ($bytes < 1000 * 1024)
                return floor($bytes / 1024) . " kB";
        elseif ($bytes < 1000 * 1048576)
                return floor($bytes / 1048576) . " MB";
        elseif ($bytes < 1000 * 1073741824)
                return floor($bytes / 1073741824) . " GB";
        else
                return floor($bytes / 1099511627776) . " TB";
}

function deadtime() {
    global $announce_interval;
    return time() - floor($announce_interval * 1.3);
}

function mkprettytime($s) {
    if ($s < 0)
        $s = 0;
    $t = array();
    foreach (array("60:sec","60:min","24:hour","0:day") as $x) {
        $y = explode(":", $x);
        if ($y[0] > 1) {
            $v = $s % $y[0];
            $s = floor($s / $y[0]);
        }
        else
            $v = $s;
        $t[$y[1]] = $v;
    }

    if ($t["day"])
        return $t["day"] . "d " . sprintf("%02d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
    if ($t["hour"])
        return sprintf("%d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
//    if ($t["min"])
        return sprintf("%d:%02d", $t["min"], $t["sec"]);
//    return $t["sec"] . " secs";
}

function mkglobal($vars) {
    if (!is_array($vars))
        $vars = explode(":", $vars);
    foreach ($vars as $v) {
        if (isset($_GET[$v]))
            $GLOBALS[$v] = unesc($_GET[$v]);
        elseif (isset($_POST[$v]))
            $GLOBALS[$v] = unesc($_POST[$v]);
        else
            return 0;
    }
    return 1;
}

function tr($x,$y,$noesc=0) {
    if ($noesc)
        $a = $y;
    else {
        $a = htmlspecialchars($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    print("<tr><td class=\"row2\" valign=\"top\" align=\"right\">$x</td><td class=\"row1\" valign=\"top\" align=\"left\">$a</td></tr>\n");
}

function validfilename($name) {
    return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
}

function validemail($email) {
    return preg_match('/^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
}

function sqlesc($x) {
    return "'".mysql_real_escape_string($x)."'";
}

function sqlwildcardesc($x) {
    return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}

function urlparse($m) {
    $t = $m[0];
    if (preg_match(',^\w+://,', $t))
        return "<a href=\"$t\">$t</a>";
    return "<a href=\"http://$t\">$t</a>";
}

function parsedescr($d, $html) {
    if (!$html)
    {
      $d = htmlspecialchars($d);
      $d = str_replace("\n", "\n<br/>", $d);
    }
    return $d;
}

function stdhead($title = "", $msgalert = true) {
    global $CURUSER, $SITE_ONLINE, $FUNDS, $SITENAME, $BASEURL;
    global $ss_uri;

  if (!$SITE_ONLINE)
    die("Site is down for maintenance, please check back again later... thanks<br/>");

    header("Content-Type: text/html; charset=iso-8859-1");
    //header("Pragma: No-cache");
    if ($title == "")
        $title = $SITENAME .(isset($_GET['tbv'])?" (".TBVERSION.")":'');
    else
        $title = $SITENAME .(isset($_GET['tbv'])?" (".TBVERSION.")":''). " :: " . htmlspecialchars($title);
  if ($CURUSER)
  {
    $ss_a = @mysql_fetch_array(@mysql_query("select uri from stylesheets where id=" . $CURUSER["stylesheet"]));
    if ($ss_a) $ss_uri = $ss_a["uri"];
  }
  if (!$ss_uri)
  {
    ($r = mysql_query("SELECT uri FROM stylesheets WHERE id=1")) or die(mysql_error());
    ($a = mysql_fetch_array($r)) or die(mysql_error());
    $ss_uri = $a["uri"];
  }
  if ($msgalert && $CURUSER)
  {
    $res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " && unread='yes'") or die("OopppsY!");
    $arr = mysql_fetch_row($res);
    $unread = $arr[0];
  }

  require_once "themes/".$ss_uri."/template.php";
  require_once("themes/" . $ss_uri . "/stdhead.php");

  if ($unread)
  {
    print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"10\"><tr><td style=\"padding: 10px; background: red\">\n");
    print("<b><a href=\"$BASEURL/messages.php?action=viewmailbox\"><font color=\"white\">You have $unread new message" . ($unread > 1 ? "s" : "") . "!</font></a></b>");
    print("</td></tr></table>\n");
  }
  ////FOR TEMPORARY DEMOTION
   if ($CURUSER['override_class'] != 255 && $CURUSER) // Second condition needed so that this box isn't displayed for non members/logged out members.
 {
  print("<table class=\"coltable\"><tr><td>\n");
  print("<b><a class=\"altlink\" href=\"$BASEURL/restoreclass.php\">You are running under a lower class. Click here to restore.</a></b>");
  print("</td></tr></table>\n");
 }

} // stdhead

function stdfoot()
{
  global $CURUSER;
  global $ss_uri;

  if ($CURUSER)
  {
    $ss_a = @mysql_fetch_array(@mysql_query("select uri from stylesheets where id=" . $CURUSER["stylesheet"]));
    if ($ss_a) $ss_uri = $ss_a["uri"];
  }
  if (!$ss_uri)
  {
    ($r = mysql_query("SELECT uri FROM stylesheets WHERE id=1")) or die(mysql_error());
    ($a = mysql_fetch_array($r)) or die(mysql_error());
    $ss_uri = $a["uri"];
  }

  require_once "themes/".$ss_uri."/template.php";
  require_once("themes/" . $ss_uri . "/stdfoot.php");
}

function genbark($x,$y) {
    stdhead($y);
    print("<h2>" . htmlspecialchars($y) . "</h2>\n");
    print("<p>" . htmlspecialchars($x) . "</p>\n");
    stdfoot();
    exit();
}

function mksecret($len = 20) {
    $ret = "";
    for ($i = 0; $i < $len; $i++)
        $ret .= chr(mt_rand(0, 255));
    return $ret;
}

function httperr($code = 404) {
    header("HTTP/1.0 404 Not found");
    print("<h1>Not Found</h1>\n");
    print("<p>Sorry pal :(</p>\n");
    exit();
}

function gmtime()
{
    return strtotime(get_date_time());
}

function logincookie($id, $passhash, $updatedb = 1, $expires = 0x7fffffff)
{
        setcookie("uid", $id, $expires, "/");
        setcookie("pass", $passhash, $expires, "/");

  if ($updatedb)
          mysql_query("UPDATE users SET last_login = NOW() WHERE id = $id");
}

function logoutcookie() {
    setcookie("uid", "", 0x7fffffff, "/");
    setcookie("pass", "", 0x7fffffff, "/");
}

function loggedinorreturn() {
    global $CURUSER;
    if (!$CURUSER) {
        header("Location: login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]));
        exit();
    }
}

function deletetorrent($id) {
    global $torrent_dir;
    mysql_query("DELETE FROM torrents WHERE id = $id");
    foreach(explode(".","peers.files.comments.ratings") as $x)
        mysql_query("DELETE FROM $x WHERE torrent = $id");
    unlink("$torrent_dir/$id.torrent");
}

function pager($rpp, $count, $href, $opts = array()) {
    $pages = ceil($count / $rpp);

    if (!$opts["lastpagedefault"])
        $pagedefault = 0;
    else {
        $pagedefault = floor(($count - 1) / $rpp);
        if ($pagedefault < 0)
            $pagedefault = 0;
    }

    if (isset($_GET["page"])) {
        $page = 0 + $_GET["page"];
        if ($page < 0)
            $page = $pagedefault;
    }
    else
        $page = $pagedefault;

    $pager = "";

    $mp = $pages - 1;
    $as = "<b>&lt;&lt;&nbsp;Prev</b>";
    if ($page >= 1) {
        $pager .= "<a href=\"{$href}page=" . ($page - 1) . "\">";
        $pager .= $as;
        $pager .= "</a>";
    }
    else
        $pager .= $as;
    $pager .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $as = "<b>Next&nbsp;&gt;&gt;</b>";
    if ($page < $mp && $mp >= 0) {
        $pager .= "<a href=\"{$href}page=" . ($page + 1) . "\">";
        $pager .= $as;
        $pager .= "</a>";
    }
    else
        $pager .= $as;

    if ($count) {
        $pagerarr = array();
        $dotted = 0;
        $dotspace = 3;
        $dotend = $pages - $dotspace;
        $curdotend = $page - $dotspace;
        $curdotstart = $page + $dotspace;
        for ($i = 0; $i < $pages; $i++) {
            if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
                if (!$dotted)
                    $pagerarr[] = "...";
                $dotted = 1;
                continue;
            }
            $dotted = 0;
            $start = $i * $rpp + 1;
            $end = $start + $rpp - 1;
            if ($end > $count)
                $end = $count;
            $text = "$start&nbsp;-&nbsp;$end";
            if ($i != $page)
                $pagerarr[] = "<a href=\"{$href}page=$i\"><b>$text</b></a>";
            else
                $pagerarr[] = "<b>$text</b>";
        }
        $pagerstr = join(" | ", $pagerarr);
        $pagertop = "<p align=\"center\">$pager<br />$pagerstr</p>\n";
        $pagerbottom = "<p align=\"center\">$pagerstr<br />$pager</p>\n";
    }
    else {
        $pagertop = "<p align=\"center\">$pager</p>\n";
        $pagerbottom = $pagertop;
    }

    $start = $page * $rpp;

    return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
}

function downloaderdata($res) {
    $rows = array();
    $ids = array();
    $peerdata = array();
    while ($row = mysql_fetch_assoc($res)) {
        $rows[] = $row;
        $id = $row["id"];
        $ids[] = $id;
        $peerdata[$id] = array(downloaders => 0, seeders => 0, comments => 0);
    }

    if (count($ids)) {
        $allids = implode(",", $ids);
        $res = mysql_query("SELECT COUNT(*) AS c, torrent, seeder FROM peers WHERE torrent IN ($allids) GROUP BY torrent, seeder");
        while ($row = mysql_fetch_assoc($res)) {
            if ($row["seeder"] == "yes")
                $key = "seeders";
            else
                $key = "downloaders";
            $peerdata[$row["torrent"]][$key] = $row["c"];
        }
        $res = mysql_query("SELECT COUNT(*) AS c, torrent FROM comments WHERE torrent IN ($allids) GROUP BY torrent");
        while ($row = mysql_fetch_assoc($res)) {
            $peerdata[$row["torrent"]]["comments"] = $row["c"];
        }
    }

    return array($rows, $peerdata);
}

function commenttable($rows)
{
  global $CURUSER, $HTTP_SERVER_VARS;
  $count = 0;
  foreach ($rows as $row)
  {
	  $username = $row['username'];
	
		switch ($row['class'])
		{
			case UC_SYSOP:
			$row['class'] = '(SysOps)';
			break;
			case UC_ADMINISTRATOR:
			 $row['class'] = '(Admin)';
			break;
			case UC_MODERATOR:
			$row['class'] = '(Mod)';
			break;
			case UC_UPLOADER:
			$row['class'] = '(User)';
			break;
			case UC_USER:
			$row['class'] = '(Leecher)';
			break;
		}
		
		$frame_title = "#" . $row['id'] . " by <a href=\"userdetails.php?id=" . $row['user'] . "\"><b>" . htmlspecialchars($row['username']) . '</b></a>' . ($row['donor'] == 'yes' ? "<img src=\"pic/star.gif\" alt='Donor'/>" : "") . ($row['warned'] == 'yes' ? "<img src=\"pic/warned.gif\" alt=\"Warned\"/>" : "") . "" . $row['class'];
		begin_frame($frame_title,true,10,750,false);
		begin_table(true);
	
		if($row['user'] == $CURUSER['id'] || get_user_class() >= UC_MODERATOR) 
		{
		    $editcom = "[<a class=\"altlink\" href=\"comment.php?action=edit&amp;cid=$row[id]\">Edit</a>]";
		}
		else 
		{
		    $editcom = '';
		}
		if(get_user_class() >= UC_MODERATOR) 
		{
		    $delcom = "- [<a class=\"altlink\" href=\"comment.php?action=delete&amp;cid=$row[id]\">Delete</a>]";
		}
	
	  $avatar = htmlspecialchars($row['avatar']);
		if (!$avatar) $avatar = 'pic/default_avatar.gif';
	  $addedd = "<i>Added: " . $row['added'] . " GMT</i><br/>";
	  $text = $addedd . format_comment($row['text']);
		if ($row['editedby']) $text .= "<p><font size=\"1\" class=\"small\">Last edited by <a href=\"userdetails.php?id=$row[editedby]\"><b>$username</b></a> at $row[editedat] GMT</font></p>\n";
	
	  print("<tr valign=\"top\">\n");
	  print("<td align=\"left\" width=\"120\" class=\"tableb\"><img width=\"120\" src=\"$avatar\" alt=\"\"/></td>\n");
	  print("<td class=\"tableb\">$text $body</td>\n");
	  print("</tr>\n");
	  print("<tr><td colspan=\"2\" class=\"heading\"><div align=\"right\">$editcom $delcom</div></td></tr>");
	  
	  end_table();
	  end_frame(false);
	}
}

function searchfield($s) {
    return preg_replace(array('/[^a-z0-9]/si', '/^\s*/s', '/\s*$/s', '/\s+/s'), array(" ", "", "", " "), $s);
}

function genrelist() {
    $ret = array();
    $res = mysql_query("SELECT id, name FROM categories ORDER BY name");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}

function linkcolor($num) {
    if (!$num)
        return "red";
//    if ($num == 1)
//        return "yellow";
    return "green";
}

function ratingpic($num) {
    global $pic_base_url;
    $r = round($num * 2) / 2;
    if ($r < 1 || $r > 5)
        return;
    return "<img src=\"pic/".rank_."$r.gif\" border=\"0\" alt=\"$num / 5\" />";
}

function torrenttable($res, $variant = "index") {
        global $torrent_ttl,$pic_base_url, $CURUSER;

        if ($CURUSER["class"] < UC_VIP)
  {
          $gigs = $CURUSER["uploaded"] / (1024*1024*1024);
          $ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
          if ($ratio < 0.5 || $gigs < 5) $wait = 48;
          elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
          elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
          elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
          else $wait = 0;
  }
begin_table(true);
?>

<tr>

<td class="colhead" align="center">Type</td>
<td class="colhead" align="left">Name</td>
<!--<td class="heading" align=left>DL</td>-->
<?
        if ($wait)
        {
                print("<td class=\"colhead\" align=\"center\">Wait</td>\n");
        }

        if ($variant == "mytorrents")
  {
          print("<td class=\"colhead\" align=\"center\">Edit</td>\n");
    print("<td class=\"colhead\" align=\"center\">Visible</td>\n");
        }

?>
<td class="colhead" align="right">Files</td>
<td class="colhead" align="right">Comm.</td>
<td class="colhead" align="center">Rating</td>
<td class="colhead" align="center">Added</td>
<td class="colhead" align="center">TTL</td>
<td class="colhead" align="center">Size</td>
<!--
<td class="colhead" align=right>Views</td>
<td class="colhead" align=right>Hits</td>
-->
<td class="colhead" align="center">Snatched</td>
<td class="colhead" align="right">Seeders</td>
<td class="colhead" align="right">Leechers</td>
<?

    if ($variant == "index")
        print("<td class=\"colhead\" align=\"center\">Upped&nbsp;by</td>\n");

    print("</tr>\n");

    while ($row = mysql_fetch_assoc($res)) {
        $id = $row["id"];
        print("<tr>\n");

        print("<td align=\"center\" class=\"row2\">");
        if (isset($row["cat_name"])) {
            print("<a href=\"browse.php?cat=" . $row["category"] . "\">");
            if (isset($row["cat_pic"]) && $row["cat_pic"] != "")
                print("<img border=\"0\" src=\"pic/" . $row["cat_pic"] . "\" alt=\"" . $row["cat_name"] . "\" />");
            else
                print($row["cat_name"]);
            print("</a>");
        }
        else
            print("-");
        print("</td>\n");

        $dispname = htmlspecialchars($row["name"]);
        print("<td class=\"row2\" align=\"left\"><a href=\"details.php?");
        if ($variant == "mytorrents")
            print("returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;");
        print("id=$id");
        if ($variant == "index")
            print("&amp;hit=1");
        print("\"><b>$dispname</b></a></td>\n");

                                if ($wait)
                                {
                                  $elapsed = floor((gmtime() - strtotime($row["added"])) / 3600);
                if ($elapsed < $wait)
                {
                  $color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
                  print("<td class=\"row2\" align=\"center\"><a href=\"faq.php#id_46\"><font color=\"$color\">" . number_format($wait - $elapsed) . " h</font></a></td>\n");
                }
                else
                  print("<td class=\"row2\" align=\"center\">None</td>\n");
        }

/*
        if ($row["nfoav"] && get_user_class() >= UC_POWER_USER)
          print("<a href=viewnfo.php?id=$row[id]><img src=pic/viewnfo.gif border=0 alt='View NFO'></a>\n");
        if ($variant == "index")
            print("<a href=\"download.php/$id/" . rawurlencode($row["filename"]) . "\"><img src=pic/download.gif border=0 alt=Download></a>\n");

        else */ if ($variant == "mytorrents")
            print("<td class=\"row2\" align=\"center\"><a href=\"edit.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;id=" . $row["id"] . "\">edit</a></td>\n");
        if ($variant == "mytorrents") {
            print("<td class=\"row2\" align=\"right\">");
            if ($row["visible"] == "no")
                print("<b>no</b>");
            else
                print("yes");
            print("</td>\n");
        }

        if ($row["type"] == "single")
            print("<td class=\"row2\" align=\"right\">" . $row["numfiles"] . "</td>\n");
        else {
            if ($variant == "index")
                print("<td class=\"row2\" align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;filelist=1\">" . $row["numfiles"] . "</a></b></td>\n");
            else
                print("<td class=\"row2\" align=\"right\"><b><a href=\"details.php?id=$id&amp;filelist=1#filelist\">" . $row["numfiles"] . "</a></b></td>\n");
        }

        if (!$row["comments"])
            print("<td class=\"row2\" align=\"right\">" . $row["comments"] . "</td>\n");
        else {
            if ($variant == "index")
                print("<td class=\"row2\" align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;tocomm=1\">" . $row["comments"] . "</a></b></td>\n");
            else
                print("<td class=\"row2\" align=\"right\"><b><a href=\"details.php?id=$id&amp;page=0#startcomments\">" . $row["comments"] . "</a></b></td>\n");
        }


        print("<td class=\"row2\"  align=\"center\">");
        if (!isset($row["rating"]))
            print("---");
        else {
            $rating = round($row["rating"] * 2) / 2;
            $rating = ratingpic($row["rating"]);
            if (!isset($rating))
                print("---");
            else
                print($rating);
        }
        print("</td>\n");

        print("<td class=\"row2\" align=\"center\">" . str_replace(" ", "<br />", $row["added"]) . "</td>\n");
                $ttl = (floor($torrent_ttl/3600)) - floor((gmtime() - sql_timestamp_to_unix_timestamp($row["added"])) / 3600);
                if ($ttl == 1) $ttl .= "<br/>hour"; else $ttl .= "<br/>hours";
        print("<td class=\"row2\" align=\"center\">$ttl</td>\n");
        print("<td class=\"row2\" align=\"center\">" . str_replace(" ", "<br/>", mksize($row["size"])) . "</td>\n");
//        print("<td align=\"right\">" . $row["views"] . "</td>\n");
//        print("<td align=\"right\">" . $row["hits"] . "</td>\n");
        $_s = "";
        if ($row["times_completed"] != 1)
          $_s = "s";
        print("<td class=\"row2\" align=\"center\">" . number_format($row["times_completed"]) . "<br/>time$_s</td>\n");

        if ($row["seeders"]) {
            if ($variant == "index")
            {
               if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"]; else $ratio = 1;
                print("<td class=\"row2\" align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;toseeders=1\"><font color=\"" .
                  get_slr_color($ratio) . "\">" . $row["seeders"] . "</font></a></b></td>\n");
            }
            else
                print("<td class=\"row2\" align=\"right\"><b><a class=\"" . linkcolor($row["seeders"]) . "\" href=\"details.php?id=$id&amp;dllist=1#seeders\">" .
                  $row["seeders"] . "</a></b></td>\n");
        }
        else
            print("<td class=\"row2\" align=\"right\"><span class=\"" . linkcolor($row["seeders"]) . "\">" . $row["seeders"] . "</span></td>\n");

        if ($row["leechers"]) {
            if ($variant == "index")
                print("<td class=\"row2\" align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;todlers=1\">" .
                   number_format($row["leechers"]) . ($peerlink ? "</a>" : "") .
                   "</b></td>\n");
            else
                print("<td class=\"row2\" align=\"right\"><b><a class=\"" . linkcolor($row["leechers"]) . "\" href=\"details.php?id=$id&amp;dllist=1#leechers\">" .
                  $row["leechers"] . "</a></b></td>\n");
        }
        else
            print("<td class=\"row2\" align=\"right\">0</td>\n");

        if ($variant == "index")
            print("<td class=\"row2\" align=\"center\">" . (isset($row["username"]) ? ("<a href=\"userdetails.php?id=" . $row["owner"] . "\"><b>" . htmlspecialchars($row["username"]) . "</b></a>") : "<i>(unknown)</i>") . "</td>\n");

        print("</tr>\n");
    }

    end_table();

    return $rows;
}

function hash_pad($hash) {
    return str_pad($hash, 20);
}

function hash_where($name, $hash) {
    $shhash = preg_replace('/ *$/s', "", $hash);
    return "($name = " . sqlesc($hash) . " OR $name = " . sqlesc($shhash) . ")";
}

function get_user_icons($arr, $big = false)
{
        if ($big)
        {
                $donorpic = "starbig.gif";
                $warnedpic = "warnedbig.gif";
                $disabledpic = "disabledbig.gif";
                $style = "style='margin-left: 4pt'";
        }
        else
        {
                $donorpic = "star.gif";
                $warnedpic = "warned.gif";
                $disabledpic = "disabled.gif";
                $style = "style=\"margin-left: 2pt\"";
        }
        $pics = $arr["donor"] == "yes" ? "<img src=\"pic/$donorpic\" alt='Donor' border=\"0\" $style/>" : "";
        if ($arr["enabled"] == "yes")
                $pics .= $arr["warned"] == "yes" ? "<img src=\"pic/$warnedpic\" alt=\"Warned\" border=\"0\" $style/>" : "";
        else
                $pics .= "<img src=\"pic/$disabledpic\" alt=\"Disabled\" border=\"0\" $style/>\n";
        return $pics;
}

function verify_passkey($passkey)
{
  global $CURUSER;
  if (strlen($CURUSER['passkey']) != 32)
  {
          do {
                         $CURUSER['passkey'] = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
                        $notok=mysql_query("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]") === FALSE;
                } while($notok);
        }

        return($CURUSER['passkey']==$passkey);
}


// Old dbconn() function, now isn't called, but is done automatically when it's included
    if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
    {
                  switch (mysql_errno())
                  {
                                case 1040:
                                case 2002:
                                        if ($_SERVER[REQUEST_METHOD] == "GET")
                                                die("<html><head><meta http-equiv=refresh content=\"5 $_SERVER[REQUEST_URI]\"></head><body><table border=0 width=100% height=100%><tr><td><h3 align=center>The server load is very high at the moment. Retrying, please wait...</h3></td></tr></table></body></html>");
                                        else
                                                die("Too many users. Please press the Refresh button in your browser to retry.");
        default:
                die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());
      }
    }
    mysql_select_db($mysql_db)
        or die('dbconn: mysql_select_db: ' + mysql_error());

    userlogin();

    if (basename($_SERVER['SCRIPT_FILENAME']) == 'index.php')
        register_shutdown_function("autoclean");

// Empty dbconn for compatibility
function dbconn()
{
}

?>