<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://walnutztudio.com
 * @since      1.0.0
 *
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/includes
 * @author     WalnutZtudio <walnutztudio@gmail.com>
 */
class Wz_priceza_tracking_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wz_priceza_tracking',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
