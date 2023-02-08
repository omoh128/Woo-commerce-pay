<?php 

function my_woo_payment_gateway_activate() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( __( 'Please install and activate WooCommerce before activating this plugin.', 'my-woo-payment-gateway' ) );
    }
}


