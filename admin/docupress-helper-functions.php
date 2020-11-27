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
 * @param int $id - the post ID we're getting the estimated reading time for
 * 
 * @since 1.4.0
 */
function docupress_estimated_reading_time( $id = '' ) {
    // Verify ID is present.
    if ( ! $id ) { return; }
    // Get the post.
    $the_post = get_post( $id );
    // Content.
    $my_content = apply_filters( 'docupress_estimated_reading_content', $the_post->post_content );
    // Words.
    $words_count = str_word_count( strip_tags( $my_content ) );
    // Min words.
    $min_words = apply_filters( 'docupress_estimated_reading_min_words', 200 );
    // Minutes.
    $minutes = floor( $words_count / $min_words );
    $min     = $minutes . ' minute' . ( $minutes == 1 ? '' : 's' ) . ', ';
    // Minutes (empty if zero).
    if ( 0 == $minutes ) {
        $min = '';
    }
    // Seconds.
    $seconds = floor( $words_count % $min_words / ( $min_words / 60 ) );
    $sec     = $seconds . ' second' . ( $seconds == 1 ? '' : 's' );
    // Estimated Reading.
    $estimated_reading = '<p class="docupress-est-reading">' . apply_filters( 'docupress_estimated_reading_prefix', '' ) . apply_filters( 'docupress_estimated_reading_time_display', $min . $sec, $min, $sec ) . '</p>';

    return $estimated_reading;
}
