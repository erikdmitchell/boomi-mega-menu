=== Boomi Mega Menu ===
Contributors: erikdmitchell
Donate link: 
Tags: mega menu, menu
Requires at least: 4.5
Tested up to: 5.2.2
Stable tag: 0.4.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows creation of a mega menu using the built in menu builder.

== Description ==

Allows creation of a mega menu using the built in menu builder.

It creates columns and row options in the menu builder that allow users to build out complex mega menus with ease.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `boomi-mega-menu` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In your themes wp_nav_menu, use the following params: `'menu_class' => 'bmm-menu', 'walker' => new BMM_Nav_Walker()`

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 0.4.0 =
* Fixed resize bug where left would get set to 0.

= 0.3.3 =
* Updated code to meet our standards.

= 0.3.2 =
* Added bmm_item_href filter for the menu item href attribute.

= 0.3.1 =
* Added support fo icons in the grid icon layout.

= 0.3.0 =
* Added sub menu positioning so it is not full width.

= 0.2.2 =
* Added top position to tabpanes.

= 0.2.1 =
* Added support for menu item descriptions.

= 0.2.0 =
* Fixed third level (tabpane) width and alignment

* Added filters for icons and linkmods

* Uses minfied js and css files

= 0.1.1 =
* Fixed CSS path issue.

= 0.1.0 =
* Initial release.
