<?php
/*
	Plugin Name:			WooCommerce Rave Payment Gateway
	Plugin URI: 			https://rave.flutterwave.com
	Description:            WooCommerce payment gateway for Rave by Flutterwave
	Version:                1.0.1
	Author: 				Tunbosun Ayinla
	Author URI: 			https://bosun.me
	License:        		GPL-2.0+
	License URI:    		http://www.gnu.org/licenses/gpl-2.0.txt
	WC requires at least:   3.0.0
	WC tested up to:        3.3.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TBZ_WC_RAVE_MAIN_FILE', __FILE__ );

define( 'TBZ_WC_RAVE_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

define( 'TBZ_WC_RAVE_VERSION', '1.0.1' );

function tbz_wc_rave_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	require_once dirname( __FILE__ ) . '/includes/class-rave.php';


	add_filter( 'woocommerce_payment_gateways', 'tbz_wc_add_rave_gateway' );

}
add_action( 'plugins_loaded', 'tbz_wc_rave_init' );


/**
* Add Settings link to the plugin entry in the plugins menu
**/
function tbz_wc_rave_plugin_action_links( $links ) {

    $settings_link = array(
    	'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=tbz_rave' ) . '" title="View Settings">Settings</a>'
    );

    return array_merge( $settings_link, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tbz_wc_rave_plugin_action_links' );


/**
* Add wallet.ng Gateway to WC
**/
function tbz_wc_add_rave_gateway( $methods ) {

	$methods[] = 'Tbz_WC_Rave_Gateway';

	return $methods;

}

/**
* Display the test mode notice
**/
function tbz_wc_rave_testmode_notice(){

	$settings = get_option( 'woocommerce_tbz_rave_settings' );

	$test_mode 	= isset( $settings['testmode'] ) ? $settings['testmode'] : '';

	if ( 'yes' == $test_mode ) {
    ?>
	    <div class="update-nag">
	        Rave testmode is still enabled, Click <a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=wc-settings&tab=checkout&section=tbz_rave">here</a> to disable it when you want to start accepting live payment on your site.
	    </div>
    <?php
	}
}
add_action( 'admin_notices', 'tbz_wc_rave_testmode_notice' );