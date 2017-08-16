<?php
if( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly
}

class Lamad_Route_Controller{
	
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}
	
	/**
	 * Register custom endpoints
	 */
	public function register_routes(){
		/**
		 * Modify an existing course
		 */
		register_rest_route( 'lamad/v1', 'course/(?P<id>\d+)/user=(?P<user>\d+)', array(
			array(
				'methods'				=> 'PUT',
				'callback'				=> array( $this, 'complete_course' ),
			),
			array(
				'methods'				=> 'GET',
				'callback'				=> array( $this, 'get_course_status' )
			)
		) );
		
		/**
		 * Get all courses enrolled by user 
		 */
		register_rest_route( 'lamad/v1', 'courses/user=(?P<user>\d+)', array(
			'methods'				=> 'GET',
			'callback'				=> array( $this, 'get_courses' ),
		) );
		
		/**
		 * Get lesson content
		 */
		register_rest_route( 'lamad/v1', 'lesson/(?P<id>\d+)', array(
			'methods'				=> 'GET',
			'callback'				=> array( $this, 'get_lesson_content' ),
		) );
		
		/**
		 * Modify an exisiting lesson
		 */
		register_rest_route( 'lamad/v1', 'lesson/(?P<id>\d+)/user=(?P<user>\d+)', array(
			array(
				'methods'				=> 'PUT',
				'callback'				=> array( $this, 'complete_lesson' ),
			),
			array(
				'methods'				=> 'GET',
				'callback'				=> array( $this, 'get_lesson_status' ),
			)
		) );
		
		/**
		 * Get all lessons associated with a given course
		 */
		register_rest_route( 'lamad/v1', '/lessons/course=(?P<course>\d+)', array(
			'methods'		=> 'GET',
			'callback'		=> array( $this, 'get_course_lessons' )
		) );
	}
	
	public function get_course_status( $request ){
		$status = false;
		$course = get_post( $request['id'] );
		if( is_object( $course ) ){
			$status = get_user_meta( $request['user'], '_completed_' . sanitize_title( $course->post_title ), true );
			$status = $status == "" ? false : true;
		}
		return $status;
	}
	
	public function complete_course( $request ){
		$course = get_post( $request['id'] );
		$user_id = $request['user'];
		$lessons = get_posts( array(
			'post_type'		=> 'lesson',
			'meta_key'		=> '_course_id',
			'meta_value'	=> $request['id'],
		) );
		foreach( $lessons as $lesson ){
			if( '' == get_user_meta( $user_id, '_completed_' . sanitize_title( $lesson->post_title ), true ) ){
				break;
			}
			if( is_object( $course ) ){
				update_user_meta( $user_id, '_completed_' . sanitize_title( $course->post_title ), true );
				// Send congratulatory email to student
				$user = get_user_by( 'id', $user_id );
				ob_start();
				require( LAMAD_PLUGIN_DIR . '/templates/email-coursecomplete.php' );
				$message = ob_get_clean();
				$subject = __( 'Congratulations', 'lamad' );
				wp_mail( $user->user_email, $subject, $message );

				// Notify instructors about student completion
				$instructors = get_users( array( 'role' => 'instructor' ) );
				$profile = get_edit_user_link( $user_id );
				foreach( $instructors as $instructor ){
					$message = "<p>The following user has completed the {$course->post_title} course: $profile</p>";
					$subject = __( 'Course Completed', 'lamad' );
					wp_mail( $instructor->user_email, $subject, $message );
				}
				return true;
			}
		}
		return false;
	}
	
	public function get_lesson_status( $request ){
		$status = false;
		$lesson = get_post( $request['id'] );
		if( is_object( $lesson ) ){
			$status = get_user_meta( $request['user'], '_completed_' . sanitize_title( $lesson->post_title ), true );
			$status = $status == "" ? false : true;
		}
		return $status;
	}
	
	public function complete_lesson( $request ){
		$lesson = get_post( $request['id'] );
		if( is_object( $lesson ) ){
			update_user_meta( $request['user'], '_completed_' . sanitize_title( $lesson->post_title ), true );
			return true;
		}
		return false;
	}
	
	public function get_lesson_content( $request ){
		$lesson = get_post( $request['id'] );
		$_lesson = array();
		if( is_object( $lesson ) ){
			$_lesson['id'] = $lesson->ID;
			$_lesson['title'] = $lesson->post_title;
			$_lesson['content'] = apply_filters( 'the_content', $lesson->post_content );
		}
		return $_lesson;
	}
	
	public function get_courses( $request ){
		$_courses = array();
		$course_ids = get_user_meta( $request['user'], '_course_ids', true );
		if( ! empty( $course_ids ) ){
			$courses = get_posts( array( 'post_type' => 'course', 'include' => $course_ids, 'order' => 'ASC' ) );
			foreach( $courses as $course ){
				$_course['id'] = $course->ID;
				$_course['title'] = $course->post_title;
				$_courses[] = $_course;
			}
		}
		return $_courses;
	}
	
	public function get_course_lessons( $request ){
		$lessons = get_posts( array(
			'post_type'		=> 'lesson',
			'meta_key'		=> '_course_id',
			'meta_value'	=> $request['course'],
			'order'			=> 'ASC'
		) );
		$_lessons = array();
		foreach( $lessons as $lesson ){
			$_lesson['id'] = $lesson->ID;
			$_lesson['title'] = $lesson->post_title;
			$_lessons[] = $_lesson;
		}
		return $_lessons;
	}
	
	
}

new Lamad_Route_Controller();