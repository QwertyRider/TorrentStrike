<?

if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;

require_once ("./include/config.php");

define ('IN_PHPBB', true);
define ('IN_PORTAL', true);
$phpbb_root_path = "./".$phpbb2_folder."/";
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_validate.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_post.' . $phpEx);
include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);
require_once($phpbb_root_path . 'includes/functions_selects.'.$phpEx);



// --------------------------------------------------------------------------------
// ------------------------------ Create a new user inside phpBB2 -----------------
// --------------------------------------------------------------------------------

function insert_phpBB2user($user_name, $user_password, $user_email, $activate_account=1, $group_id = '')
{
		global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
		if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	
		global $db,$board_config;
		
		// Now we need to set the remaining fields to some default values
		// If you wish to integrate with another MOD, you should add any initilization
		// it requires after this

		$user_name = str_replace("\'", "''", addslashes($user_name));
		$user_password = str_replace("\'", "''", addslashes($user_password));
		$user_email = str_replace("\'", "''", addslashes($user_email));
		
		$user_fields['user_regdate'] = time();
		$user_fields['user_from'] = '';
		$user_fields['user_occ'] = '';
		$user_fields['user_interests'] = '';
		$user_fields['user_website'] = '';
		$user_fields['user_icq'] = '';
		$user_fields['user_aim'] = '';
		$user_fields['user_yim'] = '';
		$user_fields['user_msnm'] = '';
		$user_fields['user_sig'] = '';
		$user_fields['user_sig_bbcode_uid'] = ( $board_config['allow_bbcode'] ) ? make_bbcode_uid() : '';
		$user_fields['user_avatar'] = '';
		$user_fields['user_avatar_type'] = USER_AVATAR_NONE;
		$user_fields['user_viewemail'] = 1;
		$user_fields['user_attachsig'] = 1;
		$user_fields['user_allowsmile'] = $board_config['allow_smilies'];
		$user_fields['user_allowhtml'] = $board_config['allow_html'];
		$user_fields['user_allowbbcode'] = $board_config['allow_bbcode'];
		$user_fields['user_allow_viewonline'] = 1;
		$user_fields['user_notify'] = 0;
		$user_fields['user_notify_pm'] = 1;
		$user_fields['user_popup_pm'] = 1;
		$user_fields['user_timezone'] = $board_config['board_timezone'];
		$user_fields['user_dateformat'] = $board_config['default_dateformat'];
		$user_fields['user_lang'] = $board_config['default_lang'];
		$user_fields['user_style'] = $board_config['default_style'];
		$user_fields['user_level'] = USER;
		$user_fields['user_posts'] = 0;
		$user_fields['user_active'] = $activate_account;
		
		// add the group

		if ($group_id != '')
		{
			$groups[] = $group_id;
		}
	
		// Validate Username
		
		$name_check = validate_username(stripslashes(str_replace("''", "\'", $user_name)));
		if ($name_check['error'])
		{
			return false;
		}
		
		// Validate email

		$email_check = validate_email(stripslashes(str_replace("''", "\'", $user_email)));
		if ($email_check['error'])
		{
			return false;
		}

		// Get Userid

		$sql = "SELECT MAX(user_id) AS total
			FROM " . USERS_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}
		
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}
		$user_id = $row['total'] + 1;

		// Build the main SQL query
		$sql = "INSERT INTO " . USERS_TABLE . "	(user_id, username, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_sig_bbcode_uid, user_avatar, user_avatar_type, user_viewemail, user_aim, user_yim, user_msnm, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_viewonline, user_notify, user_notify_pm, user_popup_pm, user_timezone, user_dateformat, user_lang, user_style, user_level, user_allow_pm, user_active, user_actkey, user_posts) ";
		$sql .= "VALUES (" . $user_id . ", '" . $user_name . "', '" . $user_fields['user_regdate'] . "', '" . $user_password . "', '" . $user_email . "', '" . $user_fields['user_icq'] . "', '" . $user_fields['user_website'] . "', '" . $user_fields['user_occ'] . "', '". $user_fields['user_from'] . "', '" . $user_fields['user_interests'] . "', '" . $user_fields['user_sig'] . "', '" . $user_fields['user_sig_bbcode_uid'] . "', '" . $user_fields['user_avatar'] . "', '" . $user_fields['user_avatar_type'] . "', " . $user_fields['user_viewemail'] . ", '" . str_replace(' ', '+', $user_fields['user_aim']) . "', '" . $user_fields['user_yim'] . "', '" . $user_fields['user_msnm'] . "', " . $user_fields['user_attachsig'] . ", " . $user_fields['user_allowsmile'] . ", " . $user_fields['user_allowhtml'] . ", " . $user_fields['user_allowbbcode'] . ", " . $user_fields['user_allow_viewonline'] . ", " . $user_fields['user_notify'] . ", " . $user_fields['user_notify_pm'] . ", " . $user_fields['user_popup_pm'] . ", " . $user_fields['user_timezone'] . ", '" . $user_fields['user_dateformat'] . "', '" . $user_fields['user_lang'] . "', " . $user_fields['user_style'] . ", " . $user_fields['user_level'] . ", 1, " . $user_fields['user_active'] . ", '', '" . $user_fields['user_posts'] . "')";

		// Insert the user
		if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
		{
			$error = true;
		}


		// Insert the personal group
		$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
			VALUES ('', 'Personal User', 1, 0)";
		if ( !($result = $db->sql_query($sql)) )
		{
			$error = true;
		}

		$group_id = $db->sql_nextid();

		// Insert the user_group entry
		$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
			VALUES (" . $user_id . ", $group_id, 0)";
		if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
		{
			$error = true;
		}

		// Add the user to any applicable groups
		for ($i=0; $i<count($groups); $i++)
		{
			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
				VALUES (" . $user_id . ", " . $groups[$i] . ", 0)";
			if( !($result = $db->sql_query($sql)) )
			{
				$error = true;
			}
		}
		return ($error == true) ? false : true;

}

