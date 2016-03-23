#################################################
###             TorrentStrike V0.4              #
###              April 16th 2006                #
###            www.torrentstrike.net            #
#################################################


This Release (V0.4) contains:

- The last TBDev portal taken from CVS (April 15th 2006)
- Phpbb 2.0.20 with user and graphical integration for TBDev
- ICGstation template for phpbb
- A little sample avatar gallery for phpbb
- Graphical theme system for TBDev
- ICGstation theme for TBDev
- Default theme for TBDev
- A lot of added hack.
- A lot of bugfixes.


#########################################
    Fixed bugs from V0.1
#########################################


- BugID#4 Spacer.gif problems when not installed on localhost
- BugID#5 Smilies don't displayed in phpbb admin panel
- BugID#6 Hardcoded html removed from TBDev (It's only 50% done for now...)
- BugID#9 Installation of phpbb failed when installed from xampp 1.5.1 on windows


#########################################
    News Fixed bugs from V0.2
#########################################


- BugID#2  phpbb installation improved (not finished)
- BugID#19 Html signup page fixed (submited by devin)
- BugID#20 Better comments display (submited by devin)
- BugID#12 Cleanup code is now bug free. (old forum auto pruning removed, submitted by devin)
- BugID#15 Broken Dox link removed.
- BugID#30 Countries definitions are missing in countries.sql
- BugID#16 Local link (broken) in forum
- BugID#18 Picture missing in ICGStation theme
- BugID#24 Cleanup.php always deletes pending users
- BugID#10 Pending users are not removed from phpbb


#########################################
    News Fixed bugs from V0.3
#########################################

- BugID#57  SQL injection bug, in bittorrent.php, via validip()
- BugID#31  Statistics on main page are broken
- BugID#32  Installation in subfolder
- BugID#35  Update to latest TBDev Code
- BugID#37  phpbb viewtopic new post link broken
- BugID#40  Upgrade from V0.3 to V0.4
- BugID#41  No seeders reported in Homepage stats
- BugID#45  Reactivate Auto Cleanup Code
- BugID#53  Fix the auto deleting PMs
- BugID#54  cache in not working in staff.php
- BugID#59  phpbb "new topic" and "post reply" wrong link for guest users
- BugID#61  Mass email bug
- BugID#65  Ban IP hack is not banning anyone !
- BugID#66  Check if begin_block is used in every TS files
- BugID#67  phpbb 2.0.19 SQL Error : 1054

#########################################
    News Features from V0.3
#########################################

- Feature ID#6    Validate TBDev code as XHTML compliant
- Feature ID#33   Validate ICGStation theme as XHTML compliant
- Feature ID#2    Improve installation script
- Feature ID#23   Improve USER CLASS declaration
- Feature ID#39   Update to phpBB 2.0.19
- Feature ID#69   Update to phpBB 2.0.20
- Feature ID#42   Extend the theming system
- Feature ID#43   Users must select a country during registration
- Feature ID#52   move all javascript functions in an include file
- Feature ID#55   Add Ratio and stats in phpbb viewtopic
- Feature ID#56   update phpbb theme to the matching stylesheet
- Feature ID#58   Add the Invite hack
- Feature ID#60   add online users given by webnet in the index page
- Feature ID#62   Remove Required NFO for uploading
- Feature ID#68   Everybody can upload torrents
- Feature ID#17   List files or folder to chmod


######################
    INCLUDED HACKS
######################

        * Hacks#21: Temporary Demotion (by Retro)

        * Hacks#26: Online Staff (missing credits)

        * Hacks#27: Devin Custom Inbox (provided by devin: Based on Tux system, Thanks to OiNK for it)

        * Hacks#29: Request system (provided by devin: Thanks to OiNK and Enzo for supplying the mod)

        * Hacks#58: Invite Hack (by rightthere & others)

        * Hacks#60: Online Users on index page (missing credits)

        * Hacks#38: MySQL driven faqs (by Avataru)

        * Hacks#46: First Line of support (by aQuatomic)

        * Hacks#47: Warned panel (by Wilba)

        * Hacks#49: Contact Staff (by Echo)

        * Hacks#28: New Dynamic Control Panel (by Nazaret2005)

Default Features provided in the Control Panel:
-----------------------------------------------

SysOps Tools -Viewable by SysOp only:

            * Apache Style Statistics - Statistics of all visitings, Ip,Browser,Whence,Page (by hellix)
            * MySQL Query - Run mysql query
            * Mysql Overview - Tells when tables need optimising!(by CoLdFuSiOn)
            * PHP Info

