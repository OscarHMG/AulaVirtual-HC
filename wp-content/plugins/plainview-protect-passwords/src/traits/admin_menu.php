<?php

namespace plainview\protect_passwords\traits;

/**
	@brief		Handle the admin menu.
	@since		2015-06-10 10:39:41
**/
trait admin_menu
{
	/**
		@brief		"Constructor" for the admin menu.
		@since		2015-06-10 11:03:37
	**/
	public function admin_menu_construct()
	{
		$this->add_action( 'admin_menu' );
		$this->add_action( 'network_admin_menu' );
	}

	public function admin_menu()
	{
		// If on a network, only allow superadmins.
		if ( $this->is_network )
			if ( ! is_super_admin() )
				return;

		$this->load_language();

		// If we've reached here we are either (1) superadmin on a network or (2) admin on a single install.
		// activate_plugins = only allow admins
		add_submenu_page(
			'options-general.php',
			'Plainview Protect Passwords',
			'Protect Passwords',
			'activate_plugins',
			'pv_protect_passwords',
			[ &$this, 'admin_menu_tabs' ]
		);
	}

	public function admin_menu_settings()
	{
		$form = $this->form2();
		$form->id( 'protect_passwords_settings' );
		$r = '';
		$roles = $this->roles_as_options();
		$roles = array_flip( $roles );

		$select_size = max( 10, count( $roles ) );

		$fs = $form->fieldset( 'fs_users' );
		$fs->legend->label_( 'Roles and users' );

		$fs->markup( 'm_users' )
			->p_( 'The inputs below allow you to select which roles and users are to be prevented from resetting their passwords. The protection applies to the whole Wordpress installation.' );

		$input_protected_roles = $fs->select( 'protected_roles' )
			->value( $this->get_site_option( 'protected_roles' ) )
			->description_( 'The selected roles are prevented from having their passwords reset. Note that each blog has its own roles, in addition to the standard roles, so some roles might not exist everywhere.' )
			// Role select input.
			->label_( 'Protected Roles' )
			->multiple()
			->options( $roles )
			->size( $select_size );

		$users = [];
		foreach( $this->cached_users() as $user_id => $user_login )
			$users[ $user_id ] = $user_login;
		$users = array_flip( $users );

		$input_users = $fs->select( 'protected_users' )
			->value( $this->get_site_option( 'protected_users' ) )
			->description_( 'The selected users are prevented from having their passwords reset.' )
			// User select input.
			->label_( 'Protected users' )
			->multiple()
			->options( $users )
			->size( $select_size );

		$save = $form->primary_button( 'save' )
			->value_( 'Save settings' );

		$input_excepted_users = $fs->select( 'excepted_users' )
			->value( $this->get_site_option( 'excepted_users' ) )
			->description_( 'The selected users are excepted from the protection. Best used in combination with the protected roles.' )
			// User exception select input.
			->label_( 'User exceptions' )
			->multiple()
			->options( $users )
			->size( $select_size );

		if ( $form->is_posting() )
		{
			$form->post();
			$form->use_post_values();

			$this->update_site_option( 'protected_roles',	$input_protected_roles->get_post_value() );
			$this->update_site_option( 'protected_users',	$input_users->get_post_value() );
			$this->update_site_option( 'excepted_users',	$input_excepted_users->get_post_value() );

			$this->message( 'Options saved!' );

			$_POST = [];
			echo $this->admin_menu_settings();
			return;
		}

		$r .= $form->open_tag();
		$r .= $form->display_form_table();
		$r .= $form->close_tag();

		echo $r;
	}

	public function admin_menu_tabs()
	{
		$this->load_language();

		$tabs = $this->tabs();
		$tabs->tab( 'settings' )		->callback_this( 'admin_menu_settings' )		->name_( 'Settings' );
		$tabs->tab( 'uninstall' )		->callback_this( 'admin_uninstall' )			->name_( 'Uninstall' );

		echo $tabs;
	}

	public function network_admin_menu()
	{
		$this->load_language();
		if ( is_super_admin() )
			add_submenu_page(
				'settings.php',
				'Plainview Protect Passwords',
				'Protect Passwords',
				'activate_plugins',
				'pv_protect_passwords',
				[ &$this, 'admin_menu_tabs' ]
			);
	}
}