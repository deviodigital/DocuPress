<?php

/**
 * The estimated reading time functionality of the plugin.
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
 * Add estimated reading time to the content.
 * 
 * @return string
 */
function docupress_the_content_estimated_reading_time( $content ) {
    global $post;
    // Check if this is a DocuPress article & make sure the user hasn't turned off the estimated reading time for this post.
    if ( is_singular( 'docupress' ) && 'hide_estimated_reading' != get_post_meta( $post->ID, 'docupress_article_estimated_reading_display', true ) ) {
        return docupress_estimated_reading_time( $post->ID ) . $content;
    }

    // Default content.
    return $content;
}
add_filter( 'the_content', 'docupress_the_content_estimated_reading_time' );
