<?php
if( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly
}

class Lamad_Custom_Template{
	
	public $templates = array();
	
	public function __construct() {

		if( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ){
			add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'register_custom_template' ) );
		}
		else{
			add_filter( 'theme_page_templates', array( $this, 'add_new_template' ) );
		}
		
		add_filter( 'wp_insert_post_data', array( $this, 'register_custom_template' ) );
		
		add_filter( 'template_include', array( $this, 'view_project_template' ) );
	}
	
	public function register_custom_template( $atts ){
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		$templates = wp_get_theme()->get_page_templates();
		if( empty( $templates ) ){
			$templates = array();
		}
		wp_cache_delete( $cache_key, 'themes' );
		$templates = array_merge( $templates, $this->templates );
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
		return $atts;
	}
	
	public function add_new_template( $templates ){
		$templates = array_merge( $templates, $this->templates );
		return $templates;
	}
	
	public function view_project_template( $template ){
		global $post;
		if( ! $post ){
			return $template;
		}
		if( ! isset( $this->templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) ){
			return $template;
		}
		
		$file = LAMAD_PLUGIN_DIR . '/templates/' .  get_post_meta( $post->ID, '_wp_page_template', true );
		if( file_exists( $file ) ){
			return $file;
		}
		return $template;
	}
}