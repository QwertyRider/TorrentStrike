
<br clear="all" />

<table border="0" cellpadding="0" cellspacing="0" class="tbt"><tr><td class="tbtl"><img src="{PHPURL}images/spacer.gif" alt="" width="22" height="22" /></td><td class="tbtbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="22" align="absmiddle" /></td><td class="tbtr"><img src="{PHPURL}images/spacer.gif" alt="" width="124" height="22" /></td></tr></table>
<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <th height="25">{L_PM}</th>
	  <th>{L_USERNAME}</th>
	  <th>{L_POSTS}</th>
	  <th>{L_FROM}</th>
	  <th>{L_EMAIL}</th>
	  <th>{L_WEBSITE}</th>
	  <th>{L_SELECT}</th>
	</tr>
	<tr> 
	  <td class="cat" colspan="8" height="28"><span class="cattitle">{L_PENDING_MEMBERS}</span></td>
	</tr>
	<!-- BEGIN pending_members_row -->
	<tr> 
	  <td class="{pending_members_row.ROW_CLASS}" align="center"> {pending_members_row.PM_IMG} 
	  </td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen"><a href="{pending_members_row.U_VIEWPROFILE}" class="gen">{pending_members_row.USERNAME}</a></span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">{pending_members_row.POSTS}</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">{pending_members_row.FROM}</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">{pending_members_row.EMAIL_IMG}</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">{pending_members_row.WWW_IMG}</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gensmall"> <input type="checkbox" name="pending_members[]" value="{pending_members_row.USER_ID}" checked="checked" /></span></td>
	</tr>
	<!-- END pending_members_row -->
	<tr> 
	  <td class="cat" colspan="8" align="right"><span class="cattitle"> 
		<input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="mainoption" />
		&nbsp; 
		<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="liteoption" />
		</span></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>
