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
	exit;
}

/**
 * DocuPress Collections
 */
function docupress_collections_taxonomy() {

	$labels = array(
		'name'              => _x( 'Collections', 'taxonomy general name', 'docupress' ),
		'singular_name'     => _x( 'Collection', 'taxonomy singular name', 'docupress' ),
		'search_items'      => __( 'Search Collections', 'docupress' ),
		'all_items'         => __( 'All Collections', 'docupress' ),
		'parent_item'       => __( 'Parent Collection', 'docupress' ),
		'parent_item_colon' => __( 'Parent Collection:', 'docupress' ),
		'edit_item'         => __( 'Edit Collection', 'docupress' ),
		'update_item'       => __( 'Update Collection', 'docupress' ),
		'add_new_item'      => __( 'Add New Collection', 'docupress' ),
		'new_item_name'     => __( 'New Collection Name', 'docupress' ),
		'not_found'         => __( 'No categories found', 'docupress' ),
		'menu_name'         => __( 'Collections', 'docupress' ),
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
