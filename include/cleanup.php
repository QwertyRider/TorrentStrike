<?

require_once("bittorrent.php");

function docleanup($full_cleanup=false) {
	global $torrent_dir, $signup_timeout, $max_dead_torrent_time, $autoclean_interval, $max_dead_user_time, $max_dead_topic_time, $ad_ratio, $ap_time, $ap_limit, $ap_ratio, $torrent_ttl;
	
	// include needed to purge also phpbb accounts (not done by autocleanup)
	if ($full_cleanup==true)
	{
		global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum,$phpbb2_folder,$db;
		if(($share_phpbb2_users_with_TBDev==true)&&($activate_phpbb2_forum==true))
		{
				require_once("include/phpbb2Bridge.php");
		}
	}

	set_time_limit(0);
	ignore_user_abort(1);

	do {
		$res = mysql_query("SELECT id FROM torrents");
		$ar = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			$ar[$id] = 1;
		}

		if (!count($ar))
			break;

		$dp = @opendir($torrent_dir);
		if (!$dp)
			break;

		$ar2 = array();
		while (($file = readdir($dp)) !== false) {
			if (!preg_match('/^(\d+)\.torrent$/', $file, $m))
				continue;
			$id = $m[1];
			$ar2[$id] = 1;
			if (isset($ar[$id]) && $ar[$id])
				continue;
			$ff = $torrent_dir . "/$file";
			unlink($ff);
		}
		closedir($dp);

		if (!count($ar2))
			break;

		$delids = array();
		foreach (array_keys($ar) as $k) {
			if (isset($ar2[$k]) && $ar2[$k])
				continue;
			$delids[] = $k;
			unset($ar[$k]);
		}
		if (count($delids))
			mysql_query("DELETE FROM torrents WHERE id IN (" . join(",", $delids) . ")");

		$res = mysql_query("SELECT torrent FROM peers GROUP BY torrent");
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if (isset($ar[$id]) && $ar[$id])
				continue;
			$delids[] = $id;
		}
		if (count($delids))
			mysql_query("DELETE FROM peers WHERE torrent IN (" . join(",", $delids) . ")");

		$res = mysql_query("SELECT torrent FROM files GROUP BY torrent");
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if ($ar[$id])
				continue;
			$delids[] = $id;
		}
		if (count($delids))
			mysql_query("DELETE FROM files WHERE torrent IN (" . join(",", $delids) . ")");
	} while (0);

	$deadtime = deadtime();
	mysql_query("DELETE FROM peers WHERE last_action < FROM_UNIXTIME($deadtime)");

	$deadtime -= $max_dead_torrent_time;
	mysql_query("UPDATE torrents SET visible='no' WHERE visible='yes' AND last_action < FROM_UNIXTIME($deadtime)");

	
	// ---- delete unconfirmed invites -----
	
	$deadtime = time() - $signup_timeout;
	$user = mysql_query("SELECT invited_by FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime) AND last_access = '0000-00-00 00:00:00'");
	$arr = mysql_fetch_assoc($user);
	if (mysql_num_rows($user) > 0)
	{
		$invites = mysql_query("SELECT invites FROM users WHERE id = $arr[invited_by]");
		$arr2 = mysql_fetch_assoc($invites);
		if ($arr2[invites] < 10)
		{
			$invites = $arr2[invites] +1;
			mysql_query("UPDATE users SET invites='$invites' WHERE id = $arr[invited_by]");
		}
		if ($arr2)
			mysql_query("DELETE FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime) AND last_login < FROM_UNIXTIME($deadtime) AND last_access < FROM_UNIXTIME($deadtime) AND last_access = '0000-00-00 00:00:00'");
	}
	
	// ---- delete unconfirmed user accounts including phpbb accounts (not done by autocleanup, use manual Cleanup from the control panel to call this)
	if ($full_cleanup==true)
	{
		$deadtime = time() - $signup_timeout;
		$res = mysql_query("SELECT id,username FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime) AND last_login < FROM_UNIXTIME($deadtime) AND last_access < FROM_UNIXTIME($deadtime)");
	  while ($row = mysql_fetch_assoc($res))
	  {
	  	mysql_query("DELETE FROM users WHERE id=".$row["id"]);
	  	delete_phpBB2user($row["username"],"nopasswordcheck",false);
	  }
	}


	$torrents = array();
	$res = mysql_query("SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder");
	while ($row = mysql_fetch_assoc($res)) {
		if ($row["seeder"] == "yes")
			$key = "seeders";
		else
			$key = "leechers";
		$torrents[$row["torrent"]][$key] = $row["c"];
	}

	$res = mysql_query("SELECT torrent, COUNT(*) AS c FROM comments GROUP BY torrent");
	while ($row = mysql_fetch_assoc($res)) {
		$torrents[$row["torrent"]]["comments"] = $row["c"];
	}

	$fields = explode(":", "comments:leechers:seeders");
	$res = mysql_query("SELECT id, seeders, leechers, comments FROM torrents");
	while ($row = mysql_fetch_assoc($res)) {
		$id = $row["id"];
		$torr = $torrents[$id];
		foreach ($fields as $field) {
			if (!isset($torr[$field]))
				$torr[$field] = 0;
		}
		$update = array();
		foreach ($fields as $field) {
			if ($torr[$field] != $row[$field])
				$update[] = "$field = " . $torr[$field];
		}
		if (count($update))
			mysql_query("UPDATE torrents SET " . implode(",", $update) . " WHERE id = $id");
	}

	// ---- delete inactive user accounts including phpbb accounts (not done by autocleanup, use manual Cleanup from the control panel to call this)
	if ($full_cleanup==true)
	{
		$dt = sqlesc(get_date_time(gmtime() - $max_dead_user_time));
		$maxclass = UC_POWER_USER;
		$res = mysql_query("SELECT id,username FROM users WHERE status = 'confirmed' AND class <= $maxclass AND last_access < $dt");
	  while ($row = mysql_fetch_assoc($res))
	  {
	  	mysql_query("DELETE FROM users WHERE id=".$row["id"]);
	  	delete_phpBB2user($row["username"],"nopasswordcheck",false);
	  }
	}

	// lock topics where last post was made more than x days ago
	/*$res = mysql_query("SELECT topics.id FROM topics LEFT JOIN posts ON topics.lastpost = posts.id AND topics.sticky = 'no' WHERE " . gmtime() . " - UNIX_TIMESTAMP(posts.added) > 	$secs = $max_dead_topic_time") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
		mysql_query("UPDATE topics SET locked='yes' WHERE id=$arr[id]") or sqlerr(__FILE__, __LINE__);*/

  //remove expired warnings
  $res = mysql_query("SELECT id FROM users WHERE warned='yes' AND warneduntil < NOW() AND warneduntil <> '0000-00-00 00:00:00'") or sqlerr(__FILE__, __LINE__);
  if (mysql_num_rows($res) > 0)
  {
    $dt = sqlesc(get_date_time());
    $msg = sqlesc("Your warning has been removed. Please keep in your best behaviour from now on.\n");
    while ($arr = mysql_fetch_assoc($res))
    {
      mysql_query("UPDATE users SET warned = 'no', warneduntil = '0000-00-00 00:00:00' WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
      mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
    }
  }

	// promote power users
	$maxdt = sqlesc(get_date_time(gmtime() - $ap_time));
	$res = mysql_query("SELECT id FROM users WHERE class = ".UC_USER." AND uploaded >= $ap_limit AND uploaded / downloaded >= $ap_ratio AND added < $maxdt") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0)
	{
		$dt = sqlesc(get_date_time());
		$msg = sqlesc("Congratulations, you have been auto-promoted to [b]Power User[/b]. :)\n");
		while ($arr = mysql_fetch_assoc($res))
		{
			mysql_query("UPDATE users SET class = ".UC_POWER_USER." WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
			mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
		}
	}

	// demote power users
	$res = mysql_query("SELECT id FROM users WHERE class = ".UC_POWER_USER." AND uploaded / downloaded < $ad_ratio") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0)
	{
		$dt = sqlesc(get_date_time());
		$msg = sqlesc("You have been auto-demoted from [b]Power User[/b] to [b]User[/b] because your share ratio has dropped below $ad_ratio.\n");
		while ($arr = mysql_fetch_assoc($res))
		{
			mysql_query("UPDATE users SET class = ".UC_USER." WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
			mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
		}
	}

	// Update stats
	$seeders = get_row_count("peers", "WHERE seeder='yes'");
	$leechers = get_row_count("peers", "WHERE seeder='no'");
	mysql_query("UPDATE avps SET value_u=$seeders WHERE arg='seeders'") or sqlerr(__FILE__, __LINE__);
	mysql_query("UPDATE avps SET value_u=$leechers WHERE arg='leechers'") or sqlerr(__FILE__, __LINE__);

	// update forum post/topic count
	/*$forums = mysql_query("select id from forums");
	while ($forum = mysql_fetch_assoc($forums))
	{
		$postcount = 0;
		$topiccount = 0;
		$topics = mysql_query("select id from topics where forumid=$forum[id]");
		while ($topic = mysql_fetch_assoc($topics))
		{
			$res = mysql_query("select count(*) from posts where topicid=$topic[id]");
			$arr = mysql_fetch_row($res);
			$postcount += $arr[0];
			++$topiccount;
		}
		mysql_query("update forums set postcount=$postcount, topiccount=$topiccount where id=$forum[id]");
	}*/
	
	// delete old torrents
	$dt = sqlesc(get_date_time(gmtime() - $torrent_ttl));
	$res = mysql_query("SELECT id, name FROM torrents WHERE added < $dt AND seeders=0 AND leechers=0 ");
	while ($arr = mysql_fetch_assoc($res))
	{
		@unlink("$torrent_dir/$arr[id].torrent");
		mysql_query("DELETE FROM torrents WHERE id=$arr[id]");
		mysql_query("DELETE FROM peers WHERE torrent=$arr[id]");
		mysql_query("DELETE FROM comments WHERE torrent=$arr[id]");
		mysql_query("DELETE FROM files WHERE torrent=$arr[id]");
		write_log("Torrent $arr[id] ($arr[name]) was deleted by system (older than $days days)");
	}

	
	// --- Manage invits
	
	autoinvites(10,1,4,.90,1);
	autoinvites(10,4,7,.95,2);
	autoinvites(10,7,10,1.00,3);
	autoinvites(10,10,100000,1.05,4);
	
}

