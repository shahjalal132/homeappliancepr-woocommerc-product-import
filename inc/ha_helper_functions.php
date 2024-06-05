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

/**
 * get pending products
 */
function get_pending_products_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_products';
    $sql        = "SELECT COUNT(*) FROM $table_name WHERE status = 'pending'";
    $result     = $wpdb->get_results( $sql );

    var_dump( $result );
}

add_shortcode( 'get_pending_products', 'get_pending_products_callback' );


/**
 * get completed product  to verify product image set successfully
 */
function get_completed_products_callback() {

    global $wpdb;
    $table_name_products = $wpdb->prefix . 'sync_products';
    $table_name_images   = $wpdb->prefix . 'sync_product_images';

    // SQL query
    $sql = "SELECT  p.id , p.product_code , p.product_data , p.status , i.product_images FROM $table_name_products p LEFT JOIN $table_name_images i ON p.product_code = i.product_code WHERE status = 'completed' limit 1";

    // Retrieve pending products from the database
    $products = $wpdb->get_results( $wpdb->prepare( $sql ) );

    echo '<pre>';
    print_r( $products );
    echo '</pre>';
}

add_shortcode( 'get_completed_products', 'get_completed_products_callback' );

/**
 * get attached image products
 */
function get_attached_images_callback() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'postmeta';
    $sql        = "SELECT COUNT(*) FROM $table_name wp WHERE wp.meta_key LIKE '%_thumbnail_id%'";
    $result     = $wpdb->get_results( $wpdb->prepare( $sql ) );
    echo '<pre>';
    print_r( $result );
    echo '</pre>';
}

add_shortcode( 'get_attached_image_products', 'get_attached_images_callback' );

function dd( $value ) {
    var_dump( $value );
    die();
}

function pd( $value ) {
    echo '<pre>';
    print_r( $value );
    die();
}

function get_product_images_by_product_code_callback() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_product_images';
    $sql        = "SELECT product_images FROM $table_name WHERE product_code = 10314";
    $result     = $wpdb->get_results( $wpdb->prepare( $sql ) );
    echo '<pre>';
    print_r( $result );
    echo '</pre>';
}

add_shortcode( 'get_product_images_by_product_code', 'get_product_images_by_product_code_callback' );


// update completed products status.
function update_pending_products_callback() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'sync_products';
    $wpdb->update(
        $table_name,
        array( 'status' => 'pending' ), // Data to update
        array( 'status' => 'completed' ) // WHERE clause
    );

}

add_shortcode( 'update_pending_products', 'update_pending_products_callback' );

// Update completed products status.
function update_pending_products_to_completed_callback() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'sync_products';
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE $table_name SET status = %s WHERE product_code != %d",
            'completed', 10314
        )
    );
}

add_shortcode( 'update_pending_products_completed', 'update_pending_products_to_completed_callback' );
