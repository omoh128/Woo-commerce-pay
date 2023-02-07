<?php
class My_Woo_Payment_Gateway extends WC_Payment_Gateway {
 
    public function __construct() {
        $this->id                 = 'my_woo_payment_gateway';
        $this->icon               = apply_filters( 'woocommerce_my_woo_payment_gateway_icon', '' );
        $this->has_fields         = false;
        $this->method_title       = __( 'My Woo Payment Gateway', 'my-woo-payment-gateway' );
        $this->method_description = __( 'Allow customers to pay using my Woo payment gateway', 'my-woo-payment-gateway' );
 
        // Load the form fields.
        $this->init_form_fields();
 
        // Load the settings.
        $this->init_settings();
 
        // Define user set variables.
        $this->title        = $this->get_option( 'title' );
        $this->description  = $this->get_option( 'description' );
        $this->api_key      = $this->get_option( 'api_key' );
 
        // Actions.
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
 
        // Customer Emails.
        add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
    }
 
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => 'Enable/Disable',
                'type'    => 'checkbox',
                'label'   => 'Enable My Woo Payment Gateway',
                'default' => 'yes'
            ),
            'title' => array(
                'title'       => 'Title',
                'type'        => 'text',
                'description' => 'This controls the title which the user sees during checkout.',
                'default'     => 'My Woo Payment Gateway',
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => 'Description',
                'type'        => 'textarea',
                'description' => 'This controls the description which the user sees during checkout.',
                'default'     => 'Pay securely using My Woo Payment Gateway.',
                'desc_tip'    => true,
            ),
            'api_key' => array(
                'title'       => 'API Key',
                'type'        => 'text',
                'description' => 'Your API key for My Woo Payment Gateway.',
                'default'     => '',
                'desc_tip'    => true,
            ),
        );
    }
    public function payment_fields() {
        echo '<p>Pay securely using My Woo Payment Gateway.</p>';
    }

    public function process_payment( $order_id ) {
        global $woocommerce;
    
        $order = wc_get_order( $order_id );
    
    }
    
}
// Call the payment processing API with the order details and API key
$response = wp_remote_post( 'https://your-payment-processing-api.com/pay', array(
    'method'      => 'POST',
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true,
    'headers'     => array(
        'Authorization' => 'Bearer ' . $this->api_key,
        'Content-Type'  => 'application/json',
    ),
    'body'        => json_encode( array(
        'order_id'    => $order_id,
        'amount'      => $order->get_total(),
        'currency'    => get_woocommerce_currency(),
        'return_url'  => $this->get_return_url( $order ),
        'cancel_url'  => $order->get_cancel_order_url_raw(),
    ) ),
    'cookies'     => array(),
) );

if ( is_wp_error( $response ) ) {
    wc_add_notice( 'Payment processing failed. Please try again later or use a different payment method.', 'error' );
    return;
}

$response_code = wp_remote_retrieve_response_code( $response );
$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

if ( $response_code != 200 || ! isset( $response_body['success'] ) || ! $response_body['success'] ) {
    wc_add_notice( 'Payment processing failed. Please try again later or use a different payment method.', 'error' );
    return;
}

$order->payment_complete();
$woocommerce->cart->empty_cart();

return array(
    'result'   => 'success',
    'redirect' => $this->get_return_url( $order ),
);



function add_my_woo_payment_gateway( $methods ) {
$methods[] = 'My_Woo_Payment_Gateway';
return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_my_woo_payment_gateway' );



        