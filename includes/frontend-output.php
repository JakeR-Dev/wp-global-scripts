<?php
/**
 * Frontend output functionality for the Global Scripts Manager plugin.
 * Both scripts bypass the wp_kses filtering here since we already sanitize on input
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Determine if script output should be skipped for this request.
function gsm_should_skip_output() {
  // Block for logged in users if the option is enabled
  if ( (int) get_option( 'gsm_disable_for_logged_in', 0 ) === 1 && is_user_logged_in() ) {
    return true;
  }

  // Block for administrators if the option is enabled
  if ( (int) get_option( 'gsm_disable_for_admins', 0 ) === 1 && current_user_can( 'manage_options' ) ) {
    return true;
  }

  return false;
}

// Output the head scripts on the frontend
// Priority 1 to ensure it loads early in the head
function gsm_output_head_scripts() {
  if ( gsm_should_skip_output() ) {
    return;
  }

  $header_scripts = get_option( 'gsm_head_scripts', '' );
  if ( ! empty( $header_scripts ) ) {
    // phpcs:ignore WordPress.Security.EscapeOutput
    echo '<!-- Global Scripts Manager Plugin: Head Scripts -->' . "\n";
    echo $header_scripts . "\n";
  }
}
add_action( 'wp_head', 'gsm_output_head_scripts', 1 );

// Output the footer scripts on the frontend
// Priority 100 to ensure it runs after most other footer actions
function gsm_output_footer_scripts() {
  if ( gsm_should_skip_output() ) {
    return;
  }

  $footer_scripts = get_option( 'gsm_footer_scripts', '' );
  if ( ! empty( $footer_scripts ) ) {
    // phpcs:ignore WordPress.Security.EscapeOutput
    echo '<!-- Global Scripts Manager Plugin: Footer Scripts -->' . "\n";
    echo $footer_scripts . "\n";
  }
}
add_action( 'wp_footer', 'gsm_output_footer_scripts', 100 );