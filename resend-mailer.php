<?php
/**
 * Plugin Name: Resend Mailer
 * Plugin URI: https://electricmonument.com/
 * Description: Routes wp_mail() through the Resend API. Add your API key and sender email under Settings → Resend Mailer.
 * Version: 1.0.0
 * Author: Joe Mendonca
 * Author URI: https://electricmonument.com/
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: resend-mailer
 * Domain Path: /languages
 */

add_filter('wp_mail', function($args) {
    $apiKey = get_option('resend_mailer_api_key');
    $from   = get_option('resend_mailer_from_email');

    if (!$apiKey || !$from) return $args; // fallback if not configured

    $to = is_array($args['to']) ? implode(',', $args['to']) : $args['to'];

    $response = wp_remote_post('https://api.resend.com/emails', [
        'headers' => [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode([
            'from'    => $from,
            'to'      => $to,
            'subject' => $args['subject'],
            'html'    => wpautop($args['message']),
        ]),
    ]);

    error_log('Resend API response: ' . print_r(wp_remote_retrieve_body($response), true));
    return $args;
});

// Register plugin settings
add_action('admin_init', function () {
    register_setting('resend_mailer_settings', 'resend_mailer_api_key');
    register_setting('resend_mailer_settings', 'resend_mailer_from_email');
});

// Add settings page to admin
add_action('admin_menu', function () {
    add_options_page(
        __('Resend Mailer Settings', 'resend-mailer'),
        __('Resend Mailer', 'resend-mailer'),
        'manage_options',
        'resend-mailer',
        'resend_mailer_settings_page'
    );
});

// Settings page output
function resend_mailer_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Resend Mailer Settings', 'resend-mailer'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('resend_mailer_settings');
            do_settings_sections('resend_mailer_settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('API Key', 'resend-mailer'); ?></th>
                    <td><input type="text" name="resend_mailer_api_key" value="<?php echo esc_attr(get_option('resend_mailer_api_key')); ?>" size="50" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('From Email Address', 'resend-mailer'); ?></th>
                    <td><input type="email" name="resend_mailer_from_email" value="<?php echo esc_attr(get_option('resend_mailer_from_email')); ?>" size="50" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}