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
 * Plugin URI:        https://walnutztudio
 * Description:       Priceza Sales Conversion Tracking for Woocommerce.
 * Version:           1.0.0
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

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
