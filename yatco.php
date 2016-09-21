<?php
/**
 * Plugin Name: Yatco
 * Plugin URI: http://valant.com.ua
 * Description: 
 * Version: 1.0.1
 * Author: Valant
 * Author URI: http://valant.com.ua
 * Requires at least: 4.1.0.0
 * Tested up to: 4.6.1
 *
 * Text Domain: yatco
 * Domain Path: /i18n/
 *
 * @package Yatco
 * @category Core
 * @author Valant
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

require_once ('includes/SForceApi/SalesForceApi.php');

if ( ! class_exists( 'Yatco' ) ) :

/**
 * Main Yatco Class
 *
 * @class Yatco
 * @version 1.0.0
 */
final class Yatco {

  /**
   * @var string
   */
  public $version = '1.0.1';

  /**
   * @var Yatco The single instance of the class
   * @since 1.0.0
   */
  protected static $_instance = null;

  /**
   * @var YA_API $api
   */
  public $api = null;

  /**
   * The plugin assets URL.
   * @var     string
   * @access  public
   * @since   1.0.1
   */
  public $assets_url;
  
  /**
   * Suffix for Javascripts.
   * @var     string
   * @access  public
   * @since   1.0.1
   */
  public $script_suffix;


  /**
   * Main Yatco Instance
   *
   * Ensures only one instance of Yatco is loaded or can be loaded.
   *
   * @since 1.0.0
   * @static
   * @see YA()
   * @return Yatco - Main instance
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  /**
   * Cloning is forbidden.
   * @since 1.0.0
   */
  public function __clone() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'yatco' ), '1.0.0' );
  }

  /**
   * Unserializing instances of this class is forbidden.
   * @since 1.0.0
   */
  public function __wakeup() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'yatco' ), '1.0.0' );
  }

  /**
   * Yatco Constructor.
   */
  public function __construct() {

    $this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', __FILE__ ) ) );
    $this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    $this->define_constants();
    $this->includes();
    $this->init_hooks();

    // Load admin JS & CSS
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

    do_action( 'yatco_loaded' );
  }

  /**
   * Load admin Javascript.
   * @access  public
   * @since   1.0.0
   * @return  void
   */
  public function admin_enqueue_scripts ( $hook = '' ) {

    $screen         = get_current_screen();
    $screen_id      = $screen ? $screen->id : '';
    
    if( $screen_id == 'vessel'){
      wp_enqueue_script( $this->_token . '-meta-boxes', esc_url( $this->assets_url ) . 'js/admin/meta-boxes' . $this->script_suffix . '.js' , array( 'media-models' ), $this->_version );      
    }
  } // End enqueue_scripts ()

  /**
   * Load admin CSS.
   * @access  public
   * @since   1.0.1
   * @return  void
   */
  public function admin_enqueue_styles ( $hook = '' ) {
    
    $screen         = get_current_screen();
    $screen_id      = $screen ? $screen->id : '';

    if( $screen_id == 'vessel'){
      wp_enqueue_style( $this->_token . '-meta-boxes', esc_url( $this->assets_url ) . 'css/meta-boxes.css', array(), $this->_version );
    }

  } // End admin_enqueue_styles ()

  /**
   * Hook into actions and filters
   * @since  1.0.0
   */
  private function init_hooks() {
    add_filter( 'cron_schedules', array($this, 'cron_add_schedules'), 45, 1 );
    register_activation_hook( __FILE__, array( 'YA_Install', 'install' ) );
    add_action( 'init', array( $this, 'init' ), 0 );
  }

  public function cron_add_schedules($schedules){
    $schedules['two_hourly'] = array(
      'interval' => 60 * 60 * 2,
      'display' => __( 'Once in two hours' )
    );
    return $schedules;
  }

  /**
   * Define YA Constants
   */
  private function define_constants() {
    $upload_dir = wp_upload_dir();

    $this->define( 'YA_PLUGIN_FILE', __FILE__ );
    $this->define( 'YA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
    $this->define( 'YA_VERSION', $this->version );
    $this->define( 'YA_LOG_DIR', $upload_dir['basedir'] . '/ya-logs/' );
  }
  /**
   * Define constant if not already set
   * @param  string $name
   * @param  string|bool $value
   */
  private function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }

  /**
   * Include required core files used in admin and on the frontend.
   */
  public function includes() {
    include_once( 'includes/class-ya-autoloader.php' );
    include_once( 'includes/ya-core-functions.php' );
    if ( $this->is_request( 'admin' ) ) {
      include_once( 'includes/admin/class-ya-admin.php' );
    }

    if ( $this->is_request( 'ajax' ) ) {
      $this->ajax_includes();
    }

    if ( $this->is_request( 'frontend' ) ) {
      $this->frontend_includes();
    }
    include_once( 'includes/class-ya-post-types.php' );                        // Registers post types
    include_once( 'includes/class-ya-meta-boxes.php' );                        // Add metaboxes
    include_once( 'includes/meta-boxes/class-ya-meta-box-photo-gallery.php' ); // Photo gallery metabox
  }
  /**
   * Include required ajax files.
   */
  public function ajax_includes() {
    include_once( 'includes/class-ya-ajax.php' );                           // Ajax functions for admin and the front-end
  }
  /**
   * Include required frontend files.
   */
  public function frontend_includes() {

  }

  /**
   * Init WooCommerce when WordPress Initialises.
   */
  public function init() {
    // Before init action
    do_action( 'before_yatco_init' );
    // Init action
    do_action( 'yatco_init' );
  }

  /**
   * What type of request is this?
   * string $type ajax, frontend or admin
   * @return bool
   */
  private function is_request( $type ) {
    switch ( $type ) {
      case 'admin' :
        return is_admin();
      case 'ajax' :
        return defined( 'DOING_AJAX' );
      case 'cron' :
        return defined( 'DOING_CRON' );
      case 'frontend' :
        return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
    }
  }

  /**
   * Get the plugin url.
   * @return string
   */
  public function plugin_url() {
    return untrailingslashit( plugins_url( '/', __FILE__ ) );
  }

  /**
   * Get the plugin path.
   * @return string
   */
  public function plugin_path() {
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
  }
  /**
   * Get Ajax URL.
   * @return string
   */
  public function ajax_url() {
    return admin_url( 'admin-ajax.php', 'relative' );
  }


}

endif;
/**
 * Returns the main instance of YA to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Yatco
 */
function YA() {
  return Yatco::instance();
}

// Global for backwards compatibility.
$GLOBALS['yatco'] = YA();