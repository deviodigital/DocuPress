<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.robertdevore.com/
 * @since      1.0.0
 *
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <deviodigital@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'DocuPress_Collections', 0 );

/**
 * DocuPress Collections
 */
function DocuPress_Collections() {

	  $labels = array(
		'name'              => _x( 'Collections', 'taxonomy general name' ),
		'singular_name'     => _x( 'Collection', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Collections' ),
		'all_items'         => __( 'All Collections' ),
		'parent_item'       => __( 'Parent Collection' ),
		'parent_item_colon' => __( 'Parent Collection:' ),
		'edit_item'         => __( 'Edit Collection' ),
		'update_item'       => __( 'Update Collection' ),
		'add_new_item'      => __( 'Add New Collection' ),
		'new_item_name'     => __( 'New Collection Name' ),
		'not_found'         => 'No categories found',
		'menu_name'         => __( 'Collections' ),
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

?>