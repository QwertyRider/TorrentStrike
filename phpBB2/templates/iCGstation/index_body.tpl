<table border="0" cellpadding="0" cellspacing="0" class="tbn">
<tr>
<td class="tbnl" rowspan="3"><img src="{PHPURL}images/spacer.gif" alt="" width="76" height="39" /></td>
<td height="17" align="right" colspan="2"><span class="nav"><a href="{U_MARK_READ}" class="gensmall">{L_MARK_FORUMS_READ}</a></td>
</tr>
<td class="tbnbot">
<span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
</td>
<td class="tbnr"><img src="{PHPURL}images/spacer.gif" alt="" width="39" height="22" /></td>
</tr>
</table>

<table width="100%" cellspacing="1" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left" valign="bottom"><span class="gensmall">
	<!-- BEGIN switch_user_logged_in -->
	{LAST_VISIT_DATE}<br />
	<!-- END switch_user_logged_in -->
	{CURRENT_TIME}<br />{S_TIMEZONE}</span></td>
	<td align="right" valign="bottom" class="gensmall">
		<!-- BEGIN switch_user_logged_in -->
		<a href="{U_SEARCH_NEW}" class="gensmall">{L_SEARCH_NEW}</a><br /><a href="{U_SEARCH_SELF}" class="gensmall">{L_SEARCH_SELF}</a><br />
		<!-- END switch_user_logged_in -->
		<a href="{U_SEARCH_UNANSWERED}" class="gensmall">{L_SEARCH_UNANSWERED}</a></td>
  </tr>
</table>

<!-- BEGIN catrow -->
<table border="0" cellpadding="0" cellspacing="0" class="tbt"><tr><td class="tbtl"><img src="{PHPURL}images/spacer.gif" alt="" width="22" height="22" /></td><td class="tbtbot"><b></b><span class="gen"><a href="{catrow.U_VIEWCAT}" class="cattitle">{catrow.CAT_DESC}</a></span></td><td class="tbtr"><img src="{PHPURL}images/spacer.gif" alt="" width="124" height="22" /></td></tr></table>
<table width="100%" cellpadding="0" cellspacing="1" border="0" class="forumline">
  <tr> 
	<th colspan="2" height="22" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
	<th width="50" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="50" nowrap="nowrap">&nbsp;{L_POSTS}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
  </tr>
  <!-- BEGIN forumrow -->
  <tr> 
	<td class="row1" align="center" valign="middle"><img src="{PHPURL}{catrow.forumrow.FORUM_FOLDER_IMG}" alt="{catrow.forumrow.L_FORUM_FOLDER_ALT}" title="{catrow.forumrow.L_FORUM_FOLDER_ALT}" /></td>
	<td class="row1" width="100%"><span class="forumlink"> <a href="{catrow.forumrow.U_VIEWFORUM}" class="forumlink">{catrow.forumrow.FORUM_NAME}</a><br />
	  </span> <span class="genmed">{catrow.forumrow.FORUM_DESC}<br />
	  </span><span class="gensmall">{catrow.forumrow.L_MODERATOR} {catrow.forumrow.MODERATORS}</span></td>
	<td class="row1" align="center" valign="middle"><span class="gensmall">{catrow.forumrow.TOPICS}</span></td>
	<td class="row1" align="center" valign="middle"><span class="gensmall">{catrow.forumrow.POSTS}</span></td>
	<td class="row1" width="160" align="center" valign="middle" nowrap="nowrap"> <span class="gensmall">{catrow.forumrow.LAST_POST}</span></td>
  </tr>
  <!-- END forumrow -->
</table>
<table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>
<br />
  <!-- END catrow -->

<table border="0" cellpadding="0" cellspacing="0" class="tbt"><tr><td class="tbtl"><img src="{PHPURL}images/spacer.gif" alt="" width="22" height="22" /></td><td class="tbtbot"><span class="gen"><a href="{U_VIEWONLINE}" class="cattitle">{L_WHO_IS_ONLINE}</a></span></td><td class="tbtr"><img src="{PHPURL}images/spacer.gif" alt="" width="124" height="22" /></td></tr></table>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr> 
	<td class="row1" align="center" valign="middle" rowspan="2"><img src="{PHPURL}templates/iCGstation/images/whosonline.gif" alt="{L_WHO_IS_ONLINE}" /></td>
	<td class="row1" align="left" width="100%"><span class="gensmall">{TOTAL_POSTS}<br />{TOTAL_USERS}<br />{NEWEST_USER}</span>
	</td>
  </tr>
  <tr> 
	<td class="row1" align="left"><span class="gensmall">{TOTAL_USERS_ONLINE} &nbsp; [ {L_WHOSONLINE_ADMIN} ] &nbsp; [ {L_WHOSONLINE_MOD} ]<br />{RECORD_USERS}<br />{LOGGED_IN_USER_LIST}</span></td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>

<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{L_ONLINE_EXPLAIN}</span></td>
</tr>
</table>

<!-- BEGIN switch_user_logged_out -->
<form method="post" action="takelogin.php">
<table border="0" cellpadding="0" cellspacing="0" class="tbt"><tr><td class="tbtl"><img src="{PHPURL}images/spacer.gif" alt="" width="22" height="22" /></td><td class="tbtbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="22" /></td><td class="tbtr"><img src="{PHPURL}images/spacer.gif" alt="" width="124" height="22" /></td></tr></table>
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <td class="cat" height="28"><a name="login"></a><span class="cattitle">{L_LOGIN_LOGOUT}</span></td>
	</tr>
	<tr> 
	  <td class="row1" align="center" valign="middle" height="28"><span class="gensmall">{L_USERNAME}: 
		<input class="post" type="text" name="username" size="10" />
		&nbsp;&nbsp;&nbsp;{L_PASSWORD}: 
		<input class="post" type="password" name="password" size="10" maxlength="32" />
		&nbsp;&nbsp; &nbsp;&nbsp;{L_AUTO_LOGIN} 
		<input class="text" type="checkbox" name="autologin" />
		&nbsp;&nbsp;&nbsp; 
		<input type="submit" class="mainoption" name="login" value="{L_LOGIN}" />
		</span> </td>
	</tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>
</form>
<!-- END switch_user_logged_out -->

<br clear="all" />

<table cellspacing="3" border="0" align="center" cellpadding="0">
  <tr> 
	<td width="20" align="center"><img src="{PHPURL}templates/iCGstation/images/folder_new_big.gif" alt="{L_NEW_POSTS}"/></td>
	<td><span class="gensmall">{L_NEW_POSTS}</span></td>
	<td>&nbsp;&nbsp;</td>
	<td width="20" align="center"><img src="{PHPURL}templates/iCGstation/images/folder_big.gif" alt="{L_NO_NEW_POSTS}" /></td>
	<td><span class="gensmall">{L_NO_NEW_POSTS}</span></td>
	<td>&nbsp;&nbsp;</td>
	<td width="20" align="center"><img src="{PHPURL}templates/iCGstation/images/folder_locked_big.gif" alt="{L_FORUM_LOCKED}" /></td>
	<td><span class="gensmall">{L_FORUM_LOCKED}</span></td>
  </tr>
</table>
