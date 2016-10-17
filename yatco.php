<?php
/**
 * Plugin Name: Yatco
 * Plugin URI: http://valant.com.ua
 * Description: 
 * Version: 1.0.2
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
   * @var string
   */
  public $token = 'yatco';

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
    
    wp_register_script( $this->token . '-select2', esc_url( $this->assets_url ) . 'js/select2/select2' . $this->script_suffix . '.js' , array(), $this->version );      
    wp_register_script( $this->token . '-enhanced-select', esc_url( $this->assets_url ) . 'js/admin/enhanced' . $this->script_suffix . '.js', array( $this->token . '-select2' ), $this->version );
    
    wp_localize_script( $this->token . '-enhanced-select', 'ya_enhanced_select_params', array(
      'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', 'yatco' ),
      'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'yatco' ),
      'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'yatco' ),
      'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'yatco' ),
      'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'yatco' ),
      'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'yatco' ),
      'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'yatco' ),
      'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'yatco' ),
      'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'yatco' ),
      'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'yatco' ),
      'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'yatco' ),
      'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'yatco' ),
      'ajax_url'                  => admin_url( 'admin-ajax.php' ),
    ) );

    if( $screen_id == 'vessel'){
      $depth = array( 
        'media-models',
        'wp-color-picker',
        'jquery-ui-datepicker',
        $this->token . '-enhanced-select'
      );
      wp_enqueue_script( $this->token . '-meta-boxes', esc_url( $this->assets_url ) . 'js/admin/meta-boxes' . $this->script_suffix . '.js' , $depth, $this->version );      
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


    wp_register_style( $this->token . '-fonts', esc_url( $this->assets_url ) . 'css/yatco-fonts.css', array(), $this->version );
    wp_register_style( $this->token . '-select2', esc_url( $this->assets_url ) . 'css/select2.css', array(), $this->version );
    wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
    if( $screen_id == 'vessel'){
      $depth = array(
        $this->token . '-fonts',
        $this->token . '-select2'
      );
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_style( 'jquery-ui' );
      wp_enqueue_style( $this->token . '-meta-boxes', esc_url( $this->assets_url ) . 'css/meta-boxes.css', $depth, $this->version );
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
    include_once( 'includes/class-ya-post-types.php' );                               // Registers post types
    include_once( 'includes/class-ya-meta-boxes.php' );                               // Add metaboxes
    include_once( 'includes/class-ya-attributes.php' );                               // Registers post types

    $this->api   = new YA_API();

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
   * Init yatco when WordPress Initialises.
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