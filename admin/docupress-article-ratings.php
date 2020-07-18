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
	exit;
}

/**
 * Article ratings function.
 * 
 * This function is used to display the ratings buttons at the bottom of each
 * individual article.
 * 
 * @since 1.4.0
 */
function article_rating_getlink( $post_ID = '', $type_of_vote = '' ) {
    // Sanatize params.
    $post_ID      = intval( sanitize_text_field( $post_ID ) );
    $type_of_vote = intval ( sanitize_text_field( $type_of_vote ) );
    // Empty var.
    $article_rating_link = '';
    // Set the $post_ID var.
    if( $post_ID == '' ) $post_ID = get_the_ID();

    // Get the article ratings counts.
    $article_rating_up_count   = get_post_meta( $post_ID, '_docupress_article_rating_up', true ) != '' ? get_post_meta( $post_ID, '_docupress_article_rating_up', true ) : '0';
    $article_rating_down_count = get_post_meta( $post_ID, '_docupress_article_rating_down', true ) != '' ? get_post_meta( $post_ID, '_docupress_article_rating_down', true ) : '0';
    // Create ratings links.
    $link_up   = '<span class="article-rating-up" onclick="article_rating_vote(' . $post_ID . ', 1);" data-text="' . __( 'Vote Up', 'docupress' ) . ' +"><img src="' . plugin_dir_url( __FILE__ ) . '/images/mood-smile.svg" /></span>';
    $link_down = '<span class="article-rating-down" onclick="article_rating_vote(' . $post_ID . ', 2);" data-text="' . __( 'Vote Down', 'docupress' ) . ' -"><img src="' . plugin_dir_url( __FILE__ ) . '/images/mood-sad.svg" /></span>';
    // Article ratings content.
    $article_rating_link  = '<div  class="article-rating-container" id="article-rating-' . $post_ID . '" data-content-id="' . $post_ID . '">';
    $article_rating_link .= '<p class="article-rating-title">Was this article helpful?</p>';
    $article_rating_link .= $link_up . ' ' . $link_down;
    $article_rating_link .= '<span class="article-rating-already-voted" data-text="' . __( 'You already voted!', 'docupress' ) . '"></span>';
    $article_rating_link .= '</div>';

    return $article_rating_link;
}

/**
 * Article rating AJAX callback
 * 
 * This function handles the AJAX request to save the vote
 * 
 * @since 1.4.0
 */
function article_rating_add_vote_callback() {
    // Check the nonce - security.
    check_ajax_referer( 'article-rating-nonce', 'nonce' );

    global $wpdb;

    // Get the POST values.
    $post_ID      = intval( $_POST['postid'] );
    $type_of_vote = intval( $_POST['type'] );

    // Check the type and retrieve the meta values.
    if ( 1 == $type_of_vote ) {
        $meta_name = "_docupress_article_rating_up";
    } elseif ( 2 == $type_of_vote ) {
        $meta_name = "_docupress_article_rating_down";
    }

    // Retrieve the meta value from the DB.
    $article_rating_count = get_post_meta( $post_ID, $meta_name, true ) != '' ? get_post_meta( $post_ID, $meta_name, true ) : '0';
    $article_rating_count = $article_rating_count + 1;

    // Update the meta value
    update_post_meta( $post_ID, $meta_name, $article_rating_count );

    $results = article_rating_getlink( $post_ID, $type_of_vote );

    die( $results );
}

add_action( 'wp_ajax_article_rating_add_vote', 'article_rating_add_vote_callback' );
add_action( 'wp_ajax_nopriv_article_rating_add_vote', 'article_rating_add_vote_callback' );

/**
 * Admin column ratings display
 * 
 * Add the rating for each article in the admin dashboard.
 * 
 * @since 1.4.0
 */
function article_rating_columns( $columns ) {
    return array_merge( $columns, array(
        'article_rating_up_count'   => __( 'Up Votes', 'docupress' ),
        'article_rating_down_count' => __( 'Down Votes', 'docupress' )
    ) );
}
add_filter( 'manage_pages_columns' , 'article_rating_columns' );

/**
 * Add content to admin columns
 * 
 * Get the vote data for each article and display it in our new column
 * 
 * @since 1.4.0
 */
function article_rating_column_values( $column, $post_id ) {
    switch ( $column ) {
    case 'article_rating_up_count' :
           echo get_post_meta( $post_id, '_docupress_article_rating_up', true ) != '' ? '+' . get_post_meta( $post_id, '_docupress_article_rating_up', true ) : '0';
       break;
    case 'article_rating_down_count' :
          echo get_post_meta( $post_id, '_docupress_article_rating_down', true ) != '' ? '-' . get_post_meta( $post_id, '_docupress_article_rating_down', true ) : '0';
        break;
    }
}
add_action( 'manage_posts_custom_column' , 'article_rating_column_values', 10, 2 );

function article_rating_sortable_columns( $columns )
{
    $columns[ 'article_rating_up_count' ] = 'article_rating_up_count';
    $columns[ 'article_rating_down_count' ] = 'article_rating_down_count';
    return $columns;
}


// Apply this to the articles CPT.
function article_rating_sort_articles() {
    add_action( 'manage_edit-docupress_sortable_columns', 'article_rating_sortable_columns' );
    add_filter( 'request', 'article_rating_column_sort_orderby' );
}
add_action( 'admin_init', 'article_rating_sort_articles' );

/**
 * Sort admin column by number
 * 
 * This function tells WordPress that our article rating column should be sortable 
 * by numeric value
 * 
 * @since 1.4.0
 */
function article_rating_column_sort_orderby( $vars ) {

    if ( isset( $vars['orderby'] ) && 'article_rating_up_count' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => '_docupress_article_rating_up',
            'orderby'  => 'meta_value_num'
        ) );
    }
    if ( isset( $vars['orderby'] ) && 'article_rating_down_count' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => '_docupress_article_rating_down',
            'orderby'  => 'meta_value_num'
        ) );
    }
    return $vars;
}

/**
 * Add ratings to bottom of content
 * 
 * This function adds the ratings function after the_content is displayed
 * for individual articles
 * 
 * @since 1.4.0
 */
function article_rating_print( $content ) {
    if ( is_singular( 'docupress' ) ) {
        // Append ratings to the content.
        return $content . article_rating_getlink();
    } else {
        // Do nothing.
    }
}
add_filter( 'the_content', 'article_rating_print' );
