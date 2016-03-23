<?

define('IN_PORTAL', true);

if (isset($_GET['page'])) $page=htmlspecialchars(stripslashes($_GET['page']));
if (isset($_GET['mode'])) $mode=htmlspecialchars(stripslashes($_GET['mode']));
if (isset($_GET['logout'])) $logout=htmlspecialchars(stripslashes($_GET['logout']));
if (isset($_POST['redirect'])) $redirect=htmlspecialchars(stripslashes($_POST['redirect']));
if (isset($_GET['redirect'])) $redirect=htmlspecialchars(stripslashes($_GET['redirect']));

ob_start("ob_gzhandler"); 
require "include/TBDevBridge.php";
require_once "include/bittorrent.php";
if ($activate_phpbb2_forum==true)
{
	require_once "./".$phpbb2_folder."/config.php";
}

define("basefile", "./".$phpbb2_basefile."?"); 
define("relativebasefile", "../".$phpbb2_basefile."?");

// -------------- Page not included inside TBDev template -------------------

// Visual confirmation (display a security png in register page)
if ($page=="profile" && $mode=="confirm")
{
	include "./".$phpbb2_folder."/profile.php";
	exit;
}

// Topic review
if ($page=="posting" && $mode=="topicreview")
{
	include "./".$phpbb2_folder."/posting.php";
	exit;
}

// More smilies
if ($page=="posting" && $mode=="smilies")
{
	include "./".$phpbb2_folder."/posting.php";
	exit;
}

// Search username
if($page=="search" && $mode=="searchuser")
{
	include "./".$phpbb2_folder."/search.php";
	exit;
}

// New private message
if ($page=="privmsg" && $mode=="newpm")
{
	include "./".$phpbb2_folder."/privmsg.php";
	exit;
}

// Login to the admin panel
if ($page=="login" && $redirect == "admin/index.php")
{
	include "./".$phpbb2_folder."/login.php";
	exit;
}


// -------------- Page nested inside TBDev template -------------------

stdhead("PHPBB Forum Hack");

if ($activate_phpbb2_forum!=true)
{
	echo "<center><b>The Forum is closed</b></center>";

	if (get_user_class() == UC_SYSOP)
	{
		if(!file_exists("./".$phpbb2_folder."/config.php"))
		{
			echo "<br/><form method=\"post\" action=\"./".$phpbb2_folder."/install/install.php\">";
			echo "<input type=\"hidden\" name=\"dbhost\" value=\"".$mysql_host."\" />";
			echo "<input type=\"hidden\" name=\"dbname\" value=\"".$mysql_db."\" />";
			echo "<input type=\"hidden\" name=\"dbuser\" value=\"".$mysql_user."\" />";
			echo "<input type=\"hidden\" name=\"dbpasswd\" value=\"".$mysql_pass."\" />";
			echo "<input type=\"hidden\" name=\"admin_name\" value=\"".$CURUSER[username]."\" />";
			echo "<input type=\"hidden\" name=\"board_email\" value=\"".$CURUSER[email]."\" />";
			echo "<input type=\"submit\" value=\"phpBB not installed: INSTALL NOW!\">";
			echo "</form>";
		}
	}
	
	stdfoot();
	exit();
}


switch ($page)
{
	case "faq":
	include "./".$phpbb2_folder."/faq.php";
	break;

	
	case "modcp":
	include "./".$phpbb2_folder."/modcp.php";
	break;


	case "viewforum":
	include "./".$phpbb2_folder."/viewforum.php";
	break;

	
	case "viewonline":
	include "./".$phpbb2_folder."/viewonline.php";
	break;


	case "viewtopic":
	include "./".$phpbb2_folder."/viewtopic.php";
	break;
	
	
	case "search":
	include "./".$phpbb2_folder."/search.php";
	break;

	
	case "privmsg":
	include "./".$phpbb2_folder."/privmsg.php";
	break;
	
	
	case "posting":
	include "./".$phpbb2_folder."/posting.php";
	break;


	case "profile":
	if ($mode=="register" && $share_phpbb2_users_with_TBDev==true)
	{
		header('Location: signup.php');
		break;
	}
	if ($mode=="sendpassword" && $share_phpbb2_users_with_TBDev==true)
	{
		header('Location: recover.php');
		break;
	}
	include "./".$phpbb2_folder."/profile.php";
	break;
	
	
	case "memberlist":
	include "./".$phpbb2_folder."/memberlist.php";
	break;
	

	case "groupcp":
	include "./".$phpbb2_folder."/groupcp.php";
	break;

	
	case "login":
	if ($share_phpbb2_users_with_TBDev==true)
	{
		if ($logout==true)
		{
			header('Location: logout.php');
		}
		else
		{
			header('Location: login.php');
		}
	}
	else
	{
		include "./".$phpbb2_folder."/login.php";
	}
	break;

	
	default:
	include "./".$phpbb2_folder."/index.php";
	break;
}

stdfoot();
?>