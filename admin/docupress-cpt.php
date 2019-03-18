<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.robertdevore.com/
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

if ( ! function_exists( 'docupress_cpt' ) ) {

// Register Custom Post Type
function docupress_cpt() {

	// Get permalink base for Articles.
	$docupress_article_slug = get_option( 'docupress_article_slug' );

	// If custom base is empty, set default.
	if ( '' == $docupress_article_slug ) {
		$docupress_article_slug = 'articles';
	}

	// Capitalize first letter of new slug.
	$docupress_article_slug_cap = ucfirst( $docupress_article_slug );

	$labels = array(
		'name'                  => sprintf( esc_html__( '%s', 'Post Type General Name', 'docupress' ), $docupress_article_slug_cap ),
		'singular_name'         => sprintf( esc_html__( '%s', 'Post Type Singular Name', 'docupress' ), $docupress_article_slug_cap ),
		'menu_name'             => esc_html__( 'Documentation', 'docupress' ),
		'name_admin_bar'        => sprintf( esc_html__( '%s', 'docupress' ), $docupress_article_slug_cap ),
		'archives'              => sprintf( esc_html__( '%s Archives', 'docupress' ), $docupress_article_slug_cap ),
		'parent_item_colon'     => sprintf( esc_html__( 'Parent %s:', 'docupress' ), $docupress_article_slug_cap ),
		'all_items'             => sprintf( esc_html__( 'All %s', 'docupress' ), $docupress_article_slug_cap ),
		'add_new_item'          => sprintf( esc_html__( 'Add New %s', 'docupress' ), $docupress_article_slug_cap ),
		'add_new'               => __( 'Add New', 'docupress' ),
		'new_item'              => sprintf( esc_html__( 'New %s', 'docupress' ), $docupress_article_slug_cap ),
		'edit_item'             => sprintf( esc_html__( 'Edit %s', 'docupress' ), $docupress_article_slug_cap ),
		'update_item'           => sprintf( esc_html__( 'Update %s', 'docupress' ), $docupress_article_slug_cap ),
		'view_item'             => sprintf( esc_html__( 'View %s', 'docupress' ), $docupress_article_slug_cap ),
		'search_items'          => sprintf( esc_html__( 'Search %s', 'docupress' ), $docupress_article_slug_cap ),
		'not_found'             => __( 'Not found', 'docupress' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'docupress' ),
		'featured_image'        => __( 'Featured Image', 'docupress' ),
		'set_featured_image'    => __( 'Set featured image', 'docupress' ),
		'remove_featured_image' => __( 'Remove featured image', 'docupress' ),
		'use_featured_image'    => __( 'Use as featured image', 'docupress' ),
		'insert_into_item'      => sprintf( esc_html__( 'Insert into %s', 'docupress' ), $docupress_article_slug_cap ),
		'uploaded_to_this_item' => sprintf( esc_html__( 'Uploaded to this %s', 'docupress' ), $docupress_article_slug_cap ),
		'items_list'            => sprintf( esc_html__( '%s list', 'docupress' ), $docupress_article_slug_cap ),
		'items_list_navigation' => sprintf( esc_html__( '%s list navigation', 'docupress' ), $docupress_article_slug_cap ),
		'filter_items_list'     => sprintf( esc_html__( 'Filter %s list', 'docupress' ), $docupress_article_slug ),
	);

	$rewrite = array(
		'slug'       => $docupress_article_slug,
		'with_front' => true,
		'pages'      => true,
		'feeds'      => true,
	);

	$args = array(
		'label'                 => sprintf( esc_html__( '%s', 'docupress' ), $docupress_article_slug_cap ),
		'description'           => esc_html__( 'Documentation', 'docupress' ),
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
	);
	register_post_type( 'docupress', $args );

}
add_action( 'init', 'docupress_cpt', 0 );

}
