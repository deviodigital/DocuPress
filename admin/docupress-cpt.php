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

if ( ! function_exists( 'docupress_cpt' ) ) {
    /**
     * Registers the DocuPress custom post type.
     *
     * This function creates a custom post type for documentation articles, 
     * allowing users to manage structured content within WordPress. The post type 
     * is configured with various labels, capabilities, and permalink settings.
     *
     * Features:
     * - Uses a customizable permalink base (default: "articles").
     * - Supports key post features like title, editor, excerpt, author, and more.
     * - Includes hierarchical and archive settings for structured navigation.
     * - Enables REST API support for integration with external services.
     * - Adds a menu item in the WordPress admin dashboard.
     *
     * Hooks Registered:
     * - `init` (Action): Registers the custom post type during initialization.
     *
     * @return void
     */
    function docupress_cpt() {

        // Get permalink base for Articles.
        $article_slug = get_option( 'docupress_article_slug' );

        // If custom base is empty, set default.
        if ( '' == $article_slug ) {
            $article_slug = 'articles';
        }

        // Capitalize first letter of new slug.
        $article_slug_cap = ucfirst( $article_slug );

        $labels = [
            'name'                  => sprintf( esc_html__( '%s', 'Post Type General Name', 'docupress' ), $article_slug_cap ),
            'singular_name'         => sprintf( esc_html__( '%s', 'Post Type Singular Name', 'docupress' ), $article_slug_cap ),
            'menu_name'             => esc_html__( 'Documentation', 'docupress' ),
            'name_admin_bar'        => sprintf( esc_html__( '%s', 'docupress' ), $article_slug_cap ),
            'archives'              => sprintf( esc_html__( '%s Archives', 'docupress' ), $article_slug_cap ),
            'parent_item_colon'     => sprintf( esc_html__( 'Parent %s:', 'docupress' ), $article_slug_cap ),
            'all_items'             => sprintf( esc_html__( 'All %s', 'docupress' ), $article_slug_cap ),
            'add_new_item'          => sprintf( esc_html__( 'Add New %s', 'docupress' ), $article_slug_cap ),
            'add_new'               => esc_html__( 'Add New', 'docupress' ),
            'new_item'              => sprintf( esc_html__( 'New %s', 'docupress' ), $article_slug_cap ),
            'edit_item'             => sprintf( esc_html__( 'Edit %s', 'docupress' ), $article_slug_cap ),
            'update_item'           => sprintf( esc_html__( 'Update %s', 'docupress' ), $article_slug_cap ),
            'view_item'             => sprintf( esc_html__( 'View %s', 'docupress' ), $article_slug_cap ),
            'search_items'          => sprintf( esc_html__( 'Search %s', 'docupress' ), $article_slug_cap ),
            'not_found'             => esc_html__( 'Not found', 'docupress' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'docupress' ),
            'featured_image'        => esc_html__( 'Featured Image', 'docupress' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'docupress' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'docupress' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'docupress' ),
            'insert_into_item'      => sprintf( esc_html__( 'Insert into %s', 'docupress' ), $article_slug_cap ),
            'uploaded_to_this_item' => sprintf( esc_html__( 'Uploaded to this %s', 'docupress' ), $article_slug_cap ),
            'items_list'            => sprintf( esc_html__( '%s list', 'docupress' ), $article_slug_cap ),
            'items_list_navigation' => sprintf( esc_html__( '%s list navigation', 'docupress' ), $article_slug_cap ),
            'filter_items_list'     => sprintf( esc_html__( 'Filter %s list', 'docupress' ), $article_slug ),
        ];

        $rewrite = [
            'slug'       => $article_slug,
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        ];

        $args = [
            'label'               => sprintf( esc_html__( '%s', 'docupress' ), $article_slug_cap ),
            'description'         => esc_html__( 'Documentation', 'docupress' ),
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'page-attributes', 'post-formats' ],
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 15,
            'menu_icon'           => 'dashicons-media-text',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,        
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
        ];
        register_post_type( 'docupress', $args );
    }
    add_action( 'init', 'docupress_cpt', 0 );

}
