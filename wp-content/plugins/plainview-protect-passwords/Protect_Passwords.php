<?php
/*
Author:			edward_plainview
Author Email:	edward@plainviewplugins.com
Author URI:		https://plainviewplugins.com
Description:	Selectively protect passwords from being reset or modified.
Plugin Name:	Plainview Protect Passwords
Plugin URI:		https://plainviewplugins.com/
Version:		1
*/

DEFINE( 'PLAINVIEW_PROTECT_PASSWORDS_VERSION', 1 );

require_once( __DIR__ . '/vendor/autoload.php' );

/**
	@brief		Return the instance of ThreeWP Broadcast.
	@since		2015-06-17 20:59:58
**/
function Plainview_Protect_Passwords()
{
	return \plainview\protect_passwords\Protect_Passwords::instance();
}

new \plainview\protect_passwords\Protect_Passwords();
