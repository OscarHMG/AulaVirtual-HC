<?php

namespace plainview\protect_passwords\traits;

/**
	@brief		Password protection-related methods.
	@since		2015-06-10 11:02:58
**/
trait protection
{
	public function protection_construct()
	{
		$this->__cache = new \plainview\sdk_propas\collections\collection();

		$this->add_action( 'allow_password_reset', 'hook_true_on_freedom', 10, 2 );
		$this->add_action( 'show_password_fields', 'hook_true_on_freedom', 10, 2 );
	}

	/**
		@brief		Return the cached roles collection.
		@since		2015-06-10 11:29:35
	**/
	public function cached_roles()
	{
		if ( ! $this->__cache->has( 'roles' ) )
		{
			$collection = $this->__cache->collection( 'roles' );
			foreach( $this->roles_as_options() as $role_id => $role_name )
				$collection->set( $role_id, $role_name );
		}

		return $this->__cache->collection( 'roles' );
	}

	/**
		@brief		Return the user's cached roles.
		@since		2015-06-10 11:40:54
	**/
	public function cached_user_roles( $user_id = null )
	{
		if ( $user_id === null )
			$user_id = get_current_user_id();

		$ur = $this->__cache->collection( 'user_roles' );
		if ( ! $ur->has( $user_id ) )
			foreach( $this->get_user_capabilities( $user_id ) as $role_id => $ignore )
				$ur->collection( $user_id )->set( $role_id, true );

		return $ur->collection( $user_id );
	}

	/**
		@brief		Return the cached users collection.
		@since		2015-06-10 11:25:49
	**/
	public function cached_users()
	{
		if ( ! $this->__cache->has( 'users' ) )
		{
			$collection = $this->__cache->collection( 'users' );
			global $wpdb;
			$query = sprintf( "SELECT ID, user_login FROM `%s` ORDER BY user_login", $wpdb->users );
			$results = $wpdb->get_results( $query );
			foreach( $results as $user )
				$collection->set( $user->ID, $user->user_login );
		}

		return $this->__cache->collection( 'users' );
	}

	/**
		@brief		Return the user's capabilities on this blog as an array.
		@details	Stolen from Broadcast.
		@since		2015-03-17 18:56:30
	**/
	public static function get_user_capabilities( $user_id )
	{
		global $wpdb;
		$key = sprintf( '%scapabilities', $wpdb->prefix );
		$r = get_user_meta( $user_id, $key, true );

		if ( ! is_array( $r ) )
			$r = [];

		if ( is_super_admin() )
			$r[ 'super_admin' ] = true;

		return $r;
	}

	/**
		@brief		Return a boolean whether this user's password is protected.
		@since		2015-06-10 11:04:24
	**/
	public function is_password_protected( $user_id = null )
	{
		if ( $user_id === null )
			$user_id = get_current_user_id();

		// Is the user's role protected?
		$protected_roles = $this->get_site_option( 'protected_roles' );
		$user_roles = array_keys( $this->cached_user_roles( $user_id )->to_array() );
		$protected = count( array_intersect( $user_roles, $protected_roles) ) > 0;

		if ( ! $protected )
		{
			// Check if the user himself is protected.
			$protected_users = $this->get_site_option( 'protected_users' );
			if ( in_array( $user_id, $protected_users ) )
				$protected = true;
		}

		if ( $protected )
		{
			$excepted_users = $this->get_site_option( 'excepted_users' );
			if ( in_array( $user_id, $excepted_users ) )
				$protected = false;
		}

		return $protected;
	}

	/**
		@brief		Used for hooks that require TRUE when allowing a user to change their password.
		@since		2015-06-10 11:36:30
	**/
	public function hook_true_on_freedom( $value, $userdata )
	{
		if ( is_object( $userdata ) )
			$user_id = $userdata->ID;
		else
			$user_id = $userdata;

		if ( $value == true )
			// Show password only if NOT protected
			$value = ! $this->is_password_protected( $user_id );
		return $value;
	}

}
