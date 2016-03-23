<?

require_once("include/bittorrent.php");



loggedinorreturn();

stdhead("Upload");

if ((get_user_class() < UC_UPLOADER)&&(!ENA_UPLOAD_FOR_EVERYONE))
{
  stdmsg("Sorry...", "You are not authorized to upload torrents.  (See <a href=\"faq.php#up\">Uploading</a> in the FAQ.)");
  stdfoot();
  exit;
}

?>
<div align="center">
<form enctype="multipart/form-data" action="takeupload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_torrent_size?>" />
<p>The tracker's announce url is <b><?= $announce_urls[0] ?></b></p>

<?
begin_frame("Upload",false,10,false);
begin_table(true);
tr("Torrent file", "<input type=\"file\" name=\"file\" size=\"80\" />\n", 1);
tr("Torrent name", "<input type=\"text\" name=\"name\" size=\"80\" /><br />(Taken from filename if not specified. <b>Please use descriptive names.</b>)\n", 1);
tr("NFO file", "<input type=\"file\" name=\"nfo\" size=\"80\" /><br />(<b>Optional.</b> Can only be viewed by power users.)\n", 1);
tr("Description", "<textarea name=\"descr\" rows=\"10\" cols=\"80\"></textarea>" .
  "<br />(HTML/BB code is <b>not</b> allowed.)", 1);

$s = "<select name=\"type\">\n<option value=\"0\">(choose one)</option>\n";

$cats = genrelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
tr("Type", $s, 1);

?>
<tr><td align="center" class="row2" colspan="2"><input type="submit" class="btn" value="Do it!" /></td></tr>
<? end_table();
end_frame();
?>
</form>
</div>
<?

stdfoot();

?>