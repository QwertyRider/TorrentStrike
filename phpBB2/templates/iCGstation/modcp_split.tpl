
<form method="post" action="{S_SPLIT_ACTION}">
<table border="0" cellpadding="0" cellspacing="0" class="tbn">
<tr>
<td class="tbnl" rowspan="3"><img src="{PHPURL}images/spacer.gif" alt="" width="76" height="39" /></td>
<td height="17"></td>
<td height="17"></td>
</tr>
<td class="tbnbot"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> &raquo; <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></span></td>
<td class="tbnr"><img src="{PHPURL}images/spacer.gif" alt="" width="39" height="22" /></td>
</tr>
</table>
<br />

<table border="0" cellpadding="0" cellspacing="0" class="tbt"><tr><td class="tbtl"><img src="{PHPURL}images/spacer.gif" alt="" width="22" height="22" /></td><td class="tbtbot">{L_SPLIT_TOPIC}<img src="{PHPURL}images/spacer.gif" alt="" width="8" height="22" align="absmiddle" /></td><td class="tbtr"><img src="{PHPURL}images/spacer.gif" alt="" width="124" height="22" /></td></tr></table>
  <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <td class="row2" colspan="3" align="center"><span class="gensmall">{L_SPLIT_TOPIC_EXPLAIN}</span></td>
	</tr>
	<tr> 
	  <td class="row1" nowrap="nowrap"><span class="gen">{L_SPLIT_SUBJECT}</span></td>
	  <td class="row2" colspan="2"><input class="post" type="text" size="35" style="width: 350px" maxlength="60" name="subject" /></td>
	</tr>
	<tr> 
	  <td class="row1" nowrap="nowrap"><span class="gen">{L_SPLIT_FORUM}</span></td>
	  <td class="row2" colspan="2">{S_FORUM_SELECT}</td>
	</tr>
	<tr> 
	  <td class="cat" colspan="3" height="28"> 
		<table width="60%" cellspacing="1" cellpadding="0" border="0" align="center">
		  <tr> 
			<td width="50%" align="center"> 
			  <input class="liteoption" type="submit" name="split_type_all" value="{L_SPLIT_POSTS}" />
			</td>
			<td width="50%" align="center"> 
			  <input class="liteoption" type="submit" name="split_type_beyond" value="{L_SPLIT_AFTER}" />
			</td>
		  </tr>
		</table>
	  </td>
	</tr>
	<tr> 
	  <th class="thLeft" nowrap="nowrap">{L_AUTHOR}</th>
	  <th nowrap="nowrap">{L_MESSAGE}</th>
	  <th class="thRight" nowrap="nowrap">{L_SELECT}</th>
	</tr>
	<!-- BEGIN postrow -->
	<tr> 
	  <td align="left" valign="top" class="{postrow.ROW_CLASS}"><span class="name"><a name="{postrow.U_POST_ID}"></a>{postrow.POSTER_NAME}</span></td>
	  <td width="100%" valign="top" class="{postrow.ROW_CLASS}"> 
		<table width="100%" cellspacing="1" cellpadding="3" border="0">
		  <tr> 
			<td valign="middle"><img src="{PHPURL}templates/iCGstation/images/icon_minipost.gif" alt="{L_POST}"><span class="postdetails">{L_POSTED}: 
			  {postrow.POST_DATE}&nbsp;&nbsp;&nbsp;&nbsp;{L_POST_SUBJECT}: {postrow.POST_SUBJECT}</span></td>
		  </tr>
		  <tr> 
			<td valign="top"> 
			  <hr size="1" />
			  <span class="postbody">{postrow.MESSAGE}</span></td> 
		  </tr>
		</table>
	  </td>
	  <td width="5%" align="center" class="{postrow.ROW_CLASS}">{postrow.S_SPLIT_CHECKBOX}</td>
	</tr>
	<tr> 
	  <td colspan="3" height="1" class="row3"><img src="{PHPURL}templates/iCGstation/images/spacer.gif" width="1" height="1" alt="."></td>
	</tr>
	<!-- END postrow -->
	<tr> 
	  <td class="cat" colspan="3" height="28"> 
		<table width="60%" cellspacing="1" cellpadding="0" border="0" align="center">
		  <tr> 
			<td width="50%" align="center"> 
			  <input class="liteoption" type="submit" name="split_type_all" value="{L_SPLIT_POSTS}" />
			</td>
			<td width="50%" align="center"> 
			  <input class="liteoption" type="submit" name="split_type_beyond" value="{L_SPLIT_AFTER}" />
			  {S_HIDDEN_FIELDS} </td>
		  </tr>
		</table>
	  </td>
	</tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="tbl"><tr><td class="tbll"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblbot"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td><td class="tblr"><img src="{PHPURL}images/spacer.gif" alt="" width="8" height="4" /></td></tr></table>

  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
	  <td align="right" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td>
	</tr>
  </table>
</form>
