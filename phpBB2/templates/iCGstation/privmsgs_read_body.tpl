
<table cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td valign="middle">{INBOX_IMG}</td>
	<td valign="middle"><span class="cattitle">{INBOX} &nbsp;</span></td>
	<td valign="middle">{SENTBOX_IMG}</td>
	<td valign="middle"><span class="cattitle">{SENTBOX} &nbsp;</span></td>
	<td valign="middle">{OUTBOX_IMG}</td>
	<td valign="middle"><span class="cattitle">{OUTBOX} &nbsp;</span></td>
	<td valign="middle">{SAVEBOX_IMG}</td>
	<td valign="middle"><span class="cattitle">{SAVEBOX}</span></td>
  </tr>
</table>

<br clear="all" />

<form method="post" action="{S_PRIVMSGS_ACTION}">
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr>
	  <td valign="middle">{REPLY_PM_IMG}</td>
  </tr>
</table>

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

<table border="0" cellpadding="0" cellspacing="0" class="tbt"><tr><td class="tbtl"><img src="{PHPURL}images/spacer.gif" alt="" width="22" height="22" /></td><td class="tbtbot">{BOX_NAME} :: {L_MESSAGE}<img src="{PHPURL}images/spacer.gif" alt="" width="8" height="22" align="absmiddle" /></td><td class="tbtr"><img src="{PHPURL}images/spacer.gif" alt="" width="124" height="22" /></td></tr></table>
<table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
	  <td class="row2"><span class="genmed">{L_FROM}:</span></td>
	  <td width="100%" class="row2" colspan="2"><span class="genmed">{MESSAGE_FROM}</span></td>
	</tr>
	<tr> 
	  <td class="row2"><span class="genmed">{L_TO}:</span></td>
	  <td width="100%" class="row2" colspan="2"><span class="genmed">{MESSAGE_TO}</span></td>
	</tr>
	<tr> 
	  <td class="row2"><span class="genmed">{L_POSTED}:</span></td>
	  <td width="100%" class="row2" colspan="2"><span class="genmed">{POST_DATE}</span></td>
	</tr>
	<tr> 
	  <td class="row2"><span class="genmed">{L_SUBJECT}:</span></td>
	  <td width="100%" class="row2"><span class="genmed">{POST_SUBJECT}</span></td>
	  <td nowrap="nowrap" class="row2" align="right"> {QUOTE_PM_IMG} {EDIT_PM_IMG}</td>
	</tr>
	<tr> 
	  <td valign="top" colspan="3" class="row1"><span class="postbody">{MESSAGE}</span></td>
	</tr>
	<tr> 
	  <td width="78%" height="28" valign="bottom" colspan="3" class="row1"> 
		<table cellspacing="1" cellpadding="0" border="0" height="18">
		  <tr> 
			<td valign="middle" nowrap="nowrap">{PROFILE_IMG} {PM_IMG} {EMAIL_IMG} 
			  {WWW_IMG} {AIM_IMG} {YIM_IMG} {MSN_IMG} {ICQ_IMG}</td>
		  </tr>
		</table>
	  </td>
	</tr>
	<tr>
	  <td class="cat" colspan="3" height="28" align="right"> {S_HIDDEN_FIELDS} 
		<input type="submit" name="save" value="{L_SAVE_MSG}" class="liteoption" />
		&nbsp; 
		<input type="submit" name="delete" value="{L_DELETE_MSG}" class="liteoption" />
	  </td>
	</tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>

  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
	  <td>{REPLY_PM_IMG}</td>
	  <td align="right" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td>
	</tr>
  </table>
</form>

<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
  <tr> 
	<td valign="top" align="right"><span class="gensmall">{JUMPBOX}</span></td>
  </tr>
</table>
