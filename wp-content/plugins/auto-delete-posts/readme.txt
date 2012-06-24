=== Auto Delete Posts ===
Contributors: cypher
Donate link: http://ashwinbihari.com
Tags: post, management
Requires at least: 2.0.2
Tested up to: 2.9.2
Stable tag: 1.2.2

The Auto Delete Posts plugin can be used to delete or move all posts after an expiration date has elapsed.

== Description ==

The Auto Delete Posts plugin can be used to delete all posts after an expiration date has elapsed. It is also
possible to move all posts to a single category after expiration. Disabling the plugin will provide 
a preview of posts that will be deleted or moved.

<strong>Note</strong>: Please read the installation instructions for latest changes

== Installation ==
= First time =
1. Copy the directory auto-delete-posts to your /wp-content/plugins directory.
2. Go to 'Plugins' under the Administration menu and Activate the plugin.
3. Go to 'Settings->Auto Delete Posts' to view the current configuration.
4. Modify options, enable plugin and UPDATE.

You're done.
= Upgrade (pre v1.2) =
1. Update the plugin, activate, deactivate and re-activate the plugin to remove configuration frmo previous versions.
2. Choose the appropriate action and set the expiration days.

= Upgrade (post v1.2) =
1. Upgrade the plugin.
2. Choose the appropraite action and set the expiration days.

If everything looked good, you're done.

== Frequently Asked Questions ==
**Q-1)** How do I use this plugin?  
**A-1)** The Auto-Delete-Posts plugin will be run each time a new post or page is created or when an existing post or page is edited. 

**Q-2)** Why is there a delay after submitting a new post or editing an existing one?  
**A-2)** If you have a large number of posts that need to deleted/moved, this will slow things down. If you find you end up having to delete/move a lot of posts, then change the expiration days to a smaller values so that older posts are deleted more often and speeding things up.

**Q-3)** I upgraded to a version after v1.1 and the plugin doesn't delete, move or perform any action?  
**A-3)** Starting with v1.1, I fixed the way the options were being saved (they weren't before, a bug that didn't break anything). Due to this change, it is required to properly go and select the categories for each of the Delete, Move or Add actions of the plugin and then the actions should work as they've had before.

== Screenshots ==
N/A

== Changelog ==
= 1.2.2 =
* FEATURE: Bypass the trash can when deleting posts in WP >= 2.9.0
= 1.2.1 =
* BUG FIX: Enable the Delete or Move Plugin Action when using Per Post settings
= 1.2 =
* FEATURE: Allow for per post expiration in addition to site wide currently supported.
* FEATURE: Allow for deletion from multiple categories
= 1.1.1 =
* MESSAGE: Warn on Preview window when no Move, Delete or Add category is selected. Only affects upgrading users
= 1.1 =
* FEATURE: Allow adding new categories to existing posts  
* BUG FIX: Properly save the settings on each load.  
= 1.0.1 =
* BUG FIX: Fix the immediate delete/move hooks being out of scope  
= 1.0 =
* REWRITE: A full rewrite of the plugin to be more modular  
= 0.7.1 =  
* BUG FIX: Make both the Update Options button do the right thing.  
* BUG FIX: The Preview now only shows the chosen category, if any, or all categories  
 if none chosen.  
= 0.7 =
* BUG FIX: Use more of the WP API and avoid issues of missing database tables.  
* BUG FIX: For WP 2.1.2. Fix the SQL query to exclude cat_ID 1 and 2 from showing up in  
 the delete or move options.  
= 0.6 =
* BUG FIX: For WP 2.1.2. Fix the SQL query to exclude "Pages" from being deleted.  
= 0.5 =
* BUG FIX: Properly handle deleting posts from ALL categories.  
* BUG FIX: Fix the SQL query when deleting posting from a particular category to not  
 summarily delete ALL posts!  
= 0.4 =
* FEATURE: Added option to delete posts from a particular category.  
= 0.3 =
* BUG FIX: Incorrect Terminate in functions  
* FEATURE: Instant DELETE/MOVE ability  
= 0.2 =
* BUG FIX: Modified SQL query to not delete pages.  
* FEATURE: Added option to move posts to a particular category instead of deleting them.  
= 0.1 =
* INITIAL: Initial version.  

== Credits ==
* v0.3 work from Mani Monajjemi (www.manionline.org)  
* v0.6 work from Phil Guier (www.digitalwestex.org)
