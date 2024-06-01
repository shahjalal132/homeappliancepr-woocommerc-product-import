<?php

// create an api endpoint for products
add_action( 'rest_api_init', 'coaster_products_api' );

function coaster_products_api() {

    // Delete all products
    register_rest_route( 'homeappliancepr/v1', '/test', [
        'methods'  => 'GET',
        'callback' => 'homeappliancepr_api_test',
    ] );

    // Delete all products
    register_rest_route( 'homeappliancepr/v1', '/delete-products', [
        'methods'  => 'GET',
        'callback' => 'products_delete_api_callback',
    ] );

    // Delete all product from trash
    register_rest_route( 'homeappliancepr/v1', '/delete-trash-products', [
        'methods'  => 'GET',
        'callback' => 'products_delete_trash_api_callback',
    ] );

    // Sync Products to WooCommerce
    register_rest_route( 'homeappliancepr/v1', '/sync-products', [
        'methods'  => 'GET',
        'callback' => 'sync_products_callback',
    ] );

    // Sync Products to WooCommerce
    register_rest_route( 'homeappliancepr/v1', '/insert-product', [
        'methods'  => 'GET',
        'callback' => 'product_insert_to_db',
    ] );

    // Sync Products to WooCommerce
    register_rest_route( 'homeappliancepr/v1', '/insert-product-codes', [
        'methods'  => 'GET',
        'callback' => 'product_code_insert_to_db',
    ] );

    // Sync Products to WooCommerce
    register_rest_route( 'homeappliancepr/v1', '/get-product-codes', [
        'methods'  => 'GET',
        'callback' => 'get_product_codes_callback',
    ] );

    // Sync Products to WooCommerce
    register_rest_route( 'homeappliancepr/v1', '/get-product-image', [
        'methods'  => 'GET',
        'callback' => 'get_product_image_callback',
    ] );

}

function homeappliancepr_api_test() {
    return "It's working";
}

function products_delete_api_callback() {
    return delete_all_woocommerce_products();
}

function products_delete_trash_api_callback() {
    return delete_all_trashed_woocommerce_products();
}

function sync_products_callback() {
    return products_insert_woocommerce_callback();
}

function product_insert_to_db() {
    return insert_products_to_db_callback();
}

function product_code_insert_to_db() {
    return insert_product_codes_db();
}

function get_product_codes_callback() {
    return get_product_codes();
}

function get_product_image_callback() {
    return get_product_images();
}