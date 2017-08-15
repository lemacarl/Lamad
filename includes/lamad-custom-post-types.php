<?php
if( ! defined( 'ABSPATH' ) ) exit;

class Lamad_Custom_Post_Types{
	
	public function __construct() {
		$this->create_lessons();
		$this->create_courses();
	}
	
	public function create_lessons(){
		$labels = array(
            'name'                  => _x( 'Lessons', 'post type general name', 'lamad'),
            'singular_name'         => _x( 'Lesson', 'post type singular name', 'lamad'),
            'add_new'               => __( 'Add New', 'lamad'),
            'add_new_item'          => __( 'Add New Lesson', 'lamad'),
            'edit_item'             => __( 'Edit Lesson', 'lamad'),
            'new_item'              => __( 'New Lesson', 'lamad'),
            'all_items'             => __( 'All Lessons', 'lamad'),
            'view_item'             => __( 'View Lessons', 'lamad'),
            'search_items'          => __( 'Search Lessons', 'lamad'),
            'not_found'             => __( 'No Lessons found', 'lamad'),
            'not_found_in_trash'    => __( 'No Lessons found in Trash', 'lamad'),
            'parent_item_colon'     => '',
            'menu_name'             => __( 'Lessons', 'lamad')
		);
		$args = array(
		    'labels'              => $labels,
		    'public'              => true,
		    'publicly_queryable'  => true,
		    'show_ui'             => true,
		    'show_in_menu'        => true,
		    'show_in_admin_bar'   => true,
		    'query_var'           => true,
		    'rewrite'             => array(
                'slug'		=> 'lesson' ,
                'with_front'=> true,
                'feeds'		=> true,
                'pages'		=> true
            ),
		    'map_meta_cap'        => true,
			'menu_icon'			  => 'dashicons-book',
		    'capability_type'     => 'lesson',
		    'hierarchical'        => false,
		    'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail' )
		);
		
		if( ! post_type_exists( 'lesson' ) ){
			register_post_type( 'lesson', $args );
		}
	}
	
	public function create_courses(){
		$labels = array(
            'name'                  => _x( 'Courses', 'post type general name', 'lamad'),
            'singular_name'         => _x( 'Course', 'post type singular name', 'lamad'),
            'add_new'               => __( 'Add New', 'lamad'),
            'add_new_item'          => __( 'Add New Course', 'lamad'),
            'edit_item'             => __( 'Edit Course', 'lamad'),
            'new_item'              => __( 'New Course', 'lamad'),
            'all_items'             => __( 'All Courses', 'lamad'),
            'view_item'             => __( 'View Courses', 'lamad'),
            'search_items'          => __( 'Search Courses', 'lamad'),
            'not_found'             => __( 'No Courses found', 'lamad'),
            'not_found_in_trash'    => __( 'No Courses found in Trash', 'lamad'),
            'parent_item_colon'     => '',
            'menu_name'             => __( 'Courses', 'lamad')
		);
		$args = array(
		    'labels'              => $labels,
		    'public'              => true,
		    'publicly_queryable'  => true,
		    'show_ui'             => true,
		    'show_in_menu'        => true,
		    'show_in_admin_bar'   => true,
		    'query_var'           => true,
		    'rewrite'             => array(
                'slug'		=> 'course' ,
                'with_front'=> true,
                'feeds'		=> true,
                'pages'		=> true
            ),
		    'map_meta_cap'        => true,
			'menu_icon'			  => 'dashicons-welcome-learn-more',
		    'capability_type'     => 'course',
		    'hierarchical'        => false,
		    'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail' )
		);
		
		if( ! post_type_exists( 'course' ) ){
			register_post_type( 'course', $args );
		}
	}
}