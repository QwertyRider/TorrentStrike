<?
ob_start("ob_gzhandler");
require_once("include/bittorrent.php");

// Connect to DB & check login
dbconn();
loggedinorreturn();

// Define constants
define('PM_DELETED',0);    // Message was delted
define('PM_INBOX',1);    // Message located in Inbox for reciever
define('PM_SENTBOX',-1);  // GET value for sent box

// Determine action
$action = (string) $_GET['action'];
if (!$action)
{
  $action = (string) $_POST['action'];
  if (!$action)
  {
    $action = 'viewinbox';
  }
}

// View listing of Messages in mail box
if ($action == "viewmailbox")
{
  // Get Mailbox Number
  $mailbox = (int) $_GET['box'];
  if (!$mailbox)
  {
    $mailbox = PM_INBOX;
  }

  // Get Mailbox Name
  if ($mailbox != PM_INBOX && $mailbox != PM_SENTBOX)
  {
  	stderr("Error","Invalid Mailbox");
  }
  else
  {
    if ($mailbox == PM_INBOX)
    {
      $mailbox_name = "Inbox";
    }
    else
    {
      $mailbox_name = "Sentbox";
    }
  }


	//----- FUNCTIONS ------
	function insertJumpTo($selected = 0)
	{
	global $CURUSER;
	?><form action="messages.php" method="get">
	<input type="hidden" name="action" value="viewmailbox"/>Jump to: <select name="box">
	<option value="1"<?=($selected == PM_INBOX ? " selected=\"selected\"" : "")?>>Inbox</option>
	<option value="-1"<?=($selected == PM_SENTBOX ? " selected=\"selected\"" : "")?>>Sentbox</option><?
	?></select> <input type="submit" value="Go"/></form><?
	}

  

  // Start Page
  
  stdhead($mailbox_name); ?>

	<script type="text/javascript" src="./java_klappe.js"></script>
	<script language="JavaScript" type="text/javascript">
	//<![CDATA[
	var checkflag = "false";
	function check(field) {
	if (checkflag == "false") {
	for (i = 0; i < field.length; i++) {
	field[i].checked = true;}
	checkflag = "true";
	return "Uncheck All"; }
	else {
	for (i = 0; i < field.length; i++) {
	field[i].checked = false; }
	checkflag = "false";
	return "Check All"; }
	}
	//]]>
	</script>
	
  <form action="messages.php" method="post">
  <input type="hidden" name="action" value="moveordel"/>

  <?begin_frame($mailbox_name);?>
  <table width="100%">

    <?
    if ($mailbox != PM_SENTBOX)
    {
      $res = mysql_query('SELECT * FROM messages WHERE receiver=' . sqlesc($CURUSER['id']) . ' AND location=' . sqlesc($mailbox) . ' ORDER BY id DESC') or sqlerr(__FILE__,__LINE__);
    }
    else
    {
      $res = mysql_query('SELECT * FROM messages WHERE sender=' . sqlesc($CURUSER['id']) . ' AND saved=\'yes\' ORDER BY id DESC') or sqlerr(__FILE__,__LINE__);
    }

    if (mysql_num_rows($res) == 0)

    {
      echo("<tr><td colspan=\"5\" align=\"center\">No Messages.</td></tr>\n");
    }
    else
    {
    $news_flag = 0;
      while ($row = mysql_fetch_assoc($res))
      {
        // Get Sender Username
        if ($row['sender'] != 0)
        {
          $res2 = mysql_query("SELECT * FROM users WHERE id=" . sqlesc($row['sender']));
          $username = mysql_fetch_array($res2);
          $username = "<u><b><a href=\"userdetails.php?id=" . $row['sender'] . "\">" . $username['username'] . "</a></b></u> | ";
        $id = $row['sender'];
        }
        else
        {
          $username = "System";
        }

        $subject = htmlspecialchars($row['subject']);
        if (strlen($subject) <= 0)
        {
          $subject = "No Subject";
        }


        if ($row['sender'] != 0)
        $sendedd = "<a href=\"sendmessage.php?receiver=" . $row['sender'] . "&amp;replyto=" . $row['id'] . "\"><u><b>Reply</b></u></a>";
        else
        $sendedd = "";

    		if ($row['unread'] == 'yes' && $mailbox != PM_SENTBOX)
        {
          $subject = "<font color=\"red\">$subject</font>\n";
        }
        else
        {
          $subject = "<font>$subject</font>";
        }

    		$delete12 = "<a href=\"messages.php?action=deletemessage&amp;id=".$row['id']."\"><u><b>Delete</b></u></a>";

        if ($row['unread'] == 'yes' && $mailbox != PM_SENTBOX)
        {
       		print("<tr><td class=\"tableb\"><a href=\"javascript: klappe_news('a".$row['id']."')\"><img border=\"0\" src=\"pic/plus1.gif\" id=\"pica".$row['id']."\" alt=\"Show/Hide\"/>&nbsp;" .$row['added']. "</a> - " ."<b>$subject</b> - $username$sendedd | $delete12");
     			print("<div id=\"ka".$row['id']."\" style=\"block: none;\"><table width=\"90%\"  align=\"right\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" bgcolor=\"#F1F1F1\"><tr><td style='padding: 10px; background:#F1F1F1'><font color=\"black\"> ".format_comment($row["msg"],0)." </font></td></tr></table></div></td>");
  				mysql_query("UPDATE messages SET unread='no' WHERE id=" . $row['id'] . " AND receiver=" . sqlesc($CURUSER['id']) . " LIMIT 1");
         } else 
         {
          print("<tr><td class=\"tableb\"><a href=\"javascript: klappe_news('a".$row['id']."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pica".$row['id']."\" alt=\"Show/Hide\"/>&nbsp;" .$row['added']. "</a> - " ."<b>$subject</b> - $username$sendedd | $delete12");
     			print("<div id=\"ka".$row['id']."\" style=\"display: none;\"><table width=\"90%\" align=\"right\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" bgcolor=\"#F1F1F1\"><tr><td style='padding: 10px; background:#F1F1F1'><font color=\"black\"> ".format_comment($row["msg"],0)." </font></td></tr></table></div></td>");

        }
        echo("<td class=\"tableb\" valign=\"top\"><input type=\"checkbox\" name=\"messages[]\" value=\"" . $row['id'] . "\"/></td>\n</tr>\n");

    	}
		}

    ?>
    <tr>
      <td class="tableb" colspan="5" align="right"><div align="left">
        <input type="button" value="Check all" onclick="this.value=check(form)"/>&nbsp;<input type="submit" name="delete" value="Delete"/></div>
      </td>
    </tr>
    </table>
  	<?end_frame();?>
  	</form>

		<div align="right"><? insertJumpTo($mailbox); ?></div>
  <?
    
  stdfoot();
}

