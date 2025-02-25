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
    wp_die();
}

/**
 * Estimated reading time
 * 
 * This function adds an estimated reading time to the top of articles
 * 
 * @param  int $post_id - the post ID we're getting the estimated reading time for
 * 
 * @since  1.4.0
 * @return mixed
 */
function docupress_estimated_reading_time( $post_id   = '' ) {
    // Verify ID is present.
    if ( ! $post_id   ) { return; }
    // Get the post.
    $the_post = get_post( $post_id   );
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

/**
 * Render callback for the DocuPress Articles block.
 *
 * @param array $attributes Block attributes.
 *
 * @since 2.1.0
 * @return string HTML output.
 */
function docupress_articles_render( $attributes ) {
	$post_count          = ! empty( $attributes['postCount'] ) ? absint( $attributes['postCount'] ) : 6;
	$display_style       = ! empty( $attributes['displayStyle'] ) ? sanitize_text_field( $attributes['displayStyle'] ) : 'grid';
	$show_featured_image = isset( $attributes['showFeaturedImage'] ) ? (bool) $attributes['showFeaturedImage'] : true;
	$show_excerpt        = isset( $attributes['showExcerpt'] ) ? (bool) $attributes['showExcerpt'] : true;
	$collection          = ! empty( $attributes['collection'] ) ? sanitize_text_field( $attributes['collection'] ) : '';

	$args = [
		'post_type'      => 'docupress',
		'posts_per_page' => $post_count,
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	if ( $collection ) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'docupress_collections',
				'field'    => 'slug',
				'terms'    => $collection,
			],
		];
	}

	$query  = new WP_Query( $args );
	$output = '';

	if ( 'grid' === $display_style ) {
		$output .= '<div class="docupress-block-grid">';
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$output .= '<div>';
				if ( $show_featured_image && has_post_thumbnail() ) {
					// Use a 300x300 image instead of the default thumbnail.
					$output .= get_the_post_thumbnail( get_the_ID(), 'docupress-grid' );
				}
				$output .= '<div class="list-content">';
				$output .= '<h3><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h3>';
				if ( $show_excerpt ) {
					$output .= '<p>' . get_the_excerpt() . '</p>';
				}
				$output .= '</div>';
				$output .= '</div>';
			}
			wp_reset_postdata();
		} else {
			$output .= esc_html__( 'No articles found.', 'docupress' );
		}
		$output .= '</div>';
	} elseif ( 'list' === $display_style ) {
		$output .= '<div class="docupress-block-list">';
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$output .= '<div class="docupress-block-post">';
				if ( $show_featured_image && has_post_thumbnail() ) {
					$output .= get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
				}
				$output .= '<div class="docupress-block-item">';
				$output .= '<h3><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h3>';
				if ( $show_excerpt ) {
					$output .= '<p>' . get_the_excerpt() . '</p>';
				}
				$output .= '</div>';
				$output .= '</div>';
			}
			wp_reset_postdata();
		} else {
			$output .= esc_html__( 'No articles found.', 'docupress' );
		}
		$output .= '</div>';
	} else {
		$output .= '<div class="docupress-block-grid">';
		$output .= esc_html__( 'Invalid display style specified.', 'docupress' );
		$output .= '</div>';
	}

	return $output;
}

/**
 * Registers the DocuPress Articles block with a render callback.
 */
function docupress_register_articles_block() {
    add_image_size( 'docupress-grid', 420, 236, true );

	register_block_type( 'docupress/articles', [
		'render_callback' => 'docupress_articles_render',
		'attributes'      => [
			'postCount'         => [
				'type'    => 'number',
				'default' => 6,
			],
			'displayStyle'      => [
				'type'    => 'string',
				'default' => 'grid',
			],
			'showFeaturedImage' => [
				'type'    => 'boolean',
				'default' => true,
			],
			'showExcerpt'       => [
				'type'    => 'boolean',
				'default' => true,
			],
			'collection'        => [
				'type'    => 'string',
				'default' => '',
			],
		],
	] );
}
add_action( 'init', 'docupress_register_articles_block' );
