# Global Scripts Manager

![Global Scripts banner](assets/banner-1544x500.png)

A lightweight WordPress solution for adding and managing global tracking scripts. Scripts can be added to the head and footer via an admin settings panel with output controls.

## Description

Global Scripts Manager lets site administrators add trusted tracking or utility snippets that load site-wide without editing theme files.

The plugin provides:

- A settings page at **Settings > Global Scripts** visible only to users with `unfiltered_html` capability.
- Code editor support for both script fields
- Separate fields for header and footer scripts
- Sanitization on save using `wp_kses`
- Frontend output hooks:
  - Header scripts via `wp_head` (priority `1`)
  - Footer scripts via `wp_footer` (priority `100`)
- Output controls to disable scripts for admins or all logged-in users
- Explicit safety acknowledgement required before script content is saved or output
- Cleanup on uninstall (deletes stored options)

## Requirements

- WordPress 5.0+
- PHP 7.4+
- `unfiltered_html` capability to access script settings

## Installation

1. Upload the generated `dist/global-scripts-manager-<version>.zip` zipped folder to `/wp-content/plugins/`, or install through the WordPress Plugins screen.
2. Activate the plugin.
3. Go to **Settings > Global Scripts**.
4. Add your scripts to the Header Scripts and/or Footer Scripts fields.
5. Click **Save Changes**.

## Build Zip

From the plugin root, create a versioned release zip in `dist/` while excluding hidden and git files:

```bash
./scripts/build-dist.sh
```

This generates a file like `dist/global-scripts-manager-2.3.0.zip` based on the plugin version in `global-scripts-manager.php`.

## Usage

### Header Scripts

Content saved in `gsm_head_scripts` is printed in the `<head>` on every frontend page.

### Footer Scripts

Content saved in `gsm_footer_scripts` is printed before `</body>` on every frontend page.

## Security Notes

- Only users with `unfiltered_html` can access plugin settings.
- Inputs are sanitized on save.
- Users without `unfiltered_html` cannot save script content.
- Header/Footer script changes are blocked until the safety acknowledgement checkbox is enabled.
- Frontend output remains disabled unless acknowledgement is enabled.
- Use trusted snippets only.

## Uninstall Behavior

When the plugin is uninstalled, it deletes:

- `gsm_head_scripts`
- `gsm_footer_scripts`

In multisite, cleanup runs for each site.

## Changelog

## 2.3.0

- Restricted settings page access and settings link visibility to users with `unfiltered_html` capability.
- Enforced `unfiltered_html` capability when saving script fields.
- Added required safety acknowledgement before script content can be saved.
- Disabled frontend script output until safety acknowledgement is enabled.
- Added contextual admin warning when scripts are present but output is gated by acknowledgement.
- Added uninstall cleanup for the safety acknowledgement option.

## 2.2.0

- Finalized naming updates to align slug, text domain, and admin-facing references.
- Addressed Plugin Check compliance updates.
- Updated banner asset to reflect the Global Scripts Manager name.

## 2.1.1

- Updated plugin banner assets to reflect the Global Scripts Manager name.
- Added final screenshots for plugin listing and documentation.
- Renamed plugin branding to Global Scripts Manager for WordPress.org naming compliance.

## 2.1.0

- Updated settings page styling in admin-page.php, including success notice accent color adjustments.
- Replaced the checklist docs link with clearer Header Scripts and Footer Scripts guidance items.

## 2.0.0

- Updated plugin branding and naming.
- Added CodeMirror editor support for both script fields.
- Added output controls for admin and logged-in user sessions.
- Refreshed the settings page with a polished guidance card.

## 1.2.2

- Added screenshot documentation for WordPress.org submission readiness.
- Expanded safe script and style attribute sanitization support.
- Updated release metadata for version 1.2.2.
- Added code editor support and output controls on the settings page.

## 1.2.1

- Polished README and WordPress readme asset documentation.
- Updated release metadata and versioning.

## 1.2.0

- Added plugin banner assets for standard and high-density displays.
- Added plugin icon assets for a polished plugin listing.

## 1.1.0

- Added global header and footer script settings page.
- Added sanitization for script fields.
- Added frontend output for head and footer scripts.
- Added uninstall cleanup for stored plugin options.

## License

GPL-2.0-or-later
