<?php

require_once HA_PLUGIN_PATH . '/vendor/autoload.php';

use \JsonMachine\Items;

// TRUNCATE Table
function truncate_table( $table_name ) {

    global $wpdb;
    $wpdb->query( "TRUNCATE TABLE $table_name" );

}

function fetch_data_from_api() {

    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL            => 'https://ha.aswspr.com/API/ghPR.ashx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_POSTFIELDS     => 'tkn=B47C96DB-8855-4A18-ACDE-925249B6DDEF&cmd=get&com=001&tbl=IM_Products_Products_Per_Warehouse_View&crs=LIKE&cdn=AND&rst=jso&s1=ProductCode&v1=&s2=Web&v2=false&p1=StandardPrice&c1=%3E&f1=0&p2=QualityOnHand&c2=%3E&f2=&WEB=1',
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        )
    );

    $response = curl_exec( $curl );

    curl_close( $curl );
    return $response;
}

// Insert products to database
function insert_products_to_db_callback() {

    ob_start();

    // File path
    $file_path = __DIR__ . '/uploads/api_data.json';

    // get api response
    // $api_response = fetch_data_from_api();
    // file_put_contents( $file_path, $api_response );

    // get items from file
    $items = Items::fromFile( $file_path );

    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_products';
    truncate_table( $table_name );

    // Insert to database
    foreach ( $items as $item ) {

        // convert to json
        $json_data = json_encode( $item );

        $wpdb->insert(
            $table_name,
            [
                'operation_type'  => 'product_create',
                'operation_value' => $json_data,
                'status'          => 'pending',
            ]
        );
    }

    echo '<h4>Products inserted successfully</h4>';

    return ob_get_clean();
}

add_shortcode( 'insert_product_api', 'insert_products_to_db_callback' );


// insert product codes to db
function insert_product_codes_db() {

    ob_start();

    // File path
    $file_path = __DIR__ . '/uploads/api_data.json';

    $products = file_get_contents( $file_path );
    $products = json_decode( $products, true );

    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_product_codes';
    truncate_table( $table_name );

    if ( !empty( $products ) ) {
        foreach ( $products as $product ) {

            // extract product code
            $product_code = $product['ProductCode'];

            // insert product code to database
            $wpdb->insert(
                $table_name,
                [
                    'product_code' => $product_code,
                ]
            );
        }
    }

    return "Product codes inserted successfully";

}