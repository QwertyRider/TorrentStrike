<?
  // ---------------------------------------------------------------------------------------------------------

  function begin_main_frame()
  {
    print("<table class=\"main\" width=\"750\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"embedded\">\n");
  }

  function end_main_frame()
  {
    print("</td></tr></table>\n");
  }


  // ---------------------------------------------------------------------------------------------------------

  function begin_table($fullwidth = false, $padding = 5)
  {
  	if ($fullwidth == true) $tableWidth = ' width="100%"';
  	echo "<table".$tableWidth." cellspacing=\"1\" class=\"coltable\">";
  }

  function end_table()
  {
  	echo "</table>";
  }
  
  // ---------------------------------------------------------------------------------------------------------

	function begin_frame($caption = "&nbsp;", $center = true, $padding = 10, $full_width="100%")
	{
		if ($center==true) $center_code="align=\"center\"";
		if ($full_width==true) $width_code="width=\"$full_width\"";
		?>
		<table class="theme_table" <?echo $width_code?> cellpadding="10" <?echo $center_code?>>
			<tr>
				<td <?echo $center_code?>>
				  <table border="0" cellpadding="0" cellspacing="0" class="tbt">
				  	<tr><td class="tbtl"><img src="./phpBB2/images/spacer.gif" alt="" width="22" /></td><td class="tbtbot" nowrap="nowrap"><b><?=$caption?></b><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="22" style="vertical-align: middle;" /></td><td class="tbtr"><img src="./phpBB2/images/spacer.gif" alt="" width="124" height="22" /></td></tr>
				  </table>
				  <table width="100%" border="0" cellspacing="1" cellpadding="4" class="forumline">
						<tr>
							<td class="row1">
				  <table border="0" cellpadding="0" cellspacing="0" class="tbt">
		<?
				  	echo "<tr><td $center_code>";
	}

  function attach_frame($padding = 10)
  {
    print("</td></tr><tr><td style='border-top: 0px'>\n");
  }

  function end_frame()
  {
		?>
		</td></tr>
		</table>
				</td>
		  </tr>
			<tr>
				<td class="row2" align="center"><span class="genmed"><a href="#" class="genmed">Back to top</a></span></td>
			</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>
			</td>
			</tr>
		</table>
		<?
  }

	// ---------------------------------------------------------------------------------------------------------
  
  function insert_smilies_frame()
  {
    global $smilies, $BASEURL;

    begin_frame("Smilies", true);

    begin_table(false, 5);

    print("<tr><td class=colhead>Type...</td><td class=colhead>To make a...</td></tr>\n");

    while (list($code, $url) = each($smilies))
      print("<tr><td>$code</td><td><img src=$BASEURL/pic/smilies/$url></td>\n");

    end_table();

    end_frame();
  }
  
	// ---------------------------------------------------------------------------------------------------------------------
	// --------------------- new modules: (only used by ICGstation stdhead) display user name/class/ration  ---------------
	// ---------------------------------------------------------------------------------------------------------------------
  
	function display_user_info()
	{
		global $CURUSER;

  	if (isset($CURUSER[id])) 
  	{
  		print("<div align=\"left\"><font class=\"copyright\">");
  		
			if ($CURUSER["downloaded"] > 0)
			{
				$userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
			}
			else
			{
				if ($CURUSER["uploaded"] > 0)
				$userratio = "Inf.";
				else
				$userratio = "NA";
			}
			//FOR TEMPORARY DEMOTION BY RETRO
			if ($CURUSER['override_class'] != 255) $usrclass = "&nbsp;(".get_user_class_name($CURUSER['class']).")&nbsp;";
			elseif(get_user_class() >= UC_MODERATOR) $usrclass = "&nbsp;<a href=\"setclass.php\">(".get_user_class_name($CURUSER['class']).")</a>&nbsp;";
			
			print("&nbsp;&nbsp;Welcome, <a href=\"userdetails.php?id=$CURUSER[id]\">$CURUSER[username]</a>".$usrclass);
			if($CURUSER["invites"] == 0)
				print(", you have no invite&nbsp;&nbsp;&nbsp;");
			else
				print(", you have ".$CURUSER["invites"]." <a href=\"invite.php\">invites</a>&nbsp;&nbsp;&nbsp;");
  		print("<img src=\"./themes/ICGstation/images/down.png\" border=\"0\" alt=\"Downloaded\" align=\"middle\" />&nbsp;".mksize($CURUSER[downloaded]));
  		print("&nbsp;&nbsp;<img src=\"./themes/ICGstation/images/up.png\" border=\"0\" alt=\"Uploaded\" align=\"middle\" />&nbsp;".mksize($CURUSER[uploaded]));
  		print("&nbsp;&nbsp;<img src=\"./themes/ICGstation/images/button_cancel.png\" border=\"0\" alt=\"Ratio\" align=\"middle\" />&nbsp;".$userratio."&nbsp;");
  		print("</font></div>\n");
  	}
	}
	
  
	// -------------------------------------------------------------------------------------------------------
	// --------------------- new function: (only used by ICGstation stdhead) draw left blocks  ---------------
	// -------------------------------------------------------------------------------------------------------

	function begin_block($caption = "", $center = false, $padding = 10)
	{
		?>
		<table class="theme_table" width="100%" border="0" cellspacing="1" cellpadding="10" align="center">
			<tr>
				<td align="center">
				  <table border="0" cellpadding="0" cellspacing="0" class="theme_table">
				  	<tr>
				  	<td class="tbtl"><img src="./phpBB2/images/spacer.gif" alt="" width="22" /></td>
				  	<td class="tbtbot" nowrap="nowrap"><b><?=$caption?></b><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="22" style="vertical-align: middle;" /></td>
				  	<td class="tbtr_block"><img src="./phpBB2/images/spacer.gif" alt="" width="70" height="22" /></td>
				  	</tr>
				  </table>
				  <table width="100%" border="0" cellspacing="1" cellpadding="10" class="forumline">
						<tr>
							<td class="row1">
				  <table border="0" cellpadding="0" cellspacing="0" class="tbt">
				  	<tr><td align="center">
		<?
	}
	  
	function end_block()
	{
		?>
		</td></tr></table>
				</td>
		  </tr>
			<tr>
				<td class="row2" align="center"><span class="genmed"></span></td>
			</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="./phpBB2/images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>
			</td>
			</tr>
		</table>
		<?
	}

	// --------------------------------------------------------------------------------------------------------------
	// --------------------- new function: (only used by ICGstation stdhead) draw a categories block  ---------------
	// --------------------------------------------------------------------------------------------------------------

	function categories_block($caption = "")
	{
		global $CURUSER;
		
		if (isset($CURUSER[id]))
		{
			begin_block($caption);
			print "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"forumline\" align=\"center\">";
			print "<tr><td class=\"row1\" align=\"center\"><a href=\"browse.php?all=1\"><b>Show all</b></a></td></tr>";
			print "<tr><td class=\"row2\">";
			$rq = "SELECT id, name FROM categories ORDER BY name";
			$res = mysql_query($rq);
			while ($row = mysql_fetch_array($res))
			{
			extract ($row);
			print "<font color=\"red\">-</font>&nbsp;<a href=\"browse.php?cat=$id\">$name</a><br/>";
			}
			print "</td></tr>";
			print "</table>";
			end_block();
		} 
	}

	// --------------------------------------------------------------------------------------------------------------
	// --------------------- new function: (only used by ICGstation stdhead) draw a login block  ---------------
	// --------------------------------------------------------------------------------------------------------------

	function login_block($caption = "")
	{
		global $CURUSER;

  	if (!isset($CURUSER[id])) 
		{
			begin_block($caption);
      ?>
      <form method="post" action="takelogin.php">
      <table class="theme_table" width="100%" border="0" cellspacing="0" cellpadding="0">
	      <tr>
					<td class="row2" nowrap="nowrap" width="100%" align="right">	      
						<img src="./themes/ICGstation/images/login_logo.gif" border="0" alt="Login" align="middle" />
						<img src="./themes/ICGstation/images/username.gif" border="0" alt="username" align="middle" />
						<input class="post" type="text" name="username" size="10" /><br/>
						<img src="./themes/ICGstation/images/password.gif" border="0" alt="password" align="middle" />
						<input class="post" type="password" name="password" size="10" maxlength="32" /><br/>
					</td>
				</tr>	
				<tr>
					<td class="row2" align="center"><input type="submit" name="login" value="Login"/></td>
				</tr>
				<tr>
					<td class="row2" align="center">
						<img src="./themes/ICGstation/images/icon/icon_register.gif" border="0" alt="Register now!" align="middle" />
						<a href="signup.php" class="mainmenu">Register</a><br/>
						<a href="recover.php" class="mainmenu">Recover password</a>
					</td>
				</tr>
			</table>
		</form>
			<?
			end_block();
		}
	}

?>