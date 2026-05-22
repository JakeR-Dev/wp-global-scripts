=== Global Scripts ===
Contributors: jake-ryan
Tags: scripts, tracking, header, footer, analytics
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.1
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight solution for adding and managing global tracking scripts. Scripts can be added to the head and footer via an admin settings panel.

The plugin includes banner and icon assets in the /assets directory for a more polished plugin listing.

WordPress.org will automatically use the standard banner filenames when they are present:

* assets/banner-772x250.png
* assets/banner-1544x500.png

WordPress.org will automatically use the standard icon filenames when they are present:

* assets/icon-20x20.png
* assets/icon-128x128.png
* assets/icon-256x256.png

== Description ==

Global Scripts lets administrators add site-wide script snippets without editing theme files.

Features:

* Settings page under Settings > Global Scripts
* Separate Header Scripts and Footer Scripts fields
* Input sanitization on save
* Frontend output in wp_head and wp_footer
* Option cleanup on uninstall

== Installation ==

1. Upload the plugin folder to the /wp-content/plugins/ directory, or install through the WordPress Plugins screen.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to Settings > Global Scripts.
4. Paste trusted scripts into Header Scripts and/or Footer Scripts.
5. Save changes.

== Frequently Asked Questions ==

= Where do header scripts output? =

Header scripts are output in the page <head> using wp_head.

= Where do footer scripts output? =

Footer scripts are output before </body> using wp_footer.

= Who can manage these settings? =

Only users with the manage_options capability.

= Does uninstall remove saved data? =

Yes. Uninstall deletes gs_head_scripts and gs_footer_scripts. On multisite, it removes them per site.

== Changelog ==

= 1.2.1 =

* Polished documentation and asset notes
* Updated release metadata and versioning

= 1.2.0 =

* Added plugin banner assets for standard and high-density displays
* Added plugin icon assets for a polished plugin listing

= 1.1.0 =

* Added Global Scripts settings page
* Added head and footer script fields
* Added sanitization for script input
* Added frontend output for head and footer locations
* Added uninstall cleanup for saved options

== Upgrade Notice ==

= 1.2.1 =

Polishes plugin documentation and release metadata.

= 1.2.0 =

Adds plugin listing banner and icon assets.

= 1.1.0 =

Initial public release with header/footer script management and uninstall cleanup.