// --------------------------------------------------------------------------------
// ------------------- Activate and login a phpbb2 account ------------------------
// --------------------------------------------------------------------------------

function activate_and_login_phpBB2user ($username,$dologin=true)
{
	global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	
	global $db, $board_config, $user_ip;
	
	$sql = "SELECT user_id,
	              username,
	              user_password,
	              user_active,
	              user_level
	         FROM " . USERS_TABLE . "
	        WHERE username = '" . $username . "'";
	
	if (!($result = $db->sql_query ($sql)))
	{
		message_die (GENERAL_ERROR, "Error in obtaining userdata", "", __LINE__, __FILE__, $sql);
		exit;
	}
	
 if ($row = $db->sql_fetchrow ($result))
 {
		if (!$board_config["board_disable"] ||
		($row["user_level"] == ADMIN))
		{
				$sql = "UPDATE " . USERS_TABLE . " SET user_active = 1 WHERE username = '" . $username . "'";
				
				if (!($result = $db->sql_query ($sql)))
				{
					message_die (GENERAL_ERROR, "Error in activating user account", "", __LINE__, __FILE__, $sql);
					exit;
				}
				
				if ($dologin==true)
				{
					login_phpBB2user ($username, $row["user_password"], TRUE);
				}
		}
	}
	
}


// --------------------------------------------------------------------------------
// ------------------- Update password of phpbb2 account --------------------------
// --------------------------------------------------------------------------------

function update_phpBB2userPassword ($username, $password)
{
	global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	
	global $db, $board_config, $user_ip;
	
	$sql = "SELECT user_id,
	              username,
	              user_password,
	              user_active,
	              user_level
	         FROM " . USERS_TABLE . "
	        WHERE username = '" . $username . "'";
	
	if (!($result = $db->sql_query ($sql)))
	{
		message_die (GENERAL_ERROR, "Error in obtaining userdata", "", __LINE__, __FILE__, $sql);
		exit;
	}
   
	if (!$board_config["board_disable"] ||
	($row["user_level"] == ADMIN))
	{
		//FIXME: if ($row["user_active"])
		{
			$sql = "UPDATE " . USERS_TABLE . " SET user_password = '" . $password. "' WHERE username = '" . $username . "'";
			
			if (!($result = $db->sql_query ($sql)))
			{
				message_die (GENERAL_ERROR, "Error in updating password", "", __LINE__, __FILE__, $sql);
				exit;
			}
		}
	}
}

// --------------------------------------------------------------------------------
// ------------------- Update email of phpbb2 account --------------------------
// --------------------------------------------------------------------------------

function update_phpBB2userEmail ($username, $email)
{
	global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	
	
	global $db, $board_config, $user_ip;
	
	$sql = "SELECT user_id,
	              username,
	              user_email,
	              user_active,
	              user_level
	         FROM " . USERS_TABLE . "
	        WHERE username = '" . $username . "'";
	
	if (!($result = $db->sql_query ($sql)))
	{
		message_die (GENERAL_ERROR, "Error in obtaining userdata", "", __LINE__, __FILE__, $sql);
		exit;
	}
   
	if (!$board_config["board_disable"] ||
	($row["user_level"] == ADMIN))
	{
		// FIXME: if ($row["user_active"])
		{
			$sql = "UPDATE " . USERS_TABLE . " SET user_email = '" . $email. "' WHERE username = '" . $username . "'";
			
			if (!($result = $db->sql_query ($sql)))
			{
				message_die (GENERAL_ERROR, "Error in updating email", "", __LINE__, __FILE__, $sql);
				exit;
			}
		}
	}
}

