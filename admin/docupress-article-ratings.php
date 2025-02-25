<?php

/**
 * The article ratings functionality of the plugin
 *
 * @link       https://deviodigital.com
 * @since      1.4.0
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
 * Article ratings function.
 * 
 * This function is used to display the ratings buttons at the bottom of each
 * individual article.
 * 
 * @since  1.4.0
 * @return void
 */
function docupress_article_rating_display( $post_ID = '', $type_of_vote = '' ) {
    // Sanatize params.
    $post_ID      = intval( sanitize_text_field( $post_ID ) );
    $type_of_vote = intval( sanitize_text_field( $type_of_vote ) );

    // Empty var.
    $content = '';

    // Set the $post_ID var.
    if ( '' == $post_ID ) $post_ID = get_the_ID();

    // Create ratings links.
    $face_smile = '<span class="article-rating-smile" onclick="DocuPressVote(' . $post_ID . ', 1);" data-text="' . esc_attr__( 'Vote Up', 'docupress' ) . ' +"><img src="' . plugin_dir_url( __FILE__ ) . '/images/mood-smile.svg" /></span>';
    $face_frown = '<span class="article-rating-frown" onclick="DocuPressVote(' . $post_ID . ', 2);" data-text="' . esc_attr__( 'Vote Down', 'docupress' ) . ' -"><img src="' . plugin_dir_url( __FILE__ ) . '/images/mood-sad.svg" /></span>';

    // Article ratings content.
    $content  = '<div  class="article-rating-container" id="article-rating-' . $post_ID . '" data-content-id="' . $post_ID . '">';
    $content .= '<p class="article-rating-title">' . esc_attr__( 'Was this article helpful?', 'docupress' ) . '</p>';
    $content .= $face_smile . ' ' . $face_frown;
    $content .= '</div>';

    return $content;
}

/**
 * Article rating AJAX callback
 * 
 * This function handles the AJAX request to save the vote
 * 
 * @since 1.4.0
 */
function docupress_article_rating_add_vote() {
    // Check the nonce - security.
    check_ajax_referer( 'docupress-article-rating-nonce', 'nonce' );

    // Get the POST values.
    $post_ID      = intval( filter_input( INPUT_POST, 'postid' ) );
    $type_of_vote = intval( filter_input( INPUT_POST, 'type' ) );

    // Check the type and retrieve the meta values.
    if ( 1 == $type_of_vote ) {
        $meta_name = 'docupress_article_smile';
    } elseif ( 2 == $type_of_vote ) {
        $meta_name = 'docupress_article_frown';
    } else {
        $meta_name = '';
    }

    // Retrieve the meta value from the DB.
    $article_rating_count = '' != get_post_meta( $post_ID, $meta_name, true ) ? get_post_meta( $post_ID, $meta_name, true ) : '0';
    $article_rating_count = $article_rating_count + 1;

    // Update the meta value.
    update_post_meta( $post_ID, $meta_name, $article_rating_count );

    // Get results.
    $results = docupress_article_rating_display( $post_ID, $type_of_vote );

    die( $results );
}
add_action( 'wp_ajax_docupress_article_rating_add_vote', 'docupress_article_rating_add_vote' );
add_action( 'wp_ajax_nopriv_docupress_article_rating_add_vote', 'docupress_article_rating_add_vote' );

/**
 * Admin column ratings display
 * 
 * Add the rating for each article in the admin dashboard.
 * 
 * @since 1.4.0
 */
function docupress_article_rating_columns( $columns ) {
    return array_merge( $columns, [
        'article_smile_count' => esc_attr__( 'Up Votes', 'docupress' ),
        'article_frown_count' => esc_attr__( 'Down Votes', 'docupress' )
    ] );
}
add_filter( 'manage_docupress_posts_columns' , 'docupress_article_rating_columns' );

/**
 * Add content to admin columns
 * 
 * Get the vote data for each article and display it in our new column
 * 
 * @since  1.4.0
 * @return void
 */
function docupress_article_rating_column_values( $column, $post_id ) {
    switch ( $column ) {
    case 'article_smile_count' :
        $face_count = get_post_meta( $post_id, 'docupress_article_smile', true ) != '' ? '+' . get_post_meta( $post_id, 'docupress_article_smile', true ) : '0';
        break;
    case 'article_frown_count' :
        $face_count = get_post_meta( $post_id, 'docupress_article_frown', true ) != '' ? '-' . get_post_meta( $post_id, 'docupress_article_frown', true ) : '0';
        break;
    }
    esc_attr_e( $face_count );
}
add_action( 'manage_docupress_posts_custom_column' , 'docupress_article_rating_column_values', 10, 2 );

/**
 * Sortable columns
 * 
 * @since  1.4.0
 * @return void
 */
function docupress_article_rating_sortable_columns( $columns ) {
    $columns[ 'article_smile_count' ] = 'article_smile_count';
    $columns[ 'article_frown_count' ] = 'article_frown_count';
    return $columns;
}

/**
 * Sort admin column by number
 * 
 * This function tells WordPress that our article rating column should be sortable 
 * by numeric value
 * 
 * @since  1.4.0
 * @return void
 */
function docupress_article_rating_column_sort_orderby( $vars ) {
    // Smile count.
    if ( isset( $vars['orderby'] ) && 'docupress_article_smile_count' == $vars['orderby'] ) {
        $vars = array_merge( $vars, [
            'meta_key' => 'docupress_article_smile',
            'orderby'  => 'meta_value_num'
        ] );
    }
    // Frown count.
    if ( isset( $vars['orderby'] ) && 'docupress_article_frown_count' == $vars['orderby'] ) {
        $vars = array_merge( $vars, [
            'meta_key' => 'docupress_article_frown',
            'orderby'  => 'meta_value_num'
        ] );
    }
    return $vars;
}

/**
 * Registers sortable columns for the DocuPress custom post type.
 *
 * This function hooks into WordPress admin to make the article rating column sortable
 * in the DocuPress post type list table. It registers the sorting mechanism
 * by adding the necessary action and filter hooks.
 *
 * Hooks Registered:
 * - `manage_edit-docupress_sortable_columns` (Action): Adds sortable columns.
 * - `request` (Filter): Modifies the sorting order for the column.
 *
 * @since  1.4.0
 * @return void
 */
function docupress_article_rating_sort_articles() {
    add_action( 'manage_edit-docupress_sortable_columns', 'docupress_article_rating_sortable_columns' );
    add_filter( 'request', 'docupress_article_rating_column_sort_orderby' );
}
add_action( 'admin_init', 'docupress_article_rating_sort_articles' );

/**
 * Add ratings to bottom of content
 * 
 * This function adds the ratings function after the_content is displayed
 * for individual articles
 * 
 * @since  1.4.0
 * @return mixed
 */
function docupress_article_rating_print( $content ) {
    // Check if this is a singular 'docupress' post type and if we're in the main loop.
    if ( is_singular( 'docupress' ) && in_the_loop() && is_main_query() ) {
        // Append ratings to the content.
        return $content . docupress_article_rating_display();
    }
    // Default content.    
    return $content;
}
add_filter( 'the_content', 'docupress_article_rating_print' );
