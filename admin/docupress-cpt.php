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

if ( ! function_exists('DocuPress') ) {

// Register Custom Post Type
function DocuPress() {

	$labels = array(
		'name'                  => _x( 'Articles', 'Post Type General Name', 'docupress' ),
		'singular_name'         => _x( 'Article', 'Post Type Singular Name', 'docupress' ),
		'menu_name'             => __( 'Documentation', 'docupress' ),
		'name_admin_bar'        => __( 'Documentation', 'docupress' ),
		'archives'              => __( 'Article Archives', 'docupress' ),
		'attributes'            => __( 'Article Attributes', 'docupress' ),
		'parent_item_colon'     => __( 'Parent Article:', 'docupress' ),
		'all_items'             => __( 'All Articles', 'docupress' ),
		'add_new_item'          => __( 'Add New Article', 'docupress' ),
		'add_new'               => __( 'Add New', 'docupress' ),
		'new_item'              => __( 'New Article', 'docupress' ),
		'edit_item'             => __( 'Edit Article', 'docupress' ),
		'update_item'           => __( 'Update Article', 'docupress' ),
		'view_item'             => __( 'View Article', 'docupress' ),
		'view_items'            => __( 'View Articles', 'docupress' ),
		'search_items'          => __( 'Search Articles', 'docupress' ),
		'not_found'             => __( 'Not found', 'docupress' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'docupress' ),
		'featured_image'        => __( 'Featured Image', 'docupress' ),
		'set_featured_image'    => __( 'Set featured image', 'docupress' ),
		'remove_featured_image' => __( 'Remove featured image', 'docupress' ),
		'use_featured_image'    => __( 'Use as featured image', 'docupress' ),
		'insert_into_item'      => __( 'Insert into article', 'docupress' ),
		'uploaded_to_this_item' => __( 'Uploaded to this article', 'docupress' ),
		'items_list'            => __( 'Articles list', 'docupress' ),
		'items_list_navigation' => __( 'Articles list navigation', 'docupress' ),
		'filter_items_list'     => __( 'Filter articles list', 'docupress' ),
	);
	$rewrite = array(
		'slug'                  => 'article',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Article', 'docupress' ),
		'description'           => __( 'Documentation Articles', 'docupress' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'page-attributes', 'post-formats', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 15,
		'menu_icon'             => 'dashicons-media-text',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
		'rest_base'             => 'documentation',
		'rest_controller_class' => 'WP_REST_Articles_Controller',
	);
	register_post_type( 'docupress', $args );

}
add_action( 'init', 'DocuPress', 0 );

}