===  WP Find Your Nearest ===
Contributors: SocialEvolution
Tags: location, distance, find, finder, postcode, zipcode, locator, nearest
Requires at least: 3.0.0
Tested up to: 4.4
Stable tag: 0.3.1

"Find Your Nearest" creates a custom post type which can be associated with a latitude and longitude calculated from your local postal code, which can then be sorted by distance from a postal code entered into a search field.

== Description ==

Have you ever wanted the functionality to allow your visitors to search for their nearest... shop, drop in centre, you name it?

This plug-in meets that need. You can create as many entries as you want, associate them with latitude and longitude using the Google Map API and allow your vistors to search for their nearest item by entering their own postal code.

This plug-in has been well tested with UK postcodes and also tested with US zipcodes and AUS postal codes among others. All feedback welcome.

The premium version is not currently available but is under redevelopment. Please do not make support requests for the premium version via the boards here, as we cannot support a premium product via the Wordpress support forum.

A combination of circumstances have prevented the original author from dedicating the time necessary to support and develop this plug-in. That has now changed! The maintenance of this plugin has been taken over by SocialEvolution in collaboration with the original author.

== Installation ==

1. Upload the folder wp-find-your-nearest to the `/wp-content/plugins/` directory
2. Activate the plug-in through the 'Plugins' menu in WordPress
3. Create a page that you will use to display your search results... Note: This page will not contain anything until a search is performed using the search form provided by the "FYN - Search Form" widget. It can contain other text that will be overwritten when landed on as a result of a Find Your Nearest search.
4. Visit the settings page under the Menu item "Find Your Nearest" and set the necessary options. The "Search Results Page" and "Country" settings are required for the plugin to work.
5. You can now create new "Find Your Nearest" items. Enter the postal code in the required field and click the "Update Latitude and Longitude" button to call the Google Maps API and place the latitude and longitude in the appropriate fields before saving the entry.
6. The plugin includes a widget to enable searching. "FYN - Search Form" simply adds a search form to your sidebar.

== Screenshots ==

1. The settings page
2. The add new item page

== Changelog ==

= 0.3.1 =
* Fix bug that caused conflict with some other plugins

= 0.3.0 =
* Full code refactor
* Full internationalisation
* Remove information regarding premium version of plugin

= 0.2.5 =
* urgent bug fix to identify location correctly

= 0.2.4 =
* Fix bug that resulted in occasional false lat/long values being returned

= 0.2.3 =
* Updated Google API to V3

= 0.2.2 =
* Add information regarding premium version of plugin

= 0.2.1 =
* Fix bug that caused search to break if widget placed in header
* Fix bug that prevented results displaying different places with same postal code

= 0.2.0 =
* Included new option to limit search results
* Added Google map to search results displayed in modal dialog box
* Regional Categories navigation widget removed pending bugfix

= 0.1 =
* Launch

== Upgrade Notice ==

= 0.2.2 =
New Premium Version


