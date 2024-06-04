<?php

// Function to delete all WooCommerce products
function delete_all_woocommerce_products() {

    // Define arguments to query all products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 100,
    );

    // Retrieve all products based on the query arguments
    $products = get_posts( $args );

    // Loop through each product and delete it
    foreach ( $products as $product ) {
        wp_delete_post( $product->ID, true ); // Set the second parameter to true to bypass the trash and delete permanently
    }

    // Return a message indicating that all WooCommerce products have been deleted
    return '<h2>Products have been deleted.</h2>';
}
// Add a shortcode 'delete_all_products' that triggers the function
add_shortcode( 'delete_all_products', 'delete_all_woocommerce_products' );


// Function to delete all trashed WooCommerce products permanently
function delete_all_trashed_woocommerce_products() {

    // Define arguments to query all trashed products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'trash',
    );

    // Retrieve all trashed products based on the query arguments
    $trashed_products = get_posts( $args );

    // Loop through each trashed product and delete it permanently
    foreach ( $trashed_products as $product ) {
        wp_delete_post( $product->ID, true ); // Set the second parameter to true to bypass the trash and delete permanently
    }

    // Return a message indicating that all trashed WooCommerce products have been permanently deleted
    return '<h2>All trashed WooCommerce products have been permanently deleted.</h2>';
}
// Add a shortcode 'delete_products_from_trash' that triggers the function
add_shortcode( 'delete_products_from_trash', 'delete_all_trashed_woocommerce_products' );


/**
 * Truncate table
 *
 * @return void
 */
function truncate_table_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_product_images';
    $wpdb->query( "TRUNCATE TABLE $table_name" );
}

add_shortcode( 'truncate_table', 'truncate_table_callback' );

/**
 * get_images_data
 *
 * @return void
 */
function get_images_data_callback() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_product_images';
    // $sql        = "SELECT * FROM $table_name";
    $sql    = "SELECT COUNT(*) FROM $table_name";
    $result = $wpdb->get_results( $sql );

    echo '<pre>';
    print_r( $result );
    echo '</pre>';
}

add_shortcode( 'get_images_data', 'get_images_data_callback' );