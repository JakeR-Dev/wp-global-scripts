<?php
/**
 * Admin page functionality for the Global Scripts Manager plugin.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Add settings page to admin menu (for users with unfiltered_html capability only)
function gsm_add_settings_page() {
  if ( ! current_user_can( 'unfiltered_html' ) ) {
    return;
  }

  add_options_page(
    'Global Scripts Manager',
    'Global Scripts',
    'unfiltered_html',
    'global-scripts-manager',
    'gsm_render_settings_page'
  );
}
add_action( 'admin_menu', 'gsm_add_settings_page' );

// Load assets on the plugin settings page.
function gsm_enqueue_admin_assets( $hook_suffix ) {
  // Make sure we're on the GSM settings page before loading assets
  if ( 'settings_page_global-scripts-manager' !== $hook_suffix ) {
    return;
  }

  // Load Codemirror assets for the header and footer script textareas
  $editor_settings = wp_enqueue_code_editor( [ 'type' => 'text/html' ] );

  if ( $editor_settings !== false ) {
    wp_enqueue_script( 'wp-theme-plugin-editor' );
    wp_enqueue_style( 'wp-codemirror' );

    $encoded_settings = wp_json_encode( $editor_settings );

    wp_add_inline_script(
      'wp-theme-plugin-editor',
      "jQuery(function($){var settings={$encoded_settings};wp.codeEditor.initialize('gsm_head_scripts', settings);wp.codeEditor.initialize('gsm_footer_scripts', settings);});"
    );
  }

  // Load plugin stylesheet
  wp_enqueue_style( 'gsm-admin-styles', plugin_dir_url( __DIR__ ) . 'assets/styles.css', [], '1.0' );
}
add_action( 'admin_enqueue_scripts', 'gsm_enqueue_admin_assets' );

// Add a settings link to the plugin actions on the plugins page (for users with unfiltered_html capability only)
function gsm_add_settings_link( $links ) {
  if ( ! current_user_can( 'unfiltered_html' ) ) {
    return $links;
  }

  $settings_link = '<a href="options-general.php?page=global-scripts-manager">' . __( 'Settings', 'global-scripts-manager' ) . '</a>';
  array_unshift( $links, $settings_link );
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) . '/global-scripts-manager.php' ), 'gsm_add_settings_link' );

// Register settings for head and footer scripts, and the output control checkboxes.
function gsm_register_settings() {
  register_setting( 'global_scripts_group', 'gsm_head_scripts', [
    'sanitize_callback' => 'gsm_sanitize_head_scripts',
    'default' => '',
  ]);

  register_setting( 'global_scripts_group', 'gsm_footer_scripts', [
    'sanitize_callback' => 'gsm_sanitize_footer_scripts',
    'default' => '',
  ]);

  register_setting( 'global_scripts_group', 'gsm_disable_for_admins', [
    'sanitize_callback' => 'gsm_sanitize_checkbox',
    'default' => 0,
  ]);

  register_setting( 'global_scripts_group', 'gsm_disable_for_logged_in', [
    'sanitize_callback' => 'gsm_sanitize_checkbox',
    'default' => 0,
  ]);

  register_setting( 'global_scripts_group', 'gsm_acknowledge_script_risk', [
    'sanitize_callback' => 'gsm_sanitize_checkbox',
    'default' => 0,
  ]);
}
add_action( 'admin_init', 'gsm_register_settings' );

// Normalize checkbox values to 1 or 0.
function gsm_sanitize_checkbox( $input ) {
  return ! empty( $input ) ? 1 : 0;
}

// Detect whether risk acknowledgement is enabled in the current settings submission.
function gsm_is_risk_acknowledged_in_request() {
  if ( ! isset( $_POST['gsm_acknowledge_script_risk'] ) ) {
    return false;
  }

  $submitted_value = sanitize_text_field( wp_unslash( $_POST['gsm_acknowledge_script_risk'] ) );
  return '1' === $submitted_value;
}

// Sanitize the head scripts
function gsm_sanitize_head_scripts( $input ) {
  return gsm_sanitize_scripts( $input, 'gsm_head_scripts' );
}

// Sanitize the footer scripts
function gsm_sanitize_footer_scripts( $input ) {
  return gsm_sanitize_scripts( $input, 'gsm_footer_scripts' );
}

// Utility function to sanitize script inputs
// Allow only <script>, <noscript>, and <style> tags and their common attributes
function gsm_sanitize_scripts( $input, $option_name ) {
  $sanitized_input = wp_kses( $input, [
    'script' => [
      'type' => true,
      'src' => true,
      'charset' => true,
      'async' => true,
      'defer' => true,
      'crossorigin' => true,
      'integrity' => true,
      'nonce' => true,
      'referrerpolicy' => true,
      'fetchpriority' => true,
      'data-*' => true,
      'id' => true,
    ],
    'noscript' => [],
    'style' => [
      'type' => true,
      'media' => true,
      'nonce' => true,
      'data-*' => true,
    ],
  ]);

  // Block users who don't have the unfiltered_html capability from saving scripts
  // Only show the notice once per request.
  if ( ! current_user_can( 'unfiltered_html' ) ) {
    static $capability_notice_added = false;

    if ( ! $capability_notice_added ) {
      add_settings_error(
        'global_scripts_group',
        'gsm_unfiltered_html_required',
        __( 'Your account is not allowed to save script content on this site. Please contact your site administrator if you believe this is an error.', 'global-scripts-manager' ),
        'error'
      );

      $capability_notice_added = true;
    }

    return (string) get_option( $option_name, '' );
  }

  // Block script changes unless the user has explicitly acknowledged the risk.
  if ( ! gsm_is_risk_acknowledged_in_request() && '' !== trim( $sanitized_input ) ) {
    static $validation_notice_added = false;

    if ( ! $validation_notice_added ) {
      add_settings_error(
        'global_scripts_group',
        'gsm_risk_acknowledgement_required',
        __( 'To save Header or Footer Scripts, you must enable the safety acknowledgement checkbox below.', 'global-scripts-manager' ),
        'error'
      );

      $validation_notice_added = true;
    }

    return (string) get_option( $option_name, '' );
  }

  return $sanitized_input;
}

// Render the actual options page
function gsm_render_settings_page() {
  // Block users without the unfiltered_html capability.
  if ( ! current_user_can( 'unfiltered_html' ) ) return;

  $risk_acknowledged = (int) get_option( 'gsm_acknowledge_script_risk', 0 ) === 1;
  $scripts_present = ! empty( get_option( 'gsm_head_scripts', '' ) ) || ! empty( get_option( 'gsm_footer_scripts', '' ) ); ?>

  <div class="wrap">
    <!-- Title -->
    <h1><?php esc_html_e( 'Global Scripts Manager', 'global-scripts-manager' ); ?></h1>

    <?php if ( $scripts_present && ! $risk_acknowledged ) : ?>
      <div class="notice notice-warning inline">
        <p><?php esc_html_e( 'Script output is currently disabled. Enable the safety acknowledgement below to activate saved scripts.', 'global-scripts-manager' ); ?></p>
      </div>
    <?php endif; ?>

    <!-- Intro card -->
    <div class="gs-intro-card">
      <h2><?php esc_html_e( 'Warning: Add scripts with caution', 'global-scripts-manager' ); ?></h2>
      <p><strong><?php esc_html_e( 'Using untrusted scripts can compromise your site\'s security and reliability.', 'global-scripts-manager' ); ?></strong></p>
      <p><?php esc_html_e( 'Use the editors below to add site-wide scripts for your header and footer.', 'global-scripts-manager' ); ?></p>
      <ul class="gs-checklist">
        <li><?php esc_html_e( 'Use snippets only from trusted providers.', 'global-scripts-manager' ); ?></li>
        <li><?php esc_html_e( 'Use the output toggles to avoid tracking admin sessions.', 'global-scripts-manager' ); ?></li>
        <li><?php esc_html_e( 'Use Header Scripts for tags that must load in the site <head>.', 'global-scripts-manager' ); ?></li>
        <li><?php esc_html_e( 'Use Footer Scripts for tags that should run before the closing </body> tag.', 'global-scripts-manager' ); ?></li>
      </ul>
    </div>

    <!-- Script fields -->
    <form method="post" action="options.php">
      <?php settings_fields( 'global_scripts_group' ); ?>
      <table class="form-table">
        <!-- Header scripts -->
        <tr>
          <th scope="row"><label for="gsm_head_scripts"><?php esc_html_e( 'Header Scripts', 'global-scripts-manager' ); ?></label></th>
          <td>
            <textarea id="gsm_head_scripts" name="gsm_head_scripts" rows="8" class="large-text code"><?php echo esc_textarea( get_option( 'gsm_head_scripts' ) ); ?></textarea>
            <p class="description"><?php esc_html_e( 'Scripts added here will output inside &lt;head&gt; on every page.', 'global-scripts-manager' ); ?></p>
          </td>
        </tr>
        <!-- Footer scripts -->
        <tr>
          <th scope="row"><label for="gsm_footer_scripts"><?php esc_html_e( 'Footer Scripts', 'global-scripts-manager' ); ?></label></th>
          <td>
            <textarea id="gsm_footer_scripts" name="gsm_footer_scripts" rows="8" class="large-text code"><?php echo esc_textarea( get_option( 'gsm_footer_scripts' ) ); ?></textarea>
            <p class="description"><?php esc_html_e( 'Scripts added here will output before &lt;/body&gt; on every page.', 'global-scripts-manager' ); ?></p>
          </td>
        </tr>
        <!-- Output controls -->
        <tr>
          <th scope="row"><?php esc_html_e( 'Output Controls', 'global-scripts-manager' ); ?></th>
          <td>
            <fieldset>
              <!-- Disable for admins -->
              <label for="gsm_disable_for_admins">
                <input
                  id="gsm_disable_for_admins"
                  name="gsm_disable_for_admins"
                  type="checkbox"
                  value="1"
                  <?php checked( 1, (int) get_option( 'gsm_disable_for_admins', 0 ) ); ?>
                />
                <?php esc_html_e( 'Do not output scripts for administrators.', 'global-scripts-manager' ); ?>
              </label>
              <br />
              <!-- Disable for all logged-in users -->
              <label for="gsm_disable_for_logged_in">
                <input
                  id="gsm_disable_for_logged_in"
                  name="gsm_disable_for_logged_in"
                  type="checkbox"
                  value="1"
                  <?php checked( 1, (int) get_option( 'gsm_disable_for_logged_in', 0 ) ); ?>
                />
                <?php esc_html_e( 'Do not output scripts for all logged-in users.', 'global-scripts-manager' ); ?>
              </label>
              <p class="description"><?php esc_html_e( 'If both are enabled, script output is disabled for any logged-in user.', 'global-scripts-manager' ); ?></p>
            </fieldset>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e( 'Safety Acknowledgement', 'global-scripts-manager' ); ?></th>
          <td>
            <fieldset>
              <input type="hidden" name="gsm_acknowledge_script_risk" value="0" />
              <label for="gsm_acknowledge_script_risk">
                <input
                  id="gsm_acknowledge_script_risk"
                  name="gsm_acknowledge_script_risk"
                  type="checkbox"
                  value="1"
                  <?php checked( 1, (int) get_option( 'gsm_acknowledge_script_risk', 0 ) ); ?>
                />
                <?php esc_html_e( 'I understand these scripts run site-wide and I have verified they are safe and trusted.', 'global-scripts-manager' ); ?>
              </label>
              <p class="description"><?php esc_html_e( 'Scripts are not saved or output until this acknowledgement is enabled.', 'global-scripts-manager' ); ?></p>
            </fieldset>
          </td>
        </tr>
      </table>
      <!-- Update options button -->
      <?php submit_button(); ?>
    </form>
  </div>
<?php } ?>