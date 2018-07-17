<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://walnutztudio.com
 * @since      1.0.0
 *
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wz_priceza_tracking
 * @subpackage Wz_priceza_tracking/public
 * @author     WalnutZtudio <walnutztudio@gmail.com>
 */
class Wz_priceza_tracking_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wz_priceza_tracking-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wz_priceza_tracking-public.js', array( 'jquery' ), $this->version, false );

	}

}

/**
 * Add custom tracking code to the thank-you page
 */
add_action( 'woocommerce_thankyou', 'my_custom_tracking' );

function my_custom_tracking( $order_id ) {

	// Lets grab the order
	$order = wc_get_order( $order_id );
	// Get Payment title method
	$payment_js = $order->get_payment_method_title();

	/**
	 * Put your tracking code here
	 * You can get the order total etc e.g. $order->get_total();
	 */
	 
	// This is the order total
	$order->get_total();
 
	// This is how to grab line items from the order 
	$line_items = $order->get_items();

	// This loops over line items
	foreach ( $line_items as $item ) {
  		// This will be a product
		$product = $order->get_product_from_item( $item );

  		// This is the products ID
		$id = $product->get_id();
		$product_js .= $id ."|" ;
		
		// This is the qty purchased
		$qty = $item['qty'];
		$qty_js .= $qty . "|";
		
		// Line item total cost including taxes and rounded
		$total = $order->get_line_total( $item, true, true );
		
		// Line item subtotal (before discounts)
		$subtotal = $order->get_line_subtotal( $item, true, true );
	}

	// Remove end of string |
	$product_js = substr($product_js, 0, -1);
	$qty_js = substr($qty_js, 0, -1);
	$merchantId = get_option('wz_priceza_tracking_merchantId');
	?>
	
	<script type="text/javascript">
		var _pztrack = {
			type : "purchase",
			merchantId : "<?= $merchantId ?>",
			productId: "<?= $product_js ?>",
			value : "<?= $qty_js ?>",
			filter : "<?= $payment_js ?>",
			data: "<?= $order->id ?>"
		};

		(function() {
		    var pzsc = document.createElement('script');
		    pzsc.type = 'text/javascript';
		    pzsc.async = true;
		    pzsc.src = ('https:' == document.location.protocol ? 'https://www.' : 'http://www.') + 'priceza.com/js/tracking-2.0.js';    
		    var s = document.getElementsByTagName('script')[0];
		    s.parentNode.insertBefore(pzsc, s);
		})();
	</script>

	<script type="text/javascript">
		var _pztrack = {
			type : "purchase",
			merchantId : "<?= $merchantId ?>",
			filter : "MEMBER-SIGNUP"
		};

		/*(function() {
		    var pzsc = document.createElement('script');
		    pzsc.type = 'text/javascript';
		    pzsc.async = true;
		    pzsc.src = ('https:' == document.location.protocol ? 'https://www.' : 'http://www.') + 'priceza.com/js/tracking-2.0.js';    
		    var s = document.getElementsByTagName('script')[0];
		    s.parentNode.insertBefore(pzsc, s);
		})();*/
	</script>

<?php
}
