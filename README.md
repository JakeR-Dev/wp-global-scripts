# Global Scripts

![Global Scripts banner](assets/banner-1544x500.png)

A lightweight solution for adding and managing global tracking scripts. Scripts can be added to the head and footer via an admin settings panel.

## Description

Global Scripts lets site administrators add trusted tracking or utility snippets that load site-wide without editing theme files.

The plugin provides:

- A settings page at **Settings > Global Scripts**
- Separate fields for header and footer scripts
- Sanitization on save using `wp_kses`
- Frontend output hooks:
  - Header scripts via `wp_head` (priority `1`)
  - Footer scripts via `wp_footer` (priority `100`)
- Cleanup on uninstall (deletes stored options)

## Assets

The plugin includes banner and icon assets in the `assets/` directory for GitHub and WordPress.org presentation:

- `assets/banner-772x250.png`
- `assets/banner-1544x500.png`

- `assets/icon-20x20.png`
- `assets/icon-128x128.png`
- `assets/icon-256x256.png`

WordPress.org will automatically use the standard banner and icon files when they are present in `assets/` with these names.

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Administrator capability (`manage_options`) to edit settings

## Installation

1. Upload the `global-scripts` folder to `/wp-content/plugins/`, or install through the WordPress Plugins screen.
2. Activate the plugin.
3. Go to **Settings > Global Scripts**.
4. Add your scripts to the Header Scripts and/or Footer Scripts fields.
5. Click **Save Changes**.

## Usage

### Header Scripts

Content saved in `gs_head_scripts` is printed in the `<head>` on every frontend page.

### Footer Scripts

Content saved in `gs_footer_scripts` is printed before `</body>` on every frontend page.

## Security Notes

- Only users with `manage_options` can edit plugin settings.
- Inputs are sanitized on save.
- Use trusted snippets only.

## Uninstall Behavior

When the plugin is uninstalled, it deletes:

- `gs_head_scripts`
- `gs_footer_scripts`

In multisite, cleanup runs for each site.

## Changelog

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