Admin Tools - Viewable by Admin only:

            * News page - Add, edit and remove news items from the homepage
            * Manage Categories (by Sembulah)
            * Create Poll - Create a new poll
            * Poll Overview - See what the users have voted in the polls
            * Mass Email - Send a mass email to all users (Based on DJMcTom's)
            * Mass PM - Sends a pm to specified classes | by DJMcTom
            * Manage Unconfirmed Users (by killua)
            * Manage Unconnectable Users (by Wilba)
            * Ban IP
            * Add Users
            * Delete Users (by todgerme)
            * Update users invites


Moderators Tools - Viewable by Moderators only:

            * Site Statistics (by CoLdFuSiOn)
            * Uploading Stats - List upload activity and category activity
            * Newest users - 100 newest user accounts
            * Client Lister - List all clients the users are using
            * Delete Req - Delete torrent request(s)
            * Test IP - Test if an ip is banned or not
            * Manage FAQ - Add edit or remove items from the FAQ
            * Warned Panel - List Warned Users, remove warning and disable accounts (by Wilba)
            * Cleanup - Perform a manual cleanup



##############################################
             FRESH INSTALLATION
(if you're upgrading from V0.3, don't do this)
##############################################


-1- Uploading the files:
------------------------

Upload all files to your website.


-2- Additional setup: Creating missing files and CHMOD them (only needed by restricted apache server)
-------------------------------------------------------------------------------------------------------------
 
Files to create:

 - include/config.php (or simply rename the blank_config.php to config.php thats located in the include folder)
 - phpBB2/config.php (or simply rename the blank_config.php to config.php thats located in the phpBB2 folder)
 
Files or folder to chmod:

  - chmod 777 your bitbucket folder
  - chmod 777 your torrents folder
  - chmod 666 phpBB2/config.php
  - chmod 666 include/config.php
  - chmod 777 phpBB2/images/avatars (only neeeded if you want your users to send their own avatars)
  - chmod 666 info/stats.txt
  - chmod 666 your cache/staff.txt


-3- Setting up Torrentstrike:
----------------------

open your web browser to http://www.yourdomain.com/admincp.php
Enter all the needed info there and submit.
Keep the 'Activate PhpBB Forum' box unchecked for now !

After saving your config, the install will install the needed sql files for you.


-4- Create a Torrentstrike sysop account:
--------------------------------

open your web browser to http://www.yourdomain.com and choose Register
Register your account. 
This first created account will be granted with Sysop administrative rights, so it's good to choose 'admin' as 'Desired username'.


-5- Installing phpbb:
--------------------

Click the forum link, and choose "INSTALL NOW !" (This link is only shown if you are the sysop)
All parameters are set for you by TorrentStrike (check if they are correct for you), you only need to reenter your sysop account password.
Click the 'finish installation' button.

-6- Activate phpbb:
-------------------

open your web browser to http://www.yourdomain.com/admincp.php
check the 'Activate PhpBB Forum' box and submit.
delete install/ and contrib/ directories from the phpbb2 folder.
Logout and then Login again from the sysop account to update the phpbb admin login. (do it ,it's important!)

-7- Configure phpbb:
-------------------

use the link 'Go to Administration Panel' inside each phpbb page, you will need to identify yourself again


####################################
    UPGRADING FROM V0.3 TO V0.4
####################################

DON'T FORGET TO BACKUP YOUR DATABASE AND WEBSITE FILES BEFORE UPGRADING !


-1- Set your TorrentStrike V0.3 Offline:
----------------------------------------

open your web browser to http://www.yourdomain.com/admincp.php
Uncheck the "Site Online" box and save your config

-2- Upload TorrentStrike V0.4 files:
------------------------------------

Upload all the V0.4 file to your old TorrentStrike, there is 2 ways to do this:
- You can simply overwrite all old files with V0.4 files.
- You can delete all files from your V0.3 install, except include/config.php and phpBB2/config.php, then upload all V0.4 files.


-3- Update TorrentStrike config:
-------------------------------

open your web browser to http://www.yourdomain.com/admincp.php
The upgrade step will ask you to: 
1 - upgrade your database
2 - run the phpbb upgrade script (if you're not using phpbb it's not necessary)
3 - Return to admincp and check back the Site Online box and save your new config by submitting your changes!



#######################
   ADDITIONNAL NOTE
#######################

Autocleanup is now active again, but accounts are never deleted (This is because of a bug with phpbb account cleaning)
You can use the Full Cleanup function in the Admin/mods Control Panel to perform a manual full cleanup (including account removal) when you want !

You want to upgrade with a modified version of TorrentStrike V0.3:
------------------------------------------------------------------
We can't certify the upgrade will work, but one thing is sure: You will lose your personal add-ons.
You can use a tool like winmerge (http://winmerge.sourceforge.net) to diff V0.3 original files with your modified V0.3 files,
and keep trace of what you need to report in the new V0.4 files after upgrading.

If you need support for this king of problem, be careful: 
Half of the users patch the code everywhere and simply don't know what they're doing !
We have no mercy for them: So double check what you're doing before asking for help...


------------------------------------------
Send us your comments, bugs, hacks, ideas:
www.torrentstrike.net
------------------------------------------
