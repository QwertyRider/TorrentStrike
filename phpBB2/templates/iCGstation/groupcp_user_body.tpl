
<table border="0" cellpadding="0" cellspacing="0" class="tbn">
<tr>
<td class="tbnl" rowspan="3"><img src="{PHPURL}images/spacer.gif" alt="" width="76" height="39" /></td>
<td height="17"></td>
<td height="17"></td>
</tr>
<td class="tbnbot"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
<td class="tbnr"><img src="{PHPURL}images/spacer.gif" alt="" width="39" height="22" /></td>
</tr>
</table>
<br />

<table border="0" cellpadding="0" cellspacing="0" class="tbt"><tr><td class="tbtl"><img src="{PHPURL}images/spacer.gif" alt="" width="22" height="22" /></td><td class="tbtbot">{L_GROUP_MEMBERSHIP_DETAILS}<img src="{PHPURL}images/spacer.gif" alt="" width="8" height="22" align="absmiddle" /></td><td class="tbtr"><img src="{PHPURL}images/spacer.gif" alt="" width="124" height="22" /></td></tr></table>
<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
  <!-- BEGIN switch_groups_joined -->
  <!-- BEGIN switch_groups_member -->
  <tr> 
	<td class="row1"><span class="gen">{L_YOU_BELONG_GROUPS}</span></td>
	<td class="row2" align="right"> 
	  <table width="90%" cellspacing="1" cellpadding="0" border="0">
		<tr><form method="get" action="{S_USERGROUP_ACTION}">
			<td width="40%"><span class="gensmall">{GROUP_MEMBER_SELECT}</span></td>
			<td align="center" width="30%"> 
			  <input type="submit" value="{L_VIEW_INFORMATION}" class="liteoption" />{S_HIDDEN_FIELDS}
			</td>
		</form></tr>
	  </table>
	</td>
  </tr>
  <!-- END switch_groups_member -->
  <!-- BEGIN switch_groups_pending -->
  <tr> 
	<td class="row1"><span class="gen">{L_PENDING_GROUPS}</span></td>
	<td class="row2" align="right"> 
	  <table width="90%" cellspacing="1" cellpadding="0" border="0">
		<tr><form method="get" action="{S_USERGROUP_ACTION}">
			<td width="40%"><span class="gensmall">{GROUP_PENDING_SELECT}</span></td>
			<td align="center" width="30%"> 
			  <input type="submit" value="{L_VIEW_INFORMATION}" class="liteoption" />{S_HIDDEN_FIELDS}
			</td>
		</form></tr>
	  </table>
	</td>
  </tr>
  <!-- END switch_groups_pending -->
  <!-- END switch_groups_joined -->
  <!-- BEGIN switch_groups_remaining -->
  <tr> 
	<th colspan="2" align="center"" height="25">{L_JOIN_A_GROUP}</th>
  </tr>
  <tr> 
	<td class="row1"><span class="gen">{L_SELECT_A_GROUP}</span></td>
	<td class="row2" align="right"> 
	  <table width="90%" cellspacing="1" cellpadding="0" border="0">
		<tr><form method="get" action="{S_USERGROUP_ACTION}">
			<td width="40%"><span class="gensmall">{GROUP_LIST_SELECT}</span></td>
			<td align="center" width="30%"> 
			  <input type="submit" value="{L_VIEW_INFORMATION}" class="liteoption" />{S_HIDDEN_FIELDS}
			</td>
		</form></tr>
	  </table>
	</td>
  </tr>
  <!-- END switch_groups_remaining -->
</table>
<table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>

<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
  <tr> 
	<td align="right" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td>
  </tr>
</table>

<br clear="all" />

<table width="100%" cellspacing="2" border="0" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
