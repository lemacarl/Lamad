<?php
/**
 * Plugin Name:		Lamad
 * Plugin URI:		https://github.com/lemacarl/lamad
 * Description:		Used to provide online Christian learning content
 * Version:			1.0.0
 * Author:			Lema
 * Author URI:		https://github.com/lemacarl
 * Text Domian:		lamad
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /languages
 */

if( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly
}

if( ! class_exists( 'Lamad' ) ){
	
	final class Lamad{
		
		/**
		 * Reference to plugin version
		 * @var string
		 */
		private $version = '1.0.0';
		
		/**
		 * Options key
		 * @var string
		 */
		private $options_key = 'lamad';
		
		/**
		 * Instance container
		 * @var Lamad
		 */
		private static $instance = null;
		
		/**
		 * Instantiate Lamad
		 * @return Lamad
		 */
		public static function instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}
			return self::$instance;
		}
		
		/**
		 * Cloning is not allowed
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Hold your horses right there!', 'lamad' ), $this->version );
		}
		
		/**
		 * Unserializing instances is not allowed
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Hold your horses right there!', 'lamad' ), $this->version );
		}
		
		public function __construct() {
			$this->define_constants();
			$this->do_requires();
			
			register_activation_hook( __FILE__, array( 'Lamad_Install', 'activate' ) );
			register_deactivation_hook( __FILE__, array( 'Lamad_Install', 'deactivate' ) );
		}
		
		/**
		 * Define constants that will be used later in the plugin
		 */
		public function define_constants(){
			if( ! defined( 'LAMAD_PLUGIN_FILE' ) ){
				define( 'LAMAD_PLUGIN_FILE', __FILE__ );
			}
			if( ! defined( 'LAMAD_PLUGIN_DIR' ) ){
				define( 'LAMAD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
			if( ! defined( 'LAMAD_PLUGIN_URL' ) ){
				define( 'LAMAD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
			if( ! defined( 'LAMAD_OPTIONS_KEY' ) ){
				define( 'LAMAD_OPTIONS_KEY', $this->options_key );
			}
			if( ! defined( 'LAMAD_VERSION' ) ){
				define( 'LAMAD_VERSION', $this->version );
			}
		}
		
		public function do_requires(){
			require_once( LAMAD_PLUGIN_DIR . '/includes/lamad-route-controller.php' );
			require_once( LAMAD_PLUGIN_DIR . '/includes/lamad-install.php' );
			require_once( LAMAD_PLUGIN_DIR . '/includes/lamad-custom-post-types.php' );
			require_once( LAMAD_PLUGIN_DIR . '/includes/lamad-public.php' );
			if( is_admin() ){
				require_once( LAMAD_PLUGIN_DIR . '/includes/lamad-admin.php' );
			}
		}
	
	}
	
	function Lamad(){
		return Lamad::instance();
	}
	
	Lamad();
}