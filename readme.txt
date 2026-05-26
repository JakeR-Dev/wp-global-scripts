=== Global Scripts Manager ===
Contributors: jakerfluid
Tags: scripts, tracking, header, footer, analytics
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 2.3.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add trusted global scripts with separate header/footer fields and output controls.

== Description ==

Global Scripts Manager lets administrators add site-wide script snippets without editing theme files.

Features:

* Settings page under Settings > Global Scripts
* Code editor for script fields
* Separate Header Scripts and Footer Scripts fields
* Input sanitization on save
* Settings access restricted to users with unfiltered_html capability
* Script field updates restricted to users with unfiltered_html capability
* Safety acknowledgement required before saving or outputting scripts
* Frontend output remains disabled until acknowledgement is enabled
* Frontend output in wp_head and wp_footer
* Output controls to disable scripts for admins or all logged-in users
* Option cleanup on uninstall

== Installation ==

1. Upload the plugin folder to the /wp-content/plugins/ directory, or install through the WordPress Plugins screen.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to Settings > Global Scripts.
4. Paste trusted scripts into Header Scripts and/or Footer Scripts.
5. Save changes.

== Screenshots ==

1. Plugin location under Settings in the WordPress admin menu.
2. Header Scripts and Footer Scripts fields in the plugin settings screen.

== Frequently Asked Questions ==

= Where do header scripts output? =

Header scripts are output in the page <head> using wp_head.

= Where do footer scripts output? =

Footer scripts are output before </body> using wp_footer.

= Who can manage these settings? =

Only users with the unfiltered_html capability.

= Does uninstall remove saved data? =

Yes. Uninstall deletes gsm_head_scripts and gsm_footer_scripts. On multisite, it removes them per site.

== Changelog ==

= 2.3.0 =

* Restricted settings page access and settings link visibility to users with unfiltered_html capability
* Enforced unfiltered_html capability when saving script fields
* Added required safety acknowledgement before script content can be saved
* Disabled frontend script output until safety acknowledgement is enabled
* Added contextual admin warning when scripts are present but output is gated by acknowledgement
* Added uninstall cleanup for the safety acknowledgement option

= 2.2.0 =

* Finalized naming updates to align slug, text domain, and admin-facing references
* Addressed Plugin Check compliance updates
* Updated banner asset to reflect the Global Scripts Manager name

= 2.1.1 =

* Updated plugin banner assets to reflect the Global Scripts Manager name
* Added final screenshots for plugin listing and documentation
* Renamed plugin branding to Global Scripts Manager for WordPress.org naming compliance

= 2.1.0 =

* Updated settings page styling in admin-page.php, including success notice accent color adjustments
* Replaced checklist docs link with clearer Header Scripts and Footer Scripts guidance items

= 2.0.0 =

* Updated plugin branding and naming
* Added CodeMirror editor support for both script fields
* Added output controls for admin and logged-in user sessions
* Refreshed settings page with a polished guidance card

= 1.2.2 =

* Added screenshot entries to plugin readme
* Expanded safe script/style attribute sanitization for modern snippets
* Updated contributor slug and release metadata
* Added code editor and output controls in plugin settings

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

= 2.3.0 =

Adds stronger security controls for script management, including unfiltered_html access enforcement, required safety acknowledgement for script saves, and output gating until acknowledgement is enabled.

= 2.2.0 =

Finalizes naming updates, Plugin Check compliance updates, and refreshed banner asset naming.

= 2.1.1 =

Updates plugin banners and screenshots, and renames branding to Global Scripts Manager for naming compliance.

= 2.1.0 =

Includes settings page styling updates and clearer Header/Footer checklist guidance.

= 2.0.0 =

Major feature release with refreshed admin UX, script editors, and output controls.

= 1.2.2 =

Adds screenshots and broader safe attribute support for script embeds.

= 1.2.1 =

Polishes plugin documentation and release metadata.

= 1.2.0 =

Adds plugin listing banner and icon assets.

= 1.1.0 =

Initial public release with header/footer script management and uninstall cleanup.
