================================================================================
GET YOUR MINT ON

Following Instructions 101: 

	Please read *every* step of the relevant instructions before undertaking 
	the first step. A little context goes a long way towards understanding.

Installation in three easy steps. Update & uninstall instructions below.
--------------------------------------------------------------------------------

1. Open /mint/config/db.php, fill in your database connection details and save.

2. Upload the entire /mint/ folder to your server.

3. Now visit Mint in a web browser <http://www.yourdomain.com/mint/> and follow
the remaining instructions. The same instructions will be accessible later from 
the Preferences or by visiting <http://www.yourdomain.com/mint/?instructions>.


================================================================================
Initial Update from Mint 1

It is always a good idea to backup your data before performing any sort of 
update. The Backup/Restore Pepper available in your Mint Preferences is perfect 
for this sort of thing.
--------------------------------------------------------------------------------

IF YOU HAVE THE THIRD-PARTY "XXX STRONG MINT" PEPPER INSTALLED YOU MUST 
UNINSTALL IT BEFORE UPGRADING MINT. THIS PEPPER USES A FUNCTION THAT CAN CAUSE 
A SIGNIFICANT DELAY IN RECORDING A HIT TO YOUR SITE. IF A HIT IS RECORDED DURING
THE UPDATE PROCESS YOU RISK IRREPARABLY CORRUPTING YOUR MINT CONFIG DATA. UNTIL 
THIS PEPPER IS UPDATED TO RESOLVE THIS ISSUE IT SHOULD NOT BE REINSTALLED.

1. Open /mint/config/db.php, fill in your database connection details and save.

2. Rename the /mint/ folder to /mint-update/ and upload to your server.

3. Once the upload is complete, rename the following folders:

	mint			>	mint-old
	mint-update		>	mint

3b. Be sure to copy over any third-party Pepper and .htaccess files from your
previous installation.

4. Clear your browser's cache and visit Mint <http://www.yourdomain.com/mint/>.
(I've noticed that newer versions of Safari are reluctant to forget cached
JavaScript so you may need to quit and relaunch Safari if Mint appears to be
broken.)


================================================================================
Update from a previous version of Mint 2

It is always a good idea to backup your data before performing any sort of 
update. The Backup/Restore Pepper available in your Mint Preferences is perfect 
for this sort of thing.
--------------------------------------------------------------------------------

OVERVIEW

Essentially what you're about to do is replace the following directories in your
Mint installation with updated versions:

	/app/
	/pepper/shauninman/default/
	/pepper/shauninman/backuprestore/
	/styles/mint_chocolate/
	/styles/vanilla_mint/

The renaming process outlined below serves to reduce the likelihood that someone
visits your site while key Mint files are mid-update.

1. Rename the new /app/ folder contained in this download to /app-new/ 
and do the same for any Pepper in the /pepper/shauninman/ directory 
(eg. /peppername-new/) then upload the renamed directories into your 
existing Mint installation. Also upload the contents of the new /styles/ 
directory and index.php.

2. Once the upload is complete, rename the following folders on your server:

	app			>	app-old
	app-new		>	app
	
And for each updated Pepper in /pepper/shauninman/

	peppername		>	peppername-old
	peppername-new	>	peppername

Be sure to delete the /peppername-old/ directories or your Pepper Preferences 
will not show up.

3. Clear your browser's cache and visit Mint <http://www.yourdomain.com/mint/>.
(I've noticed that newer versions of Safari are reluctant to forget cached
JavaScript so you may need to quit and relaunch Safari if Mint appears to be
broken.)


================================================================================
Uninstalling Mint

Halitosis be damned!
--------------------------------------------------------------------------------

1. Log into your Mint installation and click on Preferences.

2. Click on Uninstall and follow the instructions.

3. If you chose to attach the Mint JavaScript include using the advanced method,
don't forget to remove the related code from your .htaccess files.


================================================================================
Copyright 2004-2008 Shaun Inman. This package cannot be redistributed without
permission from http://www.shauninman.com/

More info at: http://www.haveamint.com/