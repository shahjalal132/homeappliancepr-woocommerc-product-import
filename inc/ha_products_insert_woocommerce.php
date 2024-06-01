<?php

require_once HA_PLUGIN_PATH . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

// WordPress WooCommerce product insert shortcode function
function products_insert_woocommerce_callback() {
    ob_start();

    // Get global $wpdb object
    global $wpdb;

    // Define table names
    $table_name_products = $wpdb->prefix . 'sync_products';
    $table_name_images   = $wpdb->prefix . 'sync_product_images';

    // SQL query
    $sql = "SELECT  p.id , p.product_code , p.product_data , p.status , i.product_images FROM $table_name_products p LEFT JOIN $table_name_images i ON p.product_code = i.product_code WHERE status = 'pending' limit 1";

    // Retrieve pending products from the database
    $products = $wpdb->get_results( $wpdb->prepare( $sql ) );

    // Loop through each pending product
    foreach ( $products as $product ) {

        // extract product images
        $images = $product->product_images ?? '';
        $images = json_decode( $images, true );

        // Extract product details from the decoded data
        $product_data = json_decode( $product->product_data, true );

        //woocommerce store information
        $website_url     = home_url();
        $consumer_key    = 'ck_eac96df93ffb3c7465dec6b82ec9aa8c94d7bf03';
        $consumer_secret = 'cs_60723c6f2b6456a6b0b8a8172119ec554a282aed';

        // Extract product details from the decoded data
        $product_code    = isset( $product_data['ProductCode'] ) ? $product_data['ProductCode'] : '';
        $color           = isset( $product_data['Color'] ) ? $product_data['Color'] : '';
        $product_name    = isset( $product_data['ProductName'] ) ? $product_data['ProductName'] : '';
        $department_code = isset( $product_data['DepartmentCode'] ) ? $product_data['DepartmentCode'] : '';
        $department_name = isset( $product_data['DepartmentName'] ) ? $product_data['DepartmentName'] : '';
        $product_price   = isset( $product_data['StandardPrice'] ) ? $product_data['StandardPrice'] : '';
        $type_code       = isset( $product_data['TypeCode'] ) ? $product_data['TypeCode'] : '';
        $valuation_code  = isset( $product_data['ValuationCode'] ) ? $product_data['ValuationCode'] : '';
        $vendor_code     = isset( $product_data['VendorCode'] ) ? $product_data['VendorCode'] : '';
        $vendor_name     = isset( $product_data['VendorName'] ) ? $product_data['VendorName'] : '';
        $brand_name      = isset( $product_data['BrandName'] ) ? $product_data['BrandName'] : '';
        $model_code      = isset( $product_data['Model'] ) ? $product_data['Model'] : '';
        $description     = isset( $product_data['Description'] ) ? $product_data['Description'] : '';
        $weight          = isset( $product_data['NetWeight'] ) ? $product_data['NetWeight'] : '';
        $width           = isset( $product_data['Width'] ) ? $product_data['Width'] : '';
        $height          = isset( $product_data['Height'] ) ? $product_data['Height'] : '';
        $stock           = isset( $product_data['Quantity1'] ) ? $product_data['Quantity1'] : '';


        // Set up the API client with your WooCommerce store URL and credentials
        $client = new Client(
            $website_url,
            $consumer_key,
            $consumer_secret,
            [
                'verify_ssl' => false,
                'wp_api'     => true,
                'version'    => 'wc/v3',
                'timeout'    => 400,
            ]
        );

        // if sku already exists, update the product
        $args = array(
            'post_type'  => 'product',
            'meta_query' => array(
                array(
                    'key'     => '_sku',
                    'value'   => $product_code,
                    'compare' => '=',
                ),
            ),
        );

        // Check if the product already exists
        $existing_products = new WP_Query( $args );

        if ( $existing_products->have_posts() ) {
            $existing_products->the_post();

            // get product id
            $_product_id = get_the_ID();

            // Update the status of the processed product in your database
            $wpdb->update(
                $table_name_products,
                [ 'status' => 'completed' ],
                [ 'id' => $product->id ]
            );

            // Update the product
            $product_data = [
                'name'        => "$product_name",
                'sku'         => "$product_code",
                'type'        => 'simple',
                'description' => "$description",
                'attributes'  => [],
            ];

            // update product
            $client->put( 'products/' . $_product_id, $product_data );

            return "Product Updated";

        } else {

            // Create a new product
            $_product_data = [
                'name'        => "$product_name",
                'sku'         => "$product_code",
                'type'        => 'simple',
                'description' => "$description",
                'attributes'  => [],
            ];

            // Create the product
            $_product   = $client->post( 'products', $_product_data );
            $product_id = $_product->id;

            $wpdb->update(
                $table_name_products,
                [ 'status' => 'completed' ],
                [ 'id' => $product->id ]
            );

            // Update product meta data
            update_post_meta( $product_id, '_regular_price', $product_price );
            update_post_meta( $product_id, '_price', $product_price );

            // Set the short description
            $short_description =
                '<h3>Details</h3> <br>'
                . '<p>Brand : ' . $brand_name . '</p>'
                . '<p>Type : ' . $type_code . '</p>'
                . '<p>Color : ' . $color . '</p>'
                . '<br>' . '<br>'
                . '<h3>Dimensions</h3> <br>'
                . '<p>Width : ' . $width . '</p>'
                . '<p>Height : ' . $height . '</p>'
                . '<p>Weight : ' . $weight . '</p>'
            ;

            // Update the product
            $_args = array(
                'ID'           => $product_id,
                'post_excerpt' => $short_description,
            );

            wp_update_post( $_args );

            //Update product meta data in WordPress
            update_post_meta( $product_id, '_stock', $stock );

            //display out of stock message if stock is 0
            update_post_meta( $product_id, '_manage_stock', 'yes' );

            if ( $stock <= 0 ) {
                update_post_meta( $product_id, '_stock_status', 'outofstock' );
            } else {
                update_post_meta( $product_id, '_stock_status', 'instock' );
            }

            // Set product images
            if ( !empty( $images ) && is_array( $images ) ) {
                foreach ( $images as $image_url ) {

                    // Extract the image name from the URL
                    $image_name = basename( $image_url );

                    // Get WordPress upload directory
                    $upload_dir = wp_upload_dir();

                    // Download the image from the URL and save it to the upload directory
                    $image_data = file_get_contents( $image_url );

                    $image_file = $upload_dir['path'] . '/' . $image_name;
                    file_put_contents( $image_file, $image_data );

                    // Prepare image data to be attached to the product
                    $file_path = $upload_dir['path'] . '/' . $image_name;
                    $file_name = basename( $file_path );

                    // Define the attachment details
                    $attachment = [
                        'post_mime_type' => mime_content_type( $file_path ),
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_name ),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                    ];

                    // Insert the image as an attachment
                    $attach_id = wp_insert_attachment( $attachment, $file_path, $product_id );

                    // Add image to the product gallery
                    if ( $attach_id && !is_wp_error( $attach_id ) ) {

                        // Set the product image (thumbnail)
                        set_post_thumbnail( $product_id, $attach_id );

                        // Set gallery
                        $gallery_ids = get_post_meta( $product_id, '_product_image_gallery', true );
                        $gallery_ids = explode( ',', $gallery_ids );

                        // Add the new image to the existing gallery
                        $gallery_ids[] = $attach_id;

                        // Update the product gallery
                        update_post_meta( $product_id, '_product_image_gallery', implode( ',', $gallery_ids ) );
                    }
                }
            }

        }

        return 'product insert successfully';
    }

    return ob_get_clean();
}
add_shortcode( 'products_insert_woocommerce', 'products_insert_woocommerce_callback' );
