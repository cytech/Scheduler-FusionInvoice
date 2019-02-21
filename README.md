# Scheduler-FusionInvoice
Scheduler Addon for FusionInvoice 2018-8

Scheduler Addon for FusionInvoice
----------------------------------
Installation or Update:

Initial Installation or update from Scheduler v2.0.1 and later:

1.) Unzip "scheduler_fusioninvoice_v2_0_1.tar.gz" to temporary directory

2.) Upload the unzipped files and directories to your FusionInvoice web folder, keeping the directory structure.

3.) Change permissions as necessary for your website setup.

4.) In FusionInvoice:

Initial Install:

Select System (gear icon) Addons.

Select Install button next to Scheduler Addon.

To Update:

Select System (gear icon) Addons.

Select Update button next to Scheduler Addon.

Update from Scheduler v2.0.0 or earlier:

1.) Delete the <FusionInvoice>/custom/addons/Scheduler directory in your FusionInvoice web server directory.

2.) Unzip "scheduler_fusioninvoice_v2_0_1.tar.gz" to temporary directory

3.) Upload the unzipped files and directories to your FusionInvoice web folder, keeping the directory structure.

4.) Change permissions as necessary for your website setup.

5.) In FusionInvoice:

Select System (gear icon) Addons.

Select Update button next to Scheduler Addon.

Scheduler menu - Utilities - Settings

Set options to your preference.

If running the Workorders Addon for FusionInvoice:

Set Enable "Create Workorder" functionality to "Yes"

Optional:

FusionInvoice System menu - Settings - Dashboard:

Enable scheduler summary widget

Set display order = 4, column width = 6

Set invoice display order = 2, quote display order = 3


NOTE: Occasionally there may be a conflict with old views in the Laravel cache.
If you receive an odd error on page load after install try and clear the cache by:
(in browser address bar:)
http://YourFusionInvoice/scheduler/viewclear

Under some upgrade circumstances, you may also get an error of some database table
not defined. This is most likely do to the fact that the addon migration has not been run yet.
To run the migration:
(in browser address bar:)

http://YourFusionInvoice/addons
This should take you to the FusionInvoice addon page where you can "Complete Upgrade".


