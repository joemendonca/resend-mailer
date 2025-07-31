=== Resend Mailer ===
Contributors: joemendonca
Tags: resend, mailer, email, smtp
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send WordPress emails through the Resend API. Lightweight. Reliable. Easy.

== Description ==

This plugin overrides wp_mail() to use the Resend email API instead of PHP's default mail() function or SMTP servers. Enter your Resend API key and from email in the plugin settings.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings → Resend Mailer and enter your API key and verified sender email.

== Frequently Asked Questions ==

= Does it support plain text? =

Currently, only the HTML body is sent via the `message` field. You may modify the plugin to add plain text if needed.

= Is Resend free? =

Resend offers a free tier. Visit [resend.com](https://resend.com) for details.

== Screenshots ==

1. Settings page with API key and sender input

== Changelog ==

= 1.0.0 =
* First release

== Upgrade Notice ==

= 1.0.0 =
Initial stable version