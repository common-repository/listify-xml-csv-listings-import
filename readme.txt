=== Import Listings into the Listify Theme ===
Contributors: soflyy, wpallimport
Tags: wp job manager, import listings, import business listings, import directory, business directory, import business directory, listify, import listify, import listify listings, import listify business listings
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.1.2

Easily import listings from any XML or CSV file to the Listify theme with the Listify Add-On for WP All Import.

== Description ==

The Listify Add-On for [WP All Import](https://wordpress.org/plugins/wp-all-import) makes it easy to bulk import your business directory to the Listify theme in less than 10 minutes.

The left side shows all of the fields that you can import to and the right side displays a listing from your XML/CSV file. Then you can simply drag & drop the data from your XML or CSV into the Listify fields to import it.

The importer is so intuitive it is almost like manually adding a listing in Listify.

We have several other add-ons available, each specific to a different theme. This is a walkthrough of the Realia Add-On, which is very similar to the Listify Add-On.

https://www.youtube.com/watch?v=_wvz0FfbutA

= Why you should use the Listify Add-On for WP All Import =

* Instead of using the Custom Fields section of WP All Import, you are shown the fields like Listing Expiry Date, Company Name, etc. in plain English.

* Automatically find the listing location just like manually adding a listing.

* Full support for Listify's search dropdowns, categories, settings, and fields.

* Supports files in any format and structure. There are no requirements that the data in your file be organized in a certain way. CSV imports into Listify is easy, no matter the structure of your file.

* Supports files of practically unlimited size by automatically splitting them into chunks. WP All Import is limited solely by your server settings.

= WP All Import Professional Edition =

The Listify Add-On for WP All Import is fully compatible with [the free version of WP All Import](https://wordpress.org/plugins/wp-all-import). 

However, [the professional edition of WP All Import](https://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=listify) includes premium support and adds the following features:

* Import files from a URL: Download and import files from external websites, even if they are password protected with HTTP authentication. 

* Cron Job/Recurring Imports: WP All Import Pro can check periodically check a file for updates, and add, edit, delete, and update your listings.

* Custom PHP Functions: Pass your data through custom functions by using [my_function({data[1]})] in your import template. WP All Import will pass the value of {data[1]} through my_function and use whatever it returns.

* Access to premium technical support.

[Upgrade to the professional edition of WP All Import now.](https://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=listify)

= Developers: Create Your Own Add-On =
This Add-On was created using the [Rapid Add-On API](https://www.wpallimport.com/documentation/addon-dev/overview/) for WP All Import. We've made it really easy to write your own Add-On. 

Don't have time? We'll write one for you.

[Read more about getting an Add-On made for your plugin or theme.](https://www.wpallimport.com/add-ons/#developers)

= Related Plugins =
[Import Listings into Inventor WP](https://wordpress.org/plugins/import-xml-csv-listings-to-inventor-wp/)  
[Import Listings into the Jobify Theme](https://wordpress.org/plugins/jobify-xml-csv-listings-import/)  
[Import Listings into WP Job Manager](https://wordpress.org/plugins/wp-job-manager-xml-csv-listings-import/)
[Import Listings into the Listable Theme](https://wordpress.org/plugins/import-xml-csv-listings-to-listable-theme)

== Installation ==

First, install [WP All Import](https://wordpress.org/plugins/wp-all-import).

Then install the Listify Add-On.

To install the Listify Add-On, either:

* Upload the plugin from the Plugins page in WordPress

* Unzip import-property-listings-into-listify.zip and upload the contents to /wp-content/plugins/, and then activate the plugin from the Plugins page in WordPress

The Listify Add-On will appear in the Step 3 of WP All Import.

== Frequently Asked Questions ==

= WP All Import works with any theme, so what’s the point of using the Listify Add-On? =

Aside from making your import easier and simpler, the Listify Add-On will fully support your theme’s various image galleries and file attachments as well as allow you to easily import location data.

== Changelog ==

= 1.1.2 =
* improvement: add WP-CLI support
* improvement: add failed geocoding explanations in import history log
* improvement: add history log entries for all updates
* improvement: update rapid add-on API
* improvement: use Google Maps API key from Listify settings if key unspecified in import template
* improvement: use "City, State" format when importing location from latitude/longitude coordinates
* API: add filter wpai_listify_addon_enable_logs
* bug fix: PHP notices and warnings during imports
* bug fix: "No API Key" option for geocoding shouldn't be available (Google requires an API key)
* bug fix: jobs with listing expiry date older than today have an "active" status
* bug fix: gallery images removed when updating all custom fields
* bug fix: gallery images duplicated when re-running the import

= 1.1.1 =
* bug fix: warnings & notices in debug log
* bug fix: can't change "Search through the Media Library for existing images" option
* improvement: update rapid add-on api

= 1.1.0 =
* Fix for geolocation fields.

= 1.0.9 =
* Add support for multiple open/close hours and timezone import.

= 1.0.8 =
* Added Geolocation section.

= 1.0.7 =
* Added Company Logo/Avatar Field
* Added Social Profile fields in the case that they're associated with listings
* Added option to control whether the featured image is added to the listing gallery

= 1.0.6 =
* Updated rapid addon api and fixed field update permission check for new posts

= 1.0.5 =
* Update function names to avoid conflicts

= 1.0.4 =
* Fix bug for galleries with more than two images

= 1.0.3 =
* Improve video imports

= 1.0.2 =
* Fix admin notice bug

= 1.0.1 =
* Child theme compatibility

= 1.0.0 =
* Initial release on WP.org.

== Support ==

We do not handle support in the WordPress.org community forums.

We do try to handle support for our free version users at the following e-mail address:

E-mail: support@wpallimport.com

Support for free version customers is not guaranteed and based on ability. For premium support, purchase [WP All Import Pro](https://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=listify).
