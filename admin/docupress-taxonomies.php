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
 * DocuPress Collections
 */
function docupress_collections_taxonomy() {

	$labels = array(
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
	);

	register_taxonomy( 'docupress_collections','docupress', array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'collections',
			'with_front' => false,
		),
	));

}
add_action( 'init', 'docupress_collections_taxonomy', 0 );