// --------------------------------------------------------------------------------
// ------------------- Perform a login of a phpbb2 account ------------------------
// --------------------------------------------------------------------------------

function login_phpBB2user ($username, $password, $autologin)
{
   global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
   if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
   
   global $db, $board_config, $user_ip;

   $sql = "SELECT user_id,
                  username,
                  user_password,
                  user_active,
                  user_level
             FROM " . USERS_TABLE . "
            WHERE username = '" . $username . "'";

   if (!($result = $db->sql_query ($sql)))
   {
      //message_die (GENERAL_ERROR, "Error in obtaining userdata", "", __LINE__, __FILE__, $sql);
      exit;
   }

   if ($row = $db->sql_fetchrow ($result))
   {
      if (!$board_config["board_disable"] ||
          ($row["user_level"] == ADMIN))
      {
         if ($row["user_active"])
         {
            if ($row["user_password"] == $password)
            {
               if ($userdata = session_begin ($row["user_id"], $user_ip, PAGE_INDEX, FALSE, $autologin))
               {
                  return TRUE;
               }
               else
               {
                 // message_die (CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
                  return FALSE;
               }
            }
            else
            {
               //message_die (GENERAL_ERROR, "Password mismatch between main site and forum", "", __LINE__, __FILE__, $sql);
               return FALSE;
            }
         }
         else
         {
            //message_die (GENERAL_ERROR, "User is not active in forum", "", __LINE__, __FILE__, $sql);
            return FALSE;
         }
      }
      else
      {
         //message_die (GENERAL_ERROR, "Forum is disabled", "", __LINE__, __FILE__, $sql);
         return FALSE;
      }
   }
   else
   {
      //message_die (GENERAL_ERROR, "Username mismatch between main site and forum", "", __LINE__, __FILE__, $sql);
      return FALSE;
   }
}

// --------------------------------------------------------------------------------
// ------------------- perform a logout of phpbb2 account -------------------------
// --------------------------------------------------------------------------------

function logout_phpBB2user ()
{
	 global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	 if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	
   global $user_ip;

   $userdata = session_pagestart ($user_ip, PAGE_LOGIN);
   init_userprefs ($userdata);

   if ($userdata["session_logged_in"])
   {
      session_end ($userdata["session_id"], $userdata["user_id"]);
   }
}


// --------------------------------------------------------------------
// ------------------- Delete a phpbb2 account ------------------------
// --------------------------------------------------------------------

