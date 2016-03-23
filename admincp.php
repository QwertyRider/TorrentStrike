<?
define ('ConfigFN','include/config.php');
define ('TBVERSION','TorrentStrike V0.4');

require_once('include/global.php');
include('include/sql_parse.php');


function stdhead($title = "", $msgalert = true) {
    global $CURUSER, $SITE_ONLINE, $FUNDS, $SITENAME;
    global $ss_uri;

    header("Content-Type: text/html; charset=iso-8859-1");
    //header("Pragma: No-cache");
    if ($title == "")
        $title = $SITENAME .(isset($_GET['tbv'])?" (".TBVERSION.")":'');
    else
        $title = $SITENAME .(isset($_GET['tbv'])?" (".TBVERSION.")":''). " :: " . htmlspecialchars($title);
        
 	$ss_uri = 'ICGstation';
  
  require_once "themes/".$ss_uri."/template.php";
  require_once("themes/" . $ss_uri . "/stdhead.php");
}


function stdfoot() 
{
  global $ss_uri;
  $ss_uri = 'ICGstation';

  require_once "themes/".$ss_uri."/template.php";
  require_once("themes/" . $ss_uri . "/stdfoot.php");
}

function tr($x,$y,$noesc=0) {
    if ($noesc)
        $a = $y;
    else {
        $a = htmlspecialchars($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    print("<tr><td class=\"heading\" valign=\"top\" align=\"left\">$x</td><td valign=\"top\" align=left class=\"rowhead2\">$a</td></tr>\n");
}
function utime()
{
  return (float) preg_replace('/^0?(\S+) (\S+)$/X', '$2$1', microtime());
}
	$pgs=utime();
	stdhead('Admin Options');
// Templates
//	Options Type Templates
//	current format:
//		Key => array(Numeric,DisplayInput,ValidationRule,PPFilter)
//  where:
//    Key: is the Options Menu Type
//    Numeric: dictates if the code shud be enclosed in quotes, or shud be left as is (eval possibly)
//    DisplayInput: is the code for the Input box on the form
//    ValidationRule: is php code that $val is passed thru, must set $ok, 0=failed 1=success
//    PrePFilter: is php code that is applied before the the value is saved to the config
//    PstPFilter: is php code that is applied after retrieving it from the config
//    
//    may contain:
//          $key: FormName from Options Menu
//          $val: Value from either config.php or DefaultValue from Options Menu

	$templates=array(
			'hidden' => array(0,'<input name="$key" type="hidden" id="$key" value="$val" size="83" maxlength="80" readonly>',NULL,NULL,NULL),
			'string' => array(0,'<input name="$key" type="text" id="$key" value="$val" size="83" maxlength="80">',NULL,NULL,NULL),
			'password' => array(0,'<input name="$key" type="text" id="$key" value="$val" size="83" maxlength="80">',NULL,NULL,NULL),
			'path' => array(0,'<input name="$key" type="text" id="$key" value="$val" size="83" maxlength="80">','is_valid_path',NULL,NULL),
			'url' => array(0,'<input name="$key" type="text" id="$key" value="$val" size="83" maxlength="80">','is_valid_url',NULL,NULL),
			'rurl' => array(0,'<input name="$key" type="text" id="$key" value="$val" size="83" maxlength="80">','is_valid_rurl',NULL,NULL),
			'aurl' => array(0,'<textarea name="annurl" cols="80" rows="3" wrap="off" id="annurl">$val</textarea>','is_valid_urls',NULL,NULL),
			'email' => array(0,'<input name="$key" type="text" id="$keyid" value="$val" size="83" maxlength="80">','is_valid_email',NULL,NULL),
			'tf' => array(1,'<input name="$key" type="checkbox" value="true" $checked>','is_tf',NULL,NULL),
			'int' => array(1,'<input name="$key" type="text" id="$key" value="$val" size="43" maxlength="40">','is_numformula',NULL,NULL),
			'bytes' => array(1,'<input name="$key" type="text" id="$key" value="$val" size="43" maxlength="40">','is_numformula',NULL,NULL),
			'sec' => array(1,'<input name="$key" type="text" id="$key" value="$val" size="43" maxlength="40">','is_numformula',NULL,NULL),
			'float' => array(1,'<input name="$key" type="text" id="$key" value="$val" size="11" maxlength="8">','is_floatformula',NULL,NULL),
		);


// Options Menu Array
// A little more complicates
// each entry is either an string or an array
// if it's a string, than it's a Header that contains arrays below it
// the array for menu items is under this format
//  DisplayName, Type, FormName, ConfigName, Description, DefaultValue
//     DisplayName: Display name on the Form
//     Type: The type of input expected, used in validating user/config input
//     FormName: the name that appears on the form, and the variable name used in php (global)
//     ConfigName: the variable name (preceded with $) or constant name (defined) in config.php
//     Description: brief description displayed next/under the input button on the form
//     Default/Value: Default value used if not found in config.php or on POST
	$options=array(
		'Site Info',
			array('TBVersion','hidden','tbv','TBVERSION','TBDevnet Versioning info',TBVERSION),
			array('Site Name','string','sitename','$SITENAME','Name of your torrent tracker','My Tracker'),
			array('Site Url','url','siteurl','$BASEURL','Your site url, used in page links (no ending slash)','http://www.mytracker.net'),
			array('Base Url','url','baseurl','$DEFAULTBASEURL','Sites base path, used in emails (no ending slash)','http://www.mytracker.net'),
			array('Site Email','email','siteemail','$SITEEMAIL','Email for sender/return path','noreply@mytracker.net'),
			array('Announce Urls','aurl','annurl','$announce_urls[]','Announce urls','http://www.mytracker.net/announce.php'),
		'Database',
			array('Host','string','dhost','$mysql_host','Database host (domain or ip)','localhost'),
			array('User','string','duser','$mysql_user','Database username','tb'),
			array('Password','password','dpass','$mysql_pass','Database password',''),
			array('Database','string','ddb','$mysql_db','Database name','bittorrent'),
		'Switches',
			array('Site Online','tf','bonline','$SITE_ONLINE','Site Open for business?','true'),
			array('Members Only','tf','bmembers','$MEMBERSONLY','Only registered users may use','true'),
			array('Email Confirmation','tf','bconfirm','ENA_EMAIL_CONFIRM','Use Email Confirmation','true'),
			array('Alternate Announce','tf','baltann','ENA_ALTANNOUNCE','Enable Alternate Announce/scrape urls','true'),
			array('Passkey System','tf','bpasskey','ENA_PASSKEY','Enable Passkey System','true'),
			array('--- &nbsp;Limit Connections','tf','bplc','ENA_PASSKEYLIMITCONNECTIONS','Limit Amount of connections (Required: Passkey System)','false'),
		'Limits',
			array('Users','int','limitusers','$maxusers','Max Users before signups close','75000'),
			array('Invits','int','limitinvit','$invites','Max Users before invits close','76000'),
			array('Peers','int','limitpeers','$PEERLIMIT','Max Peers allowed, not implemented','50000'),
			array('Torrent Size','bytes','limittsize','$max_torrent_size','Max torrent filesize that can be uploaded','10000000'),
			array('Votes','int','limitminvotes','$minvotes','Minimum # of votes for rating display','1'),
			array('Max File Size','bytes','maxfilesize','$maxfilesize','Max filesize that can be uploaded into bitbucket','256 * 1024'),
		'Paths',
			array('Torrents','path','dirtorrents','$torrent_dir','Server path to torrent folder (complete or relative, no ending slash)','torrents'),
			array('BitBucket','rurl','dirbucket','$bitbucket_dir','Relative Server/url path to BitBucket folder (no beginning,no ending slash)','bitbucket'),
			array('Images<br/>(unused)','rurl','urlpics','$pic_base_url','Relative Image url path (with beginning & ending slash)','/pic/'),
		'Timed',
			array('Announce Interval','sec','tannounce','$announce_interval','Time between announces to give to user clients.','60 * 30'),
			array('Autoclean Interval','sec','taclean','$autoclean_interval','How long between autoclean runs.','900'),
			array('Signup Timeout<br/>(unused by auto-cleanup)','sec','tsignupto','$signup_timeout','How long to wait before deleting unconfirmed accounts and invites.','86400 * 3'),
			array('Dead Torrent Time','sec','tdeadtorrent','$max_dead_torrent_time','How long to wait to make torrents invisible (no seeds/no peers)','6 * 3600'),
			array('Dead User Time<br/>(unused by auto-cleanup)','sec','tdeaduser','$max_dead_user_time','How long to wait before deleting inactive user accounts..','42*86400'),
			array('Dead Topic Time<br/>(unused)','sec','tdeadtopic','$max_dead_topic_time','How long to wait before setting inactive forum topics locked..','7*86400'),
			array('Torrent TTL','sec','ttorrentttl','$torrent_ttl','How long do torrents live for.','28*86400'),
		'Auto Promote to Power Users',
			array('Transfer Limit','bytes','aplimit','$ap_limit','Uploaded amount for promotion','25*1024*1024*1024'),
			array('Minimum Ratio','float','apratio','$ap_ratio','Minimum ratio for promotion','1.05'),
			array('Time Limit','sec','aptime','$ap_time','The promotion is only valid if the user has been registered longer than the time entered','28*86400'),
		'User Class Behaviors',
			array('Minimum Ratio','float','adratio','$ad_ratio','Minimum ratio required to keep Power User','.95'),
			array('Allow Upload For Everyone','tf','public_upload','ENA_UPLOAD_FOR_EVERYONE','Allow all members to upload their torrents (If unchecked: Only class >= uploader can post torrents) ','false'),
		'Phpbb Settings',
			array('PhpBB Path','rurl','dirphpbb','$phpbb2_folder','Server path to phpbb folder (only relative, no beginning no ending slash)','phpBB2'),
			array('PhpBB Basefile','string','phpbbbasefile','$phpbb2_basefile','Name of the phpbb basefile (rename the file if you changed this)','phpbb2.php'),
			array('Activate PhpBB Forum','tf','phpbbonline','$activate_phpbb2_forum','Activate the phpbb forum (do not activate the forum yet, if installing for the first time)','false'),
			array('Shared PhpBB Users','tf','phpbbshared','$share_phpbb2_users_with_TBDev','Share phpbb users with TBDev (Share Mode)','true'),
	);

	function is_valid_email($val)
	{
		return preg_match('/^([a-zA-Z0-9_\-\.]+@(?:[a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,4}|\x22.+\x22\s\x3c[a-zA-Z0-9_\-\.]+@(?:[a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,4}\x3e)$/',$val);
	}
	function is_valid_url($val)
	{
		$pp=parse_url($val);
		return (("$pp[scheme]://$pp[host]".(isset($pp["port"]) ? ":$pp[port]":"").(isset($pp["path"]) ? "$pp[path]":"")==$val) ? true:false);
	}
	function is_valid_path($val)
	{
		return is_valid_rurl($val,1);
	}
	function is_valid_rurl($val,$rta=0)
	{
		GLOBAL $submission;
		$pp=parse_url($val);
		if(($ok= ( $pp["path"]==$val ? true:false)) && $submission)
		{
			$val=(($val[0]=='/' && $rta==0) ? substr($val,1):$val);
			if(!is_dir($val))
				mkdir($val,0777);
			else if(!$rta)
				@chmod($val,0777);
			$ok=is_dir($val);
		}
		return $ok;
	}
	function is_valid_urls($val)
	{
		if($ok=is_array($val))
		{
			foreach($val as $value)
			{
				if(($ok=is_valid_url($value))===false)
					break;
        }					
		}
		return ($ok);
	}		
	function is_tf($val)
	{
		return (in_array($val,array("true","false",1,0)) ? true:false);
	}
	function is_numformula($val)
	{
		return ((preg_match("/^[0-9\s-\x2b\x28\x29\x2a]+$/",$val)==1) ? true:false);
	}
	function is_floatformula($val)
	{
		return ((preg_match("/^[0-9\s-\x2b\x28\x29\x2a\x2e]+$/",$val)==1) ? true:false);
	}
	function check_aurl($val)
	{
		$arr=array();
		foreach($val as $value)
		{ $value=fixup($value);
			if(!empty($value))
				$arr[]=$value;
		}
		return $arr;
	}
	function fixup($val)
	{
		if($val[0]=='"' || $val[0]=="'")
			$val=substr($val,1,strlen($val)-2);
		return stripslashes(trim($val));
	}
		
	function calctime($val)
	{
		$days=intval($val / 86400);
		$val-=$days*86400;
		$hours=intval($val / 3600);
		$val-=$hours*3600;
		$mins=intval($val / 60);
		$secs=$val-($mins*60);
		return "<br>&nbsp;&nbsp;&nbsp;$days days, $hours hrs, $mins minutes, $secs Seconds";
	}
	function calcbytes($val)
	{
		$tb=intval($val / ($ml=1073741824));
		$val-=$tb*$ml;
		$gb=intval($val / ($ml/=1024));
		$val-=$gb*$ml;
		$mb=intval($val / ($ml/=1024));
		$val-=($mb*$ml);
		$kb=intval($val / ($ml/=1024));
		$bytes=$val-($kb*$ml);
		return "<br>&nbsp;&nbsp;&nbsp;$tb TB, $gb GB, $mb MB, $kb KB, $bytes Bytes";
	}

//------------------ db connection ---------------//

function dbconnForInstall()
{
		include "include/config.php";
    if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
    {
                  switch (mysql_errno())
                  {
                                case 1040:
                                case 2002:
                                        if ($_SERVER[REQUEST_METHOD] == "GET")
                                                die("<html><head><meta http-equiv=refresh content=\"5 $_SERVER[REQUEST_URI]\"></head><body><table border=0 width=100% height=100%><tr><td><h3 align=center>The server load is very high at the moment. Retrying, please wait...</h3></td></tr></table></body></html>");
                                        else
                                                die("Too many users. Please press the Refresh button in your browser to retry.");
        default:
                die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());
      }
    }
    mysql_select_db($mysql_db)
        or die('dbconn: mysql_select_db: ' + mysql_error());
}

//------------------ save config file	----------------//

	function saveconfig()
	{
		global $options,$pnum,$pdef,$haveconfig;
		
  	if($fh=fopen(ConfigFN,'w'))
  	{
  		$config="<?php\n//\n// Generated by admincp.php on ". gmdate("M d Y H:i:s") ."\n// XTBDevnet\n//\n\n";
  		foreach($options as $okey => $oval)
  			if(is_array($oval))
  			{
  					$config.="// ". $oval[4] ."\n";
  					$q=($pnum[$okey] ? '' : '"');
						$add=($oval[3][0]!='$' ? true : false);
						if(!is_array($pdef[$okey]))
							$config.= ($add ? "define ('":''). $oval[3] .($add ? "',":' = '). $q . addcslashes(stripslashes($pdef[$okey]),"\0..\37\"$\\\177..\377"). $q .($add ? ')':'') .";\n";
						else
							foreach($pdef[$okey] as $val)
								$config.=($add ? "define ('":''). $oval[3] .($add ? "',":' = ') . $q. addcslashes(stripslashes($val),"\0..\37\"$\\\177..\377") .$q .($add ? ')':'') .";\n";
  			}
  		$config.="?>\n";
  		fwrite($fh,$config);	
  		fclose($fh);
  		$haveconfig=true;
  	}
  }


//------------------ Install one sql file in the database ----------------//

function installDbFile($dbms_schema)
{
	
	$remove_remarks = 'remove_remarks';
	$delimiter = ';'; 
	$delimiter_basic = ';'; 

	$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
	$sql_query = $remove_remarks($sql_query);
	$sql_query = split_sql_file($sql_query, $delimiter);

	for ($i = 0; $i < sizeof($sql_query); $i++)
	{
		if (trim($sql_query[$i]) != '')
		{
			if (!($result = mysql_query($sql_query[$i])))
			{
				echo "<b>Error in $dbms_schema:</b> ".mysql_error()." <br/>";
				end_frame();
				end_main_frame();
				stdfoot();
				die();
			}
		}
	}
	echo "<b>$dbms_schema file successfully installed...</b><br/>";
}


//-------------- Install SQL files during a first time install -------------//

function freshSQLFilesPostInstall()
{
		global $plkp,$plkp,$pdef;
		$key_duser=$plkp['duser'];
		$key_dpass=$plkp['dpass'];
		$key_dhost=$plkp['dhost'];
		$key_ddb=$plkp['ddb'];

		if ($_POST["installDb"]=="yes")
		{
			print "<center><h1><b>SQL files Installation</b></h1></center><br/>";
			
			print "<center>Press start to install the sql files in your database</center><br/>";
			
			echo '<form action="" method="post" enctype="application/x-www-form-urlencoded" name="upgrade">';
		  echo '<input name="installDb" type="hidden" value="Go">';
			echo '<input name="luser" type="hidden" value="'.$pdef[$key_duser].'">';
			echo '<input name="lpass" type="hidden" value="'.$pdef[$key_dpass].'">';
			echo '<center><input type="submit" name="Submit" value="Start"></center></form>';
			end_frame();
			end_main_frame();
			stdfoot();
			die();
		}
		
		if ($_POST["installDb"]=="Go")
		{
			dbconnForInstall();
			installDBFile("sql/database.sql");
			installDBFile("sql/categories.sql");
			installDBFile("sql/countries.sql");
			installDBFile("sql/stylesheets.sql");
			installDBFile("sql/hacks.sql");
			
			echo "<br/>Installation is finished now, you can click here to register a <a href=".$BASEURL."signup.php>Sysop Account</a><br/>";
			
			end_frame();
			end_main_frame();
			stdfoot();
			die();
		}
		
}

//------------------ Upgrade from V0.3 to V0.4 ----------------//

function upgrade_V03_V04()
{
	global $plkp,$plkp,$pdef;
	$key=$plkp['duser'];
	$key2=$plkp['dpass'];
	
	if ($_POST["upgrade"]!="yes")
	{
		print "<center><h1><b>You need to upgrade from TorrentStrike V0.3 to ".TBVERSION."</b></h1></center>";
		print "<center><font color=red>WARNING: BACKUP YOUR DATABASE BEFORE RUNNING THIS !</font></center><br/>";
		echo '<form action="" method="post" enctype="application/x-www-form-urlencoded" name="upgrade">';
	  echo '<input name="upgrade" type="hidden" value="yes">';
		echo '<input name="luser" type="hidden" value="'.$pdef[$key].'">';
		echo '<input name="lpass" type="hidden" value="'.$pdef[$key2].'">';
		echo '<center><input type="submit" name="Submit" value="Start"></center></form>';
	}
	else
	{
		dbconnForInstall();
		mysql_query("UPDATE users SET class=class*16") or die("Upgrade to V0.4 failed :(");
		installDBFile("sql/upgrade/torrentstrike_0.3_to_0.4.sql");

  	$keyTBVersion=$plkp['tbv'];
  	$pdef[$keyTBVersion]=TBVERSION;
		saveconfig();

		print "<center><b>Successfully upgraded to ".TBVERSION."</b><br/>";
		print "POST UPGRADE STEP:<br/><br/>PhpBB database need to be upgraded to version 2.0.19 too!<br/>";
		print "<a href=\"".$BASEURL."/phpBB2/install/update_to_latest.php\" target=\"blank\">click here to do it now! (open in a new window)</a><br/><br/>";
		echo '<form action="" method="post" enctype="application/x-www-form-urlencoded" name="upgrade">';
		echo '<input name="luser" type="hidden" value="'.$pdef[$key].'">';
		echo '<input name="lpass" type="hidden" value="'.$pdef[$key2].'">';
		echo '<center><input type="submit" name="Submit" value="continue to configuration"></center></form>';
	}

	end_frame();
	end_main_frame();
	stdfoot();
	die();
}

//----------------------------------------------------//
	
	
// Setup array of Reference Values for quicker lookups	
	foreach($options as $key => $value)
	{
		if(is_array($value))
		{
			$plkp[$value[2]]=$key;
			$ptyp[$key]=$value[1];
			$pnum[$key]=$templates[$value[1]][0];
			$prep[$key]=$value[2];
			$pvar[$key]=$value[3];
			$pdef[$key]=($value[1]=='aurl' ? explode('\n',$value[5]):$value[5]);
		}
	}
	// If this is a submitted form, fill in our form defaults
	if($_POST['action']=='submit') 
	{
		$submission=true;
		foreach($ptyp as $pkey => $val)
			if($val=='tf')
				$pdef[$pkey]=0;
  	foreach($_POST as $pkey => $pvalue)
		{
  		if(isset($plkp[$pkey])) 
  		{
  			$key=$plkp[$pkey];
  		  $pdef[$key] = ($ptyp[$key]=='aurl' ? explode("\n",$pvalue) : ($ptyp[$key]=='tf' ? 1 :$pvalue));
  		}
  		
  	}
  } else
	
	// Read our config.php file and get valid contents
	// replace form defaults if option exists
	if($fh=@fopen(ConfigFN,'r'))
	{
		$config=fread($fh,filesize(ConfigFN)+1);
		fclose($fh);
		if (filesize(ConfigFN)!=0) $haveconfig=true;
		preg_match_all("/^define\s*\(\s*[\x22\x27](.+)[\x22\x27]\s*,\s*(\d+|.+)\s*\)\s*;$/m",$config,$defines);
  	preg_match_all("/^([$][a-zA-Z0-9\x5f]+)\s*=\s*(\d+|[\x22\x27].+[\x22\x27])\s*;$/m",$config,$vars);
  	unset ($config);
  	$config[0]=array_merge($defines[1],$vars[1]);
  	$config[1]=array_merge($defines[2],$vars[2]);
  	foreach($config[0] as $ck => $val)
  		if(!(($key=array_search($val,$pvar))==FALSE))
  		{
  			if($config[1][$ck][0]!='"')
  				$pdef[$key]=$config[1][$ck];
  			else if($ptyp[$key]!='aurl')
  				$pdef[$key]=substr($config[1][$ck],1,strlen($config[1][$ck])-2);
  			else
  				$pdef[$key][]=substr($config[1][$ck],1,strlen($config[1][$ck])-2);
  		}
  }
  
  // Validate the form entries
  foreach($pdef as $key => $val)
  {
		if(!empty($templates[$ptyp[$key]][2]))
		{
			if($pnum[$key])
				eval("\$val = (". ($ptyp[$key]=='float'?'float':'int') .")($val);");
			else
				$val=($ptyp[$key]=='aurl' ? check_aurl($val):fixup($val));
			// Use the defaults if validation fails
			
			$pdef[$key]=(!call_user_func($templates[$ptyp[$key]][2],$val) ? ($ptyp[$key]=='aurl' ? explode("\n",$options[$key][5]):$options[$key][5]) : $val);

		}
  }
  // Simple login validation check
  if($haveconfig)
  {
  	$key=$plkp['duser'];
  	$key2=$plkp['dpass'];
  	if(!(empty($pdef[$key])) && !(empty($pdef[$key2])))
  	{
	  	$validlogin=($_POST['luser']==$pdef[$key] && $_POST['lpass']==$pdef[$key2]);
	  	if(!$validlogin) {
				begin_main_frame();
				begin_frame("Admin Control Panel Login");
  			begin_table(1);
  			echo '<form action="" method="post" enctype="application/x-www-form-urlencoded" name="login">';
  			tr($options[$key][4],'<input name="luser" type="text" size="83" maxlength="80">',1);
  			tr($options[$key2][4],'<input name="lpass" type="password" size="83" maxlength="80">',1);
				end_table();
				echo '	<center><input type="submit" name="Submit" value="Submit">	</center>';
				end_frame();
				end_main_frame();
				stdfoot();
				die();
			}
		}
  }
  		  	
	if($submission)
	{
		saveconfig();
  }

 // add some extra info to some options
 // final processing for form display
 foreach($pdef as $key => $val)
 {
  	switch($ptyp[$key])
  	{
  		case 'sec':
  			$options[$key][4].=calctime($pdef[$key]);
  			break;
  		case 'bytes':
  			$options[$key][4].=calcbytes($pdef[$key]);
  			break;
  		case 'aurl':
  			$options[$key][4].='<br>&nbsp;<strong>One per line.</strong>';
  			$pdef[$key] = implode("\n",$pdef[$key]);
  			
  	}
  }
	
// ---------------------------------------------
// OMG, Finally the Output Portion of the script
// ---------------------------------------------

	begin_main_frame();
	begin_frame('Configuration Settings');

	if (isset($_POST["installDb"]))
		freshSQLFilesPostInstall();
	
	$keyTBVersion=$plkp['tbv'];
	$tbcurversion = $pdef[$keyTBVersion];
	if (!strcmp($tbcurversion,"XTBDev 0.10 Beta"))
	{
		upgrade_V03_V04();
	}
	
?>
	<CENTER><H1><BOLD><?= TBVERSION ?></BOLD></H1></CENTER>
	<form action="" method="post" enctype="application/x-www-form-urlencoded" name="config">
<?
	begin_table(1);
	foreach($options as $value)
	{
		if(is_string($value))
			echo "<tr><td colspan=2 class='title' bgcolor='#6699CC'><CENTER>$value</CENTER></td></tr>";
		else if(is_array($value)) {
			$key=$value[2];
			$val=htmlspecialchars(stripslashes($pdef[$plkp[$key]]));
			if($value[1])
				$checked=$val ? ' checked':'';
			eval('$opt="'. addslashes( $templates[$value[1]][1] ) .($value[1]=='tf'?'':'<br>') .'";');
			if($value[1]!='hidden')	
				tr($value[0],"&nbsp;$opt&nbsp;$value[4]",1);
			else 
				echo $opt;
		}
	}
	end_table();
?>
	<br>
	<center>
	<input name="action" type="hidden" value="submit" readonly>
	<? if ($haveconfig==false) print("<input name=\"installDb\" type=\"hidden\" value=\"yes\" />");?>
	<input type="submit" name="Submit" value="Submit">
	&nbsp;&nbsp;&nbsp;
  <input type="reset" name="Reset" value="Reset">
	</center>
	</form>
<?
	end_frame();
	
	end_main_frame();
	$pgt=utime()-$pgs;
	echo "<CENTER>Page Generated in $pgt Seconds</CENTER>";
	stdfoot();
	die();
?>
