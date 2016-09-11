=== Plainview Protect Passwords ===
Contributors: edward_plainview
License: GPLv3
Requires at least: 4.2.2
Stable tag: trunk
Tags: security, passwords, protect, change, reset
Tested up to: 4.5.3

Selectively protect passwords from being reset or modified.

== Description ==

Prevent password resets or changes to specific users or user roles. User-specific exceptions to the sweeping user roles can be set. The user's password is then protected from modification:

* In the user's profile editor
* Using the password reset link in the login window

The settings are available to either the super admin on the network, or normal admins on a single installation. If you wish to hide the settings completely, put the following in your wp-config.php:

`define( 'PLAINVIEW_PROTECT_PASSWORDS_HIDE', true );`

Requires PHP 5.4.

== Installation ==

1. Check that your web host has PHP v5.4.
1. Activate the plugin locally or for the network. The latter option is necessary for the plugin to work on network installations.
1. Visit either Admin > Options > Protect Password, or Network admin > Settings > Protect Password, depending on your installation.

== Screenshots ==

1. Settings page
1. The admin user's password has been protected from reset

== Frequently Asked Questions ==

= When does the plugin protect my installation? =

* If crackers have access to your e-mail but not your Wordpress login.
* You do not want to be bothered by people abusing the "reset password" function for your account.

= I've forgotten my admin password. How do I get a new one? =

The easiest thing to do is rename the plugin's directory. This will disable the plugin and allow you to reset your admin password.

You can edit the database, but that's far more difficult than a simple directory rename.

== Changelog ==

= 1 20150617 =

* Inital release