// $lenght:   (in days) number of days to wait before giving new invits
// $minlimit: (in GigaBytes) minimum of downloaded GigaBytes to receive the new invits
// $maxlimit: (in GigaBytes) maximum of downloaded GigaBytes to receive the new invits
// $minratio: Minimal ratio needed to receive the new invits
// $invites:  Number of invite to give

function autoinvites($length, $minlimit, $maxlimit, $minratio, $invites)
{
	$time = sqlesc(get_date_time(gmtime() - (($length)*86400)));
	$minlimit = $minlimit*1024*1024*1024;
	$maxlimit = $maxlimit*1024*1024*1024;
	$res = mysql_query("SELECT id, invites FROM users WHERE class > 0 AND enabled = 'yes' AND downloaded >= $minlimit AND downloaded < $maxlimit AND uploaded / downloaded >= $minratio AND warned = 'no' AND invites < 10 AND invitedate < $time") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0)
	{
		while ($arr = mysql_fetch_assoc($res))
		{
			if ($arr[invites] == 9)
			$invites = 1;
			elseif ($arr[invites] == 8 && $invites == 3)
			$invites = 2;
			mysql_query("UPDATE users SET invites = invites+$invites, invitedate = NOW() WHERE id=$arr[id]") or sqlerr(__FILE__, __LINE__);
		}
	}
}

?>