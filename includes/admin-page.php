<?php
/**
 * Admin page functionality for the Global Scripts Manager plugin.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Add settings page to admin menu
function gsm_add_settings_page() {
  add_options_page(
    'Global Scripts Manager',
    'Global Scripts',
    'manage_options',
    'global-scripts-manager',
    'gsm_render_settings_page'
  );
}
add_action( 'admin_menu', 'gsm_add_settings_page' );

// Load CodeMirror editor on the plugin settings page.
function gsm_enqueue_admin_assets( $hook_suffix ) {
  if ( 'settings_page_global-scripts-manager' !== $hook_suffix ) {
    return;
  }

  $editor_settings = wp_enqueue_code_editor( [ 'type' => 'text/html' ] );

  if ( false !== $editor_settings ) {
    wp_enqueue_script( 'wp-theme-plugin-editor' );
    wp_enqueue_style( 'wp-codemirror' );

    $encoded_settings = wp_json_encode( $editor_settings );

    wp_add_inline_script(
      'wp-theme-plugin-editor',
      "jQuery(function($){var settings={$encoded_settings};wp.codeEditor.initialize('gsm_head_scripts', settings);wp.codeEditor.initialize('gsm_footer_scripts', settings);});"
    );
  }
}
add_action( 'admin_enqueue_scripts', 'gsm_enqueue_admin_assets' );

// Add a settings link to the plugin actions on the plugins page
function gsm_add_settings_link( $links ) {
  $settings_link = '<a href="options-general.php?page=global-scripts-manager">' . __( 'Settings', 'global-scripts-manager' ) . '</a>';
  array_unshift( $links, $settings_link );
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) . '/global-scripts-manager.php' ), 'gsm_add_settings_link' );

// Register settings for head and footer scripts, and the output control checkboxes.
function gsm_register_settings() {
  register_setting( 'global_scripts_group', 'gsm_head_scripts', [
    'sanitize_callback' => 'gsm_sanitize_scripts',
    'default' => '',
  ]);

  register_setting( 'global_scripts_group', 'gsm_footer_scripts', [
    'sanitize_callback' => 'gsm_sanitize_scripts',
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
}
add_action( 'admin_init', 'gsm_register_settings' );

// Normalize checkbox values to 1 or 0.
function gsm_sanitize_checkbox( $input ) {
  return ! empty( $input ) ? 1 : 0;
}

// Utility function to sanitize script inputs
// Allow only <script>, <noscript>, and <style> tags and their common attributes
function gsm_sanitize_scripts( $input ) {
  return wp_kses( $input, [
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
}

// Render the actual options page
function gsm_render_settings_page() {
  // Block users without the manage_options capability
  if ( ! current_user_can( 'manage_options' ) ) return; ?>

  <div class="wrap">
    <!-- Title -->
    <h1><?php esc_html_e( 'Global Scripts Manager', 'global-scripts-manager' ); ?></h1>

    <!-- Styles -->
    <style>
      .gs-intro-card {
        margin: 14px 0 18px;
        padding: 16px 18px;
        border: 1px solid #dcdcde;
        border-left: 4px solid #1E1E2E;
        border-radius: 4px;
        background: #fff;
      }
      .gs-intro-card h2 {
        margin: 0 0 8px;
      }
      .gs-intro-card p {
        margin: 0 0 8px;
      }
      .gs-checklist {
        margin: 0;
        padding-left: 14px;
        list-style: disc;
      }
      .notice-success {
        border-left-color: #7C6AF7;
      }
    </style>

    <!-- Intro card -->
    <div class="gs-intro-card">
      <h2><?php esc_html_e( 'Add trusted scripts with confidence', 'global-scripts-manager' ); ?></h2>
      <p><?php esc_html_e( 'Use the editors below to add site-wide scripts for your header and footer.', 'global-scripts-manager' ); ?></p>
      <ul class="gs-checklist">
        <li><?php esc_html_e( 'Paste snippets only from trusted providers.', 'global-scripts-manager' ); ?></li>
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
      </table>
      <!-- Update options button -->
      <?php submit_button(); ?>
    </form>
  </div>
<?php } ?>