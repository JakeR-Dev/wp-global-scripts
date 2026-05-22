<?php
/**
 * Frontend output functionality for the WP Global Scripts plugin.
 * Both scripts bypass the wp_kses filtering here since we already sanitize on input
 */

// Determine if script output should be skipped for this request.
function gs_should_skip_output() {
  // Block for logged in users if the option is enabled
  if ( (int) get_option( 'gs_disable_for_logged_in', 0 ) === 1 && is_user_logged_in() ) {
    return true;
  }

  // Block for administrators if the option is enabled
  if ( (int) get_option( 'gs_disable_for_admins', 0 ) === 1 && current_user_can( 'manage_options' ) ) {
    return true;
  }

  return false;
}

// Output the head scripts on the frontend
// Priority 1 to ensure it loads early in the head
function gs_output_head_scripts() {
  if ( gs_should_skip_output() ) {
    return;
  }

  $header_scripts = get_option( 'gs_head_scripts', '' );
  if ( ! empty( $header_scripts ) ) {
    // phpcs:ignore WordPress.Security.EscapeOutput
    echo '<!-- WP Global Scripts Plugin: Head Scripts -->' . "\n";
    echo $header_scripts . "\n";
  }
}
add_action( 'wp_head', 'gs_output_head_scripts', 1 );

// Output the footer scripts on the frontend
// Priority 100 to ensure it runs after most other footer actions
function gs_output_footer_scripts() {
  if ( gs_should_skip_output() ) {
    return;
  }

  $footer_scripts = get_option( 'gs_footer_scripts', '' );
  if ( ! empty( $footer_scripts ) ) {
    // phpcs:ignore WordPress.Security.EscapeOutput
    echo '<!-- WP Global Scripts Plugin: Footer Scripts -->' . "\n";
    echo $footer_scripts . "\n";
  }
}
add_action( 'wp_footer', 'gs_output_footer_scripts', 100 );