<?php

/*
 * Plugin Name:       Aswspr Product API
 * Plugin URI:        https://ha.aswspr.com
 * Description:       aswspr product api
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Imjol
 * Author URI:        https://imjol.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 */

if ( !defined( 'WPINC' ) ) {
    die;
}

// Define plugin path
if ( !defined( 'HA_PLUGIN_PATH' ) ) {
    define( 'HA_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Define plugin uri
if ( !defined( 'HA_PLUGIN_PATH' ) ) {
    define( 'HA_PLUGIN_PATH', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

// Create wp_sync_products db table when plugin activate
register_activation_hook( __FILE__, 'ha_aswspr_products_table_create' );

// Remove wp_sync_products db table when plugin deactivate
register_deactivation_hook( __FILE__, 'ha_aswspr_products_table_remove' );



// Including requirements files
require_once HA_PLUGIN_PATH . '/inc/ha_aswspr_product_db.php';
require_once HA_PLUGIN_PATH . '/inc/aswspr_add_products_insert_db.php';
require_once HA_PLUGIN_PATH . '/inc/ha_products_insert_woocommerce.php';
require_once HA_PLUGIN_PATH . '/inc/shortcode_api.php';
