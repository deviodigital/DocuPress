<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://deviodigital.com
 * @since      1.0.0
 *
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <contact@deviodigital.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    wp_die();
}

/**
 * Registers the 'Collections' taxonomy for the DocuPress custom post type.
 *
 * This function creates a hierarchical taxonomy named 'Collections' 
 * to categorize DocuPress articles. It includes various labels for the 
 * WordPress admin UI and enables REST API support.
 *
 * Features:
 * - Hierarchical structure (similar to categories).
 * - Supports custom permalinks with the slug 'collections'.
 * - Displays in the WordPress admin menu and post editor.
 * - Enables REST API access for integration with external applications.
 *
 * Labels:
 * - Includes labels for managing collections in the WordPress admin.
 *
 * Hooks Registered:
 * - `init` (Action): Registers the taxonomy on WordPress initialization.
 *
 * @return void
 */
function docupress_collections_taxonomy() {

    $labels = [
        'name'              => _x( 'Collections', 'taxonomy general name', 'docupress' ),
        'singular_name'     => _x( 'Collection', 'taxonomy singular name', 'docupress' ),
        'search_items'      => esc_attr__( 'Search Collections', 'docupress' ),
        'all_items'         => esc_attr__( 'All Collections', 'docupress' ),
        'parent_item'       => esc_attr__( 'Parent Collection', 'docupress' ),
        'parent_item_colon' => esc_attr__( 'Parent Collection:', 'docupress' ),
        'edit_item'         => esc_attr__( 'Edit Collection', 'docupress' ),
        'update_item'       => esc_attr__( 'Update Collection', 'docupress' ),
        'add_new_item'      => esc_attr__( 'Add New Collection', 'docupress' ),
        'new_item_name'     => esc_attr__( 'New Collection Name', 'docupress' ),
        'not_found'         => esc_attr__( 'No categories found', 'docupress' ),
        'menu_name'         => esc_attr__( 'Collections', 'docupress' ),
    ];

    register_taxonomy( 'docupress_collections','docupress', [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'query_var'         => true,
        'rewrite'           => [
            'slug'       => 'collections',
            'with_front' => false,
        ],
    ] );

}
add_action( 'init', 'docupress_collections_taxonomy', 0 );
