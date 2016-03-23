<?
require "include/bittorrent.php";

stdhead();
?>

<b>Click the PayPal button below if you wish to make a donation!</b>

<p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="dirty.pool@gmail.com">
<input type="hidden" name="item_name" value="Torrentstrike Project Donation">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="tax" value="0">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" border="0" name="submit"
alt="Make payments with PayPal - it's fast, free and secure!" style='margin-top: 5px'>
</form>
</p>

<p>
<? begin_main_frame(); begin_frame(); ?>
<table border=0 cellspacing=0 cellpadding=0><tr valign=top>
<td class=embedded>
<img src=pic/flag/uk.gif style='margin-right: 10px'>
</td>
<td class=embedded>
Torrentstrike is a non profit project meaning any donations are welcome as it will help keep the project alive:
</td>
</tr></table>
<? end_frame(); begin_frame("Other ways to donate"); ?>
No other ways at the moment...
<? end_frame(); end_main_frame(); ?>
</p>
<?
stdfoot();
?>