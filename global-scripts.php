<?php
/**
 * Plugin Name: Global Scripts
 * Plugin URI:  https://github.com/JakeR-Dev/global-scripts
 * Description: Add tracking scripts to the <head> and <footer> of every page via a global settings panel.
 * Version:     1.0.0
 * Author:      Jake Ryan
 * License:     GPL-2.0-or-later
 * Text Domain: global-scripts
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/admin-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/frontend-output.php';