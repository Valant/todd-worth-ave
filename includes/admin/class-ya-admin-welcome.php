<?php
/**
 * Welcome Page Class
 *
 * Shows a feature overview for the new version (major) and credits.
 *
 * @author      Vlant
 * @category    Admin
 * @package     Yatco/Admin
 * @version     2.3.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * YA_Admin_Welcome class
 */
class YA_Admin_Welcome {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {

		if ( empty( $_GET['page'] ) ) {
			return;
		}

		$welcome_page_name  = __( 'About Yatco', 'yatco' );
		$welcome_page_title = __( 'Welcome to Yatco', 'yatco' );

		switch ( $_GET['page'] ) {
			case 'ya-load-vessels' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'ya-load-vessels', array( $this, 'load_vessels' ) );
				add_action( 'admin_print_styles-' . $page, array( $this, 'admin_css' ) );
				add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_js' ) );
			break;
			case 'ya-reparse-data' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'ya-reparse-data', array( $this, 'reparse_data' ) );
				add_action( 'admin_print_styles-' . $page, array( $this, 'admin_css' ) );
				add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_js' ) );
				break;
			case 'ya-data-actions' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'ya-data-actions', array( $this, 'data_actions' ) );
				add_action( 'admin_print_styles-' . $page, array( $this, 'admin_css' ) );
				add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_js' ) );
				break;
		}
	}
	/**
	 * admin_css function.
	 */
	public function admin_css() {
		wp_enqueue_style( 'yatco-activation', YA()->plugin_url() . '/assets/css/load-all-vessels.css', array(), YA_VERSION );
	}
	/**
	 * admin_js function.
	 */
	public function admin_js() {
		wp_register_script( 'yatco-activation', YA()->plugin_url() . '/assets/js/admin/load-all-vessels.js', array('jquery'), YA_VERSION );
		wp_register_script( 'yatco-reparse', YA()->plugin_url() . '/assets/js/admin/reparse-data.js', array('jquery'), YA_VERSION );
		wp_register_script( 'yatco-data-actions', YA()->plugin_url() . '/assets/js/admin/data-actions.js', array('jquery'), YA_VERSION );
		$translation_array = array(
			'i18n_stopping'           => self::esc_quotes( __( 'Stopping...', 'yatco' ) ),
			'i18n_failures'           => __( 'All done! %1$s vessel(s) were successfully loaded in %2$s seconds and there were %3$s failure(s).', 'yatco' ),
			'i18n_nofailures'         => __( 'All done! %1$s vessel(s) were successfully loaded in %2$s seconds and there were 0 failures.', 'yatco' ),
			'i18n_fatal_error'        => esc_js( __( 'The merge request was abnormally terminated. This is likely due to the image exceeding available memory or some other type of fatal error.', 'yatco' ) ),
		);
		wp_localize_script( 'yatco-activation', 'yatco_opt', $translation_array );
		wp_enqueue_script( 'yatco-activation');
		wp_enqueue_script( 'yatco-reparse');
		wp_enqueue_script( 'yatco-data-actions');
	}
	static function esc_quotes( $string ) {
    return str_replace( '"', '\"', $string );
  }

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'ya-load-vessels' );

		?>
		<style type="text/css">
			/*<![CDATA[*/
			.ya-badge:before {
				font-family: dashicons !important;
				content: "\f527";
				color: #fff;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				font-size: 80px;
				font-weight: normal;
				width: 165px;
				height: 165px;
				line-height: 165px;
				text-align: center;
				position: absolute;
				top: 0;
				<?php echo is_rtl() ? 'right' : 'left'; ?>: 0;
				margin: 0;
				vertical-align: middle;
			}
			.ya-badge {
				position: relative;
				background: #3A5F8C;
				text-rendering: optimizeLegibility;
				padding-top: 150px;
				height: 52px;
				width: 165px;
				font-weight: 600;
				font-size: 14px;
				text-align: center;
				color: #fff;
				margin: 5px 0 0 0;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,.2);
				box-shadow: 0 1px 3px rgba(0,0,0,.2);
			}
			.about-wrap .ya-badge {
				position: absolute;
				top: 0;
				<?php echo is_rtl() ? 'left' : 'right'; ?>: 0;
			}
			.about-wrap .ya-feature {
				overflow: visible !important;
				*zoom:1;
			}
			.about-wrap h3 + .ya-feature {
				margin-top: 0;
			}
			.about-wrap .ya-feature:before,
			.about-wrap .ya-feature:after {
				content: " ";
				display: table;
			}
			.about-wrap .ya-feature:after {
				clear: both;
			}
			.about-wrap .feature-rest div {
				width: 50% !important;
				padding-<?php echo is_rtl() ? 'left' : 'right'; ?>: 100px;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
				margin: 0 !important;
			}
			.about-wrap .feature-rest div.last-feature {
				padding-<?php echo is_rtl() ? 'right' : 'left'; ?>: 100px;
				padding-<?php echo is_rtl() ? 'left' : 'right'; ?>: 0;
			}
			.about-wrap div.icon {
				width: 0 !important;
				padding: 0;
				margin: 20px 0 !important;
			}
			.about-wrap .feature-rest div.icon:before {
				font-family: Yatco !important;
				font-weight: normal;
				width: 100%;
				font-size: 170px;
				line-height: 125px;
				color: #3A5F8C;
				display: inline-block;
				position: relative;
				text-align: center;
				speak: none;
				margin: <?php echo is_rtl() ? '0 -100px 0 0' : '0 0 0 -100px'; ?>;
				content: "\e01d";
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
			}
			.about-integrations {
				background: #fff;
				margin: 20px 0;
				padding: 1px 20px 10px;
			}
			.changelog h4 {
				line-height: 1.4;
			}
			/*]]>*/
		</style>
		<?php
	}

	/**
	 * Intro text/links shown on all about pages.
	 */
	private function intro() {

		// Flush after upgrades
		if ( ! empty( $_GET['ya-updated'] ) || ! empty( $_GET['ya-installed'] ) ) {
			flush_rewrite_rules();
		}

		// Drop minor version if 0
		$major_version = substr( YA()->version, 0, 3 );
		?>
		<h1><?php printf( __( 'Welcome to Yatco %s', 'yatco' ), $major_version ); ?></h1>

		<div class="about-text yatco-about-text">
			<?php
				if ( ! empty( $_GET['ya-installed'] ) ) {
					$message = __( 'Thanks, all done!', 'yatco' );
				} else{
					$message = __( 'Thanks for installing!', 'yatco' );
				}

				printf( __( '%s Yatco %s is more powerful, stable and secure than ever before. We hope you enjoy using it.', 'yatco' ), $message, $major_version );
			?>
		</div>

		<div class="ya-badge"><?php printf( __( 'Version %s', 'yatco' ), YA()->version ); ?></div> <br>
		<?php
	}

	/**
	 * Output the about screen.
	 */
	public function load_vessels() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<!--<div class="changelog point-releases"></div>-->
			<hr>
			<div class="changelog">
				<h2><?php _e('Load Vessels', 'yatco'); ?></h2>				
				<div id="message" style="display: none;"></div>
				<?php 
				$yatco_api_key = get_option('yatco_api_key');
				$api = new YA_Admin_API();
				if($yatco_api_key){
					?>
					<p>
						<input id="loadvessel-start" class="button hide-if-no-js" type="button" value="<?php _e('Start', 'yatco'); ?>">
					</p>
					<div id="loadvessel_progressbar">
						<p>
							<?php _e('Please be patient while the vessels are loaded. This can take a while if your server is slow or if load many vessels and categories. Do not navigate away from this page until this script is done or the vessels will not be loaded. You will be notified via this page when the loading is completed.', 'yatco'); ?>
						</p>
						<div id="loadvessel-bar">
		          <div style="" id="loadvessel-bar-percent">0%</div>
		          <div id="loadvessel-progressbar-value" style="width: 0%;"></div>
		        </div>
		        <p>
						<input id="loadvessel-stop" class="button hide-if-no-js" type="button" value="<?php _e('Abort', 'yatco'); ?>">
						</p>
						<h3 class="title"><?php _e('Debugging Information', 'yatco'); ?></h3>
						<p>
							<?php
							$count_v = $api->get_total_count(); 
							?>
							<input type="hidden" id="loadvessel_record_count" value="<?php echo $count_v['RecordCount']; ?>">
							<input type="hidden" id="loadvessel_page_count" value="<?php echo $count_v['PageCount']; ?>">
		          <b><?php _e('Total', 'yatco'); ?></b>: <?php echo $count_v['RecordCount']; ?><br>
		          <b><?php _e('Loaded', 'yatco'); ?></b>: <span id="loadvessel-debug-successcount">0</span><br>
		          <b><?php _e('Failure', 'yatco'); ?></b>: <span id="loadvessel-debug-failurecount">0</span>
		        </p>
		        <ol id="loadvessel-debuglist">
		        </ol>
					</div>
				<?php } else{ ?>
				<form action="<?php echo admin_url( 'index.php' ); ?>" method="get">
				<input type="hidden" name="page" value="ya-load-vessels">
					<p>
						<?php _e('Please enter YATCO API Key for start loading.', 'yatco'); ?>
					</p>
					<input type="text" name="yatco_api_key">
					<p>
						<input class="button button-primary hide-if-no-js" type="submit" value="<?php _e('Save', 'yatco'); ?>">
					</p>
				</form>
				<?php }?>
				<hr />
	  	</div>
		<?php
	}

	public function reparse_data()
	{
		?>
		<div class="wrap about-wrap">

		<?php $this->intro(); ?>

		<!--<div class="changelog point-releases"></div>-->
		<hr>
		<div class="changelog">
			<h2><?php _e('Re-parse Data', 'yatco'); ?></h2>
			<div id="parse-settings"><label for="reparse-post-id">Vessel page ID: </label><input type="text" name="reparse-post-id" id="reparse-post-id" placeholder="All" size="6"></div>
			<div id="message" style="display: none;"></div>
			<?php
			$yatco_api_key = get_option('yatco_api_key');
			$api = new YA_Admin_API();
			if($yatco_api_key){
				?>
				<p>
					<input id="reparse-start" class="button hide-if-no-js" type="button" value="<?php _e('Start', 'yatco'); ?>">
				</p>
				<div id="loadvessel_progressbar">
					<p>
						<?php _e('Please be patient while the vessels are loaded. This can take a while if your server is slow or if load many vessels and categories. Do not navigate away from this page until this script is done or the vessels will not be loaded. You will be notified via this page when the loading is completed.', 'yatco'); ?>
					</p>
					<div id="loadvessel-bar">
						<div style="" id="loadvessel-bar-percent">0%</div>
						<div id="loadvessel-progressbar-value" style="width: 0%;"></div>
					</div>
					<p>
						<input id="loadvessel-stop" class="button hide-if-no-js" type="button" value="<?php _e('Abort', 'yatco'); ?>">
					</p>
					<h3 class="title"><?php _e('Debugging Information', 'yatco'); ?></h3>
					<p>
						<?php
						$count_v = $api->get_missing_data_count();
						?>
						<input type="hidden" id="loadvessel_record_count" value="<?= $count_v ?>">
						<input type="hidden" id="loadvessel_page_count" value="<?= ceil($count_v / 10) ?>">
						<b><?php _e('Total', 'yatco'); ?></b>: <?php echo $count_v; ?><br>
						<b><?php _e('Loaded', 'yatco'); ?></b>: <span id="loadvessel-debug-successcount">0</span><br>
						<b><?php _e('Failure', 'yatco'); ?></b>: <span id="loadvessel-debug-failurecount">0</span>
					</p>
					<ol id="loadvessel-debuglist">
					</ol>
				</div>
			<?php } else{ ?>
				<form action="<?php echo admin_url( 'index.php' ); ?>" method="get">
					<input type="hidden" name="page" value="ya-load-vessels">
					<p>
						<?php _e('Please enter YATCO API Key for start loading.', 'yatco'); ?>
					</p>
					<input type="text" name="yatco_api_key">
					<p>
						<input class="button button-primary hide-if-no-js" type="submit" value="<?php _e('Save', 'yatco'); ?>">
					</p>
				</form>
			<?php }?>
			<hr />
		</div>
		<?php
	}

	public function data_actions()
	{
		?>
		<div class="wrap about-wrap">

		<?php $this->intro(); ?>

		<!--<div class="changelog point-releases"></div>-->
		<hr>
		<div class="changelog">
			<h2><?php _e('Data Actions', 'yatco'); ?></h2>
			<div id="data-actions-settings">
				<input type="radio" name="action" value="yatco_parse_vessel_tags" data-type="cron">Parse Amenities and Toys</input>
				<input type="radio" name="action" value="yatco_save_vessel_new_construction" data-type="cron">Save New Construction status</input>
<!--				<input type="radio"  name="action" value="create_data_hashes"  data-type="cron">Create data hashes</option>-->
			</div>
			<div id="message" style="display: none;"></div>
			<?php
			$yatco_api_key = get_option('yatco_api_key');
			$api = new YA_Admin_API();
			if($yatco_api_key){
				?>
				<p>
					<input id="data-action-start" class="button hide-if-no-js" type="button" value="<?php _e('Start', 'yatco'); ?>">
				</p>
				<div id="loadvessel_progressbar">
					<p>
						<?php _e('Please be patient while the vessels are loaded. This can take a while if your server is slow or if load many vessels and categories. Do not navigate away from this page until this script is done or the vessels will not be loaded. You will be notified via this page when the loading is completed.', 'yatco'); ?>
					</p>
					<div id="loadvessel-bar">
						<div style="" id="loadvessel-bar-percent">0%</div>
						<div id="loadvessel-progressbar-value" style="width: 0%;"></div>
					</div>
					<p>
						<input id="data-action-stop" class="button hide-if-no-js" type="button" value="<?php _e('Abort', 'yatco'); ?>">
					</p>
					<h3 class="title"><?php _e('Debugging Information', 'yatco'); ?></h3>
					<p>
						<?php
						$count_v = $api->get_missing_data_count();
						?>
						<input type="hidden" id="loadvessel_record_count" value="<?= $count_v ?>">
						<input type="hidden" id="loadvessel_page_count" value="<?= ceil($count_v / 10) ?>">
						<b><?php _e('Total', 'yatco'); ?></b>: <?php echo $count_v; ?><br>
						<b><?php _e('Loaded', 'yatco'); ?></b>: <span id="loadvessel-debug-successcount">0</span><br>
						<b><?php _e('Failure', 'yatco'); ?></b>: <span id="loadvessel-debug-failurecount">0</span>
					</p>
					<ol id="loadvessel-debuglist">
					</ol>
				</div>
			<?php } else{ ?>
				<form action="<?php echo admin_url( 'index.php' ); ?>" method="get">
					<input type="hidden" name="page" value="ya-load-vessels">
					<p>
						<?php _e('Please enter YATCO API Key for start loading.', 'yatco'); ?>
					</p>
					<input type="text" name="yatco_api_key">
					<p>
						<input class="button button-primary hide-if-no-js" type="submit" value="<?php _e('Save', 'yatco'); ?>">
					</p>
				</form>
			<?php }?>
			<hr />
		</div>
		<?php
	}
	

	/**
	 * Sends user to the welcome page on first activation.
	 */
	public function welcome() {

		if(isset($_GET['yatco_api_key'])){
			update_option('yatco_api_key', $_GET['yatco_api_key']);
		}

		// Bail if no activation redirect transient is set
		if ( ! get_transient( '_ya_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_ya_activation_redirect' );

		// Bail if we are waiting to install or update via the interface update/install links
		if ( YA_Admin_Notices::has_notice( 'install' ) || YA_Admin_Notices::has_notice( 'update' ) ) {
			return;
		}

		// Bail if activating from network, or bulk, or within an iFrame
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || defined( 'IFRAME_REQUEST' ) ) {
			return;
		}

		if ( ( isset( $_GET['action'] ) && 'upgrade-plugin' == $_GET['action'] ) || ( ! empty( $_GET['page'] ) && $_GET['page'] === 'ya-load-vessels' ) ) {
			return;
		}

		wp_redirect( admin_url( 'index.php?page=ya-load-vessels' ) );
		exit;
	}
}

new YA_Admin_Welcome();
