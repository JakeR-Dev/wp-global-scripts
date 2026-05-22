<?php
/**
 * Admin page functionality for the Global Scripts plugin.
 */

// Add settings page to admin menu
function gs_add_settings_page() {
  add_options_page(
    'Global Scripts',
    'Global Scripts',
    'manage_options',
    'global-scripts',
    'gs_render_settings_page'
  );
}
add_action( 'admin_menu', 'gs_add_settings_page' );

// Add a settings link to the plugin actions on the plugins page
function gs_add_settings_link( $links ) {
  $settings_link = '<a href="options-general.php?page=global-scripts">' . __( 'Settings', 'global-scripts' ) . '</a>';
  array_unshift( $links, $settings_link );
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) . '/global-scripts.php' ), 'gs_add_settings_link' );

// Register settings for head and footer scripts
function gs_register_settings() {
  register_setting( 'global_scripts_group', 'gs_head_scripts', [
    'sanitize_callback' => 'gs_sanitize_scripts',
    'default' => '',
  ]);

  register_setting( 'global_scripts_group', 'gs_footer_scripts', [
    'sanitize_callback' => 'gs_sanitize_scripts',
    'default' => '',
  ]);
}
add_action( 'admin_init', 'gs_register_settings' );

// Utility function to sanitize script inputs
// Allow only <script>, <noscript>, and <style> tags and their common attributes
function gs_sanitize_scripts( $input ) {
  return wp_kses( $input, [
    'script' => [
      'type' => true,
      'src' => true,
      'async' => true,
      'defer' => true,
      'id' => true,
    ],
    'noscript' => [],
    'style' => [
      'type' => true,
    ],
  ]);
}

// Render the actual options page
function gs_render_settings_page() {
  if ( ! current_user_can( 'manage_options' ) ) return; ?>

  <div class="wrap">
    <h1><?php esc_html_e( 'Global Scripts', 'global-scripts' ); ?></h1>
    <p><?php esc_html_e( 'Add global tracking scripts to the head and footer of your site. Be sure to use only trusted scripts, and include all pertinent <script>, <noscript>, and <style> tags.', 'global-scripts' ); ?></p>
    <form method="post" action="options.php">
      <?php settings_fields( 'global_scripts_group' ); ?>
      <table class="form-table">
        <tr>
          <th scope="row"><label for="gs_head_scripts"><?php esc_html_e( 'Header Scripts', 'global-scripts' ); ?></label></th>
          <td>
            <textarea id="gs_head_scripts" name="gs_head_scripts" rows="8" class="large-text code"><?php echo esc_textarea( get_option( 'gs_head_scripts' ) ); ?></textarea>
            <p class="description"><?php esc_html_e( 'Scripts added here will output inside &lt;head&gt; on every page.', 'global-scripts' ); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="gs_footer_scripts"><?php esc_html_e( 'Footer Scripts', 'global-scripts' ); ?></label></th>
          <td>
            <textarea id="gs_footer_scripts" name="gs_footer_scripts" rows="8" class="large-text code"><?php echo esc_textarea( get_option( 'gs_footer_scripts' ) ); ?></textarea>
            <p class="description"><?php esc_html_e( 'Scripts added here will output before &lt;/body&gt; on every page.', 'global-scripts' ); ?></p>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
<?php } ?>