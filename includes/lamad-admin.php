<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Lamad_Admin{
	
	public function __construct() {
		
		//Add Course meta box to Lessons
		add_action( 'add_meta_boxes', array( $this, 'show_lesson_course_meta_box' ) );
		
		//Save Lesson `_course_id`
		add_action( 'save_post', array( $this, 'save_lesson_course' ) , 10, 2 );
		
		//Add Lessons meta box to Courses
		add_action( 'add_meta_boxes', array( $this, 'show_course_lessons_meta_box' ) );
		
		add_action( 'manage_users_columns', array( $this, 'modify_users_columns' ) );
		//Render courses column in users table
		add_filter( 'manage_users_custom_column', array( $this, 'render_courses_column' ), 10, 3 );

		//Add custom fields to student user profile
		add_action( 'show_user_profile', array( $this, 'modify_student_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'modify_student_profile' ) );
		
		//Save custom fields in student user profile
        add_action('personal_options_update', array( $this, 'save_student_profile' ));
        add_action('edit_user_profile_update', array( $this, 'save_student_profile' ));
		
		// Filter student admin dashboard
		add_action( 'admin_menu', array( $this, 'remove_admin_menus' ),9999 );
	}
	
	/**
	 * Remove admin menus from student admin dashboard
	 * @global WP_User $current_user
	 * @global WP_Menu $menu
	 */
	public function remove_admin_menus(){
		global $current_user;
		if( isset( $current_user->roles ) && is_array(  $current_user->roles  ) && in_array( 'student', $current_user->roles ) ){
			global $menu;
			$allowed_menus = array( 'profile.php' );
			foreach($menu as $menu_item ){
				if( !in_array( $menu_item[2], $allowed_menus ) ){
					remove_menu_page( $menu_item[2] );
				}
			}
		}

		if( ( $current_user->roles ) && is_array(  $current_user->roles  ) && ! in_array( 'administrator', $current_user->roles  ) ){
			remove_menu_page( 'jetpack' );
		}
		
	}

	/**
	 * Add custom user meta fields to student profile
	 * @param WP_User $user
	 */
	public function modify_student_profile( $user ){
		?>
			<h2><?php echo __('Student Information', 'lamad') ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="gender"><?php echo __('Gender', 'lamad') ?></label></th>
					<td>
						<input type="radio" name="gender" value="Female" class="tog" <?php checked( 'Female', get_user_meta( $user->ID, '_gender', true ), true ); ?>  />Female
						<input type="radio" name="gender" value="Male" class="tog" <?php checked( 'Male', get_user_meta( $user->ID, '_gender', true ), true ); ?>  />Male
					</td>
				</tr>
				<tr>
					<th><label for="occupation"><?php echo __('Occupation', 'lamad') ?></label></th>
					<td>
						<input type="text" name="occupation" id="occupation" value="<?php echo get_user_meta( $user->ID, '_occupation', true ); ?>" class="regular-text"  />
					</td>
				</tr>
				<tr>
					<th><label for="marital_status"><?php echo __('Marital Status', 'lamad') ?></label></th>
					<td>
						<input type="radio" name="marital_status" value="Single" class="tog" <?php checked( 'Single', get_user_meta( $user->ID, '_marital_status', true ), true ); ?>  />Single
						<input type="radio" name="marital_status" value="Married" class="tog" <?php checked( 'Married', get_user_meta( $user->ID, '_marital_status', true ), true ); ?>  />Married
					</td>
				</tr>
				<tr>
					<th><label for="church"><?php echo __('Church', 'lamad') ?></label></th>
					<td>
						<input type="text" name="church" id="church" value="<?php echo get_user_meta( $user->ID, '_church', true ); ?>" class="regular-text"  />
					</td>
				</tr>
				<tr>
					<th><label for="address"><?php echo __('Address', 'lamad') ?></label></th>
					<td>
						<input type="text" name="address" id="address" value="<?php echo get_user_meta( $user->ID, '_address', true ); ?>" class="regular-text"  />
					</td>
				</tr>
				<tr>
					<th><label for="phone"><?php echo __('Phone', 'lamad') ?></label></th>
					<td>
						<input type="text" name="phone" id="phone" value="<?php echo get_user_meta( $user->ID, '_phone', true ); ?>" class="regular-text"  />
					</td>
				</tr>
			</table>		
		<?php
	}
	
	/**
	 * Save custom user fields in student profile
	 * @param id $user_id
	 */
	public function save_student_profile( $user_id ){
        if ( isset( $_POST[ 'gender' ] ) && !empty( $_POST[ 'gender' ] ) ) {
			update_user_meta( $user_id, '_gender', sanitize_text_field( $_POST[ 'gender' ] ) );
		}
        if ( isset( $_POST[ 'occupation' ] ) && !empty( $_POST[ 'occupation' ] ) ) {
			update_user_meta( $user_id, '_occupation', sanitize_text_field( $_POST[ 'occupation' ] ) );
		}
        if ( isset( $_POST[ 'marital_status' ] ) && !empty( $_POST[ 'marital_status' ] ) ) {
			update_user_meta( $user_id, '_marital_status', sanitize_text_field( $_POST[ 'marital_status' ] ) );
		}
        if ( isset( $_POST[ 'church' ] ) && !empty( $_POST[ 'church' ] ) ) {
			update_user_meta( $user_id, '_church', sanitize_text_field( $_POST[ 'church' ] ) );
		}
        if ( isset( $_POST[ 'address' ] ) && !empty( $_POST[ 'address' ] ) ) {
			update_user_meta( $user_id, '_address', sanitize_text_field( $_POST[ 'address' ] ) );
		}
        if ( isset( $_POST[ 'phone' ] ) && !empty( $_POST[ 'phone' ] ) ) {
			update_user_meta( $user_id, '_phone', sanitize_text_field( $_POST[ 'phone' ] ) );
		}
	}
	
	/**
	 * Render courses column in users table
	 * @param string $output
	 * @param string $column_name
	 * @param int $user_id
	 * @return string
	 */
	public function render_courses_column( $output, $column_name, $user_id ){
		if( 'courses' == $column_name ){
			$course_ids = get_user_meta( $user_id, '_course_ids', true );
			if( ! empty( $course_ids ) ){
				foreach( $course_ids as $course_id ){
					$course = get_post( $course_id );
					$output .= $course->post_title . '<br>';
				}
			}
		}
		return $output;
	}
	
	public function modify_users_columns( $column_headers ){
		unset( $column_headers['posts'] );
		$column_headers['courses'] = 'Courses';
		return $column_headers;
	}
	
	public function show_course_lessons_meta_box(){
		add_meta_box( 'course-lessons-meta-box', __( 'Lessons', 'lamad' ), array( $this, 'render_course_lessons_meta_box' ), 'course', 'advanced', 'high' );
	}
	
	public function render_course_lessons_meta_box(){
		global $post;
		$lessons = get_posts( array(
			'post_type'			=> 'lesson',
			'posts_per_page'	=> -1,
			'meta_key'			=> '_course_id',
			'meta_value'		=> $post->ID,
			'order'				=> 'ASC'
		) );
		if( ! empty( $lessons ) ){
			echo '<ol>';
			foreach( $lessons as $lesson ){
				echo "<li><a href='". get_edit_post_link( $lesson->ID ) ."' target='_blank' >{$lesson->post_title}</a></li>";
			}
			echo '</ol>';
		}
		else{
			echo __( 'There are no lessons.', 'lamad' );
		}
	}
	
	public function show_lesson_course_meta_box(){
		add_meta_box( 'lesson-course-meta-box', __( 'Select Course', 'lamad' ), array( $this, 'render_lesson_course_meta_box' ), 'lesson', 'side', 'high' );
	}
	
	public function render_lesson_course_meta_box(){
		global $post;
		$course_id = get_post_meta( $post->ID, '_course_id', true );
		$courses = get_posts( array( 'post_type' => 'course', 'posts_per_page' => -1, 'order' => 'ASC' ) );
		if( ! empty( $courses ) ){
			echo '<select name="course" >';
			foreach ( $courses as $course) {
				echo "<option value='{$course->ID}' " . selected( $course->ID, $course_id, false ) . " >{$course->post_title}</option>";
			}
			echo '</select>';
		}
		else{
			echo __( 'There are no courses.', 'lamad' );
		}
	}
	
	public function save_lesson_course( $post_id, $post ){
		if( 'lesson' != $post->post_type ){
			return;
		}
		if( isset( $_POST['course'] ) && !empty( $_POST['course'] ) ){
			update_post_meta( $post_id, '_course_id', $_POST['course'] );
		}
	}
	
}

new Lamad_Admin();