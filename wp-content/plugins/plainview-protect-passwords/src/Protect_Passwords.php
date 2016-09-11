<?php

namespace plainview\protect_passwords;

/**
	@brief		Prevent passwords from being reset.
	@since		2015-06-10 10:19:18
**/
class Protect_Passwords
	extends \plainview\sdk_propas\wordpress\base
{
	use traits\admin_menu;
	use traits\protection;

	public function _construct()
	{
		if ( ! defined( 'PLAINVIEW_PROTECT_PASSWORDS_HIDE' ) )
			$this->admin_menu_construct();
		$this->protection_construct();
	}

	public function site_options()
	{
		return array_merge( [
			'protected_roles' => [ 'super_admin', 'admin' ],		// Roles that are protected
			'protected_users' => [ 1 ],								// Users that are protected
			'excepted_users' => [],									// No user exceptions
		], parent::site_options() );
	}
}
