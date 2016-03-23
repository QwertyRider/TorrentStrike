<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <title>
<?= $title ?>
  </title>
  <link rel="stylesheet" type="text/css" href="./themes/<?=$ss_uri."/".$ss_uri?>.css" />
  </head>
  <body>
    <table class="theme_table" width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
      <tr>
        <td width="100%">
          <table width="100%" cellpadding="0" cellspacing="1" border="0" class="forumline">
            <tr>
              <td class="row2" width="20%" align="center">
                <img src="./themes/ICGstation/images/icon/icon_faq.gif" border="0" alt="Home" align="middle" /><br />

                <a href="index.php" class="mainmenu">Home</a>
              </td>
              <td class="row2" width="20%" align="center">
                <img src="./themes/ICGstation/images/icon/icon_search.gif" border="0" alt="Gallery" align="middle" /><br />
                <a href="browse.php" class="mainmenu">Browse</a>
              </td>
              <td class="row2" width="20%" align="center">
                <img src="./themes/ICGstation/images/icon/icon_profile.gif" border="0" alt="Profile" align="middle" /><br />
                <a href="my.php" class="mainmenu">Profile</a>
              </td>
              <td class="row2" width="20%" align="center">
                <img src="./themes/ICGstation/images/icon/icon_memberlist.gif" border="0" alt="Forums" align="middle" /><br />
                <a href="phpbb2.php" class="mainmenu">Forums</a>
              </td>
              <td class="row2" width="20%" align="center">
                <img src="./themes/ICGstation/images/icon/icon_memberlist.gif" border="0" alt="FAQ" align="middle" /><br />
                <a href="faq.php" class="mainmenu">FAQ</a>
              </td>
            </tr>
          </table>
          <table class="theme_table" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td style="background-image : url(./themes/ICGstation/images/bg.jpg);" width="60%" valign="middle">
                <a href="index.php"><img src="./themes/ICGstation/images/banner.gif" border="0" alt="" /></a>

              </td>
              <td style="background-image : url(./themes/ICGstation/images/bg.jpg);" width="40%" valign="middle"></td>
            </tr>
          </table>
          <table class="theme_table" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td class="navpic" width="450" nowrap="nowrap">	      
	      			<?display_user_info();?>
	      			</td>
              <td width="100%" align="center" class="navpic" nowrap="nowrap"></td>
              <td class="navpic" width="150" nowrap="nowrap"><div align="right">
              </div>
              </td>
			</tr>
		</table>
<table class="theme_table" width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
      <tr valign="top">
        <td><img src="./themes/ICGstation/images/7px.gif" width="1" height="1" border="0" alt="" /></td>
</tr></table>
<table class="theme_table" width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
<tr valign="top">
  <td valign="top" align="center" width="1" style="background-image : url(./themes/ICGstation/images/7px.gif);" /></tr>

  <tr>
    <td valign="top" height="100%">
      <table class="theme_table" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td valign="top" width="180">
          
          <!--- block panel start here -->
          <?
          
          login_block("Connection");
					
          begin_block("General");?>
          <table width="100%" border="0" cellspacing="1" cellpadding="1" class="forumline" align="center">
          <tr><td class="row2" align="center" width="100%"><a href="index.php">Home</a></td></tr>
          <tr><td class="row1" align="center" width="100%"><a href="rules.php">Rules</a></td></tr>          
          <tr><td class="row2" align="center" width="100%"><a href="faq.php">FAQ</a></td></tr>          
          <tr><td class="row1" align="center" width="100%"><a href="phpbb2.php">Forums</a></td></tr>
          <tr><td class="row2" align="center" width="100%"><a href="links.php">Links</a></td></tr>
          <tr><td class="row1" align="center" width="100%"><a href="chat.php">Chat</a></td></tr>          
          </table>
          <?
          end_block();
          
          if (isset($CURUSER[id]))
          {
	          begin_block("Personal");?>
	          <table width="100%" border="0" cellspacing="1" cellpadding="1" class="forumline" align="center">
	          <tr><td class="row2" align="center" width="100%"><a href="my.php">Profile</a></td></tr>
	          <tr><td class="row1" align="center" width="100%"><a href="userdetails.php?id=<?=$CURUSER[id]?>">Details</a></td></tr>
	          <tr><td class="row2" align="center" width="100%"><a href="messages.php?action=viewmailbox">Private Messages</a></td></tr>
	          <tr><td class="row1" align="center" width="100%"><a href="friends.php">Friends</a></td></tr>
	          <tr><td class="row2" align="center" width="100%"><a href="logout.php">Logout</a></td></tr>
	          </table>
	          <?
	          end_block();
	          
	          begin_block("Torrents");?>
	          <table width="100%" border="0" cellspacing="1" cellpadding="1" class="forumline" align="center">
	          <tr><td class="row2" align="center" width="100%"><a href="browse.php">Browse</a></td></tr>
	          <tr><td class="row1" align="center" width="100%"><a href="viewrequests.php">Requests</a></td></tr>
	          <tr><td class="row2" align="center" width="100%"><a href="upload.php">Upload</a></td></tr>
	          <tr><td class="row1" align="center" width="100%"><a href="mytorrents.php">My Torrents</a></td></tr>	          
	          </table>
	          <?
	          end_block();

	          categories_block("Cats");
	          
	          begin_block("Infos");?>
	          <table width="100%" border="0" cellspacing="1" cellpadding="1" class="forumline" align="center">
          	<tr><td class="row2" align="center" width="100%"><a href="topten.php">Top 10</a></td></tr>
          	<tr><td class="row1" align="center" width="100%"><a href="staff.php">Staff</a></td></tr>
          	<tr><td class="row2" align="center" width="100%"><a href="log.php">Log</a></td></tr>
	          </table>
	          <?
	          end_block();


          	if (get_user_class() >= UC_MODERATOR)
          	{
          		begin_block("Admin");
          		echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" class="forumline" align="center">';
           		echo '<tr><td class="row2" align="center" width="100%"><a href="controlpanel.php">Control Panel</a></td></tr>';
           		echo '<tr><td class="row1" align="center" width="100%"><a href="usersearch.php">User Search</a></td></tr>';
           		echo '<tr><td class="row2" align="center" width="100%"><a href="staffbox.php">StaffBox</a></td></tr>';
           		echo '</table>';
           		end_block();
	          }
           	else
           	{
	           	if($CURUSER[support]=='yes')
	           	{
	          		begin_block("StaffBox");
	          		echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" class="forumline" align="center">';
	           		echo '<tr><td class="row2" align="center" width="100%"><a href="staffbox.php">StaffBox</a></td></tr>';
	           		echo '</table>';
	           		end_block();
	           	}
           	}
	        }
          ?>
  				<!--- block panel finish here -->
  
          </td>
          <td valign="top" align="center">
          <? begin_frame(); ?>
