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

/**
 * Generates a shortcode to display a list of DocuPress articles.
 *
 * This shortcode allows users to output a customizable list of articles from 
 * the DocuPress custom post type. Users can filter articles by collections, 
 * set a limit on the number of posts displayed, and control the order.
 *
 * Attributes:
 * - `limit` (int)          : Number of articles to display (default: 5).
 * - `collections` (string) : Filter by taxonomy slug ('all' for all articles, default: all).
 * - `order` (string)       : Sorting order (e.g., 'ASC', 'DESC').
 * - `viewall` (string)     : Whether to display a "View All" link for collections (default: on).
 *
 * Hooks Applied:
 * - `docupress_shortcode_query_args` : Filters the WP_Query arguments for all articles.
 * - `docupress_shortcode_query_args_collections` : Filters the WP_Query arguments for collection-based queries.
 * - `docupress_shortcode_view_all_collections_url` : Filters the URL for the "View All" link.
 *
 * Output:
 * - Returns an unordered list (`<ul>`) of articles with links.
 * - Displays a message if no articles are found.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output of the article list or a message if no articles are found.
 */
 function docupress_shortcode( $atts ) {

    // Attributes.
    extract( shortcode_atts(
        [
            'limit'       => '5',
            'collections' => 'all',
            'order'       => '',
            'viewall'     => 'on',
        ],
        $atts,
        'docupress'
    ) );

    if ( 'all' === $collections ) {
        // Args.
        $args = [
            'post_type' => 'docupress',
            'showposts' => $limit,
            'orderby'   => $order,
        ];
        // Filter args.
        $args = apply_filters( 'docupress_shortcode_query_args', $args );
    } else {
        // Args.
        $args = [
            'post_type' => 'docupress',
            'showposts' => $limit,
            'orderby'   => $order,
            'tax_query' => [
                [
                    'taxonomy' => 'docupress_collections',
                    'field'    => 'slug',
                    'terms'    => $collections
                ],
            ],
        ];
        // Filter args.
        $args = apply_filters( 'docupress_shortcode_query_args_collections', $args );
    }

    // Get results.
    $docupress_articles = new WP_Query( $args );

    // Display message if no articles are found.
    if ( ! $docupress_articles->have_posts() ) {
        return '<p class="docupress-shortcode-empty">' . esc_attr__( 'No articles found', 'docupress' ) . '</p>';
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
                $docupress_list .= '<a href="' . esc_url( $collections_url ) . '">' . esc_attr__( 'view all', 'docupress' ) . ' &rarr;</a>';
                $docupress_list .= '</li>';
            }

            $docupress_list .= '</ul>';
    }

    return $docupress_list;
}
add_shortcode( 'docupress', 'docupress_shortcode' );
