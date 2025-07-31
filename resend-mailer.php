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

// Override wp_mail to use Resend API
add_filter('wp_mail', function($args) {
    $apiKey = get_option('resend_mailer_api_key');
    $from   = get_option('resend_mailer_from_email');

    if (!$apiKey || !$from) return $args; // Fallback if not configured

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

    return $args; // Do not stop wp_mail completely, just hook into it
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
        <h1>Resend Mailer Settings</h1>

        <div style="margin: 20px 0 40px; text-align: center;">
            <img src="<?php echo plugin_dir_url(__FILE__); ?>Banner.jpg" 
                 alt="Resend Mailer Banner" 
                 style="max-width: 100%; height: auto; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        </div>

        <form method="post" action="options.php">
            <?php
            settings_fields('resend_mailer_settings');
            do_settings_sections('resend_mailer_settings');
            ?>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="resend_mailer_api_key">API Key</label></th>
                    <td>
                        <input type="text" id="resend_mailer_api_key" name="resend_mailer_api_key" 
                               value="<?php echo esc_attr(get_option('resend_mailer_api_key')); ?>" 
                               class="regular-text" placeholder="e.g. re_xxxxxxxxxxxxxxx" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="resend_mailer_from_email">From Email</label></th>
                    <td>
                        <input type="email" id="resend_mailer_from_email" name="resend_mailer_from_email" 
                               value="<?php echo esc_attr(get_option('resend_mailer_from_email')); ?>" 
                               class="regular-text" placeholder="noreply@yourdomain.com" />
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}