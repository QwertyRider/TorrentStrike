<?

require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

stdhead("Contact Staff", false); 
echo "<h1>Send message to Staff</h1>"; 
begin_frame("Contact Staff",true,5,450);

?>
<form method="post" name="message" action="takecontact.php">
<?begin_table();?>
<tr><td class="row2"<?=$replyto?" colspan=\"2\"":""?>>
<? if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"]) { ?> 
<input type="hidden" name="returnto value=<?=$_GET["returnto"] ? $_GET["returnto"] : $_SERVER["HTTP_REFERER"]?>"/>
<? } ?>
Subject <input type="text" size="74" name="subject"/><br/><br/>
<textarea name="msg" cols="80" rows="15"><?=htmlspecialchars($body)?></textarea></td></tr>
<tr><td class="row2" align="center"><input type="submit" value="Send it!" class="btn"/></td></tr>
<?end_table();?>
</form>
<?
end_frame();
stdfoot();
?>
