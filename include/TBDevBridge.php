<?

// --------------------------------------------------------------------
// ------------------------ Update TBDev password ------------------------
// --------------------------------------------------------------------

function update_TBDevpassword ($username, $wantpassword)
{
	global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	if ((!isset($wantpassword))||(!isset($username))) return;

	dbconn(true);
	$res = mysql_query("SELECT id, secret FROM users WHERE username = '$username'");
	$row = mysql_fetch_array($res);
	$passhash = md5($row["secret"] . $wantpassword . $row["secret"]);
	
	mysql_query("UPDATE users SET passhash='" . $passhash . "' WHERE id=".$row["id"]);
	logincookie($row["id"], $passhash, hash_pad($row["secret"], 20));

}

// --------------------------------------------------------------------
// ------------------------ Update TBDev email ------------------------
// --------------------------------------------------------------------

function update_TBDevemail ($username, $newemail)
{
	global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	if ((!isset($newemail))||(!isset($username))) return;
	
	dbconn(true);
	$res = mysql_query("SELECT id FROM users WHERE username = '$username'");
	$row = mysql_fetch_array($res);
	mysql_query("UPDATE users SET email='" . $newemail . "' WHERE id=".$row["id"]);
}

// --------------------------------------------------------------------
// ------------------------ Update TBDev style ------------------------
// --------------------------------------------------------------------

function update_TBDevstyle ($username, $newstyle)
{
	global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	if ((!isset($newstyle))||(!isset($username))) return;
	
	dbconn(true);
	$res = mysql_query("SELECT id FROM stylesheets WHERE phpbb_style = '$newstyle'");
	if ($row = mysql_fetch_array($res))
	{
		mysql_query("UPDATE users SET stylesheet=" . $row["id"] . " WHERE username='".$username."'");
	}
}

?>