function delete_phpBB2user ($username, $password, $checkpassword=true)
{
	 global $db,$lang,$share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	 if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	 
   $sql = "SELECT user_id,
                  username,
                  user_password,
                  user_active,
                  user_level
             FROM " . USERS_TABLE . "
            WHERE username = '" . $username . "'";

   if (!($result = $db->sql_query ($sql)))
   {
      message_die (GENERAL_ERROR, "Error in obtaining userdata", "", __LINE__, __FILE__, $sql);
      exit;
   }

		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain user_id information', '', __LINE__, __FILE__, $sql);
			return;
		}
		
		$user_id = $row['user_id'];

		if (!($this_userdata = get_userdata($user_id)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
			return;
		}
		
		if (($row['user_password']!=$password)&&($checkpassword==true))
		{
			message_die(GENERAL_MESSAGE, "Password mismatch phpbb user not deleted" );
			return;
		}
			
			$sql = "SELECT g.group_id
				FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
				WHERE ug.user_id = $user_id
					AND g.group_id = ug.group_id
					AND g.group_single_user = 1";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
				return;
			}

			$row = $db->sql_fetchrow($result);
			
			$sql = "UPDATE " . POSTS_TABLE . "
				SET poster_id = " . DELETED . ", post_username = '" . str_replace("\\'", "''", addslashes($this_userdata['username'])) . "' 
				WHERE poster_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update posts for this user', '', __LINE__, __FILE__, $sql);
				return;
			}

			$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_poster = " . DELETED . " 
				WHERE topic_poster = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update topics for this user', '', __LINE__, __FILE__, $sql);
				return;
			}
			
			$sql = "UPDATE " . VOTE_USERS_TABLE . "
				SET vote_user_id = " . DELETED . "
				WHERE vote_user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update votes for this user', '', __LINE__, __FILE__, $sql);
				return;
			}
			
			$sql = "SELECT group_id
				FROM " . GROUPS_TABLE . "
				WHERE group_moderator = $user_id";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select groups where user was moderator', '', __LINE__, __FILE__, $sql);
				return;
			}
			
			while ( $row_group = $db->sql_fetchrow($result) )
			{
				$group_moderator[] = $row_group['group_id'];
			}
			
			if ( count($group_moderator) )
			{
				$update_moderator_id = implode(', ', $group_moderator);
				
				$sql = "UPDATE " . GROUPS_TABLE . "
					SET group_moderator = " . $user_id . "
					WHERE group_moderator IN ($update_moderator_id)";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
					return;
				}
			}

			$sql = "DELETE FROM " . USERS_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user', '', __LINE__, __FILE__, $sql);
				return;
			}

			$sql = "DELETE FROM " . USER_GROUP_TABLE . "
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from user_group table', '', __LINE__, __FILE__, $sql);
				return;
			}

			$sql = "DELETE FROM " . GROUPS_TABLE . "
				WHERE group_id = " . $row['group_id'];
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
				return;
			}

			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
				WHERE group_id = " . $row['group_id'];
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
				return;
			}

			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE user_id = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from topic watch table', '', __LINE__, __FILE__, $sql);
				return;
			}
			
			$sql = "DELETE FROM " . BANLIST_TABLE . "
				WHERE ban_userid = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user from banlist table', '', __LINE__, __FILE__, $sql);
				return;
			}

			$sql = "SELECT privmsgs_id
				FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_from_userid = $user_id 
					OR privmsgs_to_userid = $user_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
				return;
			}

			// This little bit of code directly from the private messaging section.
			while ( $row_privmsgs = $db->sql_fetchrow($result) )
			{
				$mark_list[] = $row_privmsgs['privmsgs_id'];
			}
			
			if ( count($mark_list) )
			{
				$delete_sql_id = implode(', ', $mark_list);
				
				$delete_text_sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
					WHERE privmsgs_text_id IN ($delete_sql_id)";
				$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id IN ($delete_sql_id)";
				
				if ( !$db->sql_query($delete_sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
					return;
				}
				
				if ( !$db->sql_query($delete_text_sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $delete_text_sql);
					return;
				}
			}
}

// ------------------------------------------------------------------------
// ------------------- Update phpbb Theme of users ------------------------
// ------------------------------------------------------------------------

function update_phpBB2style ($username,$TS_style)
{
	global $share_phpbb2_users_with_TBDev,$activate_phpbb2_forum;
	if(($share_phpbb2_users_with_TBDev!=true)||($activate_phpbb2_forum!=true)) return;
	
	global $db, $board_config, $user_ip;
	
	$res = mysql_query("SELECT phpbb_style FROM stylesheets WHERE id='".$TS_style."'") or die(mysql_error());
	if (!($row = mysql_fetch_array($res))) return;
	
	$sql = "SELECT themes_id,
	              style_name 
	         FROM " . THEMES_TABLE . "
	        WHERE style_name = '" . $row["phpbb_style"] . "'";

	if (!($result = $db->sql_query ($sql)))
	{
		message_die (GENERAL_ERROR, "Error in obtaining theme list", "", __LINE__, __FILE__, $sql);
		exit;
	}
	
 if ($row = $db->sql_fetchrow ($result))
 {
 		$new_style = $row["themes_id"];
 	}
 	else
 	{
 		$new_style = $board_config['default_style'];
 	}
	
	$sql = "SELECT user_id,
	              username,
	              user_level
	         FROM " . USERS_TABLE . "
	        WHERE username = '" . $username . "'";
	
	if (!($result = $db->sql_query ($sql)))
	{
		message_die (GENERAL_ERROR, "Error in obtaining userdata", "", __LINE__, __FILE__, $sql);
		exit;
	}
	
 if ($row = $db->sql_fetchrow ($result))
 {
		if (!$board_config["board_disable"] ||
		($row["user_level"] == ADMIN))
		{
				$sql = "UPDATE " . USERS_TABLE . " SET user_style = $new_style WHERE username = '" . $username . "'";
				
				if (!($result = $db->sql_query ($sql)))
				{
					message_die (GENERAL_ERROR, "Error in updating phpbb user theme", "", __LINE__, __FILE__, $sql);
					exit;
				}
		}
	}
}



?>