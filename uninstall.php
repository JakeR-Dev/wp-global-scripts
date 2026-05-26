<?php
/**
 * Uninstall functionality for the Global Scripts plugin.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Gather option names to delete
$option_names = [
  'gsm_head_scripts',
  'gsm_footer_scripts',
  'gsm_disable_for_admins',
  'gsm_disable_for_logged_in',
  'gsm_acknowledge_script_risk',
];

// Loop through multisite if present, deleting options for each site
if ( is_multisite() ) {
  $blog_ids = get_sites( [ 'fields' => 'ids' ] );

  foreach ( $blog_ids as $blog_id ) {
    switch_to_blog( $blog_id );

    foreach ( $option_names as $option_name ) {
      delete_option( $option_name );
    }

    restore_current_blog();
  }
// Otherwise, delete the options directly
} else {
  foreach ( $option_names as $option_name ) {
    delete_option( $option_name );
  }
}