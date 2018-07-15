<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://walnutztudio.com
 * @since      1.0.0
 *
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/admin
 * @author     WalnutZtudio <walnutztudio@gmail.com>
 */
class Wz_priceza_tracking_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wz_priceza_tracking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wz_priceza_tracking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wz_priceza_tracking-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wz_priceza_tracking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wz_priceza_tracking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wz_priceza_tracking-admin.js', array( 'jquery' ), $this->version, false );

	}

}

add_action( 'admin_menu', 'walnut_priceza_tracking_plugin_menu' );
if( !function_exists("walnut_priceza_tracking_plugin_menu") ){
	function walnut_priceza_tracking_plugin_menu(){

	$page_title = 'Woo Priceza Tracking';
	$menu_title = 'Priceza Tracking';
	$capability = 'manage_options';
	$menu_slug  = 'wz-priceza-tracking';
	$function   = 'walnut_priceza_tracking_page';

	add_options_page( $page_title,
					$menu_title, 
					$capability, 
					$menu_slug, 
                    $function);
	}
}


if( !function_exists("walnut_priceza_tracking_page") ){
	function walnut_priceza_tracking_page(){

		if(!isset($_GET['tab']) || $_GET['tab'] == '' || $_GET['tab'] == 'license'){
			$nav_tab_active = 'license';
		}elseif($_GET['tab'] == 'license'){
			$nav_tab_active = 'license';
		}else {
			$nav_tab_active = 'settings';
		}
	?>
	
	<div class="wrap">
			<h1>Woo Priceza Tracking</h1>
			<form method="post" action="options.php">
				<h2 class="nav-tab-wrapper">
				<?php
					$license_key = get_option( 'wz_priceza_tracking_license_key' );
					$status  = get_option( 'wz_priceza_tracking_license_status' );
					?>
					<?php if( $status !== false && $status == 'valid') { 
						//$nav_tab_active = 'settings';
						?>
						<a href="<?php echo admin_url('options-general.php?page=wz-priceza-tracking&tab=settings'); ?>" class="nav-tab <?php if($nav_tab_active == 'settings') echo 'nav-tab-active'; ?>">
							<?php _e( 'Settings', 'wz_priceza_tracking' ); ?>
						</a>
					<?php } ?>
						<a href="<?php echo admin_url('options-general.php?page=wz-priceza-tracking&tab=license'); ?>" class="nav-tab <?php if($nav_tab_active == 'license') echo 'nav-tab-active'; ?>">
							<?php _e( 'License', 'wz_priceza_tracking' ); ?>
						</a>
				</h2>
				<?php if($nav_tab_active == 'settings'){?>
					<?php settings_fields( 'wz_priceza_tracking_setting' ); ?>
					<!--?php do_settings_sections( 'wz_priceza_tracking_setting' ); ?-->
					<h2 class="title"><?php _e('Setting', 'wz_priceza_tracking'); ?></h2>  
					<p>if have problem you can contact <a href="https://walnutztudio.com" target="_blank">WalnutZtudio</a></p>
					<table class="form-table" width="100%">
						<tbody>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php _e('merchantId', 'wz_priceza_tracking'); ?> 
								</th>
								<td>
									<input type="text" name="wz_priceza_tracking_merchantId" value="<?php echo get_option( 'wz_priceza_tracking_merchantId' ); ?>"/>
									<p class="description" id="wz_priceza_tracking_enable_description"><?php _e('Your can insert merchantId for Priceza Sales Conversion Tracking.', 'wz_priceza_tracking');?></p>
								</td>
							</tr>
						</tbody>
					</table>
				<?php } ?>

				<?php if($nav_tab_active == 'license'){ ?>
					<?php settings_fields( 'wz_priceza_tracking_license' ); ?>
					<!--?php do_settings_sections( 'wz_priceza_tracking_license' ); ?-->
					<h2 class="title"><?php _e('License', 'wz_priceza_tracking'); ?></h2>
					<table class="form-table" width="100%">
						<tbody>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php _e('License Key', 'wz_priceza_tracking'); ?> 
								</th>
								<td>
									<input type="text" name="wz_priceza_tracking_license_key" class="wz_priceza_tracking_license_key" value="<?php esc_attr_e( $license_key ); ?>"/>
									<label class="description" for="wz_priceza_tracking_license_key"><?php _e('Enter your license key'); ?></label>
								</td>
							</tr>
							<?php if( false !== $license_key ) { ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php _e('Activate License', 'wz_priceza_tracking'); ?>
								</th>
								<td>
									<?php if( $status !== false && $status == 'valid' ) { ?>
										<span style="color:green;"><?php _e('active', 'wz_priceza_tracking'); ?></span>
										<?php wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
										<input type="submit" class="button-secondary" name="wz_priceza_tracking_license_deactivate" value="<?php _e('Deactivate License', 'wz_priceza_tracking'); ?>"/>
									<?php } else {
										wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
										<input type="submit" class="button-secondary" name="wz_priceza_tracking_license_activate" value="<?php _e('Activate License', 'wz_priceza_tracking'); ?>"/>
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
	}
}

function update_wz_priceza_tracking_merchantId() {
	// creates our settings in the options table
	register_setting( 'wz_priceza_tracking_setting', 'wz_priceza_tracking_merchantId' );
}
add_action('admin_init', 'update_wz_priceza_tracking_merchantId');

function update_wz_priceza_tracking_license() {
	// creates our settings in the options table
	register_setting('wz_priceza_tracking_license', 'wz_priceza_tracking_license_key', 'edd_sanitize_license' );
}
add_action('admin_init', 'update_wz_priceza_tracking_license');

function edd_sanitize_license( $new ) {
	$old = get_option( 'wz_priceza_tracking_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'wz_priceza_tracking_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}