<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Lamad_Install{
	
	/**
	 * Instance container
	 * @var Lamad_Install
	 */
	private static $_instance = null;
	
	/**
	 * Load instance of class
	 * @return Lamad_Install
	 */
	public static function instance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public static function activate(){
		$instance = Lamad_Install::instance();
		$instance->create_custom_roles();
		$instance->add_caps();
	}
	
	/**
	 * Create custom user roles
	 */
	private function create_custom_roles(){
		add_role( 'student', __( 'Student', 'lamad' ), array(
			'read'		=> true
		) );
		add_role( 'instructor', __( 'Instructor', 'lamad' ), array(
			'read'			=> true,
			'edit_posts'	=> true,
			'upload_files'	=> true,
			'list_users'	=> true
		) );
	}
	
	/**
	 * Add caps to `instructor` and `administrator`
	 * @global WP_Roles $wp_roles
	 */
	private function add_caps(){
		global $wp_roles;
		if( ! isset( $wp_roles ) ){
			if( class_exists( 'WP_Roles' ) ){
				$wp_roles = new WP_Roles();
			}
		}
		if( is_object( $wp_roles ) ){
			$caps = $this->get_custom_caps();
			foreach( $caps as $cap_type ){
				foreach( $cap_type as $cap ){
					$wp_roles->add_cap( 'administrator', $cap );
					$wp_roles->add_cap( 'instructor', $cap );
				}
			}
		}
		
	}
	
	/**
	 * Return custom post type caps
	 * @return array
	 */
	private function get_custom_caps(){
		$caps = array();
		$cap_types = array( 'course', 'lesson' );
		foreach( $cap_types as $cap_type ){
			$caps[$cap_type] = array(
					"edit_{$cap_type}",
					"read_{$cap_type}",
					"delete_{$cap_type}",
					"edit_{$cap_type}s",
					"edit_others_{$cap_type}s",
					"publish_{$cap_type}s",
					"read_private_{$cap_type}s",
					"delete_{$cap_type}s",
					"delete_private_{$cap_type}s",
					"delete_published_{$cap_type}s",
					"delete_others_{$cap_type}s",
					"edit_private_{$cap_type}s",
					"edit_published_{$cap_type}s",
			);
		}
		return $caps;
	}
	
	public static function deactivate(){
		flush_rewrite_rules();
	}
}

Lamad_Install::instance();