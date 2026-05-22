<?php
/**
 * Plugin Name: Global Scripts
 * Plugin URI:  https://github.com/JakeR-Dev/global-scripts
 * Description: A lightweight solution for adding and managing global tracking scripts. Scripts can be added to the head and footer via an admin settings panel.
 * Version:     1.2.0
 * Author:      Jake Ryan
 * License:     GPL-2.0-or-later
 * Text Domain: global-scripts
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/admin-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/frontend-output.php';