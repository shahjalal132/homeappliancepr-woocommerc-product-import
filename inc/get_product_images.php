<?php

// Function to get product codes from the database
function get_product_codes() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_product_codes';

    // Get product codes from the table
    $product_codes_results = $wpdb->get_results( "SELECT product_code FROM $table_name LIMIT 10" );

    // Extract product codes into a simple array
    $product_codes = array_map( function ($item) {
        return $item->product_code;
    }, $product_codes_results );

    return $product_codes;
}
// add_shortcode('show_product_codes', 'get_product_codes');

// Function to get product images by code and insert them into the database
function get_product_image_by_code() {
    // Get product codes
    $product_codes = get_product_codes();

    // Base URL for images
    $base_url = "https://ha.aswspr.com/Documents/001/IM/%s/Images/%d.jpg";

    if ( !empty( $product_codes ) ) {
        foreach ( $product_codes as $product_code ) {
            // Array to hold valid image URLs
            $images          = [];
            $found_any_image = false;

            // Loop through possible image numbers (1 to 8)
            for ( $image_number = 1; $image_number <= 8; $image_number++ ) {
                // Construct the image URL
                $image_url = sprintf( $base_url, $product_code, $image_number );

                // Make a HEAD request to check if the image exists
                $response = wp_remote_head( $image_url );

                if ( !is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) == 200 ) {
                    // If status code is 200, the image exists, add it to the array
                    $images[]        = $image_url;
                    $found_any_image = true;
                    echo "Image found: $image_url<br>";
                } else {
                    // If the image does not exist
                    echo "Image not found: $image_url<br>";
                    break; // Break the loop if the image is not found
                }
            }

            // Insert qualified image paths into the database
            if ( $found_any_image ) {
                insert_product_images_db( $product_code, $images );
            } else {
                echo "No images found for product code: $product_code<br>";
            }
        }
    }
}

add_shortcode('show_product_image', 'get_product_image_by_code');

// Function to insert product images into the database
function insert_product_images_db( $product_code, $images ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_product_images';

    // Convert images array to JSON for storage
    $images_json = json_encode( $images );

    // Insert product code and images into the table
    $wpdb->insert(
        $table_name,
        [
            'product_code'   => $product_code,
            'product_images' => $images_json,
        ]
    );
}
