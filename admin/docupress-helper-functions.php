<?php

/**
 * The helper functions used throughout the plugin.
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
 * Estimated reading time
 * 
 * This function adds an estimated reading time to the top of articles
 * 
 * @since 1.4.0
 * 
 * @todo rearrange the $m and $s variables so the wording (minutes/seconds) is it's 
 * own var name, so I can apply a filter to it for people to change as needed
 */
function docupress_estimated_reading_time( $content ) {
    global $post;
    $mycontent = $post->post_content;
    $word      = str_word_count( strip_tags( $mycontent ) );
    $m         = floor( $word / apply_filters( 'docupress_estimated_reading_min_words', 200 ) );
    $min       = $m . ' minute' . ( $m == 1 ? '' : 's' );
    $s         = floor( $word % apply_filters( 'docupress_estimated_reading_min_words', 200 ) / ( apply_filters( 'docupress_estimated_reading_min_words', 200 ) / 60 ) );
    $sec       = $s . ' second' . ( $s == 1 ? '' : 's' );
    $est       = '<p class="docupress-est-reading">' . apply_filters( 'docupress_estimated_reading_prefix', '' ) . apply_filters( 'docupress_estimated_reading_time_display', $min . ', '  . $sec, $min, $sec ) . '</p>';

    // Check if this is a DocuPress article.
    if ( is_singular( 'docupress' ) ) {
        return $est . $content;
    } else {
        // Do nothing.
        return $content;
    }
}
add_filter( 'the_content', 'docupress_estimated_reading_time' );
