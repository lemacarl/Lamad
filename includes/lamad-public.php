<?php
if( ! defined( 'ABSPATH' ) ) exit;

class Lamad_Public{
	
	public function __construct() {
		// Register custom post types
		add_action( 'init', array( $this, 'create_custom_post_types' ) );
		
		// Enqueue scripts and styles required on the front end
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		
		// Registration form short code
		add_shortcode( 'lamad_registration_form', array( $this, 'render_registration_form' ) );
		
		// Handle registration
		add_action( 'wp_ajax_lamad_registration', array( $this, 'register_student' ) );
		add_action( 'wp_ajax_nopriv_lamad_registration' , array( $this, 'register_student' ) );
		
		//Redirect student on login
		add_filter( 'login_redirect', array( $this, 'redirect_student_login' ), 10, 3 );
		
	}
	
	/**
	 * Redirect student to dashboard on login
	 * @param string $redirect
	 * @param string $request
	 * @param WP_User $user
	 * @return string
	 */
	public function redirect_student_login( $redirect, $request, $user ){
		if( isset( $user->roles ) && is_array( $user->roles ) ){
			if( in_array( 'student', $user->roles ) ){
				$redirect = home_url( 'student-dashboard' );
			}
		}
		return $redirect;
	}
	
	/**
	 * Register a new student
	 */
	public function register_student(){
		if( ! wp_verify_nonce( $_POST['lamad_nonce'], 'lamad-registration' ) ){
			die( __( 'Busted', 'lamad' ) );
		}
		$response = array();
		$course			= sanitize_text_field( $_POST['course'] );
		$first_name		= sanitize_text_field( $_POST['first_name'] );
		$last_name		= sanitize_text_field( $_POST['last_name'] );
		$gender			= sanitize_text_field( $_POST['gender'] );
		$occupation		= sanitize_text_field( $_POST['occupation'] );
		$marital_status	= sanitize_text_field( $_POST['marital_status'] );
		$church			= sanitize_text_field( $_POST['church'] );
		$phone			= sanitize_text_field( $_POST['phone'] );
		$email			= sanitize_text_field( $_POST['email'] );
		$address		= sanitize_text_field( $_POST['address'] );
		$password		= sanitize_text_field( $_POST['password'] );
		
		if( email_exists( $email ) ){
			$lost_password_url = '<a href="' . wp_lostpassword_url() . '">' . __( 'Forgot your password?', 'lamad' ) . '</a>';
			$response['message'] = sprintf( __( 'This email address is already registered. %s' ) , $lost_password_url );
			wp_send_json_error( $response );
			die();
		}
		$default_username = preg_replace( '/\s+/', '', strtolower( $last_name ) . '.' . strtolower( $first_name ) );
		$append = 1;
		$username = $default_username;
		while( username_exists( $username ) ){
			$username = $default_username . $append;
			$append ++;
		}
		$user_data = array(
			'user_login'	=> $username,
			'user_email'	=> $email,
			'user_pass'		=> $password,
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		);
		$user_id = wp_insert_user( $user_data );
		if( !is_wp_error( $user_id ) ){
			$login_url = '<a href="' . wp_login_url( home_url( 'student-dashboard' ) ) . '">' . __( 'Login', 'lamad' ) . '</a>';
			$response['message'] = sprintf( __( 'You have been registered. Your username is <b>%s</b>. %s' ) , $username, $login_url );
			$student = get_user_by( 'id', $user_id );
			if( is_object( $student ) ){
				$student->add_role( 'student' );
			}
			
			update_user_meta( $user_id, '_gender', $gender );
			update_user_meta( $user_id, '_occupation', $occupation );
			update_user_meta( $user_id, '_marital_status', $marital_status );
			update_user_meta( $user_id, '_church', $church );
			update_user_meta( $user_id, '_address', $address );
			update_user_meta( $user_id, '_phone', $phone );
			update_user_meta( $user_id, '_course_ids', array( $course ) );
			
			wp_send_json_success( $response );
			die();
		}
		else{
			$response['message'] = __( 'You have not been registered. Please try again.', 'lamad' );
			wp_send_json_error( $response );
			die();
		}
	}
	
	public function render_registration_form(){
		ob_start();
		$courses = get_posts( array( 'post_type' => 'course', 'posts_per_page' => -1, 'order' => 'ASC' ) );
		require( LAMAD_PLUGIN_DIR . '/templates/registration.php' );
		return ob_get_clean();
	}
	
	/**
	 * Enqueue front end scripts
	 */
	public function enqueue_scripts(){
		wp_enqueue_script( 'g-recaptcha', '//www.google.com/recaptcha/api.js' );
		wp_enqueue_script( 'jquery-validate', LAMAD_PLUGIN_URL . 'assets/js/jquery.validate.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'lamad-public', LAMAD_PLUGIN_URL . 'assets/js/lamad-public.js', array( 'jquery', 'jquery-validate' ) );
		wp_localize_script( 'lamad-public', 'lamadPublic' , array(
			'ajaxUrl'			=> admin_url( 'admin-ajax.php' ),
			'grecaptchaError'	=> sprintf( __( 'Please check the <em>%s</em> checkbox and wait for it to complete loading', 'lamad'), "I'm not a robot" )
		) );
	}
	
	/**
	 * Enqueue front end stylesheets
	 */
	public function enqueue_styles(){
		wp_enqueue_style( 'lamad-public', LAMAD_PLUGIN_URL . 'assets/css/lamad-public.css' );
	}
	
	/**
	 * Register custom post types
	 * @return \Lamad_Custom_Post_Types
	 */
	public function create_custom_post_types(){
		return new Lamad_Custom_Post_Types();
	}
	
}

new Lamad_Public();