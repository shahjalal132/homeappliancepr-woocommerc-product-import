<?php

function get_product_codes() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_product_codes';

    // get product codes
    $product_codes_results = $wpdb->get_results( "SELECT product_code FROM $table_name" );

    // Extract product codes into a simple array
    $product_codes = array_map( function ($item) {
        return $item->product_code;
    }, $product_codes_results );

    return $product_codes;
}
// add_shortcode( 'show_product_codes', 'get_product_codes' );

function get_product_image_by_code() {

    // get product codes
    // $product_codes = get_product_codes();

    $product_code = 19993;
    $image_number = 1;

    // base url
    $base_url = "https://ha.aswspr.com/Documents/001/IM/$product_code/Images/$image_number.jpg";

    // send a request to get the image
    $response    = wp_remote_get( $base_url );
    $status_code = wp_remote_retrieve_response_code( $response );

    

}

// add_shortcode( 'show_product_image', 'get_product_image_by_code' );