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
	exit;
}

// Add Shortcode
function docupress_shortcode( $atts ) {

    // Attributes.
	extract( shortcode_atts(
		array(
            'limit'       => '5',
            'collections' => 'all',
            'order'       => '',
            'viewall'     => 'on',
		),
		$atts,
		'docupress'
	) );
    
    if ( 'all' === $collections ) {
        // Args.
        $args = array(
            'post_type' => 'docupress',
            'showposts' => $limit,
            'orderby'   => $order,
        );
        // Filter args.
        $args = apply_filters( 'docupress_shortcode_query_args', $args );
    } else {
        // Args.
        $args = array(
            'post_type' => 'docupress',
            'showposts' => $limit,
            'orderby'   => $order,
            'tax_query' => array(
                array(
                    'taxonomy' => 'docupress_collections',
                    'field'    => 'slug',
                    'terms'    => $collections
                ),
            ),
        );
        // Filter args.
        $args = apply_filters( 'docupress_shortcode_query_args_collections', $args );
    }

    // Get results.
    $docupress_articles = new WP_Query( $args );

    // Display message if no articles are found.
    if ( ! $docupress_articles->have_posts() ) {
        return '<p class="docupress-shortcode-empty">' . __( 'No articles found', 'docupress' ) . '</p>';
        exit;
    }

    // Check if articles exist.
    if ( $docupress_articles->have_posts() ) {

            // Create UL for articles.
            $docupress_list = '<ul class="docupress-shortcode-list">';

            while ( $docupress_articles->have_posts() ) : $docupress_articles->the_post();
                // Loop through the articles.
                $docupress_list .= '<li>';
                $docupress_list .= '<a href="' . get_the_permalink( $docupress_articles->ID ) . '" class="docupress-shortcode-link">' . get_the_title( $docupress_articles->ID ) . '</a>';
                $docupress_list .= '</li>';
            endwhile;

            // Website link.
            if ( 'all' !== $collections && 'on' === $viewall ) {
                // URL for collections link.
                $collections_url = apply_filters( 'docupress_shortcode_view_all_collections_url', get_bloginfo( 'url' ) . '/collections/' . $collections, $collections );
                // Create list item with link.
                $docupress_list .= '<li>';
                $docupress_list .= '<a href="' . esc_url( $collections_url ) . '">' . __( 'view all', 'docupress' ) . ' &rarr;</a>';
                $docupress_list .= '</li>';
            }

            $docupress_list .= '</ul>';
    }

    return $docupress_list;
}
add_shortcode( 'docupress', 'docupress_shortcode' );
