{META}
{NAV_LINKS}
<link rel="stylesheet" href="{PHPURL}/templates/iCGstation/{T_HEAD_STYLESHEET}" type="text/css" -->

<!-- BEGIN switch_enable_pm_popup -->
<script language="Javascript" type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
		window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
	}
//-->
</script>
<!-- END switch_enable_pm_popup -->

<a name="top"></a>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr> 
	<td width="1" nowrap="nowrap"></td>
	<td width="100%">
		<table width="100%" cellpadding="0" cellspacing="1" border="0" class="forumline">
			<tr> 
				<!-- BEGIN switch_user_logged_out -->
				<td class="row2" width="100%" align="center">
				<form method="post" action="takelogin.php">
				<img src="{PHPURL}templates/iCGstation/images/login_logo.gif" border="0" alt="{L_LOGIN}" align="absmiddle" /><img src="{PHPURL}templates/iCGstation/images/username.gif" border="0" alt="{L_USERNAME}" align="absmiddle" /><input type="text" name="username" size="10" /><img src="{PHPURL}templates/iCGstation/images/password.gif" border="0" alt="{L_PASSWORD}" align="absmiddle" /><input type="password" name="password" size="10" maxlength="32" /><br />{L_AUTO_LOGIN} <input class="text" type="checkbox" name="autologin" />&nbsp;&nbsp;&nbsp;<input type="hidden" name="submit" value="{L_LOGIN}"><input type="hidden" name="login" value="{L_LOGIN}"><input type="submit" name="login" value="{L_LOGIN}"/></td>
				</form></td>
				<td class="row2" width="150" nowrap="nowrap" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_register.gif" border="0" alt="{L_REGISTER}" align="absmiddle" /><br /><a href="{U_REGISTER}" class="mainmenu">{L_REGISTER}</a></td>
				<!-- END switch_user_logged_out -->
				<!-- BEGIN switch_user_logged_in -->
				<td class="row2" width="20%" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_faq.gif" border="0" alt="{L_FAQ}" align="absmiddle" /><br /><a href="{U_FAQ}" class="mainmenu">{L_FAQ}</a></td>
				<td class="row2" width="20%" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_search.gif" border="0" alt="{L_SEARCH}" align="absmiddle" /><br /><a href="{U_SEARCH}" class="mainmenu">{L_SEARCH}</a></td>
				<td class="row2" width="20%" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_memberlist.gif" border="0" alt="{L_MEMBERLIST}" align="absmiddle" /><br /><a href="{U_MEMBERLIST}" class="mainmenu">{L_MEMBERLIST}</a></td>
				<td class="row2" width="20%" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_group.gif" border="0" alt="{L_USERGROUPS}" align="absmiddle" /><br /><a href="{U_GROUP_CP}" class="mainmenu">{L_USERGROUPS}</a></td>
				<td class="row2" width="20%" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_profile.gif" border="0" alt="{L_PROFILE}" align="absmiddle" /><br /><a href="{U_PROFILE}" class="mainmenu">{L_PROFILE}</a></td>
				<td class="row2" width="150" nowrap="nowrap" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_login.gif" border="0" alt="{L_LOGIN_LOGOUT}" align="absmiddle" /><br /><a href="{U_LOGIN_LOGOUT}" class="mainmenu">{L_LOGIN_LOGOUT}</a></td>
				<!-- END switch_user_logged_in -->
				<td class="row2" width="150" nowrap="nowrap" align="center"><img src="{PHPURL}templates/iCGstation/images/icon/icon_pm.gif" border="0" alt="{PRIVATE_MESSAGE_INFO}" align="absmiddle" /><br /><a href="{U_PRIVATEMSGS}" class="mainmenu">{PRIVATE_MESSAGE_INFO}</a></td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<!--
			<tr> 
				<td width="50%" valign="top"><a href="{U_INDEX}"><img src="{PHPURL}/templates/iCGstation/images/banner.gif" border="0" /></a></td>
				<td width="50%" colspan="2" valign="top"><a href="{U_INDEX}"><img src="{PHPURL}/templates/iCGstation/images/banner_span.jpg" width="100%" height="100" border="0" /></a></td>
			</tr>
		-->	
		<br/>
			<tr> 
				<td width="50%" class="navpic">&nbsp;&nbsp;<span class="maintitle">{SITENAME}</span><span class="maintitle">&nbsp;-&nbsp;{SITE_DESCRIPTION}</span></td>
				<td width="50%" class="navpic"></td>
			</tr>
		</table>
		<table width="100%" cellspacing="10" cellpadding="0" border="0">
			<tr>
				<td align="center" width="100%" valign="middle">