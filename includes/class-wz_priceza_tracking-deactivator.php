<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://walnutztudio.com
 * @since      1.0.0
 *
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/includes
 * @author     WalnutZtudio <walnutztudio@gmail.com>
 */
class Wz_priceza_tracking_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option("wz_priceza_tracking_license");
		delete_option("wz_priceza_tracking_merchantId");
	}

}
