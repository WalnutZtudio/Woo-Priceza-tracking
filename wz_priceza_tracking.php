<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://walnutztudio.com
 * @since             1.0.0
 * @package           Wz_priceza_tracking
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Priceza Tracking
 * Plugin URI:        https://walnutztudio.com/downloads/woo-priceza-tracking/
 * Description:       Priceza Sales Conversion Tracking for WooCommerce.
 * Version:           1.0.5
 * Author:            WalnutZtudio
 * Author URI:        https://walnutztudio.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wz_priceza_tracking
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WZ_PRICEZA_TRACKING_VERSION', '1.0.5' );

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'WZ_PRICEZA_TRACKING_STORE_URL', 'https://walnutztudio.com' );

// the name of your product. This should match the download name in EDD exactly
define( 'WZ_PRICEZA_TRACKING_ITEM_NAME', 'Woo Priceza Tracking' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
// load our custom updater
	include( dirname( __FILE__ ) . '/wz_priceza_tracking_updater.php' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wz_priceza_tracking-activator.php
 */
function activate_wz_priceza_tracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wz_priceza_tracking-activator.php';
	Wz_priceza_tracking_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wz_priceza_tracking-deactivator.php
 */
function deactivate_wz_priceza_tracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wz_priceza_tracking-deactivator.php';
	Wz_priceza_tracking_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wz_priceza_tracking' );
register_deactivation_hook( __FILE__, 'deactivate_wz_priceza_tracking' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wz_priceza_tracking.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wz_priceza_tracking() {

	$plugin = new Wz_priceza_tracking();
	$plugin->run();

}
run_wz_priceza_tracking();

if( get_option('wz_priceza_tracking_license_status') !== false && get_option('wz_priceza_tracking_license_status') == 'valid' ) {
	add_action( 'woocommerce_thankyou', 'wz_priceza_tracking' );
	//add_action( 'user_register', 'wz_priceza_tracking_signup' );
}

/**
* Updater.
*/
add_action( 'admin_init', 'edd_sl_wz_priceza_tracking_plugin_updater', 0 );

function edd_sl_wz_priceza_tracking_plugin_updater() {
	$status  = get_option( 'wz_priceza_tracking_license_status' );

	if($status == 'valid'){
		/* retrieve our license key from the DB */
		$license_key = trim( get_option( 'wz_priceza_tracking_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater( WZ_PRICEZA_TRACKING_STORE_URL, __FILE__, array(
			'version'   => WZ_PRICEZA_TRACKING_VERSION,
			'license'   => $license_key,
			'item_name' => WZ_PRICEZA_TRACKING_ITEM_NAME,
			'author'    => 'WalnutZtudio'
		));
	}
}



function wz_priceza_tracking_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['wz_priceza_tracking_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'wz_priceza_tracking_nonce', 'wz_priceza_tracking_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'wz_priceza_tracking_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( WZ_PRICEZA_TRACKING_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( WZ_PRICEZA_TRACKING_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), WZ_PRICEZA_TRACKING_ITEM_NAME );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'admin.php?page=wz-priceza-tracking&tab=license');
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'wz_priceza_tracking_license_status', $license_data->license );
		wp_redirect( admin_url( 'admin.php?page=wz-priceza-tracking&tab=license' ) );
		exit();
	}
}
add_action('admin_init', 'wz_priceza_tracking_activate_license');



/**
**********************************************
* Deactivate license.
**********************************************
*/
add_action('admin_init', 'wz_priceza_tracking_deactivate_license');

function wz_priceza_tracking_deactivate_license() {

	/* listen for our activate button to be clicked */
	if( isset( $_POST['wz_priceza_tracking_license_deactivate'] ) ) {

		/* run a quick security check */
		if( ! check_admin_referer( 'wz_priceza_tracking_nonce', 'wz_priceza_tracking_nonce' ) )
			return; // get out if we didn't click the Activate button

		/* retrieve the license from the database */
		$license = trim( get_option( 'wz_priceza_tracking_license_key' ) );

		/* data to send in our API request */
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( WZ_PRICEZA_TRACKING_ITEM_NAME ),
			'url'        => home_url()
		);

		/* Call the custom API. */
		$response = wp_remote_post( WZ_PRICEZA_TRACKING_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		/* make sure the response came back okay */
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'wz_priceza_tracking' );
			}

			$base_url = admin_url( 'options-general.php?page=wz-priceza-tracking&tab=license' );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		/* decode the license data */
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		/* $license_data->license will be either "deactivated" or "failed" */
		if( $license_data->license == 'deactivated' ) {
			delete_option( 'wz_priceza_tracking_license_status' );
		}

		wp_redirect( admin_url( 'admin.php?page=wz-priceza-tracking&tab=license' ) );
		exit();
	}
}

function wz_priceza_tracking_check_license() {

	global $wp_version;

	$license = trim( get_option( 'wz_priceza_tracking_license' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( WZ_PRICEZA_TRACKING_ITEM_NAME ),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( WZ_PRICEZA_TRACKING_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}

/**
* Show admin notice if activate/deactivate license is fail.
*/
add_action( 'admin_notices', 'wz_priceza_tracking_admin_notices' );

function wz_priceza_tracking_admin_notices() {
	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch( $_GET['sl_activation'] ) {

			case 'false':
			$message = urldecode( $_GET['message'] );
			?>
			<div class="error">
				<p><?php echo $message; ?></p>
			</div>
			<?php
			break;

			case 'true':
			default:
			/* Developers can put a custom success message here for when activation is successful if they way. */
			break;
		}
	}
}
