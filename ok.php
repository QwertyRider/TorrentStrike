<?

require_once("include/bittorrent.php");


if (!mkglobal("type"))
	die();

if ($type == "signup" && mkglobal("email")) {
	stdhead("User signup");
        stdmsg("Signup successful!",
	"A confirmation email has been sent to the address you specified (" . htmlspecialchars($email) . "). You need to read and respond to this email before you can use your account. If you don't do this, the new account will be deleted automatically after a few days.");
	stdfoot();
}
elseif ($type == "sysop") {
		stdhead("Sysop Account activation");
		stdmsg("Sysop Account successfully activated!", 
			(isset($CURUSER)?
			"Your account has been activated! You have been automatically logged in. You can now continue to the <a href=\"./\"><b>main page</b></a> and start using your account.":
			"Your account has been activated! However, it appears that you could not be logged in automatically. A possible reason is that you disabled cookies in your browser. You have to enable cookies to use your account. Please do that and then <a href=\"login.php\">log in</a> and try again.")
		);
	}
elseif ($type == "confirmed") {
	stdhead("Already confirmed");
	stdmsg("Already confirmed","This user account has already been confirmed. You can proceed to <a href=\"login.php\">log in</a> with it.");
	stdfoot();
}
elseif ($type == "invite" && mkglobal("email")) {
	stdhead("User invite");
	stdmsg("Invite successful!","A confirmation email has been sent to the address you specified (" . htmlspecialchars($email) . "). They need to read and respond to this email before they can use their account. If they don't do this, the new account will be deleted automatically after a few days.");
stdfoot();
}
elseif ($type == "confirm") {
	stdhead("Signup confirmation");
	stdmsg("Account successfully confirmed!",
		isset($CURUSER) ? 
			"<p>Your account has been activated! You have been automatically logged in. You can now continue to the <a href=\"./\"><b>main page</b></a> and start using your account.</p>\n".
			"<p>Before you start using torrentbits we urge you to read the <a href=\"rules.php\"><b>RULES</b></a> and the <a href=\"faq.php\"><b>FAQ</b></a>.</p>\n"
			:"Your account has been activated! However, it appears that you could not be logged in automatically. A possible reason is that you disabled cookies in your browser. You have to enable cookies to use your account. Please do that and then <a href=\"login.php\">log in</a> and try again."
	);
	stdfoot();

}
else
	die();

?>