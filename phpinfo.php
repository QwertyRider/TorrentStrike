<?php 

require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();
if (get_user_class() < UC_SYSOP)
stderr("Error", "Permission denied.");

phpinfo(); 

?>