<?

ob_start("ob_gzhandler");

require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

$nick = ($CURUSER ? $CURUSER["username"] : ("Guest" . rand(1000, 9999)));
$ircNick = $CURUSER['username'] . "";
stdhead("Chat");
begin_main_frame();
?>
<center>
<b>Installation Instruction:</b><br/>
-1- Download <a href="http://prdownloads.sourceforge.net/pjirc/pjirc_2_2_0_bin.zip?download">Pjirc</a> and depack it in the pjirc folder<br/>
-2- Change the server host and the chanel in chat.php:<br/>
<? 
echo htmlspecialchars('<param name="host" value="myirchost.com">');
echo ("<br/>");
echo htmlspecialchars('<param name="command1" value="/join #MyTrackerChannel">');
?>
</center>
<?
begin_frame("$SITENAME IRC Chat");
?>
<applet name="applet" codebase="/pjirc/" code="IRCApplet.class" archive="irc.jar, pixx.jar" width="640" height="400">
<param name="CABINETS" value="irc.cab, securedirc.cab, pixx.cab"/>
<param name="nick" value="<? echo $ircNick; ?>"/>
<param name="fullname" value="<? echo $SITENAME;?> User"/>
<param name="host" value=" irc.lv"/>
<param name="command1" value="/join #UniNet!_Tracker"/>
<param name="gui" value="pixx"/>
document.write('<param name="coding" value="3"/>');
<param name="style:bitmapsmileys" value="true"/>
<param name="style:smiley1" value=":) img/sourire.gif"/>
<param name="style:smiley2" value=":-) img/sourire.gif"/>
<param name="style:smiley3" value=":-D img/content.gif"/>
<param name="style:smiley4" value=":d img/content.gif"/>
<param name="style:smiley5" value=":-O img/OH-2.gif"/>
<param name="style:smiley6" value=":o img/OH-1.gif"/>
<param name="style:smiley7" value=":-P img/langue.gif"/>
<param name="style:smiley8" value=":p img/langue.gif"/>
<param name="style:smiley9" value=";-) img/clin-oeuil.gif"/>
<param name="style:smiley10" value=";) img/clin-oeuil.gif"/>
<param name="style:smiley11" value=":-( img/triste.gif"/>
<param name="style:smiley12" value=":( img/triste.gif"/>
<param name="style:smiley13" value=":-| img/OH-3.gif"/>
<param name="style:smiley14" value=":| img/OH-3.gif"/>
<param name="style:smiley15" value=":'( img/pleure.gif"/>
<param name="style:smiley16" value=":$ img/rouge.gif"/>
<param name="style:smiley17" value=":-$ img/rouge.gif"/>
<param name="style:smiley18" value="(H) img/cool.gif"/>
<param name="style:smiley19" value="(h) img/cool.gif"/>
<param name="style:smiley20" value=":-@ img/enerve1.gif"/>
<param name="style:smiley21" value=":@ img/enerve2.gif"/>
<param name="style:smiley22" value=":-S img/roll-eyes.gif"/>
<param name="style:smiley23" value=":s img/roll-eyes.gif"/>
</applet>
<br/>
<input type="button" value="Smile" onclick="document.applet.setFieldText(document.applet.getFieldText()+':)');document.applet.requestSourceFocus()"/>
<input type="button" value="Chanlist" onclick="document.applet.sendString('/list')"/>
<input type="button" value="Quit" onclick="document.applet.sendString('/quit')"/>

<?
end_frame();
end_main_frame();
stdfoot();
?>