<?php
require_once HA_PLUGIN_PATH . '/vendor/autoload.php';

use \JsonMachine\Items;

function aswspr_product_db()
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ha.aswspr.com/API/ghPR.ashx',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'tkn=71C0BEF4-BF21-4CDC-99E8-EBB9507A5E05&cmd=get&com=001&tbl=IM_Products_Products_Per_Warehouse_View&crs=LIKE&cdn=AND&rst=jso',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

// Insert products to database
function insert_products_to_db_callback()
{
    ob_start();

    $api_response = aswspr_product_db();

    $file_path = __DIR__ . '/uploads/api_data.json';
    $items = Items::fromFile($file_path);

    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_products';
    // $wpdb->query( "TRUNCATE TABLE $table_name" );

    // Insert to database
    foreach ( $items as $item ) {

        // convert to json
        $json_data = json_encode($item);

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

add_shortcode('insert_product_api', 'insert_products_to_db_callback');
