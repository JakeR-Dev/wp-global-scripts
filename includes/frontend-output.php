<?php
/**
 * Frontend output functionality for the Global Scripts plugin.
 * Both scripts bypass the wp_kses filtering here since we already sanitize on input
 */

// Output the head scripts on the frontend
// Priority 1 to ensure it loads early in the head
function gs_output_head_scripts() {
  $header_scripts = get_option( 'gs_head_scripts', '' );
  if ( ! empty( $header_scripts ) ) {
    // phpcs:ignore WordPress.Security.EscapeOutput
    echo '<!-- Global Scripts Plugin: Head Scripts -->' . "\n";
    echo $header_scripts . "\n";
  }
}
add_action( 'wp_head', 'gs_output_head_scripts', 1 );

// Output the footer scripts on the frontend
// Priority 100 to ensure it runs after most other footer actions
function gs_output_footer_scripts() {
  $footer_scripts = get_option( 'gs_footer_scripts', '' );
  if ( ! empty( $footer_scripts ) ) {
    // phpcs:ignore WordPress.Security.EscapeOutput
    echo '<!-- Global Scripts Plugin: Footer Scripts -->' . "\n";
    echo $footer_scripts . "\n";
  }
}
add_action( 'wp_footer', 'gs_output_footer_scripts', 100 );