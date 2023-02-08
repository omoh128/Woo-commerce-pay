<?php
/**
 * Plugin Name: My Woo Payment Gateway
 * Description: A custom WooCommerce payment gateway.
 * Version: 1.0.0 
 * Author: Omomoh Agiogu 
 * Author URI: https://github.com/omoh128
 * Text Domain: my-woo-payment-gateway
 * Domain Path: /languages
 * Requires at least: 4.9
 * Tested up to: 5.6
 *
 * @package My_Woo_Payment_Gateway
 */
 
defined( 'ABSPATH' ) or exit;
 
require_once 'class-my-woo-payment-gateway.php';
 
add_filter( 'woocommerce_payment_gateways', 'my_woo_payment_gateway_add_to_gateways' );
 
function my_woo_payment_gateway_add_to_gateways( $gateways ) {
    $gateways[] = 'My_Woo_Payment_Gateway';
    return $gateways;
}
 
//register_activation_hook( __FILE__, 'my_woo_payment_gateway_activate' );
//register_deactivation_hook( __FILE__, 'my_woo_payment_gateway_deactivate' );
 

 
function my_woo_payment_gateway_deactivate() {
    // Place your deactivation code here.
}
