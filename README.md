![Resend Mailer banner](./Banner.jpg)

# Resend Mailer

A lightweight WordPress plugin that routes all outgoing `wp_mail()` email through the [Resend](https://resend.com) transactional email API. Works as a clean SMTP alternative. No extra features, just reliable delivery.

## Features

- Sends all site email through Resend
- Adds a simple settings page in `wp-admin`
- No bloat — one small PHP file
- Ideal for developers managing multiple WordPress installs

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin via `Plugins > Installed Plugins`
3. Enter your Resend API key and "From Email" under `Settings > Resend Mailer`

## Requirements

- WordPress 5.0+
- PHP 7.4+

## License

GPL 2.0 — Do whatever you like, just please give credit if you fork it.

---

Made with love by [Joe Mendonca](https://electricmonument.com)