if ($action == "moveordel")
{
  $pm_id = (int) $_POST['id'];
  $pm_box = (int) $_POST['box'];
  $pm_messages = $_POST['messages'];
  if ($_POST['move'])
  {
    if ($pm_id)
    {
      // Move a single message
      @mysql_query("UPDATE messages SET location=" . sqlesc($pm_box) . " WHERE id=" . sqlesc($pm_id) . " AND receiver=" . $CURUSER['id'] . " LIMIT 1");
    }
    else
    {
      // Move multiple messages
      @mysql_query("UPDATE messages SET location=" . sqlesc($pm_box) . " WHERE id IN (" . implode(", ", array_map("sqlesc",$pm_messages)) . ') AND receiver=' . $CURUSER['id']);
    }
    // Check if messages were moved
    if (@mysql_affected_rows() == 0)
    {
      stderr("Error","Messages couldn't be moved! ");
    }

    header("Location: messages.php?action=viewmailbox&box=" . $pm_box);
    exit();
  }
  elseif ($_POST['delete'])
  {
    if ($pm_id)
    {
      // Delete a single message
      $res = mysql_query("SELECT * FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
      $message = mysql_fetch_assoc($res);
      if ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'no')
      {
        mysql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
      }
      elseif ($message['sender'] == $CURUSER['id'] && $message['location'] == PM_DELETED)
      {
        mysql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
      }
      elseif ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'yes')
      {
        mysql_query("UPDATE messages SET location=0 WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
      }
      elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED)
      {
        mysql_query("UPDATE messages SET saved='no' WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
      }
    }
    else
    {
      // Delete multiple messages
      foreach ($pm_messages as $id)
      {
        $res = mysql_query("SELECT * FROM messages WHERE id=" . sqlesc((int) $id));
        $message = mysql_fetch_assoc($res);
        if ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'no')
        {
          mysql_query("DELETE FROM messages WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
        }
        elseif ($message['sender'] == $CURUSER['id'] && $message['location'] == PM_DELETED)
        {
          mysql_query("DELETE FROM messages WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
        }
        elseif ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'yes')
        {
          mysql_query("UPDATE messages SET location=0 WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
        }
        elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED)
        {
          mysql_query("UPDATE messages SET saved='no' WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
        }
      }
    }
    // Check if messages were moved
    if (@mysql_affected_rows() == 0)
    {
      stderr("Error","Messages couldn't be deleted! ");
    }
    else
    {
      header("Location: messages.php?action=viewmailbox");
      exit();
    }
  }
  stderr("Error","No action");
}

if ($action == "deletemessage")
{
  $pm_id = (int) $_GET['id'];
  $pm_box = (int) $_POST['box'];

  // Delete message
  $res = mysql_query("SELECT * FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
  if (!$res)
  {
    stderr("Error","No message with this ID.");
  }
  if (mysql_num_rows($res) == 0)
  {
    stderr("Error","No message with this ID.");
  }
  $message = mysql_fetch_assoc($res);
  if ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'no')
  {
    $res2 = mysql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
  }
  elseif ($message['sender'] == $CURUSER['id'] && $message['location'] == PM_DELETED)
  {
    $res2 = mysql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
  }
  elseif ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'yes')
  {
    $res2 = mysql_query("UPDATE messages SET location=0 WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
  }
  elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED)
  {
    $res2 = mysql_query("UPDATE messages SET saved='no' WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
  }
  if (!$res2)
  {
    stderr("Error","Could not delete message.");
  }
  if (mysql_affected_rows() == 0)
  {
    stderr("Error","Could not delete message.");
  }
  else
  {
    header("Location: messages.php?action=viewmailbox&box=" . $pm_box . "&id=" . $message['location']);
    exit();
  }
}


?>